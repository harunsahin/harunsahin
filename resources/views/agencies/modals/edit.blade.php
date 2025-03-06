<!-- Düzenleme Modal -->
<div class="modal fade" id="editAgencyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Acente Düzenle</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editAgencyForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editAgencyId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Acente Adı</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                        <div class="invalid-feedback">Bu alan zorunludur</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefon</label>
                        <input type="tel" class="form-control" id="editPhone" name="phone" placeholder="(___) ___ __ __">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">E-posta</label>
                        <input type="email" class="form-control" id="editEmail" name="email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adres</label>
                        <textarea class="form-control" id="editAddress" name="address" rows="3"></textarea>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="editIsActive" name="is_active" value="1">
                        <label class="form-check-label">Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> İptal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 