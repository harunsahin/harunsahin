<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>Ad Soyad</th>
            <th>E-posta</th>
            <th>Rol</th>
            <th>Durum</th>
            <th>İşlemler</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td>
                    <span class="badge bg-{{ $user->status == 'Aktif' ? 'success' : 'danger' }}">
                        {{ $user->status }}
                    </span>
                </td>
                <td>
                    <button class="btn btn-warning btn-sm edit-user" data-id="{{ $user->id }}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-sm delete-user" data-id="{{ $user->id }}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Henüz kullanıcı eklenmemiş.</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $users->links() }} 