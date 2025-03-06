@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Acenteler</h3>
                </div>
                <div class="card-body">
                    <!-- Arama Formu -->
                    <form id="searchForm" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" 
                                           name="search" 
                                           class="form-control" 
                                           placeholder="Acente adı, e-posta veya telefon ile ara..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="status" class="form-select">
                                        <option value="">Tüm Durumlar</option>
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Pasif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Ara
                                </button>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createAgencyModal">
                                    <i class="fas fa-plus"></i> Yeni Acente
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Tablo -->
                    <div id="agenciesTable">
                        @include('agencies.partials.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('agencies.modals.create')
@include('agencies.modals.edit')

<!-- Edit Modals -->
@foreach($agencies as $agency)
    <x-agency-modal 
        id="editAgencyModal{{ $agency->id }}"
        title="Acente Düzenle"
        :action="route('agencies.update', $agency->id)"
        method="PUT"
        :agency="$agency"
    />
@endforeach

<!-- View Modal -->
@include('agencies.modals.view')

<!-- JavaScript -->
@push('scripts')
<script>
$(document).ready(function() {
    // CSRF Token ayarı
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Yeni Acente Formu Gönderimi
    $('#createAgencyForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalBtnText = submitBtn.html();
        
        // Form validasyonu
        if (!form[0].checkValidity()) {
            form.addClass('was-validated');
            return;
        }

        // Submit butonunu devre dışı bırak ve loading göster
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Kaydediliyor...');

        // Form verilerini hazırla
        const formData = {
            name: form.find('input[name="name"]').val(),
            phone: form.find('input[name="phone"]').val(),
            email: form.find('input[name="email"]').val(),
            address: form.find('textarea[name="address"]').val(),
            is_active: form.find('input[name="is_active"]').is(':checked') ? 1 : 0,
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        console.log('Gönderilen veriler:', formData);

        $.ajax({
            url: '{{ route("agencies.store") }}',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log('Başarılı yanıt:', response);
                if (response.success) {
                    // Modal'ı kapat
                    $('#createAgencyModal').modal('hide');
                    
                    // Formu sıfırla
                    form[0].reset();
                    form.removeClass('was-validated');
                    
                    // Başarı mesajı göster
                    Swal.fire({
                        icon: 'success',
                        title: 'Başarılı!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                    // Sayfayı yenile
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            },
            error: function(xhr, status, error) {
                console.error('Ajax hatası:', {xhr, status, error});
                
                // Validasyon hataları
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(field => {
                        const input = form.find(`[name="${field}"]`);
                        input.addClass('is-invalid');
                        input.siblings('.invalid-feedback').text(errors[field][0]);
                    });

                    // Hata mesajını göster
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: Object.values(errors)[0][0],
                        confirmButtonText: 'Tamam'
                    });
                } else {
                    // Genel hata
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: xhr.responseJSON?.message || 'Bir hata oluştu',
                        confirmButtonText: 'Tamam'
                    });
                }
            },
            complete: function() {
                // Submit butonunu tekrar aktif et
                submitBtn.prop('disabled', false).html(originalBtnText);
            }
        });
    });

    // Acente Silme Fonksiyonu
    window.deleteAgency = function(id) {
        Swal.fire({
            title: 'Emin misiniz?',
            text: "Bu acente silinecek!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/agencies/${id}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Başarılı!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: xhr.responseJSON?.message || 'Acente silinirken bir hata oluştu.',
                            confirmButtonText: 'Tamam'
                        });
                    }
                });
            }
        });
    };

    // Arama formu submit olayını güncelle
    let searchTimeout;
    $('input[name="search"]').on('input', function() {
        clearTimeout(searchTimeout);
        const form = $('#searchForm');
        
        searchTimeout = setTimeout(function() {
            form.submit();
        }, 500); // 500ms bekle
    });

    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalBtnText = submitBtn.html();
        
        // Submit butonunu devre dışı bırak ve loading göster
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Aranıyor...');
        
        $.ajax({
            url: '{{ route("agencies.index") }}',
            type: 'GET',
            data: form.serialize(),
            success: function(response) {
                $('#agenciesTable').html(response);
                // URL'i güncelle
                window.history.pushState({}, '', '{{ route("agencies.index") }}?' + form.serialize());
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: 'Arama sırasında bir hata oluştu.'
                });
            },
            complete: function() {
                // Submit butonunu tekrar aktif et
                submitBtn.prop('disabled', false).html(originalBtnText);
            }
        });
    });

    // Durum değiştiğinde otomatik arama
    $('select[name="status"]').on('change', function() {
        $('#searchForm').submit();
    });

    // Durum değiştirme
    $(document).on('change', '.toggle-status', function() {
        const id = $(this).data('id');
        const status = $(this).prop('checked') ? 1 : 0;
        const toggle = $(this);
        
        $.ajax({
            url: `/agencies/${id}/toggle-status`,
            type: 'PUT',
            data: { status: status },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Başarılı!',
                    text: 'Durum başarıyla güncellendi.',
                    timer: 1500,
                    showConfirmButton: false
                });
            },
            error: function() {
                toggle.prop('checked', !status);
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: 'Durum güncellenirken bir hata oluştu.'
                });
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
/* Switch toggle için özel stil */
.form-switch .form-check-input {
    width: 3em;
}
.form-switch .form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
.form-switch .form-check-input:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
</style>
@endpush
@endsection 