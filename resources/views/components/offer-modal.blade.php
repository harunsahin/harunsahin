@props([
    'id',
    'title',
    'statuses',
    'companies',
    'agencies',
    'action',
    'method' => 'POST',
    'offer' => null,
    'formClass' => ''
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="{{ $id }}Label">
                    <i class="fas fa-{{ $method === 'POST' ? 'plus' : 'edit' }} me-2"></i>{{ $title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <form id="{{ $id }}Form" action="{{ $action }}" method="POST" enctype="multipart/form-data" class="needs-validation {{ $formClass }}" novalidate>
                @csrf
                @if($method === 'PUT')
                    @method('PUT')
                    <input type="hidden" name="_method" value="PUT">
                @endif

                <div class="modal-body">
                    <div class="info-list">
                        <div class="row g-3">
                            <!-- Sol Kolon: Temel ve Tarih Bilgileri -->
                            <div class="col-md-8">
                                <!-- Temel Bilgiler -->
                                <div class="info-section">
                                    <h6 class="section-title">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Temel Bilgiler</span>
                                    </h6>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row g-3">
                        <!-- Acenta -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label small text-muted">Acenta</label>
                                <small class="d-block text-muted mb-2">(Acenta veya firma alanlarından en az biri zorunludur)</small>
                                <select class="form-select select2-with-add" 
                                       id="{{ $id }}_agency_id" 
                                       name="agency_id">
                                    <option value="">Acenta seçin...</option>
                                    @foreach($agencies as $agency)
                                        <option value="{{ $agency->id }}" {{ optional($offer)->agency_id == $agency->id ? 'selected' : '' }}>
                                            {{ $agency->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Firma -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label small text-muted">Firma</label>
                                <small class="d-block text-muted mb-2">(Acenta veya firma alanlarından en az biri zorunludur)</small>
                                <select class="form-select select2-with-add" 
                                       id="{{ $id }}_company_id" 
                                       name="company_id">
                                    <option value="">Firma seçin...</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ optional($offer)->company_id == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                                                <!-- Yetkili Kişi -->
                        <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label small text-muted">Yetkili Kişi</label>
                                                        <input type="text" 
                                                               class="form-control" 
                                       id="{{ $id }}_full_name" 
                                       name="full_name" 
                                                               value="{{ optional($offer)->full_name }}"
                                                               required>
                            </div>
                        </div>

                        <!-- Telefon -->
                        <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label small text-muted">Telefon</label>
                                                        <input type="tel" 
                                                               class="form-control" 
                                       id="{{ $id }}_phone" 
                                       name="phone" 
                                                               value="{{ optional($offer)->phone }}"
                                                               required>
                            </div>
                        </div>

                        <!-- E-posta -->
                        <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label small text-muted">E-posta</label>
                                                        <input type="email" 
                                                               class="form-control" 
                                       id="{{ $id }}_email" 
                                       name="email" 
                                                               value="{{ optional($offer)->email }}"
                                                               required>
                            </div>
                        </div>

                                                <!-- Oda/Kişi Sayısı -->
                        <div class="col-md-6">
                                                    <div class="row g-3">
                                                        <!-- Oda Sayısı -->
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="form-label small text-muted">Oda</label>
                                                                <input type="number" 
                                                                       class="form-control" 
                                       id="{{ $id }}_room_count" 
                                       name="room_count" 
                                                                       value="{{ optional($offer)->room_count }}"
                                       required
                                                                       min="1">
                            </div>
                        </div>

                        <!-- Kişi Sayısı -->
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="form-label small text-muted">Kişi</label>
                                                                <input type="number" 
                                                                       class="form-control" 
                                       id="{{ $id }}_pax_count" 
                                       name="pax_count" 
                                                                       value="{{ optional($offer)->pax_count }}"
                                       required
                                                                       min="1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            </div>
                        </div>

                                <!-- Tarih Bilgileri -->
                                <div class="info-section">
                                    <h6 class="section-title">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>Tarih Bilgileri</span>
                                    </h6>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row g-3">
                                <!-- Giriş Tarihi -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label small text-muted">Giriş Tarihi</label>
                                                        <input type="text" 
                                                               class="form-control flatpickr" 
                                               id="{{ $id }}_checkin_date" 
                                               name="checkin_date" 
                                                               value="{{ optional($offer)->checkin_date }}"
                                                               required>
                                    </div>
                                </div>

                                <!-- Çıkış Tarihi -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label small text-muted">Çıkış Tarihi</label>
                                                        <input type="text" 
                                                               class="form-control flatpickr" 
                                               id="{{ $id }}_checkout_date" 
                                               name="checkout_date" 
                                                               value="{{ optional($offer)->checkout_date }}"
                                                               required>
                            </div>
                        </div>

                        <!-- Opsiyon Tarihi -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label small text-muted">Opsiyon Tarihi</label>
                                                        <input type="text" 
                                                               class="form-control flatpickr" 
                                       id="{{ $id }}_option_date" 
                                       name="option_date" 
                                                               value="{{ optional($offer)->option_date }}"
                                                               required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>

                            <!-- Sağ Kolon: Durum, Dosyalar ve Notlar -->
                            <div class="col-md-4">
                        <!-- Durum -->
                                <div class="info-section">
                                    <h6 class="section-title">
                                        <i class="fas fa-tag"></i>
                                        <span>Durum</span>
                                    </h6>
                                    <div class="card">
                                        <div class="card-body">
                                <select class="form-select" 
                                        id="{{ $id }}_status_id" 
                                        name="status_id" 
                                        required>
                                                <option value="">Durum Seçiniz...</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" 
                                                            {{ optional($offer)->status_id == $status->id ? 'selected' : '' }}
                                                            style="color: {{ $status->color }}">
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            </div>
                        </div>

                        <!-- Dosyalar -->
                                <div class="info-section">
                                    <h6 class="section-title">
                                        <i class="fas fa-paperclip"></i>
                                        <span>Dosyalar</span>
                                    </h6>
                                    <div class="card">
                                        <div class="card-body">
                                    <input type="file" 
                                           class="form-control" 
                                           name="files[]" 
                                           id="{{ $id }}_files" 
                                           multiple 
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                                            
                                            @if($offer && $offer->files->count() > 0)
                                            <div class="mt-3">
                                                <div class="list-group list-group-flush files-content">
                                                    @foreach($offer->files as $file)
                                                    <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                        <a href="{{ route('offers.download-file', $file->id) }}" class="text-decoration-none text-dark">
                                                            <i class="fas fa-file me-2"></i>{{ $file->original_name }}
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger delete-file" 
                                                                data-file-id="{{ $file->id }}"
                                                                data-file-name="{{ $file->original_name }}">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Notlar -->
                                <div class="info-section">
                                    <h6 class="section-title">
                                        <i class="fas fa-sticky-note"></i>
                                        <span>Notlar</span>
                                    </h6>
                                    <div class="card">
                                        <div class="card-body">
                                            <textarea class="form-control" 
                                                      id="{{ $id }}_notes" 
                                                      name="notes" 
                                                      rows="3">{{ optional($offer)->notes }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                        <span>İptal</span>
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        <span>Kaydet</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('components.modal-styles')

@push('styles')
<style>
/* Modal Genel Stilleri */
.modal-content {
    border: none;
    border-radius: 0.75rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    background: #fff;
}

.modal-header {
    padding: 1rem 1.5rem;
    border: none;
}

.modal-header .modal-title {
    font-size: 1.1rem;
    font-weight: 500;
}

.modal-header .btn-close {
    opacity: 1;
}

.modal-body {
    padding: 1.5rem;
}

/* Bölüm Stilleri */
.info-section {
    margin-bottom: 1.5rem;
}

.section-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
}

.card {
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    border-radius: 0.5rem;
}

.card-body {
    padding: 1rem;
}

/* Form Grup Stilleri */
.form-group {
    margin-bottom: 0;
}

.form-label {
    margin-bottom: 0.35rem;
}

/* Form Kontrol Stilleri */
.form-control,
.form-select {
    height: 38px;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    border: 1px solid #E2E8F0;
    font-size: 0.9rem;
    background-color: #F7FAFC;
    transition: all 0.2s ease;
}

.form-control:hover,
.form-select:hover {
    border-color: #CBD5E0;
}

.form-control:focus,
.form-select:focus {
    border-color: #0dcaf0;
    box-shadow: 0 0 0 3px rgba(13, 202, 240, 0.1);
    background-color: #fff;
}

textarea.form-control {
    min-height: 100px;
    resize: none;
}

/* Select2 Özel Stilleri */
.select2-container {
    width: 100% !important;
}

.select2-container--bootstrap-5 .select2-selection {
    height: 45px !important;
    background-color: #F7FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    padding: 0 0.75rem;
    position: relative;
}

.select2-container--bootstrap-5 .select2-selection:hover {
    border-color: #CBD5E0;
}

.select2-container--bootstrap-5.select2-container--focus .select2-selection {
    border-color: #0dcaf0;
    box-shadow: 0 0 0 3px rgba(13, 202, 240, 0.1);
    background-color: #fff;
}

.select2-container--bootstrap-5 .select2-selection__rendered {
    padding: 0 !important;
    color: #2D3748;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    width: 100%;
}

.select2-container--bootstrap-5 .select2-selection__placeholder {
    color: #A0AEC0;
}

.select2-container--bootstrap-5 .select2-selection__clear {
    position: absolute;
    right: 2.5rem;
    top: 50%;
    transform: translateY(-50%);
    margin: 0;
    padding: 0;
    border: none;
    background: none;
    color: #CBD5E0;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 16px;
    height: 16px;
    font-size: 1rem;
    font-weight: 300;
}

.select2-container--bootstrap-5 .select2-selection__clear:hover {
    color: #718096;
}

.select2-container--bootstrap-5 .select2-selection__arrow {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    width: auto;
    height: auto;
}

.select2-container--bootstrap-5 .select2-selection__arrow b {
    display: none;
}

.select2-container--bootstrap-5 .select2-selection__arrow::after {
    content: '\f107';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    color: #718096;
    font-size: 0.9rem;
}

.select2-container--bootstrap-5 .select2-dropdown {
    border: 1px solid #0dcaf0;
    border-radius: 0.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin-top: 4px;
    padding: 0.5rem;
}

.select2-container--bootstrap-5 .select2-search {
    margin-bottom: 0.5rem;
}

.select2-container--bootstrap-5 .select2-search__field {
    height: 45px;
    border: 1px solid #E2E8F0 !important;
    border-radius: 0.4rem;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

.select2-container--bootstrap-5 .select2-results__options {
    max-height: 250px;
    padding: 0.25rem;
}

.select2-container--bootstrap-5 .select2-results__option {
    padding: 0.75rem 1rem;
    border-radius: 0.4rem;
    margin: 2px 0;
    font-size: 0.9rem;
}

.select2-container--bootstrap-5 .select2-results__option--highlighted {
    background-color: #0dcaf0 !important;
}

.select2-add-new {
    padding: 0.75rem 1rem;
    background: #F7FAFC;
    border-top: 1px solid #E2E8F0;
    margin-top: 0.5rem;
    text-align: center;
}

.select2-add-new button {
    color: #0dcaf0;
    border: none;
    background: none;
    padding: 0;
    font-weight: 500;
    font-size: 0.9rem;
    cursor: pointer;
}

.select2-add-new button:hover {
    color: #0ba5c0;
    text-decoration: underline;
}

/* Flatpickr Özel Stilleri */
.flatpickr-input {
    background-color: #F7FAFC !important;
}

.flatpickr-calendar {
    border-radius: 0.75rem !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    border: none !important;
}

.flatpickr-day.selected,
.flatpickr-day.startRange,
.flatpickr-day.endRange {
    background: #0dcaf0 !important;
    border-color: #0dcaf0 !important;
}

/* Buton Stilleri */
.modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #EDF2F7;
    gap: 0.5rem;
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    font-size: 0.9rem;
    height: 38px;
}

.btn-info {
    background: #0dcaf0;
    border: none;
}

.btn-info:hover {
    background: #0ba5c0;
}

.btn-light {
    background: #EDF2F7;
    border: none;
    color: #4A5568;
}

.btn-light:hover {
    background: #E2E8F0;
    color: #2D3748;
}

/* Dosya Listesi Stilleri */
.files-content {
    max-height: 200px;
    overflow-y: auto;
}

.list-group-item {
    border: none;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    font-size: 0.85rem;
}

.list-group-item:last-child {
    border-bottom: none;
}

.list-group-item i {
    font-size: 1rem;
    color: #0dcaf0;
}

.list-group-item .btn-outline-danger {
    padding: 0.25rem 0.5rem;
    height: auto;
    border-color: #dc3545;
    color: #dc3545;
}

.list-group-item .btn-outline-danger:hover {
    background-color: #dc3545;
    color: #fff;
}

/* Responsive Düzenlemeler */
@media (max-width: 768px) {
    .modal-body {
        padding: 1rem;
    }
    
    .form-label {
        font-size: 0.8rem;
    }
}
</style>
@endpush

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Select2 ve Flatpickr CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- Select2, Flatpickr ve SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/tr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // CSRF token'ı ayarla
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Form validasyonu
    $(`#{{ $id }}Form`).on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const agencyId = form.find('[name="agency_id"]').val();
        const companyId = form.find('[name="company_id"]').val();

        // Acenta ve firma kontrolü
        if (!agencyId && !companyId) {
            Swal.fire({
                icon: 'error',
                title: 'Hata!',
                text: 'Lütfen en az bir tane acenta veya firma seçiniz.'
            });
            return false;
        }

        const submitBtn = form.find('button[type="submit"]');
        const formData = new FormData(this);

        // Submit butonunu devre dışı bırak
        submitBtn.prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Başarılı!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Modalı kapat ve sayfayı yenile
                        $(`#{{ $id }}`).modal('hide');
                        window.location.reload();
                    });
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false);
                const response = xhr.responseJSON;
                let errorMessage = 'Bir hata oluştu.';
                
                if (response.errors) {
                    errorMessage = Object.values(response.errors).flat().join('\n');
                } else if (response.message) {
                    errorMessage = response.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: errorMessage
                });
            }
        });
    });

    // Modal olayları
    $('#{{ $id }}').on('show.bs.modal', function() {
        // Modal içeriğini önce yükle
        const modalContent = $(this).find('.modal-content');
        
        // Select2'yi başlat
        initializeSelect2();

        // Tarih alanlarını ayarla
        if (!@json($offer ?? null)) {
            const today = new Date();
            checkinPicker.setDate(today);
            checkoutPicker.setDate(new Date().fp_incr(1));
            optionPicker.setDate(new Date().fp_incr(7));
            
            const defaultStatus = $('select[name="status_id"]').find('option:contains("Devam Ediyor")').val();
            if (defaultStatus) {
                $('select[name="status_id"]').val(defaultStatus).trigger('change');
            }
        }
    });

    // Select2 konfigürasyonu
    function initializeSelect2() {
        $('.select2-with-add').each(function() {
            const $select = $(this);
            const isAgency = $select.attr('id').includes('agency');
            
            // Eğer Select2 zaten başlatılmışsa, yok et
            if ($select.data('select2')) {
                $select.select2('destroy');
            }

            const config = {
                theme: 'bootstrap-5',
                dropdownParent: $select.closest('.modal-content'),
                language: {
                    errorLoading: function() {
                        return 'Sonuçlar yüklenemedi';
                    },
                    inputTooShort: function(args) {
                        return `Lütfen en az ${args.minimum} karakter girin`;
                    },
                    noResults: function() {
                        return `Sonuç bulunamadı`;
                    },
                    searching: function() {
                        return 'Aranıyor...';
                    }
                },
                ajax: {
                    url: isAgency ? '{{ route("agencies.search") }}' : '{{ route("companies.search") }}',
                    dataType: 'json',
                    delay: 250,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(params) {
                        return {
                            q: params.term || '',
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        if (!data || !data.results) {
                            return {
                                results: [],
                                pagination: {
                                    more: false
                                }
                            };
                        }

                        return {
                            results: data.results.map(item => ({
                                id: item.id,
                                text: item.name || item.text
                            })),
                            pagination: {
                                more: data.pagination && data.pagination.more
                            }
                        };
                    },
                    cache: true,
                    error: function(xhr, status, error) {
                        return {
                            results: [],
                            pagination: {
                                more: false
                            }
                        };
                    }
                },
                minimumInputLength: 0,
                placeholder: isAgency ? 'Acenta seçin...' : 'Firma seçin...',
                allowClear: true,
                width: '100%'
            };

            // Başlangıç verilerini ekle
            if (isAgency) {
                config.data = @json($agencies->map(function($agency) use ($offer) {
                    return [
                        'id' => $agency->id,
                        'text' => $agency->name,
                        'selected' => $offer && $offer->agency_id == $agency->id
                    ];
                }));
            } else {
                config.data = @json($companies->map(function($company) use ($offer) {
                    return [
                        'id' => $company->id,
                        'text' => $company->name,
                        'selected' => $offer && $offer->company_id == $company->id
                    ];
                }));
            }

            $select.select2(config);
        });
    }

    // Modal kapandığında
    $('#{{ $id }}').on('hidden.bs.modal', function() {
        console.log('Modal kapanıyor');
        const form = $(`#{{ $id }}Form`)[0];
        form.reset();
        form.classList.remove('was-validated');
        
        // Select2'yi temizle
        $('.select2-with-add').val(null).trigger('change');
        
        checkinPicker.setDate(new Date());
        checkoutPicker.setDate(new Date().fp_incr(1));
        optionPicker.setDate(new Date().fp_incr(7));
    });

    // Dosya silme işlemi
    $(document).on('click', '.delete-file', function(e) {
        e.preventDefault();
        const button = $(this);
        const fileId = button.data('file-id');
        const fileName = button.data('file-name');

        Swal.fire({
            title: 'Emin misiniz?',
            text: `"${fileName}" dosyası silinecek. Bu işlem geri alınamaz!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Evet, sil',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ route('offers.delete-file', '') }}/${fileId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        button.closest('.list-group-item').fadeOut(300, function() {
                            $(this).remove();
                            if ($('.list-group-item').length === 0) {
                                $('.list-group').parent().remove();
                            }
                        });

                        Swal.fire({
                            icon: 'success',
                            title: 'Başarılı!',
                            text: response.message || 'Dosya başarıyla silindi.',
                            confirmButtonColor: '#00B5D8'
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: xhr.responseJSON?.message || 'Dosya silinirken bir hata oluştu.',
                            confirmButtonColor: '#00B5D8'
                        });
                    }
                });
            }
        });
    });

    // Flatpickr konfigürasyonu
    const commonConfig = {
        locale: "tr",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d.m.Y",
        disableMobile: true,
        allowInput: true,
        static: true // Performans için static modu aktif et
    };

    // Giriş tarihi
    const checkinPicker = flatpickr(`#{{ $id }}_checkin_date`, {
        ...commonConfig,
        minDate: @json($offer) ? null : "today",
        onChange: function(selectedDates) {
            if (selectedDates[0]) {
                const nextDay = new Date(selectedDates[0]);
                nextDay.setDate(nextDay.getDate() + 1);
                checkoutPicker.set('minDate', nextDay);
                if (!checkoutPicker.selectedDates[0] || checkoutPicker.selectedDates[0] < nextDay) {
                    checkoutPicker.setDate(nextDay);
                }
            }
        }
    });

    // Çıkış tarihi
    const checkoutPicker = flatpickr(`#{{ $id }}_checkout_date`, {
        ...commonConfig,
        minDate: @json($offer) ? null : "today"
    });

    // Opsiyon tarihi
    const optionPicker = flatpickr(`#{{ $id }}_option_date`, {
        ...commonConfig,
        minDate: @json($offer) ? null : "today",
        defaultDate: new Date().fp_incr(7)
    });
});
</script>
@endpush