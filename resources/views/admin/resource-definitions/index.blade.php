@extends('layouts.app')

@section('title', 'Kaynak Tanımları')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title">Kaynak Tanımları</h3>
                        <button type="button" class="btn btn-danger" id="bulkDeleteBtn" style="display: none;">
                            <i class="fas fa-trash"></i> Seçili Kaynakları Sil
                        </button>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createResourceModal">
                        <i class="fas fa-plus"></i> Yeni Kaynak Ekle
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>#</th>
                                    <th>Kaynak</th>
                                    <th>Durum</th>
                                    <th>Sıra</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($resourceDefinitions as $resource)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="item-checkbox" value="{{ $resource->id }}">
                                        </td>
                                        <td>{{ $resource->id }}</td>
                                        <td>{{ $resource->kaynak }}</td>
                                        <td>
                                            <span class="badge bg-{{ $resource->is_active ? 'success' : 'danger' }}">
                                                {{ $resource->is_active ? 'Aktif' : 'Pasif' }}
                                            </span>
                                        </td>
                                        <td>{{ $resource->position }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary edit-btn" data-id="{{ $resource->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $resource->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Kaynak tanımı bulunamadı.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $resourceDefinitions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.resource-definitions.modals.create')
@include('admin.resource-definitions.modals.edit')
@include('admin.resource-definitions.modals.delete')
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
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

    // Düzenleme butonu tıklama olayı
    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("admin/resource-definitions") }}/' + id + '/edit',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#editResourceForm').data('id', id);
                    $('#edit_kaynak').val(response.data.kaynak);
                    $('#edit_is_active').val(response.data.is_active ? '1' : '0');
                    $('#edit_position').val(response.data.position);
                    $('#editResourceModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: response.message || 'Bir hata oluştu!'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: xhr.responseJSON?.message || 'Bir hata oluştu!'
                });
            }
        });
    });

    // Silme butonu tıklama olayı
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        $('#deleteResourceForm').data('id', id);
        $('#deleteResourceModal').modal('show');
    });

    // Kaynak ekleme
    $('#createResourceForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("admin.resource-definitions.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#createResourceModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: xhr.responseJSON?.error || 'Bir hata oluştu!'
                });
            }
        });
    });

    // Kaynak düzenleme
    $('#editResourceForm').on('submit', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("admin/resource-definitions") }}/' + id,
            type: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Başarılı!',
                    text: response.message
                }).then(() => {
                    $('#editResourceModal').modal('hide');
                    location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: xhr.responseJSON?.error || 'Bir hata oluştu!'
                });
            }
        });
    });

    // Kaynak silme
    $('#deleteResourceForm').on('submit', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        $.ajax({
            url: '{{ url("admin/resource-definitions") }}/' + id,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteResourceModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: xhr.responseJSON?.error || 'Bir hata oluştu!'
                });
            }
        });
    });

    // Toplu silme
    $('#bulkDeleteBtn').on('click', function() {
        const ids = [];
        $('input[name="ids[]"]:checked').each(function() {
            ids.push($(this).val());
        });
        if (ids.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Uyarı!',
                text: 'Lütfen en az bir kaynak seçin!'
            });
            return;
        }
        $.ajax({
            url: '{{ route("admin.resource-definitions.bulk-delete") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ids: ids
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: xhr.responseJSON?.error || 'Bir hata oluştu!'
                });
            }
        });
    });
});
</script>
@endpush 