<nav class="sidebar">
    <div class="sidebar-header">
        <div class="d-flex align-items-center">
            <span class="sidebar-brand">Otel Sales</span>
        </div>
        <button class="btn btn-link text-white p-0 sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div class="sidebar-user">
        <div class="d-flex align-items-center">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="ms-3">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ Auth::user()->role?->name ?? 'Rol Atanmamış' }}</div>
            </div>
        </div>
    </div>

    <ul class="sidebar-nav">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Ana Sayfa</span>
            </a>
        </li>

        <!-- Dinamik Modüller -->
        @php
            function turkishPlural($word) {
                $lastLetter = mb_substr($word, -1);
                $lastVowel = preg_match_all('/[aeıioöuü]/ui', mb_strtolower($word), $matches);
                $lastVowel = end($matches[0]);
                
                if (in_array($lastVowel, ['a', 'ı', 'o', 'u'])) {
                    return $word . 'lar';
                } else {
                    return $word . 'ler';
                }
            }

            $modules = collect(File::files(app_path('Models')))
                ->filter(function ($file) {
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
                    $name = str_replace('.php', '', $file->getFilename());
                    $baseTitle = ucfirst(Str::lower($name));
                    return [
                        'name' => $name,
                        'route' => Str::lower($name),
                        'title' => turkishPlural($baseTitle)
                    ];
                })
                ->sortBy('title');
        @endphp

        <!-- Yorumlar -->
        <li class="nav-item">
            <a href="{{ route('admin.comments.index') }}" 
               class="nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                <i class="fas fa-comments"></i>
                <span>Yorumlar</span>
            </a>
        </li>

        @foreach($modules as $module)
            @php
                $routeName = 'admin.' . $module['route'] . '.index';
                if (!Route::has($routeName)) continue;
                
                // Modül için özel ikon belirleme
                $icon = 'fas fa-cube';
                if ($module['route'] === 'yorum') {
                    $icon = 'fas fa-comments';
                }
            @endphp
            <li class="nav-item">
                <a href="{{ route($routeName) }}" 
                   class="nav-link {{ request()->routeIs('admin.' . $module['route'] . '.*') ? 'active' : '' }}">
                    <i class="{{ $icon }}"></i>
                    <span>{{ $module['title'] }}</span>
                </a>
            </li>
        @endforeach

        <li class="nav-item">
            <a href="{{ route('offers.index') }}" class="nav-link {{ request()->routeIs('offers.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span>Teklifler</span>
            </a>
        </li>

        @if(Auth::user()->role && in_array(Auth::user()->role->slug, ['admin', 'super-admin']))
        <li class="nav-title">Yönetim</li>

        <!-- Tanımlar -->
        <li class="nav-item">
            <a href="#definitionsSubmenu" data-bs-toggle="collapse" 
               class="nav-link {{ request()->routeIs('agencies.*', 'companies.*') ? 'active' : '' }}">
                <i class="fas fa-list"></i>
                <span>Tanımlar</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <ul class="collapse {{ request()->routeIs('agencies.*', 'companies.*') ? 'show' : '' }}" 
                id="definitionsSubmenu">
                <li>
                    <a href="{{ route('agencies.index') }}" 
                       class="nav-link {{ request()->routeIs('agencies.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Acenteler</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('companies.index') }}" 
                       class="nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}">
                        <i class="fas fa-hotel"></i>
                        <span>Oteller</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item">
            <a href="#modulesSubmenu" data-bs-toggle="collapse" 
               class="nav-link {{ request()->is('admin/modules*') ? 'active' : '' }}">
                <i class="fas fa-cubes"></i>
                <span>Modüller</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <ul class="collapse {{ request()->is('admin/modules*') ? 'show' : '' }}" 
                id="modulesSubmenu">
                <li>
                    <a href="{{ route('admin.modules.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.modules.*') ? 'active' : '' }}">
                        <i class="fas fa-cubes me-2"></i>
                        <span>Modüller</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.module-generator.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.module-generator.index') ? 'active' : '' }}">
                        <i class="fas fa-plus"></i>
                        <span>Yeni Modül</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Settings -->
        <li class="nav-item">
            <a href="#settingsSubmenu" data-bs-toggle="collapse" 
               class="nav-link {{ request()->routeIs('settings.*') || request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span>Ayarlar</span>
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <ul class="collapse {{ request()->routeIs('settings.*') || request()->routeIs('users.*') ? 'show' : '' }}" 
                id="settingsSubmenu">
                <li>
                    <a href="{{ route('admin.users.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Kullanıcılar</span>
                    </a>
                </li>
                @if(Auth::user()->role && Auth::user()->role->slug === 'super-admin')
                <li>
                    <a href="{{ route('admin.module-generator.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.module-generator.index') ? 'active' : '' }}">
                        <i class="fas fa-magic"></i>
                        <span>Modül Oluşturucu</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('admin.settings.statuses.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.settings.statuses.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        <span>Durumlar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.resource-definitions.index') }}" 
                       class="nav-link {{ request()->routeIs('admin.resource-definitions.*') ? 'active' : '' }}">
                        <i class="fas fa-tag"></i>
                        <span>Kaynak Tanımları</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        <!-- Çıkış -->
        <li class="nav-item mt-auto">
            <a href="#" class="nav-link" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span>Çıkış Yap</span>
            </a>
        </li>
    </ul>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

<style>
.sidebar {
    width: 260px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background: #1e1e2d;
    color: #fff;
    z-index: 1000;
    transition: all 0.3s ease;
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    padding: 1.5rem;
    background: rgba(0,0,0,0.1);
    border-bottom: 1px solid rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.sidebar-brand {
    font-size: 1.25rem;
    font-weight: 600;
    color: #fff;
    letter-spacing: 0.5px;
}

.sidebar-logo {
    display: none;
}

.sidebar-user {
    padding: 1.5rem;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    background: rgba(0,0,0,0.1);
}

.user-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(45deg, #2b5876, #4e4376);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}

.user-name {
    font-weight: 600;
    font-size: 0.95rem;
    color: #fff;
}

.user-role {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.7);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.sidebar-nav {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 1rem 0;
    list-style: none;
    margin: 0;
    overflow-y: auto;
}

.nav-title {
    padding: 1.2rem 1.5rem 0.5rem;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: rgba(255,255,255,0.4);
    font-weight: 600;
}

.nav-item {
    margin: 0.2rem 0;
}

.nav-link {
    padding: 0.8rem 1.5rem;
    color: rgba(255,255,255,0.7);
    display: flex;
    align-items: center;
    text-decoration: none;
    transition: all 0.3s;
    border-radius: 0;
    position: relative;
    overflow: hidden;
}

.nav-link:hover {
    color: #fff;
    background: rgba(255,255,255,0.1);
    padding-left: 1.8rem;
}

.nav-link.active {
    color: #fff;
    background: linear-gradient(118deg, #7367f0, #9e95f5);
    box-shadow: 0 0 10px rgba(115,103,240,0.5);
    font-weight: 500;
}

.nav-link i {
    width: 20px;
    margin-right: 0.75rem;
    font-size: 1.1rem;
}

/* Alt menü stilleri */
.collapse {
    background: rgba(0,0,0,0.1);
}

.collapse .nav-link {
    padding-left: 3.5rem;
    font-size: 0.9rem;
}

.collapse .nav-link:hover {
    padding-left: 3.8rem;
}

.nav-link .fa-chevron-down {
    transition: transform 0.3s;
    font-size: 0.8rem;
    opacity: 0.8;
}

.nav-link[aria-expanded="true"] .fa-chevron-down {
    transform: rotate(180deg);
}

/* Daraltılmış durum */
.sidebar.collapsed {
    width: 70px;
}

.sidebar.collapsed .sidebar-brand,
.sidebar.collapsed .user-name,
.sidebar.collapsed .user-role,
.sidebar.collapsed .nav-link span,
.sidebar.collapsed .nav-title {
    display: none;
}

.sidebar.collapsed .nav-link {
    justify-content: center;
    padding: 1rem;
}

.sidebar.collapsed .nav-link i {
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        margin-left: -260px;
    }
    
    .sidebar.show {
        margin-left: 0;
    }
}

/* Sidebar footer'ı kaldır */
.sidebar-footer {
    display: none;
}
</style>
