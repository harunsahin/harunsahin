<!-- Yeni Yorum Ekleme Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Yeni Yorum Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createCommentForm" action="{{ route('admin.yorums.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="adisoyadi" class="form-label">
                            <i class="fas fa-user me-2"></i>Adı Soyadı
                        </label>
                        <input type="text" class="form-control" id="adisoyadi" name="adisoyadi" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="yorum" class="form-label">
                            <i class="fas fa-comment me-2"></i>Yorum
                        </label>
                        <textarea class="form-control" id="yorum" name="yorum" rows="3" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="kaynak" class="form-label">
                            <i class="fas fa-tag me-2"></i>Kaynak
                        </label>
                        <select class="form-select" id="kaynak" name="kaynak" required>
                            <option value="">Kaynak Seçin</option>
                            @foreach($kaynaklar as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="yorumtarihi" class="form-label">
                            <i class="fas fa-calendar-alt me-2"></i>Yorum Tarihi
                        </label>
                        <input type="text" class="form-control flatpickr" id="yorumtarihi" name="yorumtarihi" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="status" class="form-label">
                            <i class="fas fa-toggle-on me-2"></i>Durum
                        </label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active">Aktif</option>
                            <option value="inactive">Pasif</option>
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