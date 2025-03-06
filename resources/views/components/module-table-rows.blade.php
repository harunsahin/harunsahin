@props([
    'items',
    'columns',
    'routePrefix' => 'admin'
])

@forelse($items as $item)
<tr class="item-row" data-id="{{ $item->id }}">
    <td>
        <div class="d-flex align-items-center">
            <div class="form-check mb-0 me-2">
                <input class="form-check-input item-checkbox" 
                       type="checkbox" 
                       value="{{ $item->id }}">
            </div>
            {{ $item->id }}
        </div>
    </td>

    @foreach($columns as $column)
        <td>
            @if($column['type'] === 'status')
                <span class="badge" style="background-color: {{ $item->status->color }}">
                    {{ $item->status->name }}
                </span>
            @elseif($column['type'] === 'date')
                {{ $item->{$column['field']} ? \Carbon\Carbon::parse($item->{$column['field']})->format('d.m.Y') : '-' }}
            @elseif($column['type'] === 'datetime')
                {{ $item->{$column['field']} ? \Carbon\Carbon::parse($item->{$column['field']})->format('d.m.Y H:i') : '-' }}
            @elseif($column['type'] === 'relation')
                {{ $item->{$column['relation']}->{$column['field']} ?? '-' }}
            @elseif($column['type'] === 'files')
                @if($item->files && $item->files->count() > 0)
                    <i class="fas fa-paperclip text-muted" 
                       data-bs-toggle="tooltip" 
                       title="{{ $item->files->count() }} dosya">
                    </i>
                @else
                    -
                @endif
            @else
                {{ $item->{$column['field']} ?? '-' }}
            @endif
        </td>
    @endforeach

    <td class="text-end">
        <div class="btn-group btn-group-sm">
            <button type="button" 
                    class="btn btn-info view-item" 
                    data-id="{{ $item->id }}"
                    data-bs-toggle="modal" 
                    data-bs-target="#viewModal"
                    title="Görüntüle">
                <i class="fas fa-eye"></i>
            </button>
            <button type="button" 
                    class="btn btn-primary edit-item" 
                    data-id="{{ $item->id }}"
                    data-bs-toggle="modal" 
                    data-bs-target="#editModal_{{ $item->id }}"
                    title="Düzenle">
                <i class="fas fa-edit"></i>
            </button>
            <button type="button" 
                    class="btn btn-danger delete-item"
                    data-id="{{ $item->id }}"
                    data-name="{{ $item->{$columns[0]['field']} }}"
                    title="Sil">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="{{ count($columns) + 2 }}" class="text-center py-4">
        <div class="text-muted">
            <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
            Kayıt bulunamadı
        </div>
    </td>
</tr>
@endforelse

@if($items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages())
<tr>
    <td colspan="{{ count($columns) + 2 }}">
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Toplam {{ $items->total() }} kayıt
            </div>
            <div class="pagination pagination-sm mb-0">
                {{ $items->links() }}
            </div>
        </div>
    </td>
</tr>
@endif

<style>
.pagination {
    margin: 0;
}

.page-link {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
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