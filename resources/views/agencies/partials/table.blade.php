<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Acente Adı</th>
                <th>E-posta</th>
                <th>Telefon</th>
                <th>Durum</th>
                <th class="text-end">İşlemler</th>
            </tr>
        </thead>
        <tbody>
            @forelse($agencies as $agency)
                <tr>
                    <td>{{ $agency->id }}</td>
                    <td>{{ $agency->name }}</td>
                    <td>{{ $agency->email }}</td>
                    <td>{{ $agency->phone }}</td>
                    <td>
                        <div class="form-check form-switch">
                            <input type="checkbox" 
                                   class="form-check-input toggle-status" 
                                   data-id="{{ $agency->id }}"
                                   {{ $agency->is_active ? 'checked' : '' }}>
                        </div>
                    </td>
                    <td class="text-end">
                        <div class="btn-group" role="group">
                            <!-- Görüntüle Butonu -->
                            <button class="btn btn-sm btn-info view-agency" 
                                    data-id="{{ $agency->id }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#viewAgencyModal">
                                <i class="fas fa-eye"></i>
                            </button>
                            <!-- Düzenle Butonu -->
                            <button class="btn btn-sm btn-warning edit-agency" 
                                    data-id="{{ $agency->id }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editAgencyModal{{ $agency->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <!-- Sil Butonu -->
                            <button class="btn btn-sm btn-danger" 
                                    onclick="deleteAgency({{ $agency->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Arama kriterlerinize uygun acente bulunamadı.
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($agencies->hasPages())
    <div class="mt-3">
        {{ $agencies->links() }}
    </div>
@endif 