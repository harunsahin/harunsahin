<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class CreateModule extends Command
{
    protected $signature = 'make:module {name} {--modal} {--fields=}';
    protected $description = 'Yeni bir modül oluşturur';

    public function handle()
    {
        $name = $this->argument('name');
        $isModal = $this->option('modal');
        $fields = json_decode($this->option('fields'), true);

        $studlyName = Str::studly($name);
        $pluralName = Str::plural(Str::snake($name));

        // Controller oluştur
        $this->createController($studlyName, $isModal, $fields);

        // View dosyalarını oluştur
        $this->createViews($studlyName, $isModal, $fields);

        // Route'ları ekle
        $this->addRoutes($pluralName, $studlyName);

        // Sidebar'a ekle
        $this->addToSidebar($studlyName, $pluralName);

        $this->info('Modül başarıyla oluşturuldu!');
        return 0;
    }

    protected function createController($name, $isModal, $fields)
    {
        $controllerPath = app_path("Http/Controllers/Admin/{$name}Controller.php");
        $controllerContent = $isModal ? 
            $this->getModalControllerTemplate($name, $fields) : 
            $this->getControllerTemplate($name, $fields);

        File::put($controllerPath, $controllerContent);
    }

    protected function createViews($name, $isModal, $fields)
    {
        $viewPath = resource_path('views/admin/' . Str::plural(Str::snake($name)));
        File::makeDirectory($viewPath, 0755, true, true);

        // index.blade.php
        $indexContent = $this->getIndexTemplate($name, $fields);
        File::put($viewPath . '/index.blade.php', $indexContent);

        if (!$isModal) {
            // create.blade.php
            $createContent = $this->getCreateTemplate($name, $fields);
            File::put($viewPath . '/create.blade.php', $createContent);

            // edit.blade.php
            $editContent = $this->getEditTemplate($name, $fields);
            File::put($viewPath . '/edit.blade.php', $editContent);
        }
    }

    protected function addRoutes($pluralName, $studlyName)
    {
        $routePath = base_path('routes/web.php');
        $routeContent = File::get($routePath);

        $newRoute = "    Route::resource('{$pluralName}', App\Http\Controllers\Admin\\{$studlyName}Controller::class);";
        
        // Route grubunu bul ve yeni route'u ekle
        $pattern = "/Route::middleware\(\['auth', 'role:admin,super-admin'\]\)->prefix\('admin'\)->name\('admin\.'\)->group\(function \(\) {/";
        $replacement = "$0\n$newRoute";
        
        $routeContent = preg_replace($pattern, $replacement, $routeContent);
        File::put($routePath, $routeContent);
    }

    protected function addToSidebar($name, $pluralName)
    {
        $sidebarPath = resource_path('views/layouts/sidebar.blade.php');
        $sidebarContent = File::get($sidebarPath);

        // Modül bağlantısı için HTML
        $moduleLink = '        <li class="nav-item">' . PHP_EOL;
        $moduleLink .= '            <a href="{{ route(\'admin.' . $pluralName . '.index\') }}" ' . PHP_EOL;
        $moduleLink .= '               class="nav-link {{ request()->routeIs(\'admin.' . $pluralName . '.*\') ? \'active\' : \'\' }}">' . PHP_EOL;
        $moduleLink .= '                <i class="' . $this->getModuleIcon($name) . '"></i>' . PHP_EOL;
        $moduleLink .= '                <span>' . ucfirst($name) . '</span>' . PHP_EOL;
        $moduleLink .= '            </a>' . PHP_EOL;
        $moduleLink .= '        </li>' . PHP_EOL;

        // Ana menüye ekle (Oteller linkinden sonra)
        $pattern = '/<li class="nav-item">\s*<a href="{{ route\(\'companies\.index\'\) }}.*?<\/li>/s';
        $replacement = "$0\n\n$moduleLink";
        
        $sidebarContent = preg_replace($pattern, $replacement, $sidebarContent);
        File::put($sidebarPath, $sidebarContent);
    }

    protected function getModuleIcon($name)
    {
        $icons = [
            'user' => 'fas fa-user',
            'role' => 'fas fa-user-tag',
            'permission' => 'fas fa-key',
            'setting' => 'fas fa-cog',
            'backup' => 'fas fa-database',
            'log' => 'fas fa-history',
            'notification' => 'fas fa-bell',
            'message' => 'fas fa-envelope',
            'comment' => 'fas fa-comments',
            'post' => 'fas fa-newspaper',
            'category' => 'fas fa-folder',
            'tag' => 'fas fa-tags',
            'file' => 'fas fa-file',
            'image' => 'fas fa-image',
            'video' => 'fas fa-video',
            'audio' => 'fas fa-music',
            'document' => 'fas fa-file-alt',
            'product' => 'fas fa-box',
            'order' => 'fas fa-shopping-cart',
            'invoice' => 'fas fa-file-invoice',
            'payment' => 'fas fa-credit-card',
            'customer' => 'fas fa-users',
            'supplier' => 'fas fa-truck',
            'employee' => 'fas fa-user-tie',
            'department' => 'fas fa-building',
            'project' => 'fas fa-project-diagram',
            'task' => 'fas fa-tasks',
            'event' => 'fas fa-calendar',
            'location' => 'fas fa-map-marker-alt',
            'report' => 'fas fa-chart-bar'
        ];

        $name = Str::singular(Str::snake($name));
        return $icons[$name] ?? 'fas fa-cube';
    }

    protected function getControllerTemplate($name, $fields)
    {
        $modelName = Str::singular($name);
        $variableName = Str::camel($modelName);
        $pluralVariableName = Str::plural($variableName);

        return <<<PHP
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\\{$modelName};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class {$name}Controller extends Controller
{
    public function __construct()
    {
        \$this->middleware('auth');
        \$this->middleware('admin');
    }

    public function index()
    {
        \${$pluralVariableName} = {$modelName}::paginate(10);
        return view('admin.{$pluralVariableName}.index', compact('{$pluralVariableName}'));
    }

    public function create()
    {
        return view('admin.{$pluralVariableName}.create');
    }

    public function store(Request \$request)
    {
        try {
            \$validated = \$request->validate({$this->getValidationRules($fields)});
            {$modelName}::create(\$validated);

            return redirect()->route('admin.{$pluralVariableName}.index')
                ->with('success', '{$modelName} başarıyla oluşturuldu.');
        } catch (\Exception \$e) {
            Log::error('{$modelName} oluşturma hatası: ' . \$e->getMessage());
            return back()->with('error', 'Bir hata oluştu.');
        }
    }

    public function edit({$modelName} \${$variableName})
    {
        return view('admin.{$pluralVariableName}.edit', compact('{$variableName}'));
    }

    public function update(Request \$request, {$modelName} \${$variableName})
    {
        try {
            \$validated = \$request->validate({$this->getValidationRules($fields)});
            \${$variableName}->update(\$validated);

            return redirect()->route('admin.{$pluralVariableName}.index')
                ->with('success', '{$modelName} başarıyla güncellendi.');
        } catch (\Exception \$e) {
            Log::error('{$modelName} güncelleme hatası: ' . \$e->getMessage());
            return back()->with('error', 'Bir hata oluştu.');
        }
    }

    public function destroy({$modelName} \${$variableName})
    {
        try {
            \${$variableName}->delete();
            return response()->json([
                'success' => true,
                'message' => '{$modelName} başarıyla silindi.'
            ]);
        } catch (\Exception \$e) {
            Log::error('{$modelName} silme hatası: ' . \$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu.'
            ], 500);
        }
    }
}
PHP;
    }

    protected function getModalControllerTemplate($name, $fields)
    {
        $modelName = Str::singular($name);
        $variableName = Str::camel($modelName);
        $pluralVariableName = Str::plural($variableName);
        $validationRules = var_export($this->getValidationRules($fields), true);

        return <<<PHP
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\\{$modelName};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class {$name}Controller extends Controller
{
    public function __construct()
    {
        \$this->middleware('auth');
        \$this->middleware('admin');
    }

    public function index()
    {
        \${$pluralVariableName} = {$modelName}::paginate(10);
        return view('admin.{$pluralVariableName}.index', compact('{$pluralVariableName}'));
    }

    public function store(Request \$request)
    {
        try {
            \$validated = \$request->validate({$validationRules});
            {$modelName}::create(\$validated);

            return response()->json([
                'success' => true,
                'message' => '{$modelName} başarıyla oluşturuldu.'
            ]);
        } catch (\Exception \$e) {
            Log::error('{$modelName} oluşturma hatası: ' . \$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu.'
            ], 500);
        }
    }

    public function update(Request \$request, {$modelName} \${$variableName})
    {
        try {
            \$validated = \$request->validate({$validationRules});
            \${$variableName}->update(\$validated);

            return response()->json([
                'success' => true,
                'message' => '{$modelName} başarıyla güncellendi.'
            ]);
        } catch (\Exception \$e) {
            Log::error('{$modelName} güncelleme hatası: ' . \$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu.'
            ], 500);
        }
    }

    public function destroy({$modelName} \${$variableName})
    {
        try {
            \${$variableName}->delete();
            return response()->json([
                'success' => true,
                'message' => '{$modelName} başarıyla silindi.'
            ]);
        } catch (\Exception \$e) {
            Log::error('{$modelName} silme hatası: ' . \$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu.'
            ], 500);
        }
    }
}
PHP;
    }

    protected function getValidationRules($fields)
    {
        $rules = [];
        foreach ($fields as $field) {
            if (isset($field['validation'])) {
                if (is_array($field['validation'])) {
                    $rules[$field['name']] = implode('|', $field['validation']);
                } else {
                    $rules[$field['name']] = $field['validation'];
                }
            } else {
                $rules[$field['name']] = '';
            }
        }
        return $rules;
    }

    protected function getTableHeaders($fields)
    {
        $headers = [];
        foreach ($fields as $field) {
            $headers[] = "                            <th>{$field['label']}</th>";
        }
        return implode("\n", $headers);
    }

    protected function getTableRows($fields, $varName)
    {
        $rows = [];
        foreach ($fields as $field) {
            $name = $field['name'];
            if ($field['type'] === 'boolean') {
                $rows[] = "                            <td>{{ \${$varName}->{$name} ? 'Evet' : 'Hayır' }}</td>";
            } else {
                $rows[] = "                            <td>{{ \${$varName}->{$name} }}</td>";
            }
        }
        return implode("\n", $rows);
    }

    protected function getFormFields($fields, $type = 'create', $varName = null)
    {
        $formFields = [];
        foreach ($fields as $field) {
            $name = $field['name'];
            $label = $field['label'];
            $value = $varName ? "{{ \${$varName}->{$name} }}" : '';
            
            switch ($field['type']) {
                case 'text':
                    $formFields[] = $this->getTextareaField($field, $value);
                    break;
                case 'boolean':
                    $formFields[] = $this->getCheckboxField($field, $value);
                    break;
                case 'select':
                    $formFields[] = $this->getSelectField($field, $value);
                    break;
                default:
                    $formFields[] = $this->getInputField($field, $value);
            }
        }
        return implode("\n\n", $formFields);
    }

    protected function getInputField($field, $value)
    {
        $type = $this->getInputType($field['type']);
        return <<<HTML
                    <div class="mb-3">
                        <label class="form-label">{$field['label']}</label>
                        <input type="{$type}" 
                               name="{$field['name']}" 
                               class="form-control @error('{$field['name']}') is-invalid @enderror" 
                               value="{$value}">
                        @error('{$field['name']}')
                            <div class="invalid-feedback">{{ \$message }}</div>
                        @enderror
                    </div>
HTML;
    }

    protected function getTextareaField($field, $value)
    {
        return <<<HTML
                    <div class="mb-3">
                        <label class="form-label">{$field['label']}</label>
                        <textarea name="{$field['name']}" 
                                  class="form-control @error('{$field['name']}') is-invalid @enderror" 
                                  rows="3">{$value}</textarea>
                        @error('{$field['name']}')
                            <div class="invalid-feedback">{{ \$message }}</div>
                        @enderror
                    </div>
HTML;
    }

    protected function getCheckboxField($field, $value)
    {
        return <<<HTML
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" 
                                   name="{$field['name']}" 
                                   class="form-check-input @error('{$field['name']}') is-invalid @enderror" 
                                   value="1"
                                   {{ {$value} ? 'checked' : '' }}>
                            <label class="form-check-label">{$field['label']}</label>
                            @error('{$field['name']}')
                                <div class="invalid-feedback">{{ \$message }}</div>
                            @enderror
                        </div>
                    </div>
HTML;
    }

    protected function getSelectField($field, $value)
    {
        return <<<HTML
                    <div class="mb-3">
                        <label class="form-label">{$field['label']}</label>
                        <select name="{$field['name']}" 
                                class="form-select @error('{$field['name']}') is-invalid @enderror">
                            <option value="">Seçiniz</option>
                            <!-- Seçenekler dinamik olarak eklenecek -->
                        </select>
                        @error('{$field['name']}')
                            <div class="invalid-feedback">{{ \$message }}</div>
                        @enderror
                    </div>
HTML;
    }

    protected function getInputType($type)
    {
        return match($type) {
            'integer', 'decimal' => 'number',
            'email' => 'email',
            'password' => 'password',
            'date' => 'date',
            'datetime' => 'datetime-local',
            'file' => 'file',
            'image' => 'file',
            default => 'text'
        };
    }

    protected function getEditModalScript($fields)
    {
        $assignments = [];
        foreach ($fields as $field) {
            if ($field['type'] === 'boolean') {
                $assignments[] = "\$('#edit{$field['name']}').prop('checked', data.{$field['name']});";
            } else {
                $assignments[] = "\$('#edit{$field['name']}').val(data.{$field['name']});";
            }
        }
        return implode("\n            ", $assignments);
    }

    protected function indent($text, $spaces)
    {
        return implode("\n" . str_repeat(' ', $spaces), explode("\n", $text));
    }

    protected function getIndexTemplate($name, $fields)
    {
        $modelName = Str::singular($name);
        $variableName = Str::camel($modelName);
        $pluralVariableName = Str::plural($variableName);

        return <<<BLADE
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">{$name}</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Yeni Ekle
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            @foreach(\${$pluralVariableName}->first()?->getFillable() ?? [] as \$field)
                                <th>{{ \${$pluralVariableName}->first()?->labels[\$field] ?? Str::title(\$field) }}</th>
                            @endforeach
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\${$pluralVariableName} as \${$variableName}Item)
                            <tr>
                                @foreach(\${$variableName}Item->getFillable() as \$field)
                                    <td>{{ \${$variableName}Item->\$field }}</td>
                                @endforeach
                                <td>
                                    <button class="btn btn-sm btn-primary edit-btn" 
                                            data-id="{{ \${$variableName}Item->id }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-btn"
                                            data-id="{{ \${$variableName}Item->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count(\${$pluralVariableName}->first()?->getFillable() ?? []) + 1 }}" class="text-center">
                                    Kayıt bulunamadı
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ \${$pluralVariableName}->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yeni {$modelName}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createForm">
                @csrf
                <div class="modal-body">
                    @foreach(\${$pluralVariableName}->first()?->getFillable() ?? [] as \$field)
                        <div class="mb-3">
                            <label class="form-label">{{ \${$pluralVariableName}->first()?->labels[\$field] ?? Str::title(\$field) }}</label>
                            <input type="{{ \${$pluralVariableName}->first()?->casts[\$field] === 'text' ? 'textarea' : 'text' }}" 
                                   name="{{ \$field }}" 
                                   class="form-control @error(\$field) is-invalid @enderror" 
                                   value="">
                            @error(\$field)
                                <div class="invalid-feedback">{{ \$message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{$modelName} Düzenle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editId">
                <div class="modal-body">
                    @foreach(\${$pluralVariableName}->first()?->getFillable() ?? [] as \$field)
                        <div class="mb-3">
                            <label class="form-label">{{ \${$pluralVariableName}->first()?->labels[\$field] ?? Str::title(\$field) }}</label>
                            <input type="{{ \${$pluralVariableName}->first()?->casts[\$field] === 'text' ? 'textarea' : 'text' }}" 
                                   name="{{ \$field }}" 
                                   id="edit{{ \$field }}"
                                   class="form-control @error(\$field) is-invalid @enderror">
                            @error(\$field)
                                <div class="invalid-feedback">{{ \$message }}</div>
                            @enderror
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
\$(document).ready(function() {
    // Create form submit
    \$('#createForm').submit(function(e) {
        e.preventDefault();
        \$.ajax({
            url: '{{ route("admin.{$pluralVariableName}.store") }}',
            method: 'POST',
            data: \$(this).serialize(),
            success: function(response) {
                if(response.success) {
                    \$('#createModal').modal('hide');
                    toastr.success(response.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Bir hata oluştu');
            }
        });
    });

    // Edit button click
    \$('.edit-btn').click(function() {
        const id = \$(this).data('id');
        \$('#editId').val(id);
        
        \$.get(\`/admin/{$pluralVariableName}/\${id}/edit\`, function(data) {
            @foreach(\${$pluralVariableName}->first()?->getFillable() ?? [] as \$field)
                \$(\`#edit{{ \$field }}\`).val(data.{{ \$field }});
            @endforeach
        });
    });

    // Edit form submit
    \$('#editForm').submit(function(e) {
        e.preventDefault();
        const id = \$('#editId').val();
        \$.ajax({
            url: \`/admin/{$pluralVariableName}/\${id}\`,
            method: 'PUT',
            data: \$(this).serialize(),
            success: function(response) {
                if(response.success) {
                    \$('#editModal').modal('hide');
                    toastr.success(response.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Bir hata oluştu');
            }
        });
    });

    // Delete button click
    \$('.delete-btn').click(function() {
        if(!confirm('Bu kaydı silmek istediğinizden emin misiniz?')) return;
        
        const id = \$(this).data('id');
        \$.ajax({
            url: \`/admin/{$pluralVariableName}/\${id}\`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Bir hata oluştu');
            }
        });
    });
});
</script>
@endpush

@endsection
BLADE;
    }

    protected function getCreateTemplate($name, $fields)
    {
        $modelName = Str::singular($name);
        $pluralVariableName = Str::plural(Str::camel($modelName));
        $displayName = Str::title(str_replace('_', ' ', $modelName));

        return <<<BLADE
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Yeni {$displayName}</h1>
        <a href="{{ route('admin.{$pluralVariableName}.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.{$pluralVariableName}.store') }}" method="POST">
                @csrf
                {$this->getFormFields($fields)}
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
BLADE;
    }

    protected function getEditTemplate($name, $fields)
    {
        $modelName = Str::singular($name);
        $variableName = Str::camel($modelName);
        $pluralVariableName = Str::plural($variableName);
        $displayName = Str::title(str_replace('_', ' ', $modelName));

        return <<<BLADE
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">{$displayName} Düzenle</h1>
        <a href="{{ route('admin.{$pluralVariableName}.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.{$pluralVariableName}.update', \${$variableName}) }}" method="POST">
                @csrf
                @method('PUT')
                {$this->getFormFields($fields, 'edit', $variableName)}
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
BLADE;
    }

    protected function getIndexViewTemplate($moduleName)
    {
        return <<<BLADE
@extendLayout

@section('content')
// ... existing code ...
@endsection
BLADE;
    }
} 