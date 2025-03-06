@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Durumlar Kartı -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-tags me-2 text-primary"></i>Durumlar
                    </h5>
                    <p class="card-text flex-grow-1">
                        Teklifler, şirketler ve acenteler için durum tanımlarını yönetin.
                    </p>
                    <a href="{{ route('admin.settings.statuses.index') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-cog me-1"></i>Durumları Yönet
                    </a>
                </div>
            </div>
        </div>

        <!-- Yedekleme Kartı -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-database me-2 text-success"></i>Yedekleme
                    </h5>
                    <p class="card-text flex-grow-1">
                        Veritabanı yedeklerini oluşturun ve yönetin.
                    </p>
                    <a href="{{ route('admin.backups.index') }}" class="btn btn-success mt-3">
                        <i class="fas fa-download me-1"></i>Yedekleri Yönet
                    </a>
                </div>
            </div>
        </div>

        <!-- Kullanıcılar Kartı -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-users me-2 text-info"></i>Kullanıcılar
                    </h5>
                    <p class="card-text flex-grow-1">
                        Sistem kullanıcılarını yönetin.
                    </p>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-info mt-3">
                        <i class="fas fa-user-cog me-1"></i>Kullanıcıları Yönet
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.card {
    transition: transform 0.2s;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.card-title {
    font-weight: 600;
    color: #333;
}

.card-text {
    color: #666;
}

.btn {
    text-transform: none;
    font-weight: 500;
}

/* Responsive düzenlemeler */
@media (max-width: 768px) {
    .card {
        margin-bottom: 1rem;
    }
    
    .btn {
        width: 100%;
    }
}
</style>
@endpush
@endsection 