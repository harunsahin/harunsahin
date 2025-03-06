@forelse($comments as $comment)
    <tr data-id="{{ $comment->id }}" class="sortable-row">
        <td>
            <input type="checkbox" class="item-checkbox" value="{{ $comment->id }}">
        </td>
        <td>
            <i class="fas fa-grip-vertical handle"></i>
        </td>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $comment->adisoyadi }}</td>
        <td>
            <div class="comment-text">
                <div class="comment-content">
                    <div class="comment-preview">{{ Str::limit($comment->yorum, 250) }}</div>
                    @if(strlen($comment->yorum) > 250)
                        <div class="comment-full">{{ $comment->yorum }}</div>
                        <button class="btn btn-link btn-sm read-more-btn p-0" style="text-decoration: none;">
                            Devamını Oku
                        </button>
                    @endif
                </div>
            </div>
        </td>
        <td>{{ \Carbon\Carbon::parse($comment->yorumtarihi)->format('d.m.Y') }}</td>
        <td>{{ $comment->kaynak }}</td>
        <td>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-info view-btn" data-id="{{ $comment->id }}" title="Görüntüle">
                    <i class="fas fa-eye"></i>
                </button>
                <button type="button" class="btn btn-sm btn-primary edit-btn" data-id="{{ $comment->id }}" title="Düzenle">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $comment->id }}" title="Sil">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center">Henüz yorum bulunmuyor.</td>
    </tr>
@endforelse

@if($comments->hasPages())
<tr>
    <td colspan="8">
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Toplam {{ $comments->total() }} kayıttan {{ $comments->firstItem() }}-{{ $comments->lastItem() }} arası gösteriliyor
            </div>
            <div>
                {{ $comments->links('vendor.pagination.custom') }}
            </div>
        </div>
    </td>
</tr>
@endif 