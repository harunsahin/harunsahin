<div class="modal fade" id="editModuleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Modül Düzenle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editModuleForm">
                    @csrf
                    <input type="hidden" id="moduleId" name="module_id">
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-cube text-primary me-2"></i>
                            <label class="form-label mb-0">Modül Adı</label>
                        </div>
                        <input type="text" class="form-control form-control-lg bg-light" id="editModuleName" name="name" readonly>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-list text-primary me-2"></i>
                                <label class="form-label mb-0">Alanlar</label>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" id="addField">
                                <i class="fas fa-plus me-2"></i>
                                Yeni Alan Ekle
                            </button>
                        </div>
                        
                        <div id="fieldsContainer" class="fields-container">
                            <!-- Alanlar dinamik olarak buraya eklenecek -->
                        </div>
                    </div>

                    <div class="modal-footer border-0 px-0 pb-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>
                            İptal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.fields-container {
    max-height: 60vh;
    overflow-y: auto;
    padding-right: 5px;
}

.fields-container::-webkit-scrollbar {
    width: 6px;
}

.fields-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.fields-container::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 3px;
}

.fields-container::-webkit-scrollbar-thumb:hover {
    background: #999;
}

.field-row {
    transition: all 0.3s ease;
}

.field-row:hover {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.field-row .card-body {
    padding: 1rem;
}

.field-row .form-label {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.remove-field {
    transition: all 0.2s ease;
}

.remove-field:hover {
    background-color: #dc3545;
    color: white;
}

.form-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
}

.modal-header {
    border-bottom: 0;
    padding: 1.5rem;
}

.modal-body {
    padding: 1.5rem;
}

.btn-close-white {
    filter: brightness(0) invert(1);
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // Yeni Alan Ekle butonuna tıklandığında
    $('#addField').on('click', function() {
        addField();
    });

    // Alan türü değiştiğinde dropdown seçeneklerini göster/gizle
    $(document).on('change', 'select[name="fields[][type]"]', function() {
        const dropdownOptions = $(this).closest('.card-body').find('.dropdown-options');
        dropdownOptions.toggle($(this).val() === 'dropdown');
    });

    // Seçenek ekleme butonu tıklandığında
    $(document).on('click', '.add-option', function() {
        const optionsContainer = $(this).closest('.dropdown-options').find('.options-container');
        const optionHtml = `
            <div class="input-group mb-2">
                <input type="text" class="form-control" name="fields[][options][]" placeholder="Seçenek değeri">
                <button type="button" class="btn btn-outline-danger remove-option">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        optionsContainer.append(optionHtml);
    });

    // Seçenek silme butonu tıklandığında
    $(document).on('click', '.remove-option', function() {
        $(this).closest('.input-group').fadeOut(300, function() {
            $(this).remove();
        });
    });

    // Alan silme butonu tıklandığında
    $(document).on('click', '.remove-field', function() {
        $(this).closest('.field-row').fadeOut(300, function() {
            $(this).remove();
        });
    });
});

// Alan ekleme fonksiyonu
function addField(existingField = '') {
    const fieldHtml = `
        <div class="card mb-3 field-row">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-database text-muted me-2"></i>
                            <label class="form-label">Alan Adı (DB)</label>
                        </div>
                        <input type="text" class="form-control" name="fields[][name]" 
                               value="${existingField}" placeholder="ornek_alan_adi"
                               pattern="[a-z0-9_]+" title="Sadece küçük harf, rakam ve alt çizgi kullanabilirsiniz">
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-tag text-muted me-2"></i>
                            <label class="form-label">Form Etiketi</label>
                        </div>
                        <input type="text" class="form-control" name="fields[][label]" 
                               value="${existingField ? toTitleCase(existingField.replace(/_/g, ' ')) : ''}" 
                               placeholder="Örnek Alan Adı">
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-code text-muted me-2"></i>
                            <label class="form-label">Alan Türü</label>
                        </div>
                        <select class="form-select" name="fields[][type]">
                            <optgroup label="Metin">
                                <option value="string" ${existingField ? 'selected' : ''}>Metin (string)</option>
                                <option value="text">Uzun Metin (text)</option>
                                <option value="email">E-posta (email)</option>
                                <option value="password">Şifre (password)</option>
                            </optgroup>
                            <optgroup label="Sayısal">
                                <option value="integer">Tam Sayı (integer)</option>
                                <option value="decimal">Ondalıklı Sayı (decimal)</option>
                            </optgroup>
                            <optgroup label="Tarih/Saat">
                                <option value="date">Tarih (date)</option>
                                <option value="datetime">Tarih/Saat (datetime)</option>
                                <option value="time">Saat (time)</option>
                            </optgroup>
                            <optgroup label="Diğer">
                                <option value="boolean">Evet/Hayır (boolean)</option>
                                <option value="file">Dosya (file)</option>
                                <option value="image">Resim (image)</option>
                                <option value="dropdown">Dropdown (dropdown)</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger remove-field w-100" title="Alanı Sil">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="dropdown-options mt-3" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-list-ul text-muted me-2"></i>
                            <label class="form-label mb-0">Dropdown Seçenekleri</label>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary add-option">
                            <i class="fas fa-plus me-2"></i>
                            Seçenek Ekle
                        </button>
                    </div>
                    <div class="options-container">
                        <!-- Seçenekler buraya dinamik olarak eklenecek -->
                    </div>
                </div>
            </div>
        </div>
    `;
    $('#fieldsContainer').append(fieldHtml);

    // Yeni eklenen alanı görünür yap
    const newField = $('#fieldsContainer').children().last();
    newField.hide().fadeIn(300);
}

// Metin düzenleme yardımcı fonksiyonu
function toTitleCase(str) {
    return str.replace(/\w\S*/g, function(txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
}
</script>
@endpush 