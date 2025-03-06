<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Turzz') }} - @yield('title', 'Panel')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    
    <!-- Sortable.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    
    <style>
        /* Sidebar Styles */
        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: #343a40;
            color: #fff;
            transition: all 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar .nav-link {
            color: #fff;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
        }

        .sidebar .nav-icon {
            font-size: 1.2rem;
            min-width: 25px;
            text-align: center;
        }

        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .menu-title {
            display: none;
        }

        .sidebar.collapsed .nav-link {
            padding: 10px;
            justify-content: center;
        }

        .sidebar.collapsed .nav-icon {
            margin: 0;
            font-size: 1.4rem;
        }

        /* Content Styles */
        .content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .content.expanded {
            margin-left: 70px;
        }

        /* Sub Menu Styles */
        .sub-menu {
            padding-left: 2rem;
        }

        .sub-menu .nav-link {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .nav-item .fa-chevron-down {
            transition: transform 0.3s;
        }

        .nav-item [aria-expanded="true"] .fa-chevron-down {
            transform: rotate(180deg);
        }

        /* Table Styles */
        .table th {
            background-color: #f8f9fa;
        }

        /* Form Styles */
        .form-floating > .form-control {
            height: calc(3.5rem + 2px);
        }

        .form-floating > label {
            padding: 1rem 0.75rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
                width: 250px !important;
            }
            
            .sidebar.show {
                margin-left: 0;
            }
            
            .content {
                margin-left: 0 !important;
            }

            .content.expanded {
                margin-left: 0 !important;
            }

            body.sidebar-open {
                overflow: hidden;
            }

            .sidebar-backdrop {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

            .sidebar-backdrop.show {
                display: block;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            @include('layouts.sidebar')
        </aside>

        <!-- Content -->
        <div class="content" id="content">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    
                    <!-- Sağ taraftaki admin menüsünü kaldır -->
                </div>
            </nav>

            <!-- Main Content -->
            @yield('content')
        </div>
    </div>

    <!-- Scripts (sıralama çok önemli) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    
    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/tr.js"></script>
    
    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Dashboard JS -->
    @if(request()->routeIs('dashboard'))
    <script src="{{ asset('js/dashboard.js') }}"></script>
    @endif
    
    <script>
        // CSRF Token ayarı
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Sayfa yüklendiğinde token'ı yenile
        $(document).ready(function() {
            refreshToken();
            initializeSidebar();
        });

        // Token yenileme fonksiyonu
        function refreshToken() {
            $.get('/csrf-token', function(data) {
                $('meta[name="csrf-token"]').attr('content', data.token);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': data.token
                    }
                });
            });
        }

        // Her 30 dakikada bir token'ı yenile
        setInterval(refreshToken, 30 * 60 * 1000);

        function initializeSidebar() {
            const isMobile = $(window).width() <= 768;
            const $sidebar = $('.sidebar');
            const $content = $('.content');
            const $backdrop = $('.sidebar-backdrop');
            const $body = $('body');

            // Önceki durumu kontrol et (sadece masaüstünde)
            if (!isMobile && localStorage.getItem('sidebarCollapsed') === 'true') {
                $sidebar.addClass('collapsed');
                $content.addClass('expanded');
            }

            // Toggle butonu tıklama
            $('.sidebar-toggle').on('click', function(e) {
                e.preventDefault();
                
                if (isMobile) {
                    $sidebar.toggleClass('show');
                    $backdrop.toggleClass('show');
                    $body.toggleClass('sidebar-open');
                } else {
                    $sidebar.toggleClass('collapsed');
                    $content.toggleClass('expanded');
                    localStorage.setItem('sidebarCollapsed', $sidebar.hasClass('collapsed'));
                }
            });

            // Mobilde backdrop tıklaması
            $('.sidebar-backdrop').on('click', function() {
                $sidebar.removeClass('show');
                $backdrop.removeClass('show');
                $body.removeClass('sidebar-open');
            });
        }

        // Toastr Options
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 3000
        };

        // Flash Messages
        @if(session('success'))
            toastr.success('{{ session('success') }}');
        @endif

        @if(session('error'))
            toastr.error('{{ session('error') }}');
        @endif
    </script>
    @stack('scripts')
</body>
</html>
