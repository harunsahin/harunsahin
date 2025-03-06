@props([
    'id',
    'title',
    'statuses',
    'model' => null,
    'action',
    'method' => 'POST'
])

<div class="modal fade" id="{{ $id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header {{ $method === 'POST' ? 'bg-primary' : 'bg-info' }} text-white">
                <h5 class="modal-title">
                    <i class="fas {{ $method === 'POST' ? 'fa-plus-circle' : 'fa-edit' }} me-2"></i>{{ $title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($method === 'PUT')
                    @method('PUT')
                @endif

                <div class="modal-body">
                    <div class="row g-3">
                        {{ $slot }}

                        <!-- Durum Seçimi -->
                        <div class="col-md-12">
                            <div class="form-floating">
                                <select class="form-select" id="status" name="status_id" required>
                                    <option value="">Seçiniz</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" 
                                                @if($model && $model->status_id == $status->id) selected @endif
                                                style="color: {{ $status->color }}">
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="status">Durum</label>
                            </div>
                        </div>

                        <!-- Dosya Yükleme -->
                        @if($model && $model->files)
                            <div class="col-12">
                                <label class="form-label">Mevcut Dosyalar</label>
                                <div class="list-group mb-3">
                                    @foreach($model->files as $file)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas {{ getFileIcon($file->mime_type) }} me-2"></i>
                                                {{ $file->original_name }}
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ $file->url }}" 
                                                   class="btn btn-info" 
                                                   target="_blank" 
                                                   title="İndir">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-danger delete-file" 
                                                        data-file-id="{{ $file->id }}"
                                                        title="Sil">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="col-12">
                            <label class="form-label">
                                {{ $model ? 'Yeni Dosya Ekle' : 'Dosya Yükle' }}
                            </label>
                            <input type="file" 
                                   class="form-control" 
                                   name="files[]" 
                                   multiple 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx">
                            <div class="form-text">
                                PDF, Word ve Excel dosyaları yükleyebilirsiniz. (Maks. 5MB)
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn {{ $method === 'POST' ? 'btn-primary' : 'btn-info' }}">
                        <i class="fas {{ $method === 'POST' ? 'fa-plus' : 'fa-save' }} me-1"></i>
                        {{ $method === 'POST' ? 'Ekle' : 'Güncelle' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Dosya silme
    $('.delete-file').click(function() {
        const fileId = $(this).data('file-id');
        const fileElement = $(this).closest('.list-group-item');
        
        Swal.fire({
            title: 'Emin misiniz?',
            text: 'Bu dosyayı silmek istediğinize emin misiniz?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("admin.files.destroy", ":id") }}'.replace(':id', fileId),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            fileElement.fadeOut(300, function() {
                                $(this).remove();
                                toastr.success('Dosya başarıyla silindi');
                            });
                        } else {
                            toastr.error(response.message || 'Dosya silinirken bir hata oluştu');
                        }
                    },
                    error: function() {
                        toastr.error('Dosya silinirken bir hata oluştu');
                    }
                });
            }
        });
    });
});
</script>
@endpush 