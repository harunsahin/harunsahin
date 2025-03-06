<div class="modal fade" id="editResourceModal" tabindex="-1" aria-labelledby="editResourceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editResourceModalLabel">Kaynak Düzenle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editResourceForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_kaynak" class="form-label">Kaynak</label>
                        <input type="text" class="form-control" id="edit_kaynak" name="kaynak" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_is_active" class="form-label">Durum</label>
                        <select class="form-select" id="edit_is_active" name="is_active" required>
                            <option value="1">Aktif</option>
                            <option value="0">Pasif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_position" class="form-label">Sıra</label>
                        <input type="number" class="form-control" id="edit_position" name="position" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </div>
            </form>
        </div>
    </div>
</div> 