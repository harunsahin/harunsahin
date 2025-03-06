<!-- Edit Modal -->
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

@push('scripts')
<script>
$(document).ready(function() {
    // Düzenleme modalını aç
    $(document).on('click', '.edit-offer', function() {
        const offerId = $(this).data('offer-id');
        const modalId = `editOfferModal_${offerId}`;
        
        // Modal'ı aç
        $(`#${modalId}`).modal('show');
    });

    // Her edit modal formu için submit işlemi
    $('[id^="editOfferModal_"]').each(function() {
        const modalId = $(this).attr('id');
        const offerId = modalId.split('_')[1];
        
        $(`#${modalId}Form`).on('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('_method', 'PUT');
            
            $.ajax({
                url: `/offers/${offerId}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $(`#${modalId}`).modal('hide');
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
                    let message = 'Bir hata oluştu. Lütfen tekrar deneyin.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: message
                    });
                }
            });
        });
    });

    // Silme işlemi
    $(document).on('click', '.delete-offer', function() {
        const id = $(this).data('offer-id');
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
                    url: `/offers/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if(response.success) {
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
                    }
                });
            }
        });
    });
});
</script>
@endpush 