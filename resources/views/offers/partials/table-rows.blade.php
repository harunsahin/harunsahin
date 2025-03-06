@forelse($offers as $offer)
<tr class="offer-row" data-offer-id="{{ $offer->id }}">
    <td>
        <div class="d-flex align-items-center">
            <div class="form-check me-2">
                <input class="form-check-input offer-checkbox" type="checkbox" value="{{ $offer->id }}">
            </div>
            {{ $offer->id }}
            @if($offer->files->count() > 0)
                <i class="fas fa-paperclip text-primary ms-1" 
                   data-bs-toggle="tooltip" 
                   data-bs-placement="top"
                   title="{{ $offer->files->count() }} dosya ekli"></i>
            @endif
        </div>
    </td>
    <td>{{ optional($offer->agency)->name ?? '-' }}</td>
    <td>{{ optional($offer->company)->name ?? '-' }}</td>
    <td>
        <div class="d-flex align-items-center">
            <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-2">
                {{ strtoupper(substr($offer->full_name, 0, 1)) }}
            </div>
            {{ $offer->full_name }}
        </div>
    </td>
    <td>{{ $offer->checkin_date ? $offer->checkin_date->format('d.m.Y') : '-' }}</td>
    <td>{{ $offer->checkout_date ? $offer->checkout_date->format('d.m.Y') : '-' }}</td>
    <td class="text-center">{{ $offer->room_count }}/{{ $offer->pax_count }}</td>
    <td>
        <span class="badge" style="background-color: {{ $offer->status->color }}" data-status-id="{{ $offer->status_id }}">
            {{ $offer->status->name }}
        </span>
    </td>
    <td class="text-end">
        <div class="btn-group btn-group-sm shadow-sm" role="group">
            <button type="button" 
                    class="btn btn-xs btn-soft-info view-offer" 
                    data-offer-id="{{ $offer->id }}"
                    data-bs-toggle="modal" 
                    data-bs-target="#viewOfferModal">
                <i class="fas fa-eye fa-sm"></i>
            </button>
            <button type="button" 
                    class="btn btn-xs btn-soft-primary edit-offer" 
                    data-offer-id="{{ $offer->id }}"
                    data-bs-toggle="modal" 
                    data-bs-target="#editOfferModal_{{ $offer->id }}">
                <i class="fas fa-edit fa-sm"></i>
            </button>
            <button type="button" 
                    class="btn btn-xs btn-soft-danger delete-offer"
                    data-offer-id="{{ $offer->id }}"
                    data-name="{{ $offer->full_name }}">
                <i class="fas fa-trash fa-sm"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="9" class="text-center py-4">
        <div class="text-muted">
            <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
            Teklif bulunamadı
        </div>
    </td>
</tr>
@endforelse

@if($offers instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <tr>
        <td colspan="9">
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="d-flex align-items-center">
                    <div class="text-muted me-3">
                        Toplam {{ $offers->total() }} kayıttan {{ $offers->firstItem() }}-{{ $offers->lastItem() }} arası gösteriliyor
                    </div>
                    <div class="d-flex align-items-center">
                        <label class="me-2 text-muted">Sayfa başına:</label>
                        <select class="form-select form-select-sm per-page-select" style="width: auto;">
                            <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200</option>
                            <option value="500" {{ request('per_page') == 500 ? 'selected' : '' }}>500</option>
                        </select>
                    </div>
                </div>
                <div>
                    {{ $offers->appends(['per_page' => request('per_page', 50)])->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </td>
    </tr>
@endif

<style>
.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}

.badge {
    font-size: 12px;
    padding: 6px 12px;
}

.btn-xs {
    padding: 0.15rem 0.35rem;
    font-size: 0.7rem;
    line-height: 1;
    border-radius: 0.15rem;
    height: 22px;
}

.btn-xs i {
    font-size: 0.7rem;
    line-height: 1;
    vertical-align: middle;
    margin-top: -2px;
}

.btn-group-sm {
    height: 22px;
}

.btn-group-sm .btn + .btn {
    margin-left: 1px;
}

.btn-soft-primary {
    color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.1);
    border-color: transparent;
}

.btn-soft-primary:hover {
    color: #fff;
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.btn-soft-info {
    color: #0dcaf0;
    background-color: rgba(13, 202, 240, 0.1);
    border-color: transparent;
}

.btn-soft-info:hover {
    color: #fff;
    background-color: #0dcaf0;
    border-color: #0dcaf0;
}

.btn-soft-danger {
    color: #dc3545;
    background-color: rgba(220, 53, 69, 0.1);
    border-color: transparent;
}

.btn-soft-danger:hover {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
}

.table > :not(caption) > * > * {
    vertical-align: middle;
}

.pagination {
    margin-bottom: 0;
}

.page-link {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

.page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}

.form-select.form-select-sm {
    padding: 0.25rem 2rem 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.2rem;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // Tooltip'leri aktif et
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Tümünü seç/kaldır
    $('#selectAll').on('change', function() {
        $('.offer-checkbox').prop('checked', $(this).prop('checked'));
        updateDeleteButton();
    });

    // Tekil checkbox değişikliklerini kontrol et
    $(document).on('change', '.offer-checkbox', function() {
        updateDeleteButton();
    });

    // Silme butonunun görünürlüğünü güncelle
    function updateDeleteButton() {
        const checkedCount = $('.offer-checkbox:checked').length;
        $('#deleteSelected').toggle(checkedCount > 0);
    }

    // Seçili teklifleri sil
    $('#deleteSelected').on('click', function() {
        const selectedIds = $('.offer-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) return;

        Swal.fire({
            title: 'Emin misiniz?',
            text: `Seçili ${selectedIds.length} teklif silinecek. Bu işlem geri alınamaz!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Evet, sil',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("offers.bulk-delete") }}',
                    method: 'POST',
                    data: {
                        ids: selectedIds,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Seçili satırları kaldır
                            selectedIds.forEach(function(id) {
                                $(`tr[data-offer-id="${id}"]`).fadeOut(300, function() {
                                    $(this).remove();
                                });
                            });

                            // Tümünü seç checkbox'ını kaldır
                            $('#selectAll').prop('checked', false);

                            // Silme butonunu gizle
                            $('#deleteSelected').hide();

                            // Başarı mesajı göster
                            Swal.fire({
                                icon: 'success',
                                title: 'Başarılı!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: xhr.responseJSON?.message || 'Teklifler silinirken bir hata oluştu.'
                        });
                    }
                });
            }
        });
    });

    // Event delegation ile silme işlemi
    $(document).on('click', '.delete-offer', function() {
        const id = $(this).data('offer-id');
        const name = $(this).data('name');
        
        Swal.fire({
            title: 'Emin misiniz?',
            text: `"${name}" teklifini silmek istediğinize emin misiniz?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/offers/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Başarılı!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            refreshTable();
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: xhr.responseJSON?.message || 'Silme işlemi sırasında bir hata oluştu.'
                        });
                    }
                });
            }
        });
    });

    // Tabloyu yenileme fonksiyonu
    function refreshTable() {
        const currentUrl = new URL(window.location.href);
        
        $.ajax({
            url: currentUrl.pathname + currentUrl.search,
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                $('#offersTableBody').html(response);
                // Tooltip'leri yeniden aktif et
                $('[data-bs-toggle="tooltip"]').tooltip();
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: 'Tablo yenilenirken bir hata oluştu.'
                });
            }
        });
    }

    // Sayfa başına kayıt sayısı değiştiğinde
    $('.per-page-select').change(function() {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('per_page', $(this).val());
        currentUrl.searchParams.set('page', 1);
        window.location.href = currentUrl.toString();
    });
});
</script>
@endpush 