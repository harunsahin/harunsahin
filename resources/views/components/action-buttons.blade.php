@props([
    'id',
    'editModalId',
    'deleteUrl',
    'showEditButton' => true,
    'showDeleteButton' => true
])

<div class="btn-group" role="group">
    @if($showEditButton)
        <button type="button" 
                class="btn btn-sm btn-primary" 
                data-bs-toggle="modal" 
                data-bs-target="#{{ $editModalId }}"
                title="Düzenle">
            <i class="fas fa-edit"></i>
        </button>
    @endif

    @if($showDeleteButton)
        <button type="button" 
                class="btn btn-sm btn-danger" 
                onclick="deleteConfirm('{{ $deleteUrl }}')"
                title="Sil">
            <i class="fas fa-trash"></i>
        </button>
    @endif
</div>

@once
    @push('scripts')
    <script>
    function deleteConfirm(url) {
        Swal.fire({
            title: 'Emin misiniz?',
            text: "Bu işlem geri alınamaz!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Başarılı!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Hata!',
                            text: 'Bir hata oluştu.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        });
    }
    </script>
    @endpush
@endonce 