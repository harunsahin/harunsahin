<div class="modal fade" id="editStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Durum Düzenle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editStatusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Durum Adı -->
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                                <label for="edit_name">Durum Adı</label>
                            </div>
                        </div>

                        <!-- Renk -->
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="color" class="form-control" id="edit_color" name="color" required>
                                <label for="edit_color">Renk</label>
                            </div>
                        </div>

                        <!-- Açıklama -->
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" id="edit_description" name="description" style="height: 100px"></textarea>
                                <label for="edit_description">Açıklama</label>
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
    // Edit modal açıldığında
    $('.edit-status').click(function() {
        const status = $(this).data('status');
        
        // Form alanlarını doldur
        $('#edit_name').val(status.name);
        $('#edit_color').val(status.color);
        $('#edit_description').val(status.description);
        
        // Form action URL'ini ayarla
        $('#editStatusForm').attr('action', `/settings/statuses/${status.id}`);
    });

    // Edit form submit
    $('#editStatusForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST', // Form method POST, _method ile PUT yapılıyor
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    $('#editStatusModal').modal('hide');
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
                let errors = xhr.responseJSON.errors;
                if (errors) {
                    Object.keys(errors).forEach(function(key) {
                        toastr.error(errors[key][0]);
                    });
                } else {
                    toastr.error('Bir hata oluştu. Lütfen tekrar deneyin.');
                }
            }
        });
    });
});
</script>
@endpush 