<!-- Create Modal -->
<x-offer-modal 
    id="createOfferModal" 
    title="Yeni Teklif"
    :statuses="$statuses"
    :companies="$companies"
    :agencies="$agencies"
    action="{{ route('offers.store') }}"
/>

@push('scripts')
<script>
$(document).ready(function() {
    // Form submit öncesi temizlik
    $('#createOfferModal').on('hidden.bs.modal', function() {
        $('#createOfferForm')[0].reset();
        $('#selectedFiles').empty();
    });

    $('#createOfferForm').on('submit', function(e) {
        e.preventDefault();
        
        // Submit butonunu devre dışı bırak
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        
        // FormData kullanarak dosya yükleme desteği
        let formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("admin.offers.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    $('#createOfferModal').modal('hide');
                    $('#createOfferForm')[0].reset();
                    $('#selectedFiles').empty();
                    
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
                console.error('XHR Response:', xhr);
                let errorMessage = 'Bir hata oluştu';
                
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors)
                            .flat()
                            .join('\n');
                    } else if (xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: errorMessage,
                    confirmButtonText: 'Tamam'
                });
            },
            complete: function() {
                // Submit butonunu tekrar aktif et
                submitBtn.prop('disabled', false);
            }
        });
    });

    // Dosya seçildiğinde bilgi göster
    $('#files').on('change', function() {
        const files = this.files;
        const selectedFilesDiv = $('#selectedFiles');
        selectedFilesDiv.empty(); // Önceki dosya bilgilerini temizle
        
        if (files.length > 0) {
            let fileList = document.createElement('ul');
            fileList.className = 'list-group mt-2';
            
            Array.from(files).forEach(file => {
                let fileSize = (file.size / 1024 / 1024).toFixed(2); // MB cinsinden
                let listItem = document.createElement('li');
                listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                listItem.innerHTML = `
                    <span>
                        <i class="fas fa-file me-2"></i>
                        ${file.name}
                    </span>
                    <span class="badge bg-primary rounded-pill">${fileSize} MB</span>
                `;
                fileList.appendChild(listItem);
            });
            
            selectedFilesDiv.append(fileList);
        }
    });
});
</script>
@endpush 