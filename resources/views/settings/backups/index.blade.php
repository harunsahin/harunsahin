@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-database me-2"></i>Yedeklemeler
                    </h5>
                    <a href="{{ route('admin.backups.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus-circle me-1"></i>Yeni Yedekleme
                    </a>
                </div>
                <div class="card-body">
                    @if(count($backups) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Dosya Adı</th>
                                    <th>Tür</th>
                                    <th class="text-end">Boyut</th>
                                    <th class="text-end">Oluşturulma Tarihi</th>
                                    <th class="text-center" style="width: 150px;">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($backups as $backup)
                                <tr>
                                    <td>{{ $backup['name'] }}</td>
                                    <td>
                                        @if(str_contains($backup['name'], '-db-'))
                                            <span class="badge bg-info">
                                                <i class="fas fa-database me-1"></i>Veritabanı
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="fas fa-folder me-1"></i>Dosyalar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">{{ formatBytes($backup['size']) }}</td>
                                    <td class="text-end">{{ $backup['created_at']->format('d.m.Y H:i:s') }}</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.backups.download', $backup['name']) }}" 
                                               class="btn btn-primary" 
                                               title="İndir">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-danger delete-backup" 
                                                    data-backup="{{ $backup['name'] }}"
                                                    title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <img src="{{ asset('images/no-data.svg') }}" alt="Veri Yok" style="width: 120px; opacity: 0.5;">
                        <p class="text-muted mt-3">Henüz hiç yedekleme oluşturulmamış.</p>
                        <a href="{{ route('admin.backups.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i>İlk Yedeklemeyi Oluştur
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Yedekleme silme işlemi
    $('.delete-backup').on('click', function() {
        const backup = $(this).data('backup');
        
        Swal.fire({
            title: 'Emin misiniz?',
            text: `"${backup}" yedeklemesini silmek istediğinize emin misiniz?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ route('admin.backups.index') }}/${backup}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Başarılı!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: xhr.responseJSON?.message || 'Yedekleme silinirken bir hata oluştu'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.btn-group .btn {
    padding: 0.25rem 0.5rem;
}

.table > :not(caption) > * > * {
    vertical-align: middle;
}

.badge {
    font-weight: 500;
}
</style>
@endpush
@endsection 