<div class="modal fade" id="createCommentModal" tabindex="-1" aria-labelledby="createCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCommentModalLabel">Yeni Yorum Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createCommentForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="fas fa-user me-2"></i>Müşteri Adı
                        </label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="source" class="form-label">
                            <i class="fas fa-globe me-2"></i>Kaynak
                        </label>
                        <select class="form-select" id="source" name="source" required>
                            <option value="">Kaynak Seçiniz...</option>
                            <option value="Tripadvisor">Tripadvisor</option>
                            <option value="Booking.com">Booking.com</option>
                            <option value="Google">Google</option>
                            <option value="Hotels.com">Hotels.com</option>
                            <option value="Otelpuan">Otelpuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">
                            <i class="fas fa-comment me-2"></i>Yorum
                        </label>
                        <textarea class="form-control" id="comment" name="comment" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="comment_date" class="form-label">
                            <i class="fas fa-calendar-alt me-2"></i>Yorum Tarihi
                        </label>
                        <input type="date" class="form-control" id="comment_date" name="comment_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>İptal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 