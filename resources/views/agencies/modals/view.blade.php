<div class="modal fade" id="viewAgencyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>
                    <span id="viewTitle">Acente Detayları</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <!-- Acente Adı -->
                    <div class="col-12">
                        <label class="form-label text-muted small">Acente Adı</label>
                        <p class="mb-0 fw-medium" id="viewName">-</p>
                    </div>

                    <!-- İletişim Bilgileri -->
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Telefon</label>
                        <p class="mb-0" id="viewPhone">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">E-posta</label>
                        <p class="mb-0" id="viewEmail">-</p>
                    </div>

                    <!-- Adres -->
                    <div class="col-12">
                        <label class="form-label text-muted small">Adres</label>
                        <p class="mb-0" id="viewAddress">-</p>
                    </div>

                    <!-- Durum -->
                    <div class="col-12">
                        <label class="form-label text-muted small">Durum</label>
                        <div id="viewStatus">-</div>
                    </div>

                    <!-- Oluşturulma ve Güncellenme -->
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Oluşturulma Tarihi</label>
                        <p class="mb-0" id="viewCreatedAt">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Güncellenme Tarihi</label>
                        <p class="mb-0" id="viewUpdatedAt">-</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Kapat
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/tr.min.js"></script>

<script>
$(document).ready(function() {
    // Görüntüleme modalı
    $('.view-agency').click(function() {
        const id = $(this).data('id');
        
        // Debug için
        console.log('Acente ID:', id);
        
        // Modal açılmadan önce içeriği temizle
        $('#viewName, #viewPhone, #viewEmail, #viewAddress, #viewCreatedAt, #viewUpdatedAt').text('-');
        $('#viewStatus').html('-');
        
        // AJAX isteği
        $.ajax({
            url: `/agencies/${id}`,
            method: 'GET',
            success: function(response) {
                console.log('Response:', response); // Debug için
                
                if (response.success) {
                    const agency = response.agency;
                    
                    // Form alanlarını doldur
                    $('#viewName').text(agency.name || '-');
                    $('#viewPhone').text(agency.phone || '-');
                    $('#viewEmail').text(agency.email || '-');
                    $('#viewAddress').text(agency.address || '-');
                    
                    // Durumu badge olarak göster
                    $('#viewStatus').html(`
                        <span class="badge bg-${agency.is_active ? 'success' : 'danger'}">
                            ${agency.is_active ? 'Aktif' : 'Pasif'}
                        </span>
                    `);
                    
                    // Tarihleri formatla
                    moment.locale('tr');
                    $('#viewCreatedAt').text(moment(agency.created_at).format('DD MMMM YYYY, HH:mm'));
                    $('#viewUpdatedAt').text(moment(agency.updated_at).format('DD MMMM YYYY, HH:mm'));
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error); // Debug için
                console.log('Status:', status);
                console.log('Response:', xhr.responseText);
                
                toastr.error('Acente detayları yüklenirken bir hata oluştu.');
            }
        });
    });
});
</script>
@endpush 