@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Kullanıcılar</h1>
            <p class="text-muted">Sistem kullanıcılarını yönetin</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="window.location.href='{{ route('admin.roles.index') }}'">
                <i class="fas fa-user-tag me-2"></i>Roller
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="fas fa-plus me-2"></i>Yeni Kullanıcı
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Kullanıcı</th>
                            <th class="border-0">E-posta</th>
                            <th class="border-0">Rol</th>
                            <th class="border-0">Durum</th>
                            <th class="border-0 text-end">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-3">
                                            <span class="avatar-text text-primary">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        {{ $user->role->display_name }}
                                    </span>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggle-active" type="checkbox" 
                                            data-user-id="{{ $user->id }}"
                                            {{ $user->is_active ? 'checked' : '' }}
                                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-sm btn-primary edit-user" 
                                                data-user="{{ $user }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editUserModal"
                                                title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if($user->id !== auth()->id())
                                            <button class="btn btn-sm btn-danger delete-user"
                                                    data-user-id="{{ $user->id }}"
                                                    title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-users fa-2x mb-3 opacity-50"></i>
                                        <p class="mb-0">Henüz kullanıcı bulunmuyor</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Kullanıcı Ekleme Modal -->
@include('users.modals.create')

<!-- Kullanıcı Düzenleme Modal -->
@include('users.modals.edit')

@push('styles')
<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-text {
    font-size: 14px;
    font-weight: 600;
}

.badge {
    font-weight: 500;
    padding: 6px 10px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Kullanıcı düzenleme modalını aç
    $('.edit-user').click(function() {
        const userData = $(this).data('user');
        $('#editUserId').val(userData.id);
        $('#editName').val(userData.name);
        $('#editEmail').val(userData.email);
        $('#editRoleId').val(userData.role_id);
    });
    
    // Kullanıcı durumunu değiştir
    $('.toggle-active').change(function() {
        const userId = $(this).data('user-id');
        const checkbox = $(this);
        
        $.ajax({
            url: `/admin/users/${userId}/toggle-active`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                    checkbox.prop('checked', !checkbox.prop('checked'));
                }
            },
            error: function(xhr) {
                toastr.error('Bir hata oluştu');
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        });
    });
    
    // Kullanıcı silme
    $('.delete-user').click(function() {
        const userId = $(this).data('user-id');
        
        Swal.fire({
            title: 'Emin misiniz?',
            text: "Bu kullanıcıyı silmek istediğinize emin misiniz?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/users/${userId}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            location.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Bir hata oluştu');
                    }
                });
            }
        });
    });
});
</script>
@endpush
@endsection 