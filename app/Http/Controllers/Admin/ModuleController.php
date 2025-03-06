<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    private function moveModuleFile($moduleName)
    {
        $oldPath = app_path("Models/{$moduleName}/{$moduleName}.php");
        $newPath = app_path("Models/{$moduleName}.php");

        if (File::exists($oldPath) && !File::exists($newPath)) {
            // Dosyayı taşı
            File::move($oldPath, $newPath);
            
            // Eski dizini sil
            File::deleteDirectory(app_path("Models/{$moduleName}"));
        }
    }

    public function index()
    {
        try {
            // Önce Yorumlar modülünü taşı
            $this->moveModuleFile('Yorumlar');

            // Models klasöründeki tüm modülleri tara
            $modules = collect(File::files(app_path('Models')))
                ->filter(function ($file) {
                    // Sistem modüllerini hariç tut
                    $excludedModels = [
                        'User.php',
                        'Role.php',
                        'Permission.php',
                        'Setting.php',
                        'Status.php',
                        'BackupSchedule.php',
                        'Offer.php',
                        'Agency.php',
                        'Company.php',
                        'Page.php',
                        'Post.php',
                        'OfferFile.php'
                    ];
                    
                    return $file->getExtension() === 'php' && 
                           !in_array($file->getFilename(), $excludedModels);
                })
                ->map(function ($file) {
                    $name = $file->getBasename('.php');
                    $modelPath = $file->getPathname();
                    
                    // Varsayılan değerler
                    $moduleData = [
                        'name' => $name,
                        'fields' => [],
                        'created_at' => File::lastModified($modelPath)
                    ];

                    // Model dosyası varsa fields'ları çıkar
                    if (File::exists($modelPath)) {
                        $content = File::get($modelPath);
                        
                        // Fillable alanları bul
                        preg_match('/protected \$fillable\s*=\s*\[(.*?)\]/s', $content, $matches);
                        
                        if (!empty($matches[1])) {
                            // Fillable alanları diziye çevir
                            $fields = array_map(function($field) {
                                return trim(str_replace(['\'', '"'], '', $field));
                            }, explode(',', $matches[1]));
                            
                            $moduleData['fields'] = array_filter($fields); // Boş değerleri temizle
                        }

                        // Field tipleri ve etiketleri bul
                        preg_match('/protected \$casts\s*=\s*\[(.*?)\]/s', $content, $castMatches);
                        if (!empty($castMatches[1])) {
                            $casts = [];
                            preg_match_all('/\'(.*?)\'\s*=>\s*\'(.*?)\'/', $castMatches[1], $castPairs);
                            if (!empty($castPairs[1])) {
                                $casts = array_combine($castPairs[1], $castPairs[2]);
                            }
                            $moduleData['casts'] = $casts;
                        }

                        // Form etiketlerini bul
                        preg_match('/protected \$labels\s*=\s*\[(.*?)\]/s', $content, $labelMatches);
                        if (!empty($labelMatches[1])) {
                            $labels = [];
                            preg_match_all('/\'(.*?)\'\s*=>\s*\'(.*?)\'/', $labelMatches[1], $labelPairs);
                            if (!empty($labelPairs[1])) {
                                $labels = array_combine($labelPairs[1], $labelPairs[2]);
                            }
                            $moduleData['labels'] = $labels;
                        }
                    }

                    return $moduleData;
                });

            return view('admin.modules.index', compact('modules'));
        } catch (\Exception $e) {
            \Log::error('Modül listesi hatası: ' . $e->getMessage());
            return back()->with('error', 'Modüller listelenirken bir hata oluştu.');
        }
    }

    public function destroy($module)
    {
        try {
            $moduleName = ucfirst($module);
            $pluralModuleName = Str::plural(strtolower($module));
            
            // 1. Dosya yolları
            $paths = [
                'controller' => app_path("Http/Controllers/Admin/{$moduleName}Controller.php"),
                'model' => app_path("Models/{$moduleName}.php"),
                'views' => resource_path("views/admin/{$pluralModuleName}"),
                'migration' => $this->findMigrationFile($module)
            ];
            
            // 2. Veritabanı tablosunu sil
            $tableName = Str::plural(Str::snake($module));
            if (Schema::hasTable($tableName)) {
                Schema::dropIfExists($tableName);
                \Log::info("Veritabanı tablosu silindi: {$tableName}");
            }
            
            // 3. Dosyaları sil
            foreach ($paths as $type => $path) {
                if ($path && File::exists($path)) {
                    if (File::isDirectory($path)) {
                        File::deleteDirectory($path);
                        \Log::info("{$type} dizini silindi: {$path}");
                    } else {
                        File::delete($path);
                        \Log::info("{$type} dosyası silindi: {$path}");
                    }
                }
            }
            
            // 4. Route tanımını kaldır
            $this->removeRouteDefinition($module);
            
            // 5. Sidebar'dan modül linkini kaldır
            $this->removeSidebarLink($module);
            
            // 6. Route ve config cache'lerini temizle
            $this->clearCaches();
            
            return response()->json([
                'success' => true,
                'message' => 'Modül ve ilgili tüm dosyalar başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Modül silme hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Modül silinirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
    
    protected function findMigrationFile($module)
    {
        $files = File::glob(database_path('migrations/*_create_' . Str::plural(strtolower($module)) . '_table.php'));
        return !empty($files) ? $files[0] : null;
    }
    
    protected function removeRouteDefinition($module)
    {
        try {
            $routesPath = base_path('routes/web.php');
            if (!File::exists($routesPath)) {
                return false;
            }

            $content = File::get($routesPath);
            $originalContent = $content;
            
            // Route tanımlarını bul ve kaldır
            $patterns = [
                // Controller import (tam namespace ile)
                "/use\s+App\\\\Http\\\\Controllers\\\\Admin\\\\".ucfirst($module)."Controller;\s*/",
                
                // Controller import (grup içinde)
                "/,\s*".ucfirst($module)."Controller(?=\s*})/",
                
                // Resource route (tam yol ile)
                "/\s*Route::resource\('".strtolower($module)."',\s*App\\\\Http\\\\Controllers\\\\Admin\\\\".ucfirst($module)."Controller::class\);/",
                
                // Resource route (kısa yol ile)
                "/\s*Route::resource\('".strtolower($module)."',\s*".ucfirst($module)."Controller::class\);/",
                
                // Boş satırları temizle
                "/^\s*[\r\n]+/m"
            ];
            
            foreach ($patterns as $pattern) {
                $content = preg_replace($pattern, '', $content);
            }
            
            // Eğer içerik değiştiyse dosyayı güncelle
            if ($content !== $originalContent) {
                File::put($routesPath, $content);
                \Log::info('Route tanımları kaldırıldı: ' . $module);
            }
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Route tanımı kaldırılırken hata: ' . $e->getMessage());
            return false;
        }
    }
    
    protected function removeSidebarLink($module)
    {
        try {
            $sidebarPath = resource_path('views/layouts/sidebar.blade.php');
            if (!File::exists($sidebarPath)) {
                return false;
            }

            $content = File::get($sidebarPath);
            $originalContent = $content;

            // Modül linkini bul ve kaldır (daha esnek bir pattern)
            $pattern = '/<li\s+class="nav-item">\s*<a\s+href="\{\{\s*route\([\'"]admin\.' . strtolower($module) . '\.index[\'"]\)\s*\}\}"\s*class="[^"]*"[^>]*>\s*<i[^>]*>[^<]*<\/i>\s*<span>[^<]*<\/span>\s*<\/a>\s*<\/li>\s*/s';
            $content = preg_replace($pattern, '', $content);

            // Eğer içerik değiştiyse dosyayı güncelle
            if ($content !== $originalContent) {
                File::put($sidebarPath, $content);
                \Log::info('Sidebar linki kaldırıldı: ' . $module);
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Sidebar linki kaldırılırken hata: ' . $e->getMessage());
            return false;
        }
    }
    
    protected function clearCaches()
    {
        try {
            // Artisan komutlarını çalıştır
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
            \Artisan::call('optimize:clear');
            
            \Log::info('Tüm cache\'ler temizlendi');
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Cache temizleme hatası: ' . $e->getMessage());
            return false;
        }
    }

    public function updateStructure(Request $request)
    {
        try {
            $moduleName = $request->input('module_id');
            $fields = $request->input('fields', []);
            $tableName = Str::snake(Str::plural($moduleName));

            \Log::info('İşlem başladı: ' . $moduleName);
            \Log::info('Tablo adı: ' . $tableName);

            // Model dosyasını güncelle
            $modelPath = app_path("Models/{$moduleName}.php");
            
            \Log::info('Model yolu: ' . $modelPath);
            \Log::info('Dosya var mı: ' . (File::exists($modelPath) ? 'Evet' : 'Hayır'));
            
            if (!File::exists($modelPath)) {
                // Alternatif yolu dene
                $modelPath = app_path("Models/" . Str::studly($moduleName) . ".php");
                \Log::info('Alternatif model yolu: ' . $modelPath);
                \Log::info('Dosya var mı: ' . (File::exists($modelPath) ? 'Evet' : 'Hayır'));
                
                if (!File::exists($modelPath)) {
                    throw new \Exception('Model dosyası bulunamadı. Aranan yollar: ' . 
                        app_path("Models/{$moduleName}.php") . ' ve ' . 
                        app_path("Models/" . Str::studly($moduleName) . ".php"));
                }
            }

            // Model içeriğini güncelle
            $content = File::get($modelPath);
            
            // Fillable alanları güncelle
            $fillableFields = array_map(function($field) {
                return "'" . trim($field['name']) . "'";
            }, $fields);
            
            $newFillable = "protected \$fillable = [" . implode(', ', $fillableFields) . "];";
            
            // Cast tanımlamalarını güncelle
            $castFields = array_map(function($field) {
                return "'" . $field['name'] . "' => '" . $field['type'] . "'";
            }, $fields);
            
            $newCasts = "protected \$casts = [" . implode(', ', $castFields) . "];";

            // Form etiketlerini güncelle
            $labelFields = array_map(function($field) {
                return "'" . $field['name'] . "' => '" . ($field['label'] ?: Str::title(str_replace('_', ' ', $field['name']))) . "'";
            }, $fields);
            
            $newLabels = "protected \$labels = [" . implode(', ', $labelFields) . "];";
            
            // Model içeriğini güncelle
            $content = preg_replace(
                '/protected \$fillable\s*=\s*\[.*?\];/s',
                $newFillable,
                $content
            );

            if (preg_match('/protected \$casts\s*=\s*\[.*?\];/s', $content)) {
                $content = preg_replace(
                    '/protected \$casts\s*=\s*\[.*?\];/s',
                    $newCasts,
                    $content
                );
            } else {
                $content = preg_replace(
                    '/(class\s+' . basename($modelPath, '.php') . '\s+extends\s+Model\s*{[^}]*)(})/',
                    "$1\n\n    " . $newCasts . "\n    $2",
                    $content
                );
            }

            if (preg_match('/protected \$labels\s*=\s*\[.*?\];/s', $content)) {
                $content = preg_replace(
                    '/protected \$labels\s*=\s*\[.*?\];/s',
                    $newLabels,
                    $content
                );
            } else {
                $content = preg_replace(
                    '/(class\s+' . basename($modelPath, '.php') . '\s+extends\s+Model\s*{[^}]*)(})/',
                    "$1\n\n    " . $newLabels . "\n    $2",
                    $content
                );
            }

            File::put($modelPath, $content);

            // Veritabanı işlemleri için transaction başlat
            DB::beginTransaction();

            try {
                // Önce tablo var mı kontrol et
                if (!Schema::hasTable($tableName)) {
                    Schema::create($tableName, function ($table) {
                        $table->id();
                        $table->timestamps();
                        $table->softDeletes();
                    });
                }

                // Yeni alanları kontrol et ve ekle
                foreach ($fields as $field) {
                    $fieldName = $field['name'];
                    $fieldType = $field['type'];

                    if (!Schema::hasColumn($tableName, $fieldName)) {
                        Schema::table($tableName, function ($table) use ($fieldName, $fieldType) {
                            switch ($fieldType) {
                                case 'string':
                                    $table->string($fieldName)->nullable();
                                    break;
                                case 'text':
                                    $table->text($fieldName)->nullable();
                                    break;
                                case 'integer':
                                    $table->integer($fieldName)->nullable();
                                    break;
                                case 'decimal':
                                    $table->decimal($fieldName, 10, 2)->nullable();
                                    break;
                                case 'boolean':
                                    $table->boolean($fieldName)->default(false);
                                    break;
                                case 'date':
                                    $table->date($fieldName)->nullable();
                                    break;
                                case 'datetime':
                                    $table->datetime($fieldName)->nullable();
                                    break;
                                case 'time':
                                    $table->time($fieldName)->nullable();
                                    break;
                                default:
                                    $table->string($fieldName)->nullable();
                            }
                        });
                    }
                }

                // View dosyalarını güncelle
                $this->updateViewFiles($moduleName, $fields);

                // Transaction'ı onayla
                DB::commit();
                
                \Log::info('İşlem başarıyla tamamlandı');

                return response()->json([
                    'success' => true,
                    'message' => 'Modül yapısı ve veritabanı başarıyla güncellendi',
                    'title' => 'Başarılı!',
                    'icon' => 'success'
                ]);
            } catch (\Exception $e) {
                // Hata durumunda transaction'ı geri al
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            // Önce tablo var mı kontrol et
            if (!Schema::hasTable($tableName)) {
                Schema::create($tableName, function ($table) {
                    $table->id();
                    $table->timestamps();
                    $table->softDeletes();
                });
            }

            // Yeni alanları kontrol et ve ekle
            foreach ($fields as $field) {
                $fieldName = $field['name'];
                $fieldType = $field['type'];

                if (!Schema::hasColumn($tableName, $fieldName)) {
                    Schema::table($tableName, function ($table) use ($fieldName, $fieldType) {
                        switch ($fieldType) {
                            case 'string':
                                $table->string($fieldName)->nullable();
                                break;
                            case 'text':
                                $table->text($fieldName)->nullable();
                                break;
                            case 'integer':
                                $table->integer($fieldName)->nullable();
                                break;
                            case 'decimal':
                                $table->decimal($fieldName, 10, 2)->nullable();
                                break;
                            case 'boolean':
                                $table->boolean($fieldName)->default(false);
                                break;
                            case 'date':
                                $table->date($fieldName)->nullable();
                                break;
                            case 'datetime':
                                $table->datetime($fieldName)->nullable();
                                break;
                            case 'time':
                                $table->time($fieldName)->nullable();
                                break;
                            default:
                                $table->string($fieldName)->nullable();
                        }
                    });
                }
            }

            // View dosyalarını güncelle
            $this->updateViewFiles($moduleName, $fields);

            DB::commit();
            \Log::info('İşlem başarıyla tamamlandı');

            return response()->json([
                'success' => true,
                'message' => 'Modül yapısı ve veritabanı başarıyla güncellendi',
                'title' => 'Başarılı!',
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Modül güncelleme hatası: ' . $e->getMessage());
            \Log::error('Hata detayı: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage(),
                'title' => 'Hata!',
                'icon' => 'error'
            ], 500);
        }
    }

    protected function updateViewFiles($moduleName, $fields)
    {
        try {
            $pluralName = Str::plural(Str::snake($moduleName));
            $viewPath = resource_path("views/admin/{$pluralName}");

            // View dizini yoksa oluştur
            if (!File::exists($viewPath)) {
                File::makeDirectory($viewPath, 0755, true);
            }

            // Index view dosyası
            $indexPath = "{$viewPath}/index.blade.php";
            if (File::exists($indexPath)) {
                $content = File::get($indexPath);

                // Tablo başlıklarını güncelle
                $headers = '';
                foreach ($fields as $field) {
                    $label = $field['label'] ?: Str::title(str_replace('_', ' ', $field['name']));
                    $headers .= "                                    <th>{$label}</th>\n";
                }
                $headers .= "                                    <th width=\"100\">İşlemler</th>";

                $content = preg_replace(
                    '/<th[^>]*>(?!<input).*?<\/th>\s*<th\s+width="100">İşlemler<\/th>/s',
                    $headers,
                    $content
                );

                // Modal form alanlarını güncelle
                $formFields = '';
                foreach ($fields as $field) {
                    $name = $field['name'];
                    $label = $field['label'] ?: Str::title(str_replace('_', ' ', $name));
                    $type = $field['type'];

                    $formFields .= "                    <div class=\"form-group\">\n";
                    $formFields .= "                        <label for=\"{$name}\">{$label}</label>\n";

                    switch ($type) {
                        case 'text':
                            $formFields .= "                        <textarea class=\"form-control\" id=\"{$name}\" name=\"{$name}\" rows=\"3\" required></textarea>\n";
                            break;
                        case 'date':
                            $formFields .= "                        <input type=\"date\" class=\"form-control\" id=\"{$name}\" name=\"{$name}\" required>\n";
                            break;
                        case 'datetime':
                            $formFields .= "                        <input type=\"datetime-local\" class=\"form-control\" id=\"{$name}\" name=\"{$name}\" required>\n";
                            break;
                        case 'dropdown':
                            $formFields .= "                        <select class=\"form-control\" id=\"{$name}\" name=\"{$name}\" required>\n";
                            $formFields .= "                            <option value=\"\">Seçiniz</option>\n";
                            if (!empty($field['options'])) {
                                foreach ($field['options'] as $option) {
                                    $formFields .= "                            <option value=\"{$option}\">{$option}</option>\n";
                                }
                            }
                            $formFields .= "                        </select>\n";
                            break;
                        default:
                            $formFields .= "                        <input type=\"text\" class=\"form-control\" id=\"{$name}\" name=\"{$name}\" required>\n";
                    }
                    $formFields .= "                    </div>\n";
                }

                $content = preg_replace(
                    '/<div class="modal-body">(.*?)<div class="modal-footer">/s',
                    "<div class=\"modal-body\">\n{$formFields}                <div class=\"modal-footer\">",
                    $content
                );

                File::put($indexPath, $content);
            }

            // Table-rows partial
            $rowsPath = "{$viewPath}/table-rows.blade.php";
            if (File::exists($rowsPath)) {
                $content = File::get($rowsPath);

                // Tablo hücrelerini güncelle
                $cells = "    <td>\n        <input type=\"checkbox\" class=\"item-checkbox\" value=\"{{ \$item->id }}\">\n    </td>\n";
                foreach ($fields as $field) {
                    $name = $field['name'];
                    if ($field['type'] === 'date' || $field['type'] === 'datetime') {
                        $cells .= "    <td>{{ \$item->{$name} ? \\Carbon\\Carbon::parse(\$item->{$name})->format('d.m.Y H:i') : '' }}</td>\n";
                    } else {
                        $cells .= "    <td>{{ \$item->{$name} }}</td>\n";
                    }
                }

                $content = preg_replace(
                    '/<td>.*?<\/td>\s*<td>/s',
                    $cells . "    <td>",
                    $content
                );

                File::put($rowsPath, $content);
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('View dosyaları güncellenirken hata: ' . $e->getMessage());
            return false;
        }
    }
} 