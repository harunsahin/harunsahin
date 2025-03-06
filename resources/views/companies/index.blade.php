@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Şirketler</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCompanyModal">
            <i class="fas fa-plus"></i> Yeni Şirket
        </button>
    </div>

    <!-- Arama Formu -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="searchForm" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Şirket Adı</label>
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">E-posta</label>
                    <input type="email" class="form-control" name="email" value="{{ request('email') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Durum</label>
                    <select class="form-select" name="status">
                        <option value="">Tümü</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Pasif</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Ara
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-times"></i> Temizle
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Şirket Adı</th>
                            <th>Telefon</th>
                            <th>E-posta</th>
                            <th>Adres</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody id="companiesTableBody">
                        @include('companies.partials.table-rows')
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $companies->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Yeni Şirket Modal -->
<div class="modal fade" id="createCompanyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Yeni Şirket</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createCompanyForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Şirket Adı</label>
                        <input type="text" class="form-control" name="name" required>
                        <div class="invalid-feedback">Bu alan zorunludur</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefon</label>
                        <input type="tel" class="form-control" name="phone" placeholder="(___) ___ __ __">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-posta</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adres</label>
                        <textarea class="form-control" name="address" rows="3"></textarea>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                        <label class="form-check-label">Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> İptal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Düzenleme Modal -->
<div class="modal fade" id="editCompanyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Şirket Düzenle</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCompanyForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editCompanyId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Şirket Adı</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                        <div class="invalid-feedback">Bu alan zorunludur</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefon</label>
                        <input type="tel" class="form-control" id="editPhone" name="phone" placeholder="(___) ___ __ __">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-posta</label>
                        <input type="email" class="form-control" id="editEmail" name="email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adres</label>
                        <textarea class="form-control" id="editAddress" name="address" rows="3"></textarea>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="editIsActive" name="is_active" value="1">
                        <label class="form-check-label">Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> İptal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Tabloyu yenileme fonksiyonu
    function refreshTable() {
        const currentUrl = new URL(window.location.href);
        
        $.ajax({
            url: currentUrl.pathname + currentUrl.search,
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                $('#companiesTableBody').html(response);
                // Tooltip'leri yeniden aktif et
                $('[data-bs-toggle="tooltip"]').tooltip();
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: 'Tablo yenilenirken bir hata oluştu.'
                });
            }
        });
    }

    // Sayfa başına kayıt sayısı değiştiğinde
    $(document).on('change', '.per-page-select', function() {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('per_page', $(this).val());
        currentUrl.searchParams.set('page', 1);
        window.location.href = currentUrl.toString();
    });

    // Arama formunu temizle
    window.resetForm = function() {
        $('#searchForm')[0].reset();
        $('#searchForm').submit();
    }

    // Yeni şirket oluşturma
    $('#createCompanyForm').submit(function(e) {
        e.preventDefault();
        
        // Form validasyonu
        if (!this.checkValidity()) {
            e.stopPropagation();
            $(this).addClass('was-validated');
            return;
        }

        // Submit butonunu devre dışı bırak
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Kaydediliyor...');

        $.ajax({
            url: '{{ route("companies.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    $('#createCompanyModal').modal('hide');
                    toastr.success(response.message);
                    refreshTable();
                }
            },
            error: function(xhr) {
                let errorMessage = 'Bir hata oluştu';
                if (xhr.responseJSON?.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                } else if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            },
            complete: function() {
                // Submit butonunu tekrar aktif et
                submitBtn.prop('disabled', false).html('<i class="fas fa-save me-1"></i>Kaydet');
            }
        });
    });

    // Düzenleme modalını aç
    $(document).on('click', '.edit-company', function() {
        const company = $(this).data('company');
        $('#editCompanyId').val(company.id);
        $('#editName').val(company.name);
        $('#editPhone').val(company.phone);
        $('#editEmail').val(company.email);
        $('#editAddress').val(company.address);
        $('#editIsActive').prop('checked', company.is_active);
    });

    // Şirket güncelleme
    $('#editCompanyForm').submit(function(e) {
        e.preventDefault();
        
        const id = $('#editCompanyId').val();
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Güncelleniyor...');

        $.ajax({
            url: `/companies/${id}`,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    $('#editCompanyModal').modal('hide');
                    toastr.success(response.message);
                    refreshTable();
                }
            },
            error: function(xhr) {
                let errorMessage = 'Bir hata oluştu';
                if (xhr.responseJSON?.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                } else if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-save me-1"></i>Güncelle');
            }
        });
    });

    // Şirket silme
    $(document).on('click', '.delete-company', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        Swal.fire({
            title: 'Emin misiniz?',
            text: `"${name}" şirketini silmek istediğinize emin misiniz?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/companies/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if(response.success) {
                            toastr.success(response.message);
                            refreshTable();
                        }
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON?.message || 'Silme işlemi sırasında bir hata oluştu.');
                    }
                });
            }
        });
    });

    // Durum değiştirme
    $(document).on('change', '.toggle-status', function() {
        const id = $(this).data('id');
        const checkbox = $(this);
        
        $.ajax({
            url: `/companies/${id}/status`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                checkbox.prop('checked', !checkbox.prop('checked'));
                toastr.error(xhr.responseJSON?.message || 'Durum güncellenirken bir hata oluştu.');
            }
        });
    });
});
</script>
@endpush
@endsection 