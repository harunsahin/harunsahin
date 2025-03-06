@forelse($yorums as $yorum)
    <tr data-id="{{ $yorum->id }}">
        <td>
            <input type="checkbox" class="item-checkbox" value="{{ $yorum->id }}">
        </td>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $yorum->adisoyadi }}</td>
        <td><div class="yorum-text">{{ $yorum->yorum }}</div></td>
        <td>{{ \Carbon\Carbon::parse($yorum->yorumtarihi)->format('d.m.Y') }}</td>
        <td>{{ $yorum->kaynak }}</td>
        <td>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-info view-btn" data-id="{{ $yorum->id }}">
                    <i class="fas fa-eye"></i>
                </button>
                <button type="button" class="btn btn-sm btn-primary edit-btn" data-id="{{ $yorum->id }}">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $yorum->id }}">
                    <i class="fas fa-trash"></i>
                </button>
                <i class="fas fa-grip-vertical sortable-handle" style="cursor: move;"></i>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center">Henüz yorum bulunmuyor.</td>
    </tr>
@endforelse

@if($yorums->hasPages())
<tr>
    <td colspan="7">
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Toplam {{ $yorums->total() }} kayıttan {{ $yorums->firstItem() }}-{{ $yorums->lastItem() }} arası gösteriliyor
            </div>
            <div>
                {{ $yorums->links('vendor.pagination.custom') }}
            </div>
        </div>
    </td>
</tr>
@endif 