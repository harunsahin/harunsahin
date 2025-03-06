@props(['id' => 'viewOfferModal'])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="{{ $id }}Label">
                    <i class="fas fa-file-alt text-primary me-2"></i>Teklif Detayları
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="text-center py-5" style="display: none;">
                    <div class="spinner-grow text-primary mb-2" role="status"></div>
                    <p class="text-muted small mb-0">Yükleniyor...</p>
                </div>

                <!-- Content Container -->
                <div class="content-container">
                    <!-- Temel Bilgiler -->
                    <div class="info-section mb-4">
                        <h6 class="section-title d-flex align-items-center mb-3">
                            <span class="icon-circle bg-primary bg-opacity-10 text-primary me-2">
                                <i class="fas fa-info"></i>
                            </span>
                            Temel Bilgiler
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-building text-primary"></i>
                                    </div>
                                    <div class="info-content">
                                        <label class="small text-muted mb-0">Acenta</label>
                                        <div id="viewAgency" class="fw-medium">-</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-hotel text-primary"></i>
                                    </div>
                                    <div class="info-content">
                                        <label class="small text-muted mb-0">Firma</label>
                                        <div id="viewCompany" class="fw-medium">-</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <div class="info-content">
                                        <label class="small text-muted mb-0">Yetkili Kişi</label>
                                        <div id="viewFullName" class="fw-medium">-</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-phone text-primary"></i>
                                    </div>
                                    <div class="info-content">
                                        <label class="small text-muted mb-0">Telefon</label>
                                        <div id="viewPhone" class="fw-medium">-</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-envelope text-primary"></i>
                                    </div>
                                    <div class="info-content">
                                        <label class="small text-muted mb-0">E-posta</label>
                                        <div id="viewEmail" class="fw-medium">-</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-bed text-primary"></i>
                                    </div>
                                    <div class="info-content">
                                        <label class="small text-muted mb-0">Oda/Kişi Sayısı</label>
                                        <div id="viewRoomPax" class="fw-medium">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarih Bilgileri -->
                    <div class="info-section mb-4">
                        <h6 class="section-title d-flex align-items-center mb-3">
                            <span class="icon-circle bg-success bg-opacity-10 text-success me-2">
                                <i class="fas fa-calendar"></i>
                            </span>
                            Tarih Bilgileri
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-sign-in-alt text-success"></i>
                                    </div>
                                    <div class="info-content">
                                        <label class="small text-muted mb-0">Giriş Tarihi</label>
                                        <div id="viewCheckinDate" class="fw-medium">-</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-sign-out-alt text-success"></i>
                                    </div>
                                    <div class="info-content">
                                        <label class="small text-muted mb-0">Çıkış Tarihi</label>
                                        <div id="viewCheckoutDate" class="fw-medium">-</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-clock text-success"></i>
                                    </div>
                                    <div class="info-content">
                                        <label class="small text-muted mb-0">Opsiyon Tarihi</label>
                                        <div id="viewOptionDate" class="fw-medium">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Durum ve Notlar -->
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-section h-100">
                                <h6 class="section-title d-flex align-items-center mb-3">
                                    <span class="icon-circle bg-warning bg-opacity-10 text-warning me-2">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                    Durum
                                </h6>
                                <div id="viewStatus" class="status-badge">-</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-section h-100">
                                <h6 class="section-title d-flex align-items-center mb-3">
                                    <span class="icon-circle bg-info bg-opacity-10 text-info me-2">
                                        <i class="fas fa-sticky-note"></i>
                                    </span>
                                    Notlar
                                </h6>
                                <div id="viewNotes" class="notes-content">-</div>
                            </div>
                        </div>
                    </div>

                    <!-- Dosyalar -->
                    <div class="info-section mt-4">
                        <h6 class="section-title d-flex align-items-center mb-3">
                            <span class="icon-circle bg-danger bg-opacity-10 text-danger me-2">
                                <i class="fas fa-paperclip"></i>
                            </span>
                            Dosyalar
                        </h6>
                        <div id="viewFiles" class="files-list">-</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Kapat
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.icon-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.section-title {
    font-size: 1rem;
    font-weight: 600;
    color: #344767;
}

.info-card {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    transition: all 0.2s ease-in-out;
}

.info-card:hover {
    background-color: #fff;
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
    transform: translateY(-1px);
}

.info-icon {
    width: 40px;
    height: 40px;
    border-radius: 0.5rem;
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
}

.info-content {
    flex: 1;
}

.status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
}

.notes-content {
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    min-height: 100px;
    white-space: pre-wrap;
}

.files-list {
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    overflow: hidden;
}

.files-list .list-group-item {
    border: none;
    border-bottom: 1px solid rgba(0,0,0,.05);
    padding: 0.75rem 1rem;
    background-color: transparent;
    transition: all 0.2s ease-in-out;
}

.files-list .list-group-item:last-child {
    border-bottom: none;
}

.files-list .list-group-item:hover {
    background-color: #fff;
}

.files-list .list-group-item i {
    color: #6c757d;
}

.modal-content {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
}

.btn {
    border-radius: 0.5rem;
    padding: 0.5rem 1rem;
    font-weight: 500;
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}

.spinner-grow {
    width: 3rem;
    height: 3rem;
}

@media (max-width: 768px) {
    .info-card {
        padding: 0.75rem;
    }

    .info-icon {
        width: 32px;
        height: 32px;
    }

    .icon-circle {
        width: 28px;
        height: 28px;
    }
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // CSRF token'ı al
    const token = $('meta[name="csrf-token"]').attr('content');

    // Teklif detaylarını yükle
    $(document).on('click', '.view-offer', function() {
        const offerId = $(this).data('offer-id');
        const modal = $('#viewOfferModal');
        const loadingSpinner = modal.find('#loadingSpinner');
        const contentContainer = modal.find('.content-container');

        // Loading spinner'ı göster ve içeriği gizle
        loadingSpinner.show();
        contentContainer.hide();

        // Debug için log ekle
        console.log('Teklif detayları yükleniyor:', { offerId });

        // Teklif detaylarını getir
        $.ajax({
            url: `/offers/${offerId}`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': token
            },
            success: function(response) {
                console.log('Sunucu yanıtı:', response);

                if (response.success) {
                    const data = response.data;
                    console.log('İşlenecek veri:', data);

                    try {
                        // Modal elementlerinin yüklenmesini bekle
                        setTimeout(function() {
                            // Temel bilgileri doldur
                            $('#viewAgency').text(data.agency || '-');
                            $('#viewCompany').text(data.company || '-');
                            $('#viewFullName').text(data.full_name || '-');
                            $('#viewPhone').text(data.phone || '-');
                            $('#viewEmail').text(data.email || '-');
                            $('#viewRoomPax').text(data.room_pax || '-');

                            // Tarih bilgilerini doldur
                            $('#viewCheckinDate').text(data.checkin_date || '-');
                            $('#viewCheckoutDate').text(data.checkout_date || '-');
                            $('#viewOptionDate').text(data.option_date || '-');

                            // Durum bilgisini doldur
                            let statusHtml = '';
                            if (data.status) {
                                statusHtml = `
                                    <span class="badge" style="background-color: ${data.status.color || '#6c757d'}">
                                        ${data.status.name || 'Belirtilmemiş'}
                                    </span>
                                `;
                            } else {
                                statusHtml = `
                                    <span class="badge" style="background-color: #6c757d">
                                        Belirtilmemiş
                                    </span>
                                `;
                            }
                            $('#viewStatus').html(statusHtml);

                            // Notları doldur
                            $('#viewNotes').text(data.notes || '-');

                            // Dosyaları doldur
                            let filesHtml = '-';
                            if (data.files && Array.isArray(data.files) && data.files.length > 0) {
                                filesHtml = data.files.map(file => `
                                    <div class="file-item">
                                        <a href="${file.url}" target="_blank" class="file-link">
                                            <i class="fas fa-file-alt me-2"></i>${file.name}
                                        </a>
                                    </div>
                                `).join('');
                            }
                            $('#viewFiles').html(filesHtml);

                            // Loading spinner'ı gizle ve içeriği göster
                            loadingSpinner.hide();
                            contentContainer.show();

                            console.log('Veriler başarıyla yüklendi');
                        }, 100);
                    } catch (error) {
                        console.error('Veri işleme hatası:', error);
                        loadingSpinner.hide();
                        contentContainer.show();
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: 'Veriler işlenirken bir hata oluştu: ' + error.message
                        });
                    }
                } else {
                    // Hata durumunda
                    console.error('Sunucu hatası:', response.message);
                    loadingSpinner.hide();
                    contentContainer.show();
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: response.message || 'Teklif bilgileri yüklenirken bir hata oluştu.'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX hatası:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                
                // AJAX hatası durumunda
                loadingSpinner.hide();
                contentContainer.show();
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: 'Teklif bilgileri yüklenirken bir hata oluştu: ' + error
                });
            }
        });
    });
});
</script>
@endpush 