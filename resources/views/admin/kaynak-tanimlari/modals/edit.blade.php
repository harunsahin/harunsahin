<!-- Kaynak Düzenleme Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Kaynak Düzenle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="edit_kaynak" class="form-label">
                            <i class="fas fa-tag me-2"></i>Kaynak
                        </label>
                        <input type="text" class="form-control" id="edit_kaynak" name="kaynak" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_is_active" class="form-label">
                            <i class="fas fa-toggle-on me-2"></i>Durum
                        </label>
                        <select class="form-select" id="edit_is_active" name="is_active">
                            <option value="1">Aktif</option>
                            <option value="0">Pasif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Düzenleme butonu tıklandığında
    $(document).on('click', '.edit-button', function() {
        var id = $(this).data('id');
        
        $.get('{{ url("admin/kaynak-tanimlari") }}/' + id + '/edit', function(response) {
            if (response.success) {
                var kaynak = response.kaynak;
                
                $('#editForm').attr('action', '{{ url("admin/kaynak-tanimlari") }}/' + id);
                $('#edit_kaynak').val(kaynak.kaynak);
                $('#edit_is_active').val(kaynak.is_active ? '1' : '0');
                
                $('#editModal').modal('show');
            } else {
                toastr.error(response.message);
            }
        });
    });

    // Düzenleme formu gönderildiğinde
    $('#editForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#editModal').modal('hide');
                    loadTable();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Bir hata oluştu.');
            }
        });
    });

    // Silme butonu tıklandığında
    $(document).on('click', '.delete-button', function() {
        var id = $(this).data('id');
        
        if (confirm('Bu kaynağı silmek istediğinize emin misiniz?')) {
            $.ajax({
                url: '{{ url("admin/kaynak-tanimlari") }}/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        loadTable();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Bir hata oluştu.');
                }
            });
        }
    });
});
</script>
@endpush 