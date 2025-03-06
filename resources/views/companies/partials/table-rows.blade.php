@forelse($companies as $company)
    <tr>
        <td>{{ $company->name }}</td>
        <td>{{ $company->phone }}</td>
        <td>{{ $company->email }}</td>
        <td>{{ Str::limit($company->address, 50) }}</td>
        <td>
            <div class="form-check form-switch">
                <input class="form-check-input toggle-status" 
                       type="checkbox" 
                       data-id="{{ $company->id }}"
                       {{ $company->is_active ? 'checked' : '' }}>
            </div>
        </td>
        <td>
            <div class="btn-group" role="group">
                <button class="btn btn-sm btn-primary edit-company" 
                        data-company="{{ $company }}"
                        data-bs-toggle="modal" 
                        data-bs-target="#editCompanyModal">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger delete-company"
                        data-id="{{ $company->id }}"
                        data-name="{{ $company->name }}">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center py-4">
            <div class="text-muted">
                <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                Şirket bulunamadı
            </div>
        </td>
    </tr>
@endforelse

@if($companies instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <tr>
        <td colspan="6">
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="d-flex align-items-center">
                    <div class="text-muted me-3">
                        Toplam {{ $companies->total() }} kayıttan {{ $companies->firstItem() }}-{{ $companies->lastItem() }} arası gösteriliyor
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
                    {{ $companies->appends(['per_page' => request('per_page', 50)])->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </td>
    </tr>
@endif 