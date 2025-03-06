@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-tags me-2"></i>Durumlar
            </h5>
            <button type="button" 
                    class="btn btn-light" 
                    data-bs-toggle="modal" 
                    data-bs-target="#createStatusModal">
                <i class="fas fa-plus-circle me-1"></i>Yeni Durum
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ad</th>
                            <th>Slug</th>
                            <th>Renk</th>
                            <th>Tip</th>
                            <th>Sıra</th>
                            <th>Durum</th>
                            <th class="text-end">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($statuses as $status)
                        <tr>
                            <td>{{ $status->id }}</td>
                            <td>{{ $status->name }}</td>
                            <td>{{ $status->slug }}</td>
                            <td>
                                <span class="badge" style="background-color: {{ $status->color }}">
                                    {{ $status->color }}
                                </span>
                            </td>
                            <td>{{ $status->type }}</td>
                            <td>{{ $status->order }}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input type="checkbox" 
                                           class="form-check-input toggle-status" 
                                           data-id="{{ $status->id }}"
                                           {{ $status->is_active ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td class="text-end">
                                <button type="button" 
                                        class="btn btn-sm btn-primary edit-status"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editStatusModal"
                                        data-status="{{ json_encode($status) }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button type="button" 
                                        class="btn btn-sm btn-danger delete-status"
                                        data-id="{{ $status->id }}"
                                        data-name="{{ $status->name }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>Yeni Durum
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createStatusForm" action="{{ route('admin.settings.statuses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Ad</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="color" class="form-label">Renk</label>
                        <input type="color" class="form-control" id="color" name="color" value="#000000">
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Tip</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="general">Genel</option>
                            <option value="offer">Teklif</option>
                            <option value="company">Şirket</option>
                            <option value="agency">Acente</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="order" class="form-label">Sıra</label>
                        <input type="number" class="form-control" id="order" name="order" value="0" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Durum Düzenle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editStatusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Ad</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_color" class="form-label">Renk</label>
                        <input type="color" class="form-control" id="edit_color" name="color">
                    </div>
                    <div class="mb-3">
                        <label for="edit_type" class="form-label">Tip</label>
                        <select class="form-select" id="edit_type" name="type" required>
                            <option value="general">Genel</option>
                            <option value="offer">Teklif</option>
                            <option value="company">Şirket</option>
                            <option value="agency">Acente</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_order" class="form-label">Sıra</label>
                        <input type="number" class="form-control" id="edit_order" name="order" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Form submit işlemleri
    $('#createStatusForm').on('submit', function(e) {
        e.preventDefault();
        
        // Submit butonunu devre dışı bırak
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin me-1"></i>Kaydediliyor...');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#createStatusModal').modal('hide');
                    $('#createStatusForm')[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Başarılı!',
                        text: response.message,
                        confirmButtonText: 'Tamam',
                        confirmButtonColor: '#0d6efd',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'Bir hata oluştu';
                if (xhr.responseJSON?.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                } else if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: errorMessage,
                    confirmButtonText: 'Tamam',
                    confirmButtonColor: '#dc3545',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            },
            complete: function() {
                // Submit butonunu tekrar aktif et
                submitBtn.prop('disabled', false)
                    .html('<i class="fas fa-save me-1"></i>Kaydet');
            }
        });
    });

    // Düzenleme modalını aç
    $('.edit-status').on('click', function() {
        const status = $(this).data('status');
        const form = $('#editStatusForm');
        
        form.attr('action', `/admin/settings/statuses/${status.id}`);
        $('#edit_name').val(status.name);
        $('#edit_color').val(status.color);
        $('#edit_type').val(status.type);
        $('#edit_order').val(status.order);
    });

    // Düzenleme formu submit
    $('#editStatusForm').on('submit', function(e) {
        e.preventDefault();
        
        // Submit butonunu devre dışı bırak
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin me-1"></i>Kaydediliyor...');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#editStatusModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Başarılı!',
                        text: response.message,
                        confirmButtonText: 'Tamam',
                        confirmButtonColor: '#0d6efd',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'Bir hata oluştu';
                if (xhr.responseJSON?.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                } else if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: errorMessage,
                    confirmButtonText: 'Tamam',
                    confirmButtonColor: '#dc3545',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            },
            complete: function() {
                // Submit butonunu tekrar aktif et
                submitBtn.prop('disabled', false)
                    .html('<i class="fas fa-save me-1"></i>Kaydet');
            }
        });
    });

    // Durum değiştirme
    $('.toggle-status').on('change', function() {
        const id = $(this).data('id');
        const isChecked = $(this).prop('checked');
        
        $.ajax({
            url: `/admin/settings/statuses/${id}/toggle`,
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                is_active: isChecked
            },
            error: function() {
                alert('Durum değiştirilemedi');
                // Checkbox'ı eski haline getir
                $(this).prop('checked', !isChecked);
            }
        });
    });

    // Silme işlemi
    $('.delete-status').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        Swal.fire({
            title: 'Emin misiniz?',
            text: `"${name}" durumunu silmek istediğinize emin misiniz?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/settings/statuses/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Başarılı!',
                                text: response.message,
                                confirmButtonText: 'Tamam',
                                confirmButtonColor: '#0d6efd',
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Bir hata oluştu';
                        if (xhr.responseJSON?.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: errorMessage,
                            confirmButtonText: 'Tamam',
                            confirmButtonColor: '#dc3545',
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOutUp'
                            }
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
/* Renk seçici input'u için özel stil */
input[type="color"] {
    height: 38px;
    padding: 2px;
}

/* Switch toggle için özel stil */
.form-switch .form-check-input {
    width: 3em;
}

/* Responsive tasarım için */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-sm {
        padding: 0.2rem 0.4rem;
        font-size: 0.75rem;
    }
}
</style>
@endpush
@endsection 