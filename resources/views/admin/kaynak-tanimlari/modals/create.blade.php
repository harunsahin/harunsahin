<!-- Yeni Kaynak Ekleme Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Yeni Kaynak Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createForm" action="{{ route('admin.kaynak-tanimlari.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="kaynak" class="form-label">
                            <i class="fas fa-tag me-2"></i>Kaynak
                        </label>
                        <input type="text" class="form-control" id="kaynak" name="kaynak" required>
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
    $('#createForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#createModal').modal('hide');
                    $('#createForm')[0].reset();
                    
                    // Tabloyu yenile
                    $.get('{{ route("admin.kaynak-tanimlari.index") }}', {
                        search: $('#searchInput').val(),
                        status: $('#statusFilter').val()
                    }, function(response) {
                        $('#tableBody').html(response);
                    });
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Bir hata oluştu.');
            }
        });
    });
});
</script>
@endpush 