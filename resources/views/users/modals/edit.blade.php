<!-- Kullanıcı Düzenleme Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Kullanıcı Düzenle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <form id="editUserForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editUserId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Ad Soyad</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">E-posta</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPassword" class="form-label">Şifre</label>
                        <input type="password" class="form-control" id="editPassword" name="password" placeholder="Değiştirmek için yeni şifre girin">
                        <small class="text-muted">Boş bırakırsanız şifre değişmeyecektir</small>
                    </div>
                    <div class="mb-3">
                        <label for="editRoleId" class="form-label">Rol</label>
                        <select class="form-select" id="editRoleId" name="role_id" required>
                            <option value="">Rol Seçin</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="editIsActive" name="is_active">
                            <label class="form-check-label" for="editIsActive">
                                Aktif
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#editUserForm').on('submit', function(e) {
        e.preventDefault();
        const userId = $('#editUserId').val();
        
        $.ajax({
            url: `/admin/users/${userId}`,
            type: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#editUserModal').modal('hide');
                    location.reload();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON.message || 'Bir hata oluştu');
            }
        });
    });
});
</script>
@endpush 