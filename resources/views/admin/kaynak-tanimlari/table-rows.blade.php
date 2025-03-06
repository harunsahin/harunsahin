@foreach($kaynaklar as $kaynak)
<tr>
    <td>
        <input type="checkbox" class="item-checkbox" value="{{ $kaynak->id }}">
    </td>
    <td>{{ $kaynak->position }}</td>
    <td>{{ $kaynak->kaynak }}</td>
    <td>
        <span class="badge bg-{{ $kaynak->is_active ? 'success' : 'danger' }}">
            {{ $kaynak->is_active ? 'Aktif' : 'Pasif' }}
        </span>
    </td>
    <td>
        <button type="button" class="btn btn-sm btn-primary edit-button" data-id="{{ $kaynak->id }}">
            <i class="fas fa-edit"></i>
        </button>
        <button type="button" class="btn btn-sm btn-danger delete-button" data-id="{{ $kaynak->id }}">
            <i class="fas fa-trash"></i>
        </button>
    </td>
</tr>
@endforeach

@if($kaynaklar->isEmpty())
<tr>
    <td colspan="5" class="text-center">Kayıt bulunamadı.</td>
</tr>
@endif

<tr>
    <td colspan="5">
        {{ $kaynaklar->links() }}
    </td>
</tr> 