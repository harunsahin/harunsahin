<div class="modal fade" id="createStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>Yeni Durum Ekle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createStatusForm" action="{{ route('admin.settings.statuses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Durum Adı -->
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" required>
                                <label for="name">Durum Adı</label>
                            </div>
                        </div>

                        <!-- Renk -->
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="color" class="form-control" id="color" name="color" required 
                                       value="#0d6efd" style="height: 50px">
                                <label for="color">Renk</label>
                            </div>
                        </div>

                        <!-- Renk önizleme -->
                        <div class="col-12">
                            <div class="text-center">
                                <span class="badge" id="colorPreview" style="background-color: #0d6efd">
                                    Önizleme
                                </span>
                            </div>
                        </div>

                        <!-- Tip -->
                        <div class="col-12">
                            <div class="form-floating">
                                <select class="form-select" id="type" name="type" required>
                                    <option value="general">Genel</option>
                                    <option value="offer">Teklif</option>
                                    <option value="company">Şirket</option>
                                    <option value="agency">Acente</option>
                                </select>
                                <label for="type">Tip</label>
                            </div>
                        </div>

                        <!-- Sıra -->
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="order" name="order" value="0" min="0" required>
                                <label for="order">Sıra</label>
                            </div>
                        </div>

                        <!-- Açıklama -->
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" id="description" name="description" style="height: 100px"></textarea>
                                <label for="description">Açıklama</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>İptal
                    </button>
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
    // Create form submit
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
                if(response.success) {
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

    // Renk seçimi değiştiğinde önizlemeyi güncelle
    $('#color').change(function() {
        const color = $(this).val();
        $('#colorPreview').css('background-color', color);
    });
});
</script>
@endpush 