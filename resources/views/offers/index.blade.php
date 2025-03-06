@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Arama Formu -->
        <x-search-form :statuses="$statuses" routePrefix="offers" />

        <!-- Tablo -->
        <div class="card">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Teklifler</h5>
                    <div class="d-flex gap-2">
                        <button type="button" 
                                id="deleteSelected" 
                                class="btn btn-danger" 
                                style="display: none;">
                            <i class="fas fa-trash me-1"></i>Seçilileri Sil
                        </button>
                        <button type="button" 
                                class="btn btn-primary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#createOfferModal">
                            <i class="fas fa-plus-circle me-2"></i>Yeni Teklif
                        </button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 80px; padding-right: 0;">
                                <div class="d-flex align-items-center">
                                    <div class="form-check mb-0 me-2">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                    ID
                                </div>
                            </th>
                            <th>Acenta</th>
                            <th>Firma</th>
                            <th>Yetkili</th>
                            <th>Giriş</th>
                            <th>Çıkış</th>
                            <th style="width: 100px">Oda/Kişi</th>
                            <th>Durum</th>
                            <th class="text-end" style="width: 120px">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody id="offersTableBody">
                        @include('offers.partials.table-rows')
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create Modal -->
        <x-offer-modal 
            id="createOfferModal" 
            title="Yeni Teklif"
            :statuses="$statuses"
            :companies="$companies"
            :agencies="$agencies"
            action="{{ route('offers.store') }}"
        />

        <!-- View Modal -->
        <x-offer-view-modal id="viewOfferModal" />

        <!-- Edit Modals -->
        @foreach($offers as $offer)
            <x-offer-modal 
                id="editOfferModal_{{ $offer->id }}"
                title="Teklif Düzenle"
                :statuses="$statuses"
                :companies="$companies"
                :agencies="$agencies"
                :offer="$offer"
                action="{{ route('offers.update', $offer->id) }}"
                method="PUT"
            />
        @endforeach
    </div>

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
    /* Genel Stiller */
    .container-fluid {
        max-width: 1800px;
    }

    /* Tablo Stilleri */
    .table {
        font-size: 0.875rem;
        margin-bottom: 0;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        padding: 0.75rem;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
    }

    .table td {
        padding: 0.75rem;
        vertical-align: middle;
    }

    /* Form Stilleri */
    .form-floating > .form-control,
    .form-floating > .form-select {
        height: calc(2.8rem + 2px);
        font-size: 0.875rem;
    }

    .form-floating > label {
        font-size: 0.875rem;
        padding: 0.75rem;
    }

    /* Buton Stilleri */
    .btn {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    /* Badge Stilleri */
    .badge {
        font-size: 0.75rem;
        padding: 0.5em 0.75em;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0.5rem !important;
        }
        
        .card {
            border-radius: 0;
        }
    }

    /* İşlem Butonları Stilleri */
    .btn-sm {
        width: 32px !important;
        height: 32px !important;
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 0.5rem !important;
        transition: all 0.2s ease-in-out !important;
        border: none !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    }

    .btn-sm:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important;
    }

    .btn-sm:active {
        transform: translateY(0) !important;
    }

    .btn-info {
        background-color: #0dcaf0 !important;
        color: #fff !important;
    }

    .btn-info:hover {
        background-color: #31d2f2 !important;
        color: #fff !important;
    }

    .btn-warning {
        background-color: #ffc107 !important;
        color: #000 !important;
    }

    .btn-warning:hover {
        background-color: #ffca2c !important;
        color: #000 !important;
    }

    .btn-danger {
        background-color: #dc3545 !important;
        color: #fff !important;
    }

    .btn-danger:hover {
        background-color: #bb2d3b !important;
        color: #fff !important;
    }

    /* Tooltip Stilleri */
    .tooltip {
        font-size: 0.875rem !important;
    }

    .tooltip-inner {
        background-color: #212529 !important;
        padding: 0.5rem 0.75rem !important;
        border-radius: 0.5rem !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    }

    /* Buton İkonları */
    .btn-sm i {
        font-size: 0.875rem !important;
        line-height: 1 !important;
    }

    /* Buton Grup Stilleri */
    .gap-2 {
        gap: 0.5rem !important;
    }

    /* Responsive Düzenlemeler */
    @media (max-width: 768px) {
        .btn-sm {
            width: 28px !important;
            height: 28px !important;
        }
        
        .btn-sm i {
            font-size: 0.75rem !important;
        }
    }

    /* Yeni Teklif Butonu */
    .btn-primary {
        height: 2.75rem;
        padding: 0.5rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 0.75rem;
        background-color: #0d6efd;
        border-color: #0d6efd;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.15);
        transition: all 0.2s ease-in-out;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
    }

    .btn-primary:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.15);
    }

    .btn-primary:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .btn-primary i {
        font-size: 0.875rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0.5rem !important;
        }
        
        .card {
            border-radius: 0;
        }

        .btn-primary {
            height: 2.5rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.8125rem;
        }

        .btn-primary i {
            font-size: 0.8125rem;
        }
    }

    /* Select2 Stilleri */
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.5rem;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        padding: 0;
        line-height: 1.5;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }

    .select2-container--bootstrap-5 .select2-dropdown {
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .select2-container--bootstrap-5 .select2-search__field {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    .select2-container--bootstrap-5 .select2-results__option {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    .select2-container--bootstrap-5 .select2-results__option--highlighted {
        background-color: #0d6efd;
        color: #fff;
    }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/tr.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.js"></script>
    <script>
    $(document).ready(function() {
        // Tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        // View Modal
        $(document).on('click', '.view-offer', function(e) {
            e.preventDefault();
            
            // ID'yi al
            const id = $(this).data('offer-id');
            if (!id) {
                console.error('Teklif ID bulunamadı!', this);
                return;
            }

            // Modal'ı aç ve loading durumunu göster
            $('#viewOfferModal').modal('show');
            $('#viewOfferModal .modal-body').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Yükleniyor...</span>
                    </div>
                    <p class="text-muted mb-0">Teklif detayları yükleniyor...</p>
                </div>
            `);

            // AJAX isteği
            $.ajax({
                url: `/offers/${id}`,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        const offer = response.data;
                        moment.locale('tr');

                        // Modal içeriğini güncelle
                        const modalContent = `
                            <div class="row">
                                <!-- Sol Kolon -->
                                <div class="col-md-8">
                                    <div class="info-section mb-4">
                                        <h6 class="section-title">
                                            <i class="fas fa-info-circle me-2"></i>Temel Bilgiler
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <i class="fas fa-building"></i>
                                                    <div>
                                                        <small>Acenta</small>
                                                        <strong>${offer.agency?.name || '-'}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <i class="fas fa-briefcase"></i>
                                                    <div>
                                                        <small>Firma</small>
                                                        <strong>${offer.company?.name || '-'}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <i class="fas fa-user"></i>
                                                    <div>
                                                        <small>Yetkili</small>
                                                        <strong>${offer.full_name || '-'}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <i class="fas fa-phone"></i>
                                                    <div>
                                                        <small>Telefon</small>
                                                        <strong>${offer.phone || '-'}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <i class="fas fa-envelope"></i>
                                                    <div>
                                                        <small>E-posta</small>
                                                        <strong>${offer.email || '-'}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-item">
                                                    <i class="fas fa-bed"></i>
                                                    <div>
                                                        <small>Oda/Kişi</small>
                                                        <strong>${offer.room_count || 0} Oda / ${offer.pax_count || 0} Kişi</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="info-section">
                                        <h6 class="section-title">
                                            <i class="fas fa-calendar me-2"></i>Tarih Bilgileri
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="info-item">
                                                    <i class="fas fa-sign-in-alt"></i>
                                                    <div>
                                                        <small>Giriş</small>
                                                        <strong>${moment(offer.checkin_date).format('DD MMMM YYYY')}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="info-item">
                                                    <i class="fas fa-sign-out-alt"></i>
                                                    <div>
                                                        <small>Çıkış</small>
                                                        <strong>${moment(offer.checkout_date).format('DD MMMM YYYY')}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="info-item">
                                                    <i class="fas fa-clock"></i>
                                                    <div>
                                                        <small>Opsiyon</small>
                                                        <strong>${offer.option_date ? moment(offer.option_date).format('DD MMMM YYYY') : '-'}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sağ Kolon -->
                                <div class="col-md-4">
                                    <div class="info-section mb-4">
                                        <h6 class="section-title">
                                            <i class="fas fa-tag me-2"></i>Durum
                                        </h6>
                                        <div class="info-item">
                                            <span class="badge" style="background-color: ${offer.status?.color || '#6c757d'}">
                                                ${offer.status?.name || 'Belirsiz'}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="info-section mb-4">
                                        <h6 class="section-title">
                                            <i class="fas fa-paperclip me-2"></i>Dosyalar
                                        </h6>
                                        <div class="files-list">
                                            ${offer.files && offer.files.length > 0 ? 
                                                offer.files.map(file => `
                                                    <div class="list-group-item">
                                                        <a href="/offers/files/${file.id}/download" class="text-decoration-none">
                                                            <i class="fas fa-file me-2"></i>${file.original_name}
                                                        </a>
                                                    </div>
                                                `).join('') : 
                                                '<div class="text-muted text-center py-3">Dosya bulunmuyor</div>'
                                            }
                                        </div>
                                    </div>

                                    <div class="info-section">
                                        <h6 class="section-title">
                                            <i class="fas fa-sticky-note me-2"></i>Notlar
                                        </h6>
                                        <div class="info-item">
                                            <div class="notes-content">
                                                ${offer.notes || 'Not bulunmuyor'}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        $('#viewOfferModal .modal-body').html(modalContent);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: response.message || 'Teklif detayları yüklenirken bir hata oluştu.'
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Hata:', xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: xhr.responseJSON?.message || 'Teklif detayları yüklenirken bir hata oluştu.'
                    });
                }
            });
        });

        // Tümünü seç/kaldır
        $('#selectAll').on('change', function() {
            $('.offer-checkbox').prop('checked', $(this).prop('checked'));
            updateDeleteButton();
        });

        // Tekil checkbox değişikliklerini kontrol et
        $(document).on('change', '.offer-checkbox', function() {
            updateDeleteButton();
        });

        // Silme butonunun görünürlüğünü güncelle
        function updateDeleteButton() {
            const checkedCount = $('.offer-checkbox:checked').length;
            $('#deleteSelected').toggle(checkedCount > 0);
        }

        // Seçili teklifleri sil
        $('#deleteSelected').on('click', function() {
            const selectedIds = $('.offer-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedIds.length === 0) return;

            Swal.fire({
                title: 'Emin misiniz?',
                text: `Seçili ${selectedIds.length} teklif silinecek. Bu işlem geri alınamaz!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Evet, sil',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("offers.bulk-delete") }}',
                        method: 'POST',
                        data: {
                            ids: selectedIds,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                // Seçili satırları kaldır
                                selectedIds.forEach(function(id) {
                                    $(`tr[data-offer-id="${id}"]`).fadeOut(300, function() {
                                        $(this).remove();
                                    });
                                });

                                // Tümünü seç checkbox'ını kaldır
                                $('#selectAll').prop('checked', false);

                                // Silme butonunu gizle
                                $('#deleteSelected').hide();

                                // Başarı mesajı göster
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata!',
                                text: xhr.responseJSON?.message || 'Teklifler silinirken bir hata oluştu.'
                            });
                        }
                    });
                }
            });
        });

        // Düzenleme modalını aç
        $(document).on('click', '.edit-offer', function(e) {
            e.preventDefault();
            const offerId = $(this).data('offer-id');
            const modalId = `editOfferModal_${offerId}`;
            
            // Modal'ı aç
            const modal = new bootstrap.Modal(document.getElementById(modalId));
            modal.show();
        });

        // Tabloyu yenileme fonksiyonu
        function loadOffers() {
            const currentUrl = new URL(window.location.href);
            
            $.ajax({
                url: currentUrl.pathname + currentUrl.search,
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#offersTableBody').html(response);
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
        });

        // Tekil silme işlemi
        $(document).on('click', '.delete-offer', function() {
            const offerId = $(this).data('offer-id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'Emin misiniz?',
                text: `"${name}" teklifini silmek istediğinize emin misiniz?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/offers/${offerId}`,
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
                                });
                                loadOffers();
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata!',
                                text: xhr.responseJSON?.message || 'Silme işlemi sırasında bir hata oluştu.'
                            });
                        }
                    });
                }
            });
        });

        // Form submit işlemi
        $('#saveOffer').on('click', function() {
            const form = $('#createOfferForm')[0];
            const formData = new FormData(form);

            $.ajax({
                url: '{{ route("offers.store") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    // Loading durumunu göster
                    $('#saveOffer').prop('disabled', true).html(`
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Kaydediliyor...
                    `);
                },
                success: function(response) {
                    if (response.success) {
                        // Formu temizle
                        form.reset();
                        
                        // Modalı kapat
                        $('#createOfferModal').modal('hide');
                        
                        // Başarı mesajı göster
                        Swal.fire({
                            icon: 'success',
                            title: 'Başarılı!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
                        // Tabloyu yenile
                        loadOffers();
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    let errorMessage = 'Bir hata oluştu.';
                    
                    if (response.errors) {
                        errorMessage = Object.values(response.errors).flat().join('\n');
                    } else if (response.message) {
                        errorMessage = response.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: errorMessage
                    });
                },
                complete: function() {
                    // Loading durumunu kaldır
                    $('#saveOffer').prop('disabled', false).text('Kaydet');
                }
            });
        });

        // Edit form submit işlemi
        $(document).on('click', '.update-offer', function() {
            const offerId = $(this).data('offer-id');
            const form = $(`#editOfferForm_${offerId}`)[0];
            const formData = new FormData(form);
            formData.append('_method', 'PUT');

            $.ajax({
                url: `/offers/${offerId}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    // Loading durumunu göster
                    $(`.update-offer[data-offer-id="${offerId}"]`).prop('disabled', true).html(`
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Güncelleniyor...
                    `);
                },
                success: function(response) {
                    if (response.success) {
                        // Modalı kapat
                        $(`#editOfferModal_${offerId}`).modal('hide');
                        
                        // Başarı mesajı göster
                        Swal.fire({
                            icon: 'success',
                            title: 'Başarılı!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        
                        // Tabloyu yenile
                        loadOffers();
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    let errorMessage = 'Bir hata oluştu.';
                    
                    if (response.errors) {
                        errorMessage = Object.values(response.errors).flat().join('\n');
                    } else if (response.message) {
                        errorMessage = response.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: errorMessage
                    });
                },
                complete: function() {
                    // Loading durumunu kaldır
                    $(`.update-offer[data-offer-id="${offerId}"]`).prop('disabled', false).text('Güncelle');
                }
            });
        });

        // Dosya silme işlemi
        $(document).on('click', '.delete-file', function() {
            const fileId = $(this).data('file-id');
            const fileName = $(this).data('file-name');
            
            Swal.fire({
                title: 'Emin misiniz?',
                text: `"${fileName}" dosyasını silmek istediğinize emin misiniz?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/offers/files/${fileId}`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                // Dosyayı listeden kaldır
                                $(`.file-item[data-file-id="${fileId}"]`).remove();
                                
                                // Başarı mesajı göster
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Başarılı!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Hata!',
                                text: xhr.responseJSON?.message || 'Dosya silinirken bir hata oluştu.'
                            });
                        }
                    });
                }
            });
        });

        // Toastr ayarlarını güncelle
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-bottom-right',
            timeOut: 3000,
            extendedTimeOut: 1000,
            preventDuplicates: true,
            newestOnTop: true,
            showEasing: 'swing',
            hideEasing: 'linear'
        };

        // Select2 başlatma
        $('.select2-with-add').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Seçiniz...',
            allowClear: true,
            language: {
                noResults: function() {
                    return "Sonuç bulunamadı";
                },
                searching: function() {
                    return "Aranıyor...";
                },
                inputTooLong: function(args) {
                    return args.input.length - args.maximum + ' karakter silmelisiniz';
                }
            }
        });

        // Acente ve Firma seçimlerini kontrol et
        function validateAgencyCompany() {
            const agencyId = $('#createOfferModal_agency_id').val();
            const companyId = $('#createOfferModal_company_id').val();
            
            if (!agencyId && !companyId) {
                $('#createOfferModal_agency_id').addClass('is-invalid');
                $('#createOfferModal_company_id').addClass('is-invalid');
                return false;
            }
            
            $('#createOfferModal_agency_id').removeClass('is-invalid');
            $('#createOfferModal_company_id').removeClass('is-invalid');
            return true;
        }

        // Form gönderilmeden önce kontrol et
        $('#createOfferModalForm').on('submit', function(e) {
            if (!validateAgencyCompany()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: 'Acenta veya firma alanlarından en az birini seçmelisiniz.'
                });
            }
        });

        // Acente veya Firma seçildiğinde validasyonu kaldır
        $('#createOfferModal_agency_id, #createOfferModal_company_id').on('change', function() {
            validateAgencyCompany();
        });
    });
    </script>
    @endpush
@endsection
