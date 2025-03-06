@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Arama Formu -->
    <x-search-form :statuses="$statuses" />

    <!-- Tablo -->
    <div class="card">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $title }}</h5>
                <div class="d-flex gap-2">
                    <button type="button" 
                            id="deleteSelected" 
                            class="btn btn-danger" 
                            style="display: none;">
                        <i class="fas fa-trash me-1"></i>Seçilileri Sil
                    </button>
                    <button type="button" 
                            class="btn btn-primary" 
                            data-bs-toggle="modal" 
                            data-bs-target="#createModal">
                        <i class="fas fa-plus-circle me-1"></i>Yeni {{ $buttonText }}
                    </button>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 80px; padding-right: 0;">
                            <div class="d-flex align-items-center">
                                <div class="form-check mb-0 me-2">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                                ID
                            </div>
                        </th>
                        @foreach($columns as $column)
                            <th>{{ $column['label'] }}</th>
                        @endforeach
                        <th class="text-end" style="width: 120px">İşlemler</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @include($partialView)
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Modal -->
    <x-module-modal 
        id="createModal" 
        title="Yeni {{ $buttonText }}"
        :statuses="$statuses"
        :model="null"
        action="{{ route($routePrefix.'.store') }}"
    />

    <!-- View Modal -->
    @include($viewModal)

    <!-- Edit Modals -->
    @foreach($items as $item)
        <x-module-modal 
            id="editModal_{{ $item->id }}"
            title="{{ $buttonText }} Düzenle"
            :statuses="$statuses"
            :model="$item"
            action="{{ route($routePrefix.'.update', $item->id) }}"
            method="PUT"
        />
    @endforeach
</div>

@push('styles')
<style>
/* Genel Stiller */
.container-fluid {
    max-width: 1800px;
}

/* Tablo Stilleri */
.table {
    font-size: 0.875rem;
    margin-bottom: 0;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    padding: 0.75rem;
    border-bottom: 2px solid #dee2e6;
    white-space: nowrap;
}

.table td {
    padding: 0.75rem;
    vertical-align: middle;
}

/* Form Stilleri */
.form-floating > .form-control,
.form-floating > .form-select {
    height: calc(2.8rem + 2px);
    font-size: 0.875rem;
}

.form-floating > label {
    font-size: 0.875rem;
    padding: 0.75rem;
}

/* Buton Stilleri */
.btn {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

/* Badge Stilleri */
.badge {
    font-size: 0.75rem;
    padding: 0.5em 0.75em;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding: 0.5rem !important;
    }
    
    .card {
        border-radius: 0;
    }
}
</style>
@endpush

@push('scripts')
<!-- Moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/tr.min.js"></script>

<script>
$(document).ready(function() {
    // Tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Tüm seçim checkbox'u
    $('#selectAll').change(function() {
        $('.item-checkbox').prop('checked', $(this).prop('checked'));
        updateDeleteButton();
    });

    // Tekil checkbox'lar
    $(document).on('change', '.item-checkbox', function() {
        updateDeleteButton();
    });

    // Silme butonu görünürlüğü
    function updateDeleteButton() {
        const checkedCount = $('.item-checkbox:checked').length;
        $('#deleteSelected').toggle(checkedCount > 0);
    }

    // Toplu silme
    $('#deleteSelected').click(function() {
        const selectedIds = $('.item-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) return;

        Swal.fire({
            title: 'Emin misiniz?',
            text: `Seçili ${selectedIds.length} öğeyi silmek istediğinize emin misiniz?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route($routePrefix.".destroy", ":id") }}'.replace(':id', selectedIds.join(',')),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Seçili öğeler başarıyla silindi');
                            loadItems();
                        } else {
                            toastr.error(response.message || 'Bir hata oluştu');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Silme işlemi sırasında bir hata oluştu');
                    }
                });
            }
        });
    });

    // Tekil silme
    $(document).on('click', '.delete-item', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        Swal.fire({
            title: 'Emin misiniz?',
            text: `"${name}" öğesini silmek istediğinize emin misiniz?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route($routePrefix.".destroy", ":id") }}'.replace(':id', id),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Öğe başarıyla silindi');
                            loadItems();
                        } else {
                            toastr.error(response.message || 'Bir hata oluştu');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Silme işlemi sırasında bir hata oluştu');
                    }
                });
            }
        });
    });

    // Öğeleri yükle
    function loadItems() {
        $.ajax({
            url: '{{ route($routePrefix.".index") }}',
            type: 'GET',
            data: $('#searchForm').serialize(),
            beforeSend: function() {
                $('#loadingIndicator').fadeIn(200);
            },
            success: function(response) {
                $('#tableBody').html(response);
                updateDeleteButton();
                
                const selectAllCheckbox = $('#selectAll');
                if (selectAllCheckbox.length) {
                    selectAllCheckbox.prop('checked', false);
                }
            },
            error: function(xhr, status, error) {
                console.error('Yükleme hatası:', error);
                let errorMessage = 'Veriler yüklenirken bir hata oluştu';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                toastr.error(errorMessage);
            },
            complete: function() {
                $('#loadingIndicator').fadeOut(200);
            }
        });
    }

    // Sayfa yüklendiğinde ilk yüklemeyi yap
    loadItems();
});
</script>
@endpush
@endsection 