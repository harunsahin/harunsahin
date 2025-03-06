<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Services\PermissionService;
use Illuminate\Support\Str;

class RoutePermissionServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Route'lar yüklendikten sonra çalışacak
        $this->app->booted(function () {
            $this->syncRoutePermissions();
        });
    }

    protected function syncRoutePermissions()
    {
        $routes = Route::getRoutes();
        $modules = [];

        foreach ($routes as $route) {
            // Sadece admin route'larını al
            if (str_starts_with($route->uri(), 'admin/')) {
                $path = explode('/', $route->uri());
                if (count($path) > 1) {
                    $module = $path[1]; // admin/{module}
                    
                    // Özel durumları kontrol et
                    if (str_contains($module, '{')) continue; // Dinamik parametreleri atla
                    if (in_array($module, ['roles'])) continue; // Bazı modülleri atla
                    
                    // Route adından modül adını temizle
                    $module = preg_replace('/[0-9]+/', '', $module);
                    $module = str_replace(['-', '_'], ' ', $module);
                    $module = Str::singular($module);
                    
                    if (!isset($modules[$module])) {
                        $modules[$module] = ucfirst($module) . ' Management';
                    }
                }
            }
        }

        // Bulunan modüller için izinleri oluştur
        foreach ($modules as $module => $description) {
            PermissionService::createModulePermissions($module, $description);
        }

        // Özel izinleri de ekle
        PermissionService::syncCustomPermissions();
    }
} 