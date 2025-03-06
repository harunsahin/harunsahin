<!-- Yorum Düzenleme Modal -->
<div class="modal fade" id="editCommentModal" tabindex="-1" aria-labelledby="editCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCommentModalLabel">Yorum Düzenle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <form id="editCommentForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_comment_id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Adı Soyadı</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_kaynak" class="form-label">Kaynak</label>
                                <select class="form-control" id="edit_kaynak" name="kaynak" required>
                                    <option value="">Kaynak Seçin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_comment" class="form-label">
                            <i class="fas fa-comment me-2"></i>Yorum
                        </label>
                        <textarea class="form-control" id="edit_comment" name="comment" rows="3" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_yorumtarihi" class="form-label">
                            <i class="fas fa-calendar me-2"></i>Yorum Tarihi
                        </label>
                        <input type="datetime-local" class="form-control" id="edit_yorumtarihi" name="yorumtarihi" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="edit_status" name="status">
                            <label class="form-check-label" for="edit_status">Aktif</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Düzenleme modalını açma
    $(document).on('click', '.edit-comment', function() {
        const id = $(this).data('id');
        $('#edit_comment_id').val(id);
        
        // Yorum verilerini getir
        $.ajax({
            url: `/admin/comments/${id}/edit`,
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    const comment = response.data;
                    $('#edit_name').val(comment.name);
                    $('#edit_kaynak').val(comment.kaynak);
                    $('#edit_comment').val(comment.comment);
                    $('#edit_yorumtarihi').val(comment.yorumtarihi);
                    $('#edit_status').prop('checked', comment.status == 1);
                    $('#editCommentModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: response.message || 'Yorum bilgileri alınamadı'
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'Bir hata oluştu';
                if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: errorMessage
                });
            }
        });
    });

    // Form gönderimi
    $('#editCommentForm').on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#edit_comment_id').val();
        const submitBtn = $(this).find('button[type="submit"]');
        
        // Submit butonunu devre dışı bırak
        submitBtn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Güncelleniyor...
        `);
        
        $.ajax({
            url: `/admin/comments/${id}`,
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Modalı kapat
                    $('#editCommentModal').modal('hide');
                    
                    // Başarı mesajı göster
                    Swal.fire({
                        icon: 'success',
                        title: 'Başarılı!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Sayfayı yenile
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: response.message || 'Güncelleme sırasında bir hata oluştu'
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
                    text: errorMessage
                });
            },
            complete: function() {
                // Submit butonunu tekrar aktif et
                submitBtn.prop('disabled', false).html(`
                    <i class="fas fa-save me-2"></i>Güncelle
                `);
            }
        });
    });
});
</script>
@endpush 