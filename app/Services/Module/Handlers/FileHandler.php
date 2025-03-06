<?php

namespace App\Services\Module\Handlers;

use App\Services\Module\Exceptions\ModuleGenerationException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FileHandler
{
    public function createTempDirectory(string $processId): string
    {
        $tempDir = storage_path("app/temp/module_{$processId}");
        
        if (File::exists($tempDir)) {
            File::deleteDirectory($tempDir);
        }
        
        File::makeDirectory($tempDir, 0755, true);
        
        return $tempDir;
    }

    public function prepareFiles(string $name, string $tableName, array $data, string $tempDir): array
    {
        $files = [];

        // Migration dosyası
        $migrationName = date('Y_m_d_His_') . "create_{$tableName}_table.php";
        $tempMigrationPath = "{$tempDir}/{$migrationName}";
        $migrationContent = $this->createMigrationStub($tableName, $data['fields']);
        File::put($tempMigrationPath, $migrationContent);
        
        $files['migration'] = [
            'temp' => $tempMigrationPath,
            'final' => database_path("migrations/{$migrationName}")
        ];

        // Model dosyası
        $tempModelPath = "{$tempDir}/{$name}.php";
        $modelContent = $this->createModelStub($name, $tableName, $data['fields']);
        File::put($tempModelPath, $modelContent);
        
        $files['model'] = [
            'temp' => $tempModelPath,
            'final' => app_path("Models/{$name}.php")
        ];

        // Controller dosyası
        $controllerPath = app_path("Http/Controllers/Admin");
        if (!File::exists($controllerPath)) {
            File::makeDirectory($controllerPath, 0755, true);
        }
        
        $tempControllerPath = "{$tempDir}/{$name}Controller.php";
        $controllerContent = $this->createControllerStub($name);
        File::put($tempControllerPath, $controllerContent);
        
        $files['controller'] = [
            'temp' => $tempControllerPath,
            'final' => "{$controllerPath}/{$name}Controller.php"
        ];

        // Route dosyası güncelleme
        $this->addRouteDefinition($name);

        // View dosyaları
        $viewsPath = resource_path("views/admin/" . Str::lower(Str::plural($name)));
        if (!File::exists($viewsPath)) {
            File::makeDirectory($viewsPath, 0755, true);
        }

        // Index view
        $tempIndexPath = "{$tempDir}/index.blade.php";
        $indexContent = $this->createIndexViewStub($name, $data['fields']);
        File::put($tempIndexPath, $indexContent);
        
        $files['index_view'] = [
            'temp' => $tempIndexPath,
            'final' => "{$viewsPath}/index.blade.php"
        ];

        // Create view
        $tempCreatePath = "{$tempDir}/create.blade.php";
        $createContent = $this->createFormViewStub($name, $data['fields'], 'create');
        File::put($tempCreatePath, $createContent);
        
        $files['create_view'] = [
            'temp' => $tempCreatePath,
            'final' => "{$viewsPath}/create.blade.php"
        ];

        // Edit view
        $tempEditPath = "{$tempDir}/edit.blade.php";
        $editContent = $this->createFormViewStub($name, $data['fields'], 'edit');
        File::put($tempEditPath, $editContent);
        
        $files['edit_view'] = [
            'temp' => $tempEditPath,
            'final' => "{$viewsPath}/edit.blade.php"
        ];

        return $files;
    }

    public function moveFiles(array $files): void
    {
        foreach ($files as $type => $paths) {
            if (File::exists($paths['temp'])) {
                $targetDir = dirname($paths['final']);
                if (!File::exists($targetDir)) {
                    File::makeDirectory($targetDir, 0755, true);
                }
                
                if (File::exists($paths['final'])) {
                    File::delete($paths['final']);
                }
                
                File::move($paths['temp'], $paths['final']);
            }
        }
    }

    public function cleanup(string $tempDir, array $files): void
    {
        // Geçici dizini temizle
        if (File::exists($tempDir)) {
            File::deleteDirectory($tempDir);
        }

        // Oluşturulan dosyaları temizle
        foreach ($files as $paths) {
            if (File::exists($paths['final'])) {
                File::delete($paths['final']);
            }
        }
    }

    protected function createMigrationStub(string $tableName, array $fields): string
    {
        $fieldDefinitions = [];
        foreach ($fields as $field) {
            $type = $field['type'];
            $name = $field['name'];
            $validations = $field['validation'] ?? [];
            
            $definition = match($type) {
                'string' => "\$table->string('{$name}')",
                'text' => "\$table->text('{$name}')",
                'integer' => "\$table->integer('{$name}')",
                'decimal' => "\$table->decimal('{$name}', 10, 2)",
                'boolean' => "\$table->boolean('{$name}')",
                'date' => "\$table->date('{$name}')",
                'datetime' => "\$table->datetime('{$name}')",
                default => "\$table->string('{$name}')"
            };

            if (in_array('nullable', $validations)) {
                $definition .= '->nullable()';
            }

            if (in_array('unique', $validations)) {
                $definition .= "->unique('{$tableName}')";
            }

            $definition .= ';';
            $fieldDefinitions[] = str_repeat(' ', 12) . $definition;
        }

        $fieldDefinitionsStr = implode("\n", $fieldDefinitions);

        return <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('{$tableName}', function (Blueprint \$table) {
            \$table->id();
{$fieldDefinitionsStr}
            \$table->timestamps();
            \$table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('{$tableName}');
    }
};
PHP;
    }

    protected function createModelStub(string $name, string $tableName, array $fields): string
    {
        $fillableStr = $this->prepareFillableFields($fields);
        $castStr = $this->prepareCastFields($fields);
        $labelStr = $this->prepareLabelFields($fields);
        $validationStr = $this->prepareValidationFields($fields);

        return <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class {$name} extends Model
{
    use HasFactory, SoftDeletes;

    protected \$table = '{$tableName}';

    protected \$fillable = [
        {$fillableStr}
    ];

    protected \$casts = [
        {$castStr}
    ];

    protected \$labels = [
        {$labelStr}
    ];

    protected \$validations = [
        {$validationStr}
    ];

    public static function getValidationRules()
    {
        return (new static)->validations;
    }

    public function getLabel(\$field)
    {
        return \$this->labels[\$field] ?? \$field;
    }
}
PHP;
    }

    protected function prepareFillableFields(array $fields): string
    {
        return implode(",\n        ", array_map(function($field) {
            return "'" . $field['name'] . "'";
        }, $fields));
    }

    protected function prepareCastFields(array $fields): string
    {
        $castFields = [];
        foreach ($fields as $field) {
            if ($field['type'] === 'text') {
                continue;
            }
            
            $castType = match($field['type']) {
                'integer' => 'integer',
                'decimal' => 'decimal:2',
                'boolean' => 'boolean',
                'date' => 'date',
                'datetime' => 'datetime',
                'string', 'email', 'password' => 'string',
                default => null
            };
            
            if ($castType !== null) {
                $castFields[] = "'" . $field['name'] . "' => '" . $castType . "'";
            }
        }
        
        return implode(",\n        ", $castFields);
    }

    protected function prepareLabelFields(array $fields): string
    {
        return implode(",\n        ", array_map(function($field) {
            $label = str_replace("'", "\\'", $field['label']);
            return "'" . $field['name'] . "' => '" . $label . "'";
        }, $fields));
    }

    protected function prepareValidationFields(array $fields): string
    {
        return implode(",\n        ", array_map(function($field) {
            $rules = $this->getValidationRules($field);
            return "'" . $field['name'] . "' => '" . implode('|', array_unique($rules)) . "'";
        }, $fields));
    }

    protected function getValidationRules(array $field): array
    {
        $rules = ['required'];

        switch ($field['type']) {
            case 'string':
            case 'text':
                $rules[] = 'string';
                if ($field['type'] === 'string') {
                    $rules[] = 'max:255';
                }
                break;
            case 'integer':
                $rules[] = 'integer';
                break;
            case 'decimal':
                $rules[] = 'numeric';
                break;
            case 'boolean':
                $rules[] = 'boolean';
                break;
            case 'date':
            case 'datetime':
                $rules[] = 'date';
                break;
            case 'email':
                $rules[] = 'email';
                break;
        }

        if (!empty($field['validation'])) {
            $rules = array_merge($rules, (array)$field['validation']);
        }

        return $rules;
    }

    protected function createIndexViewStub(string $name, array $fields): string
    {
        $pluralName = Str::plural($name);
        $lowercaseName = Str::lower($name);
        $pluralLowercaseName = Str::plural($lowercaseName);
        
        $headers = [];
        $rows = [];
        
        foreach ($fields as $field) {
            $headers[] = "<th>{$field['label']}</th>";
            $rows[] = "<td>{{ \$item->{$field['name']} }}</td>";
        }
        
        $headerStr = implode("\n                ", $headers);
        $rowStr = implode("\n                    ", $rows);
        
        return <<<BLADE
@extends('layouts.app')

@section('title', '{$pluralName}')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{$pluralName}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.{$lowercaseName}.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Yeni Ekle
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                {$headerStr}
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\$items as \$item)
                            <tr>
                                {$rowStr}
                                <td>
                                    <a href="{{ route('admin.{$lowercaseName}.edit', \$item) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm delete-item" 
                                            data-url="{{ route('admin.{$lowercaseName}.destroy', \$item) }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ \$items->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.delete-item').click(function() {
        var button = $(this);
        var url = button.data('url');
        
        Swal.fire({
            title: 'Emin misiniz?',
            text: "Bu işlem geri alınamaz!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Silindi!',
                                response.message,
                                'success'
                            ).then(() => {
                                button.closest('tr').remove();
                            });
                        } else {
                            Swal.fire(
                                'Hata!',
                                response.message,
                                'error'
                            );
                        }
                    }
                });
            }
        });
    });
});
</script>
@endpush
BLADE;
    }

    protected function createFormViewStub(string $name, array $fields, string $type): string
    {
        $lowercaseName = Str::lower($name);
        $title = $type === 'create' ? "Yeni {$name} Ekle" : "{$name} Düzenle";
        $route = $type === 'create' ? 
            "route('admin.{$lowercaseName}.store')" : 
            "route('admin.{$lowercaseName}.update', \${$lowercaseName})";
        $method = $type === 'create' ? "POST" : "PUT";
        
        $formFields = [];
        foreach ($fields as $field) {
            $value = $type === 'create' ? 
                "{{ old('{$field['name']}') }}" : 
                "{{ old('{$field['name']}', \${$lowercaseName}->{$field['name']}) }}";
            
            $formField = match($field['type']) {
                'text' => $this->createTextareaField($field, $value),
                'boolean' => $this->createCheckboxField($field, $value),
                'date', 'datetime' => $this->createDateField($field, $value),
                default => $this->createInputField($field, $value)
            };
            
            $formFields[] = $formField;
        }
        
        $formFieldsStr = implode("\n\n                ", $formFields);
        
        return <<<BLADE
@extends('layouts.app')

@section('title', '{$title}')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{$title}</h3>
                </div>
                <form id="itemForm" action="{{ {$route} }}" method="POST">
                    @csrf
                    @if('{$method}' === 'PUT')
                        @method('PUT')
                    @endif
                    
                    <div class="card-body">
                        {$formFieldsStr}
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                        <a href="{{ route('admin.{$lowercaseName}.index') }}" class="btn btn-secondary">İptal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#itemForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var method = form.attr('method');
        
        $.ajax({
            url: url,
            type: method,
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Başarılı!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';
                    
                    for (var field in errors) {
                        errorMessage += errors[field][0] + '\\n';
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: errorMessage
                    });
                }
            }
        });
    });
});
</script>
@endpush
BLADE;
    }

    protected function createInputField(array $field, string $value): string
    {
        $type = match($field['type']) {
            'integer', 'decimal' => 'number',
            'email' => 'email',
            'password' => 'password',
            default => 'text'
        };
        
        $step = $field['type'] === 'decimal' ? 'step="0.01"' : '';
        
        return <<<HTML
        <div class="form-group">
            <label for="{$field['name']}">{$field['label']}</label>
            <input type="{$type}" class="form-control @error('{$field['name']}') is-invalid @enderror" 
                   id="{$field['name']}" name="{$field['name']}" value="{$value}" {$step}>
            @error('{$field['name']}')
                <div class="invalid-feedback">{{ \$message }}</div>
            @enderror
        </div>
HTML;
    }

    protected function createTextareaField(array $field, string $value): string
    {
        return <<<HTML
        <div class="form-group">
            <label for="{$field['name']}">{$field['label']}</label>
            <textarea class="form-control @error('{$field['name']}') is-invalid @enderror" 
                      id="{$field['name']}" name="{$field['name']}" rows="3">{$value}</textarea>
            @error('{$field['name']}')
                <div class="invalid-feedback">{{ \$message }}</div>
            @enderror
        </div>
HTML;
    }

    protected function createCheckboxField(array $field, string $value): string
    {
        return <<<HTML
        <div class="form-group">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input @error('{$field['name']}') is-invalid @enderror" 
                       id="{$field['name']}" name="{$field['name']}" value="1" {{ {$value} ? 'checked' : '' }}>
                <label class="custom-control-label" for="{$field['name']}">{$field['label']}</label>
                @error('{$field['name']}')
                    <div class="invalid-feedback">{{ \$message }}</div>
                @enderror
            </div>
        </div>
HTML;
    }

    protected function createDateField(array $field, string $value): string
    {
        $type = $field['type'] === 'datetime' ? 'datetime-local' : 'date';
        
        return <<<HTML
        <div class="form-group">
            <label for="{$field['name']}">{$field['label']}</label>
            <input type="{$type}" class="form-control @error('{$field['name']}') is-invalid @enderror" 
                   id="{$field['name']}" name="{$field['name']}" value="{$value}">
            @error('{$field['name']}')
                <div class="invalid-feedback">{{ \$message }}</div>
            @enderror
        </div>
HTML;
    }

    protected function createControllerStub(string $name): string
    {
        return <<<PHP
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\\{$name};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class {$name}Controller extends Controller
{
    public function index()
    {
        \$items = {$name}::latest()->paginate(10);
        return view('admin.' . Str::lower(Str::plural('{$name}')).'.index', compact('items'));
    }

    public function create()
    {
        return view('admin.' . Str::lower(Str::plural('{$name}')).'.create');
    }

    public function store(Request \$request)
    {
        try {
            \$validated = \$request->validate({$name}::getValidationRules());
            
            \$item = {$name}::create(\$validated);

            return response()->json([
                'success' => true,
                'message' => 'Kayıt başarıyla oluşturuldu.',
                'redirect' => route('admin.' . Str::lower('{$name}').'.index')
            ]);
        } catch (\Exception \$e) {
            Log::error('{$name} oluşturma hatası: ' . \$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu.'
            ], 500);
        }
    }

    public function show({$name} \${$name})
    {
        return view('admin.' . Str::lower(Str::plural('{$name}')).'.show', compact('{$name}'));
    }

    public function edit({$name} \${$name})
    {
        return view('admin.' . Str::lower(Str::plural('{$name}')).'.edit', compact('{$name}'));
    }

    public function update(Request \$request, {$name} \${$name})
    {
        try {
            \$validated = \$request->validate({$name}::getValidationRules());
            
            \${$name}->update(\$validated);

            return response()->json([
                'success' => true,
                'message' => 'Kayıt başarıyla güncellendi.',
                'redirect' => route('admin.' . Str::lower('{$name}').'.index')
            ]);
        } catch (\Exception \$e) {
            Log::error('{$name} güncelleme hatası: ' . \$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu.'
            ], 500);
        }
    }

    public function destroy({$name} \${$name})
    {
        try {
            \${$name}->delete();
            return response()->json([
                'success' => true,
                'message' => 'Kayıt başarıyla silindi.'
            ]);
        } catch (\Exception \$e) {
            Log::error('{$name} silme hatası: ' . \$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu.'
            ], 500);
        }
    }
}
PHP;
    }

    protected function addRouteDefinition(string $name): void
    {
        $routesPath = base_path('routes/web.php');
        $routeContent = File::get($routesPath);
        
        // Controller'ı import et
        $importPattern = "/use App\\\\Http\\\\Controllers\\\\Admin\\\\{([^}]+)};/";
        if (preg_match($importPattern, $routeContent, $matches)) {
            $imports = $matches[1];
            if (!str_contains($imports, "{$name}Controller")) {
                $newImports = trim($imports) . ",\n    {$name}Controller";
                $routeContent = preg_replace($importPattern, "use App\\Http\\Controllers\\Admin\\{{$newImports}};", $routeContent);
            }
        }
        
        // Route tanımlamasını ekle
        $routeDefinition = "    Route::resource('" . Str::lower($name) . "', {$name}Controller::class);";
        
        // Admin route grubu içindeki ilk route'dan önce ekle
        $pattern = "/(Route::middleware\(\['auth', 'role:admin,super-admin'\]\)->prefix\('admin'\)->name\('admin.'\)->group\(function \(\) {.*?)(\s+Route::)/s";
        $replacement = "$1\n$routeDefinition$2";
        
        $newContent = preg_replace($pattern, $replacement, $routeContent);
        
        File::put($routesPath, $newContent);
        
        // Route cache'ini temizle
        $this->clearRouteCache();
    }
    
    protected function clearRouteCache(): void
    {
        if (File::exists(base_path('bootstrap/cache/routes.php'))) {
            File::delete(base_path('bootstrap/cache/routes.php'));
        }
        if (File::exists(base_path('bootstrap/cache/routes-v7.php'))) {
            File::delete(base_path('bootstrap/cache/routes-v7.php'));
        }
    }
} 