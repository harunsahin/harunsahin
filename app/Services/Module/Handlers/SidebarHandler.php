<?php

namespace App\Services\Module\Handlers;

use App\Services\Module\Exceptions\ModuleGenerationException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SidebarHandler
{
    private const SIDEBAR_PATH = 'resources/views/layouts/sidebar.blade.php';
    private const SIDEBAR_MARKER = '<!-- Modules -->';

    public function handle(string $name): void
    {
        $sidebarPath = base_path(self::SIDEBAR_PATH);
        
        if (!File::exists($sidebarPath)) {
            throw new ModuleGenerationException('Kenar çubuğu dosyası bulunamadı');
        }

        $sidebarContent = File::get($sidebarPath);
        $moduleLink = $this->generateModuleLink($name);

        // Modül bağlantısını ekle
        $pattern = '/<!-- Modules -->\s*<li class="nav-item">/';
        $replacement = self::SIDEBAR_MARKER . "\n" . $moduleLink . "\n        <li class=\"nav-item\">";
        
        $sidebarContent = preg_replace($pattern, $replacement, $sidebarContent);

        File::put($sidebarPath, $sidebarContent);

        \Log::info('Kenar çubuğu güncellendi', [
            'module' => $name
        ]);
    }

    private function generateModuleLink(string $name): string
    {
        $displayName = Str::title(str_replace('_', ' ', $name));
        $routeName = Str::snake($name);
        
        return <<<HTML
        <li class="nav-item">
            <a href="{{ route('admin.{$routeName}.index') }}" class="nav-link {{ request()->routeIs('admin.{$routeName}.*') ? 'active' : '' }}">
                <i class="fas fa-cube"></i>
                <span>{$displayName}</span>
            </a>
        </li>
        HTML;
    }
} 