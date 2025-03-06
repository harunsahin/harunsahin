<table class="table table-striped">
    <thead>
        <tr>
            <th>Acente Adı</th>
            <th>Telefon</th>
            <th>E-posta</th>
            <th>Durum</th>
            <th>İşlemler</th>
        </tr>
    </thead>
    <tbody>
        @forelse($agencies as $agency)
            <tr>
                <td>{{ $agency->name }}</td>
                <td>{{ $agency->phone ?? '-' }}</td>
                <td>{{ $agency->email ?? '-' }}</td>
                <td>
                    <div class="form-check form-switch">
                        <input class="form-check-input status-toggle" 
                               type="checkbox" 
                               id="status_{{ $agency->id }}"
                               data-id="{{ $agency->id }}"
                               {{ $agency->status === 'Aktif' ? 'checked' : '' }}>
                        <label class="form-check-label status-label-{{ $agency->id }}" 
                               for="status_{{ $agency->id }}">
                            {{ $agency->status }}
                        </label>
                    </div>
                </td>
                <td>
                    <button class="btn btn-sm btn-warning edit-agency" 
                            data-id="{{ $agency->id }}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" 
                            onclick="deleteAgency({{ $agency->id }})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Kayıt bulunamadı.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-end mt-3">
    {{ $agencies->links() }}
</div> 