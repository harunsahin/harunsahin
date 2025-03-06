<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">{{ $title }}</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <form action="{{ $url }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="name" name="name" required placeholder="Ad">
            <label for="name">Ad</label>
            <div class="invalid-feedback">Lütfen ad giriniz.</div>
        </div>
        
        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="E-posta">
            <label for="email">E-posta</label>
            <div class="invalid-feedback">Lütfen geçerli bir e-posta adresi giriniz.</div>
        </div>
        
        <div class="form-floating mb-3">
            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Telefon">
            <label for="phone">Telefon</label>
            <div class="invalid-feedback">Lütfen telefon numarası giriniz.</div>
        </div>
        
        <div class="form-floating mb-3">
            <textarea class="form-control" id="address" name="address" style="height: 100px" placeholder="Adres"></textarea>
            <label for="address">Adres</label>
        </div>
        
        <div class="text-end">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i>Kaydet
            </button>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    const form = $('form');
    const submitBtn = form.find('button[type="submit"]');
    
    form.on('submit', function(e) {
        e.preventDefault();
        
        // Form validasyonu
        if (!form[0].checkValidity()) {
            e.stopPropagation();
            form.addClass('was-validated');
            return;
        }
        
        // Submit butonunu devre dışı bırak
        submitBtn.prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin me-1"></i>Kaydediliyor...');
        
        // AJAX isteği
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    // Select2'ye yeni seçeneği ekle
                    const selectId = window.currentSelectId;
                    const newOption = new Option(response.data.name, response.data.id, true, true);
                    $(`#${selectId}`).append(newOption).trigger('change');
                    
                    // Modalı kapat
                    $('#dynamicModal').modal('hide');
                    
                    // Başarılı mesajı göster
                    Swal.fire({
                        title: 'Başarılı!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Tamam'
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'Bir hata oluştu.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    title: 'Hata!',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'Tamam'
                });
            },
            complete: function() {
                // Submit butonunu tekrar aktif et
                submitBtn.prop('disabled', false)
                    .html('<i class="fas fa-save me-1"></i>Kaydet');
            }
        });
    });
});
</script> 