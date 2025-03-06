<!-- Yorum Görüntüleme Modal -->
<div class="modal fade" id="viewCommentModal" tabindex="-1" aria-labelledby="viewCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewCommentModalLabel">Yorum Detayları</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Adı Soyadı</label>
                            <p id="view_name" class="form-control-static"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tarih</label>
                            <p id="view_date" class="form-control-static"></p>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Yorum</label>
                    <p id="view_comment" class="form-control-static"></p>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Kaynak</label>
                            <p id="view_kaynak" class="form-control-static"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Durum</label>
                            <p id="view_status"></p>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <h6 class="mb-3">İşlem Geçmişi</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Tarih</th>
                                    <th>Kullanıcı</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody id="logs_table_body">
                                <tr>
                                    <td colspan="3" class="text-center">Log kaydı bulunamadı</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div> 