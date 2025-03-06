@push('scripts')
<!-- Moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/tr.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
        console.log('Teklif ID:', id);

        const modal = $('#viewOfferModal');
        const loadingSpinner = modal.find('#loadingSpinner');
        const contentContainer = modal.find('.content-container');

        // Loading spinner'ı göster ve içeriği gizle
        loadingSpinner.show();
        contentContainer.hide();

        // AJAX isteği
        $.ajax({
            url: `/offers/${id}`,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                console.log('AJAX yanıtı alındı:', response);
                
                try {
                    const data = response.data || response;
                    console.log('İşlenecek veri:', data);

                    // Tarihleri formatla
                    const formatDate = (dateStr) => {
                        if (!dateStr) return '-';
                        const date = moment(dateStr);
                        return date.isValid() ? date.format('DD MMMM YYYY') : '-';
                    };

                    // Temel bilgileri doldur
                    $('#viewAgency').text(data.agency?.name || data.agency || '-');
                    $('#viewCompany').text(data.company?.name || data.company || '-');
                    $('#viewFullName').text(data.full_name || '-');
                    $('#viewPhone').text(data.phone || '-');
                    $('#viewEmail').text(data.email || '-');
                    $('#viewRoomPax').text(data.room_count && data.pax_count ? `${data.room_count} Oda / ${data.pax_count} Kişi` : '-');

                    // Tarihleri doldur
                    $('#viewCheckinDate').text(formatDate(data.checkin_date));
                    $('#viewCheckoutDate').text(formatDate(data.checkout_date));
                    $('#viewOptionDate').text(formatDate(data.option_date));

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
                                    <i class="fas fa-file-alt me-2"></i>${file.original_name}
                                </a>
                            </div>
                        `).join('');
                    }
                    $('#viewFiles').html(filesHtml);

                    // Loading spinner'ı gizle ve içeriği göster
                    loadingSpinner.hide();
                    contentContainer.show();

                    console.log('Veriler başarıyla yüklendi');
                } catch (err) {
                    console.error('Veri işleme hatası:', err);
                    loadingSpinner.hide();
                    contentContainer.show();
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: 'Veriler işlenirken bir hata oluştu: ' + err.message
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX hatası:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                
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