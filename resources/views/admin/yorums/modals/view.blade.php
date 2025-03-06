<!-- Yorum Detay Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Yorum Detayları</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Adı Soyadı:</strong>
                        <p id="view-adisoyadi"></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Yorum Tarihi:</strong>
                        <p id="view-yorumtarihi"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Kaynak:</strong>
                        <p id="view-kaynak"></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Yorum:</strong>
                        <p id="view-yorum" class="yorum-text"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Ekleyen Kullanıcı:</strong>
                        <p id="view-created-by"></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Eklenme Tarihi:</strong>
                        <p id="view-created-at"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h6 class="mb-3">Değişiklik Geçmişi</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tarih</th>
                                        <th>Kullanıcı</th>
                                        <th>Değişiklik</th>
                                    </tr>
                                </thead>
                                <tbody id="view-changes">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div> 