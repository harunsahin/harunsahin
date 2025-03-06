@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Kullanıcı Rolleri</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
            <i class="fas fa-plus"></i> Yeni Rol
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Rol Adı</th>
                            <th>Açıklama</th>
                            <th>Yetkiler</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->description }}</td>
                                <td>
                                    @foreach($role->permissions as $permission)
                                        <span class="badge bg-info me-1">{{ $permission->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @if(!in_array($role->slug, ['super-admin', 'admin']))
                                        <button class="btn btn-sm btn-primary edit-role" 
                                                data-role="{{ $role }}"
                                                data-permissions="{{ $role->permissions->pluck('id') }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editRoleModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-role"
                                                data-role-id="{{ $role->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Rol bulunamadı</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Yeni Rol Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Yeni Rol</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createRoleForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rol Adı</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Açıklama</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Yetkiler</label>
                        <div class="row">
                            @foreach($permissions as $permission)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="permissions[]" value="{{ $permission->id }}"
                                               id="perm_{{ $permission->id }}">
                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Düzenleme Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rol Düzenle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRoleForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="editRoleId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rol Adı</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Açıklama</label>
                        <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Yetkiler</label>
                        <div class="row">
                            @foreach($permissions as $permission)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input edit-permission" type="checkbox" 
                                               name="permissions[]" value="{{ $permission->id }}"
                                               id="edit_perm_{{ $permission->id }}">
                                        <label class="form-check-label" for="edit_perm_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Yeni rol oluşturma
    $('#createRoleForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("admin.roles.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    $('#createRoleModal').modal('hide');
                    toastr.success(response.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Bir hata oluştu');
            }
        });
    });

    // Düzenleme modalını aç
    $('.edit-role').click(function() {
        const role = $(this).data('role');
        const permissions = $(this).data('permissions');
        
        $('#editRoleId').val(role.id);
        $('#editName').val(role.name);
        $('#editDescription').val(role.description);
        
        // Yetkileri işaretle
        $('.edit-permission').prop('checked', false);
        permissions.forEach(permissionId => {
            $(`#edit_perm_${permissionId}`).prop('checked', true);
        });
    });

    // Rol düzenleme
    $('#editRoleForm').submit(function(e) {
        e.preventDefault();
        const roleId = $('#editRoleId').val();
        $.ajax({
            url: `/admin/roles/${roleId}`,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    $('#editRoleModal').modal('hide');
                    toastr.success(response.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Bir hata oluştu');
            }
        });
    });

    // Rol silme
    $('.delete-role').click(function() {
        if(!confirm('Bu rolü silmek istediğinizden emin misiniz?')) return;
        
        const roleId = $(this).data('role-id');
        $.ajax({
            url: `/admin/roles/${roleId}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Bir hata oluştu');
            }
        });
    });
});
</script>
@endpush
@endsection 