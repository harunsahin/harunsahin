@extends('layouts.app')

@section('title', 'Yorumlar')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
    /* Modal temel stilleri */
    .modal {
        z-index: 9999 !important;
    }
    
    .modal-backdrop {
        z-index: 9998 !important;
        background-color: rgba(0, 0, 0, 0.5);
    }
    
    .modal-dialog {
        z-index: 9999 !important;
        margin: 1.75rem auto;
    }
    
    .modal-content {
        background-color: #fff;
        border: none;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        position: relative;
    }
    
    .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        background-color: #fff;
        border-radius: 1rem 1rem 0 0;
    }
    
    .modal-body {
        padding: 1.5rem;
        background-color: #fff;
    }
    
    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        background-color: #fff;
        border-radius: 0 0 1rem 1rem;
    }
    
    /* Loading overlay düzeltmesi */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        border-radius: 1rem;
        backdrop-filter: blur(4px);
    }
    
    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #35D6ED;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    /* Modal animasyonları */
    .modal.fade .modal-dialog {
        transform: scale(0.8);
        transition: transform 0.3s ease-in-out;
    }
    
    .modal.show .modal-dialog {
        transform: scale(1);
    }
    
    /* Form elemanları */
    .modal .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .modal .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #344767;
        font-weight: 500;
    }
    
    .modal .form-control {
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid #d2d6da;
        transition: all 0.2s ease;
        font-size: 0.875rem;
        background-color: #fff;
    }
    
    .modal .form-control:focus {
        border-color: #35D6ED;
        box-shadow: 0 0 0 2px rgba(53, 214, 237, 0.25);
    }
    
    .modal textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }
    
    /* Alert stilleri */
    .modal .alert {
        border: none;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        background-color: rgba(53, 214, 237, 0.1);
        color: #35D6ED;
    }
    
    /* Buton stilleri */
    .modal .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .modal .btn-primary {
        background-color: #35D6ED;
        border-color: #35D6ED;
    }
    
    .modal .btn-primary:hover {
        background-color: #28c8df;
        border-color: #28c8df;
    }
    
    .modal .btn-secondary {
        background-color: #f8f9fa;
        border-color: #f8f9fa;
        color: #344767;
    }
    
    .modal .btn-secondary:hover {
        background-color: #e9ecef;
        border-color: #e9ecef;
    }
    
    .modal .form-group {
        margin-bottom: 1.5rem;
    }
    .modal .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #344767;
    }
    .modal .form-control {
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid #d2d6da;
        transition: all 0.2s ease;
    }
    .modal .form-control:focus {
        border-color: #35D6ED;
        box-shadow: 0 0 0 2px rgba(53, 214, 237, 0.25);
    }
    .modal textarea.form-control {
        min-height: 100px;
    }
    .modal .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .modal .btn-primary {
        background-color: #35D6ED;
        border-color: #35D6ED;
    }
    .modal .btn-primary:hover {
        background-color: #28c8df;
        border-color: #28c8df;
    }
    .modal .btn-secondary {
        background-color: #f8f9fa;
        border-color: #f8f9fa;
        color: #344767;
    }
    .modal .btn-secondary:hover {
        background-color: #e9ecef;
        border-color: #e9ecef;
    }
    .modal-content {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }
    .modal-body {
        padding: 1.5rem;
    }
    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }
    .flatpickr-input {
        background-color: #fff !important;
    }
    .flatpickr-calendar {
        border-radius: 0.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    /* Modal Animasyonları */
    .modal.fade .modal-dialog {
        transform: scale(0.8);
        transition: transform 0.3s ease-in-out;
    }
    
    .modal.show .modal-dialog {
        transform: scale(1);
    }
    
    /* Form Elemanları İyileştirmeleri */
    .form-control:focus + .form-label,
    .form-control:not(:placeholder-shown) + .form-label {
        color: #35D6ED;
    }
    
    .form-group {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .form-group .icon-wrapper {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        transition: color 0.2s;
    }
    
    .form-group .form-control {
        padding-left: 2.5rem;
    }
    
    .form-group .form-control:focus + .icon-wrapper {
        color: #35D6ED;
    }
    
    /* Loading Spinner */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1060;
        border-radius: 1rem;
        backdrop-filter: blur(4px);
    }
    
    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #35D6ED;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Tooltip ve Yardım Metinleri */
    .form-help {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
        display: block;
    }
    
    /* Buton İyileştirmeleri */
    .btn-float {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .btn-float:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    /* Hata Durumu */
    .is-invalid + .icon-wrapper {
        color: #dc3545;
    }

    /* Sıralama için stil eklentileri */
    .sortable-handle {
        cursor: move;
        padding: 10px;
        color: #6c757d;
        transition: color 0.2s;
    }

    .sortable-handle:hover {
        color: #35D6ED;
    }

    .ui-sortable-helper {
        display: table;
        background: white;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border-radius: 0.5rem;
    }

    .ui-sortable-placeholder {
        visibility: visible !important;
        background: rgba(53, 214, 237, 0.1) !important;
        border: 2px dashed #35D6ED !important;
        border-radius: 0.5rem;
    }

    .sorting {
        background-color: rgba(53, 214, 237, 0.05);
    }

    .sort-indicator {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #35D6ED;
        color: white;
        padding: 15px 25px;
        border-radius: 50px;
        box-shadow: 0 5px 15px rgba(53, 214, 237, 0.3);
        z-index: 1000;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from { transform: translateY(100px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    /* Form grupları için ek stiller */
    .modal .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .modal .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #344767;
        font-weight: 500;
    }
    
    .modal .form-label i {
        width: 20px;
        text-align: center;
    }
    
    .modal .form-control {
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid #d2d6da;
        transition: all 0.2s ease;
        font-size: 0.875rem;
    }
    
    .modal .form-control:focus {
        border-color: #35D6ED;
        box-shadow: 0 0 0 2px rgba(53, 214, 237, 0.25);
    }
    
    .modal textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }
    
    .modal .form-help {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.75rem;
        color: #6c757d;
    }
    
    /* Hata durumu için stiller */
    .modal .form-control.is-invalid {
        border-color: #dc3545;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    
    .modal .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.75rem;
        color: #dc3545;
    }
    
    /* Alert iyileştirmeleri */
    .modal .alert {
        border: none;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
    }
    
    .modal .alert-info {
        background-color: rgba(53, 214, 237, 0.1);
        color: #35D6ED;
    }
    
    .modal .alert i {
        font-size: 1rem;
    }
    
    /* Loading overlay iyileştirmeleri */
    .loading-overlay {
        border-radius: 1rem;
    }

    /* Modal temel stilleri */
    .modal {
        z-index: 1050;
    }
    
    .modal-backdrop {
        z-index: 1040;
        background-color: rgba(0, 0, 0, 0.5);
    }
    
    .modal-dialog {
        z-index: 1055;
    }
    
    .modal-content {
        background-color: #fff;
        border: none;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    /* Loading overlay düzeltmesi */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1060;
        border-radius: 1rem;
        backdrop-filter: blur(4px);
    }
    
    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #35D6ED;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    /* Modal stilleri */
    .modal-lg {
        max-width: 800px;
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .modal-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 500;
        color: #344767;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* Form elemanları */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
        color: #344767;
        font-weight: 500;
    }

    .form-label i {
        width: 20px;
        text-align: center;
        margin-right: 0.5rem;
        color: #35D6ED;
    }

    .form-control {
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid #d2d6da;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #35D6ED;
        box-shadow: 0 0 0 2px rgba(53, 214, 237, 0.25);
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    /* Butonlar */
    .btn-float {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-float:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Alert */
    .alert {
        border: none;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        background-color: rgba(53, 214, 237, 0.1);
        color: #35D6ED;
    }

    .alert i {
        font-size: 1rem;
    }

    /* Yorum alanı için özel stiller */
    .table td:nth-child(4) {
        max-width: 500px;
        white-space: normal;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .table td:nth-child(4) .yorum-text {
        display: block;
        max-height: none;
        overflow-y: visible;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Yorumlar</h3>
                </div>
                <div class="card-body">
                    <!-- Arama Formu -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form id="searchForm" class="row g-3">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="search" class="form-label">Arama</label>
                                                <input type="text" class="form-control" id="search" name="search" placeholder="Ad Soyad veya Yorum">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="daterange" class="form-label">Tarih Aralığı</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control flatpickr" id="daterange" name="daterange" placeholder="Tarih aralığı seçin">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="kaynak" class="form-label">Kaynak</label>
                                                <select class="form-select" id="kaynak" name="kaynak">
                                                    <option value="">Tümü</option>
                                                    @foreach($kaynaklar as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="form-label">&nbsp;</label>
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                                        <i class="fas fa-search"></i> Ara
                                                    </button>
                                                    <button type="button" class="btn btn-secondary" id="clearSearch">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Butonlar -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <button type="button" class="btn btn-danger" id="bulkDeleteBtn" style="display: none;">
                                <i class="fas fa-trash"></i> Seçili Yorumları Sil
                            </button>
                            <button type="button" class="btn btn-success" id="saveOrderBtn" style="display: none;">
                                <i class="fas fa-save"></i> Sıralamayı Kaydet
                            </button>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                                <i class="fas fa-plus"></i> Yeni Yorum Ekle
                            </button>
                        </div>
                    </div>

                    <!-- Tablo -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>#</th>
                                    <th>Adı Soyadı</th>
                                    <th>Yorum</th>
                                    <th>Yorum Tarihi</th>
                                    <th>Kaynak</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @include('admin.yorums.table-rows')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.yorums.modals.create')
@include('admin.yorums.modals.edit')
@include('admin.yorums.modals.view')

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/tr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
$(document).ready(function() {
    // Sayfa yüklendiğinde verileri yükle
    loadItems();

    // Flatpickr başlatma
    const flatpickrInstance = flatpickr(".flatpickr", {
        locale: "tr",
        dateFormat: "d.m.Y",
        allowInput: true,
        altInput: true,
        altFormat: "d.m.Y",
        mode: "range",
        rangeSeparator: " - ",
        onChange: function(selectedDates, dateStr) {
            if (selectedDates.length === 2) {
                loadItems();
            }
        }
    });

    // Düzenleme ve ekleme modalları için tek tarih seçimi
    let editDatePicker;
    let createDatePicker;

    // Tabloyu yenileme fonksiyonu
    function loadItems() {
        const formData = new FormData($('#searchForm')[0]);
        const searchParams = new URLSearchParams();
        
        for (let [key, value] of formData.entries()) {
            if (value) {
                if (key === 'daterange') {
                    const dates = value.split(' - ');
                    if (dates.length === 2) {
                        searchParams.append('start_date', dates[0].trim());
                        searchParams.append('end_date', dates[1].trim());
                    }
                } else {
                    searchParams.append(key, value);
                }
            }
        }
        
        $.ajax({
            url: '{{ route("admin.comments.index") }}',
            type: 'GET',
            data: searchParams.toString(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                $('#tableBody').html(response);
            },
            error: function(xhr) {
                console.error('Arama hatası:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: 'Arama yapılırken bir hata oluştu. Lütfen tekrar deneyin.'
                });
            }
        });
    }

    // Arama formu submit olayı
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        loadItems();
    });

    // Temizle butonu tıklama olayı
    $('#clearSearch').on('click', function() {
        $('#searchForm')[0].reset();
        flatpickrInstance.clear();
        loadItems();
    });

    // Input değişikliklerinde otomatik arama
    let searchTimeout;
    $('#searchForm input, #searchForm select').on('input change', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            loadItems();
        }, 500);
    });

    // Tümünü seç/kaldır
    $('#selectAll').on('change', function() {
        $('.item-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkButtons();
    });

    // Tekli checkbox değişikliği
    $(document).on('change', '.item-checkbox', function() {
        updateBulkButtons();
    });

    // Toplu silme butonu görünürlüğünü güncelle
    function updateBulkButtons() {
        const checkedCount = $('.item-checkbox:checked').length;
        $('#bulkDeleteBtn').toggle(checkedCount > 0);
    }

    // Toplu silme işlemi
    $('#bulkDeleteBtn').on('click', function() {
        const selectedIds = $('.item-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) return;

        Swal.fire({
            title: 'Emin misiniz?',
            text: `Seçili ${selectedIds.length} yorum kalıcı olarak silinecek!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.yorums.bulk-delete") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: selectedIds
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Başarılı!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            loadItems();
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: 'Yorumlar silinirken bir hata oluştu.'
                        });
                    }
                });
            }
        });
    });

    // Yeni yorum ekleme formu
    $('#createCommentForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    $('#createModal').modal('hide');
                    $('#createCommentForm')[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Başarılı!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    loadItems();
                }
            },
            error: function(xhr) {
                let errorMessage = 'Bir hata oluştu.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: errorMessage
                });
            }
        });
    });

    // Düzenleme butonu tıklama olayı
    $(document).on('click', '.edit-comment', function() {
        const id = $(this).data('id');
        $('#edit_comment_id').val(id);
        
        // Yorum verilerini getir
        $.get(`/admin/comments/${id}/edit`, function(response) {
            if (response.success) {
                const comment = response.data;
                $('#edit_name').val(comment.name);
                $('#edit_kaynak').val(comment.kaynak);
                $('#edit_content').val(comment.content);
                $('#editCommentModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: response.message || 'Yorum bilgileri alınamadı'
                });
            }
        });
    });

    // Form gönderimi
    $('#editCommentForm').on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#edit_comment_id').val();
        const submitBtn = $(this).find('button[type="submit"]');
        
        // Submit butonunu devre dışı bırak
        submitBtn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Güncelleniyor...
        `);
        
        $.ajax({
            url: `/admin/comments/${id}`,
            type: 'POST',
            data: $(this).serialize() + '&_method=PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Modalı kapat
                    $('#editCommentModal').modal('hide');
                    
                    // Başarı mesajı göster
                    Swal.fire({
                        icon: 'success',
                        title: 'Başarılı!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Sayfayı yenile
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: response.message || 'Güncelleme sırasında bir hata oluştu'
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'Bir hata oluştu';
                if (xhr.responseJSON?.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                } else if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: errorMessage
                });
            },
            complete: function() {
                // Submit butonunu tekrar aktif et
                submitBtn.prop('disabled', false).html(`
                    <i class="fas fa-save me-2"></i>Güncelle
                `);
            }
        });
    });

    // Silme butonu tıklama olayı
    $(document).on('click', '.delete-comment', function() {
        const yorumId = $(this).data('id');
        
        Swal.fire({
            title: 'Emin misiniz?',
            text: "Bu yorum kalıcı olarak silinecek!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/yorums/${yorumId}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Başarılı!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            loadItems();
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: 'Yorum silinirken bir hata oluştu.'
                        });
                    }
                });
            }
        });
    });

    // Sıralama işlevselliği
    $("#tableBody").sortable({
        handle: ".sortable-handle",
        placeholder: "ui-sortable-placeholder",
        helper: function(e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index) {
                $(this).width($originals.eq(index).width());
            });
            return $helper;
        },
        start: function(e, ui) {
            ui.placeholder.height(ui.item.height());
            ui.placeholder.width(ui.item.width());
            ui.item.addClass('sorting');
            $('#saveOrderBtn').show();
        },
        stop: function(e, ui) {
            ui.item.removeClass('sorting');
            updatePositions();
        }
    });

    // Sıralama butonu tıklama olayı
    $('#saveOrderBtn').on('click', function() {
        const positions = [];
        $('#tableBody tr').each(function(index) {
            const id = $(this).data('id');
            if (id) {
                positions.push({
                    id: id,
                    position: index + 1
                });
            }
        });

        if (positions.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Hata!',
                text: 'Sıralanacak yorum bulunamadı.'
            });
            return;
        }

        $.ajax({
            url: '{{ route("admin.yorums.reorder") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                positions: positions
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Başarılı!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#saveOrderBtn').hide();
                    loadItems();
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: 'Sıralama kaydedilirken bir hata oluştu.'
                });
            }
        });
    });

    // Pozisyonları güncelleme fonksiyonu
    function updatePositions() {
        $('#tableBody tr').each(function(index) {
            $(this).find('.position-number').text(index + 1);
        });
    }

    // View modal işlemleri
    $(document).on('click', '.view-btn', function() {
        const id = $(this).data('id');
        
        // Loading overlay göster
        $('#viewModal .loading-overlay').show();
        
        $.ajax({
            url: `/admin/yorums/${id}`,
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    const yorum = data.yorum;
                    
                    $('#view-adisoyadi').text(yorum.adisoyadi);
                    $('#view-yorumtarihi').text(yorum.yorumtarihi);
                    $('#view-kaynak').text(yorum.kaynak);
                    $('#view-yorum').text(yorum.yorum);
                    $('#view-created-by').text(yorum.creator ? yorum.creator.name : 'Bilinmiyor');
                    $('#view-created-at').text(data.created_at);
                    
                    // Değişiklik geçmişini tabloya ekle
                    let changesHtml = '';
                    if (data.changes && data.changes.length > 0) {
                        changesHtml = data.changes.map(change => `
                            <tr>
                                <td>${change.date}</td>
                                <td>${change.user}</td>
                                <td>${change.field}: ${change.old_value} → ${change.new_value}</td>
                            </tr>
                        `).join('');
                    } else {
                        changesHtml = '<tr><td colspan="3" class="text-center">Henüz değişiklik yapılmamış.</td></tr>';
                    }
                    
                    $('#view-changes').html(changesHtml);
                    
                    // Modalı göster
                    $('#viewModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: response.message || 'Yorum detayları alınamadı.'
                    });
                }
            },
            error: function(xhr) {
                console.error('Yorum detayı alınırken hata:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: 'Yorum detayları alınırken bir hata oluştu.'
                });
            },
            complete: function() {
                // Loading overlay'i gizle
                $('#viewModal .loading-overlay').hide();
            }
        });
    });
});
</script>
@endpush