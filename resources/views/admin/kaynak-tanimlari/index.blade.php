@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kaynak Tanımları</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" placeholder="Ara...">
                                <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select id="statusFilter" class="form-select">
                                <option value="">Tüm Durumlar</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Pasif</option>
                            </select>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                                <i class="fas fa-plus"></i> Yeni Kaynak Ekle
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th width="50">Sıra</th>
                                    <th>Kaynak</th>
                                    <th width="100">Durum</th>
                                    <th width="100">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                @include('admin.kaynak-tanimlari.table-rows')
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-danger" id="bulkDeleteButton" disabled>
                            <i class="fas fa-trash"></i> Seçili Kaynakları Sil
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.kaynak-tanimlari.modals.create')
@include('admin.kaynak-tanimlari.modals.edit')
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Arama işlemi
    $('#searchButton').click(function() {
        loadTable();
    });

    $('#searchInput').keypress(function(e) {
        if (e.which == 13) {
            loadTable();
        }
    });

    // Durum filtresi
    $('#statusFilter').change(function() {
        loadTable();
    });

    // Tablo yükleme fonksiyonu
    function loadTable() {
        $.get('{{ route("admin.kaynak-tanimlari.index") }}', {
            search: $('#searchInput').val(),
            status: $('#statusFilter').val()
        }, function(response) {
            $('#tableBody').html(response);
        });
    }

    // Toplu seçim
    $('#selectAll').change(function() {
        $('.item-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkDeleteButton();
    });

    // Tekli seçim
    $(document).on('change', '.item-checkbox', function() {
        updateBulkDeleteButton();
    });

    // Toplu silme butonu durumu
    function updateBulkDeleteButton() {
        var checkedCount = $('.item-checkbox:checked').length;
        $('#bulkDeleteButton').prop('disabled', checkedCount === 0);
    }

    // Toplu silme işlemi
    $('#bulkDeleteButton').click(function() {
        var ids = [];
        $('.item-checkbox:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length > 0) {
            if (confirm('Seçili kaynakları silmek istediğinize emin misiniz?')) {
                $.ajax({
                    url: '{{ route("admin.kaynak-tanimlari.bulk-delete") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: ids
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            loadTable();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('Bir hata oluştu.');
                    }
                });
            }
        }
    });

    // Sıralama işlemi
    $('#tableBody').sortable({
        handle: 'td:first',
        update: function(event, ui) {
            var positions = [];
            $('.item-checkbox').each(function(index) {
                positions.push({
                    id: $(this).val(),
                    position: index + 1
                });
            });

            $.ajax({
                url: '{{ route("admin.kaynak-tanimlari.reorder") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    positions: positions
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Bir hata oluştu.');
                }
            });
        }
    });
});
</script>
@endpush 