@props([
    'statuses',
    'routePrefix' => 'offers',
    'type' => 'default'
])

<div class="card mb-4">
    <div class="card-body">
        <form id="searchForm" class="row g-3">
            <!-- Arama Alanı -->
            <div class="col-md-4">
                <div class="form-group position-relative">
                    <label for="search" class="form-label">
                        <i class="fas fa-search me-2"></i>Ara
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search" 
                           placeholder="Arama yapın..."
                           value="{{ request('search') }}"
                           autocomplete="off">
                </div>
            </div>

            <!-- Tarih Aralığı -->
            <div class="col-md-4">
                <div class="form-group position-relative">
                    <label for="daterange" class="form-label">
                        <i class="fas fa-calendar-alt me-2"></i>Tarih Aralığı
                    </label>
                    @if($type === 'comments')
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control flatpickr-input" 
                                   id="daterange" 
                                   name="daterange" 
                                   placeholder="Tarih aralığı seçin"
                                   value="{{ request('daterange') }}"
                                   autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-clock"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" data-range="today">Bugün</a></li>
                                <li><a class="dropdown-item" href="#" data-range="yesterday">Dün</a></li>
                                <li><a class="dropdown-item" href="#" data-range="last7days">Son 7 Gün</a></li>
                                <li><a class="dropdown-item" href="#" data-range="last30days">Son 30 Gün</a></li>
                                <li><a class="dropdown-item" href="#" data-range="thisMonth">Bu Ay</a></li>
                                <li><a class="dropdown-item" href="#" data-range="lastMonth">Geçen Ay</a></li>
                                <li><a class="dropdown-item" href="#" data-range="custom">Özel Aralık</a></li>
                            </ul>
                        </div>
                    @else
                        <input type="text" 
                               class="form-control flatpickr-input" 
                               id="daterange" 
                               name="daterange" 
                               placeholder="Tarih aralığı seçin"
                               value="{{ request('daterange') }}"
                               autocomplete="off">
                    @endif
                </div>
            </div>

            <!-- Durum Filtresi -->
            <div class="col-md-3">
                <div class="form-group position-relative">
                    <label for="status" class="form-label">
                        <i class="fas fa-filter me-2"></i>Durum
                    </label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tümü</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" 
                                    {{ request('status') == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Arama Butonu -->
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Yükleniyor Göstergesi -->
<div id="loadingIndicator" class="loading-overlay" style="display: none;">
    <div class="loading-spinner">
        <i class="fas fa-spinner fa-spin me-2"></i>
        <span>Yükleniyor...</span>
    </div>
</div>

@push('styles')
<style>
/* Kart Stilleri */
.card {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    background: #fff;
    transition: all 0.3s ease-in-out;
}

.card-body {
    padding: 1.5rem;
}

/* Form Grup Stilleri */
.form-group {
    margin-bottom: 0;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: #344767;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
}

.form-label i {
    color: #35D6ED;
}

/* Form Kontrol Stilleri */
.form-control,
.form-select {
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    border: 1px solid #d2d6da;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background-color: #fff !important;
    height: auto;
}

.form-control:focus,
.form-select:focus {
    border-color: #35D6ED;
    box-shadow: 0 0 0 2px rgba(53, 214, 237, 0.25);
}

.form-control::placeholder {
    color: #adb5bd;
    opacity: 0.7;
}

/* Select2 Stilleri */
.select2-container--bootstrap-5 .select2-selection {
    border-radius: 0.5rem;
    border: 1px solid #d2d6da;
    min-height: 45px;
    padding: 0.75rem 1rem;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    color: #344767;
    line-height: 1.5;
    padding-left: 0;
}

/* Buton Stilleri */
.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    height: 45px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-primary {
    background-color: #35D6ED;
    border-color: #35D6ED;
}

.btn-primary:hover {
    background-color: #28c8df;
    border-color: #28c8df;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(53, 214, 237, 0.2);
}

.btn-primary:active {
    transform: translateY(0);
}

/* DateRangePicker Stilleri */
.daterangepicker {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    padding: 1rem;
}

.daterangepicker .calendar-table {
    border: none;
    background: #fff;
}

.daterangepicker td.active {
    background-color: #35D6ED !important;
    border-color: #35D6ED !important;
}

.daterangepicker td.in-range {
    background-color: rgba(53, 214, 237, 0.1);
    color: #35D6ED;
}

/* Dropdown Stilleri */
.dropdown-menu {
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    padding: 0.5rem;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
    color: #344767;
}

.dropdown-item:hover {
    background-color: rgba(53, 214, 237, 0.1);
    color: #35D6ED;
}

/* Responsive */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }

    .form-control,
    .form-select,
    .btn {
        font-size: 0.8125rem;
        padding: 0.625rem 0.875rem;
        height: 40px;
    }

    .form-label {
        font-size: 0.8125rem;
    }
}
</style>
@endpush

@push('scripts')
<!-- Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/tr.js"></script>

<script>
$(document).ready(function() {
    let searchTimeout;
    const searchDelay = 500; // 500ms gecikme

    // Flatpickr
    const dateRangePicker = flatpickr("#daterange", {
        mode: "range",
        dateFormat: "Y-m-d",
        locale: "tr",
        rangeSeparator: " - ",
        altInput: true,
        altFormat: "d.m.Y",
        allowInput: true,
        showMonths: 2,
        disableMobile: true,
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                loadItems();
            }
        }
    });

    // Hızlı tarih seçenekleri
    if ($('#searchForm').data('type') === 'comments') {
        $('.dropdown-item[data-range]').on('click', function(e) {
            e.preventDefault();
            const range = $(this).data('range');
            const today = new Date();
            let startDate, endDate;

            switch(range) {
                case 'today':
                    startDate = today;
                    endDate = today;
                    break;
                case 'yesterday':
                    startDate = new Date(today.setDate(today.getDate() - 1));
                    endDate = startDate;
                    break;
                case 'last7days':
                    startDate = new Date(today.setDate(today.getDate() - 7));
                    endDate = new Date();
                    break;
                case 'last30days':
                    startDate = new Date(today.setDate(today.getDate() - 30));
                    endDate = new Date();
                    break;
                case 'thisMonth':
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    endDate = new Date();
                    break;
                case 'lastMonth':
                    startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    endDate = new Date(today.getFullYear(), today.getMonth(), 0);
                    break;
                case 'custom':
                    dateRangePicker.clear();
                    dateRangePicker.open();
                    return;
            }

            dateRangePicker.setDate([startDate, endDate]);
            loadItems();
        });
    }

    // Arama alanı için debounce
    $('#search').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(loadItems, searchDelay);
    });

    // Durum değişikliğinde
    $('#status').on('change', loadItems);

    // Form submit
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        loadItems();
    });

    // Yükleme fonksiyonu
    function loadItems() {
        const form = $('#searchForm');
        const formData = new FormData(form[0]);
        
        // URL parametrelerini oluştur
        const params = new URLSearchParams();
        
        // Tarih aralığını kontrol et ve işle
        const daterange = $('#daterange').val();
        if (daterange) {
            const dates = daterange.split(' - ');
            if (dates.length === 2) {
                params.append('date_start', dates[0]);
                params.append('date_end', dates[1]);
            }
        }
        
        // Diğer form verilerini ekle
        for (const [key, value] of formData.entries()) {
            if (value && key !== 'daterange') {
                params.append(key, value);
            }
        }

        // Yükleniyor göstergesini göster
        $('#loadingIndicator').show();

        // AJAX isteği
        $.ajax({
            url: `{{ route($routePrefix . '.index') }}?${params.toString()}`,
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                $('#offersTableBody').html(response);
                // Tooltip'leri yeniden aktif et
                $('[data-bs-toggle="tooltip"]').tooltip();
                // URL'i güncelle
                window.history.pushState({}, '', `{{ route($routePrefix . '.index') }}?${params.toString()}`);
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: 'Veriler yüklenirken bir hata oluştu.'
                });
            },
            complete: function() {
                // Yükleniyor göstergesini gizle
                $('#loadingIndicator').hide();
            }
        });
    }
});
</script>
@endpush 