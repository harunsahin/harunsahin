<div class="modal fade" id="createResourceModal" tabindex="-1" aria-labelledby="createResourceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createResourceModalLabel">Yeni Kaynak Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createResourceForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kaynak" class="form-label">Kaynak</label>
                        <input type="text" class="form-control" id="kaynak" name="kaynak" required>
                    </div>
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Durum</label>
                        <select class="form-select" id="is_active" name="is_active" required>
                            <option value="1">Aktif</option>
                            <option value="0">Pasif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Sıra</label>
                        <input type="number" class="form-control" id="position" name="position" value="0">
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