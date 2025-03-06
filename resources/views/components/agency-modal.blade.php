@props([
    'id',
    'title',
    'action',
    'method' => 'POST',
    'agency' => null
])

<div class="modal fade" id="{{ $id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-{{ $method === 'POST' ? 'plus' : 'edit' }}-circle me-2"></i>{{ $title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="{{ $id }}Form" action="{{ $action }}" method="POST">
                @csrf
                @if($method === 'PUT')
                    @method('PUT')
                @endif
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Acente Adı -->
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" required
                                       value="{{ $agency ? $agency['name'] : '' }}">
                                <label for="name">Acente Adı</label>
                            </div>
                        </div>

                        <!-- Telefon -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control" id="phone" name="phone" required
                                       value="{{ $agency ? $agency['phone'] : '' }}">
                                <label for="phone">Telefon</label>
                            </div>
                        </div>

                        <!-- E-posta -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" required
                                       value="{{ $agency ? $agency['email'] : '' }}">
                                <label for="email">E-posta</label>
                            </div>
                        </div>

                        <!-- Adres -->
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" id="address" name="address" style="height: 100px">{{ $agency ? $agency['address'] : '' }}</textarea>
                                <label for="address">Adres</label>
                            </div>
                        </div>

                        <!-- Durum -->
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                       {{ $agency && $agency['is_active'] ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>İptal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#{{ $id }}Form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ $action }}',
            type: '{{ $method }}',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    $('#{{ $id }}').modal('hide');
                    $('#{{ $id }}Form')[0].reset();
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
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                if (errors) {
                    Object.keys(errors).forEach(function(key) {
                        toastr.error(errors[key][0]);
                    });
                } else {
                    toastr.error('Bir hata oluştu. Lütfen tekrar deneyin.');
                }
            }
        });
    });
});
</script>
@endpush 