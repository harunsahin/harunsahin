<!-- Düzenleme Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="loading-overlay" style="display: none;">
            <div class="loading-spinner"></div>
        </div>
        
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Yorum Düzenle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form id="editCommentForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_adisoyadi" class="form-label">
                            <i class="fas fa-user me-2"></i>Adı Soyadı
                        </label>
                        <input type="text" class="form-control" id="edit_adisoyadi" name="adisoyadi" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_yorum" class="form-label">
                            <i class="fas fa-comment me-2"></i>Yorum
                        </label>
                        <textarea class="form-control" id="edit_yorum" name="yorum" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_yorumtarihi" class="form-label">
                            <i class="fas fa-calendar-alt me-2"></i>Yorum Tarihi
                        </label>
                        <input type="text" class="form-control flatpickr" id="edit_yorumtarihi" name="yorumtarihi" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_kaynak" class="form-label">
                            <i class="fas fa-tag me-2"></i>Kaynak
                        </label>
                        <select class="form-control" id="edit_kaynak" name="kaynak" required>
                            <option value="">Kaynak Seçin</option>
                            @foreach($kaynaklar as $kaynak)
                                <option value="{{ $kaynak }}">{{ $kaynak }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_status" class="form-label">
                            <i class="fas fa-toggle-on me-2"></i>Durum
                        </label>
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="active">Aktif</option>
                            <option value="inactive">Pasif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div> 