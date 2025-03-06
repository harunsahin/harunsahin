<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // View composer ile tüm viewlara default layout atama
        View::composer('*', function ($view) {
            // Eğer view bir layout extend etmiyorsa veya farklı bir layout kullanıyorsa
            if (!str_contains($view->getName(), 'layouts.') && 
                !str_contains($view->getPath(), '@extends(\'layouts.app\')')) {
                // layouts.app dışında bir layout kullanılmaya çalışılıyorsa hata fırlat
                if (preg_match('/@extends\([\'"]((?!layouts\.app).)*[\'"]\)/', file_get_contents($view->getPath()))) {
                    throw new \Exception('Sadece layouts.app layout\'u kullanılabilir! Lütfen @extends(\'layouts.app\') kullanın.');
                }
            }
        });

        // Özel blade direktifi oluşturma
        Blade::directive('extendLayout', function () {
            return '@extends(\'layouts.app\')';
        });
    }
} 