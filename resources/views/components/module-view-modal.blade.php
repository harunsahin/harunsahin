@props([
    'id' => 'viewModal',
    'title' => 'Detaylar'
])

<div class="modal fade" id="{{ $id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>{{ $title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="info-list">
                    <!-- Temel Bilgiler -->
                    <div class="info-section mb-4">
                        <h6 class="section-title">
                            <i class="fas fa-info-circle me-2"></i>Temel Bilgiler
                        </h6>
                        <div class="row g-3">
                            {{ $slot }}
                        </div>
                    </div>

                    <!-- Durum -->
                    <div class="info-section mb-4">
                        <h6 class="section-title">
                            <i class="fas fa-tag me-2"></i>Durum
                        </h6>
                        <div id="viewStatus">-</div>
                    </div>

                    <!-- Dosyalar -->
                    <div class="info-section mb-4">
                        <h6 class="section-title">
                            <i class="fas fa-paperclip me-2"></i>Dosyalar
                        </h6>
                        <div id="viewFiles" class="list-group list-group-flush">
                            <!-- Dosyalar dinamik olarak yüklenecek -->
                        </div>
                    </div>

                    <!-- Oluşturma/Güncelleme Bilgileri -->
                    <div class="info-section">
                        <h6 class="section-title">
                            <i class="fas fa-history me-2"></i>İşlem Bilgileri
                        </h6>
                        <div id="viewCreatorInfo" class="text-muted small">-</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>

<style>
.info-section {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 0.5rem;
}

.section-title {
    color: #495057;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #dee2e6;
}

.info-item {
    margin-bottom: 0.5rem;
}

.info-label {
    display: block;
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.info-value {
    font-weight: 500;
    color: #212529;
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}

#viewFiles .list-group-item {
    border: none;
    padding: 0.5rem 0;
}

#viewFiles .list-group-item:not(:last-child) {
    border-bottom: 1px solid #dee2e6;
}

#viewCreatorInfo {
    line-height: 1.5;
}
</style> 