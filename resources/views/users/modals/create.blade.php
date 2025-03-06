@push('styles')
<style>
    .modal .form-group {
        margin-bottom: 1.5rem;
    }
    .modal .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #344767;
    }
    .modal .form-control, .modal .form-select {
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid #d2d6da;
        transition: all 0.2s ease;
    }
    .modal .form-control:focus, .modal .form-select:focus {
        border-color: #35D6ED;
        box-shadow: 0 0 0 2px rgba(53, 214, 237, 0.25);
    }
    .modal .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .modal .btn-primary {
        background-color: #35D6ED;
        border-color: #35D6ED;
    }
    .modal .btn-primary:hover {
        background-color: #28c8df;
        border-color: #28c8df;
    }
    .modal .btn-secondary {
        background-color: #f8f9fa;
        border-color: #f8f9fa;
        color: #344767;
    }
    .modal .btn-secondary:hover {
        background-color: #e9ecef;
        border-color: #e9ecef;
    }
    .modal-content {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }
    .modal-body {
        padding: 1.5rem;
    }
    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }
    .form-check-input:checked {
        background-color: #35D6ED;
        border-color: #35D6ED;
    }
    .form-check-input:focus {
        border-color: #35D6ED;
        box-shadow: 0 0 0 2px rgba(53, 214, 237, 0.25);
    }
</style>
@endpush

<!-- Kullanıcı Ekleme Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Yeni Kullanıcı Ekle
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <form id="createUserForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user me-2"></i>Ad Soyad
                        </label>
                        <input type="text" class="form-control" id="name" name="name" required 
                               placeholder="Kullanıcının adını ve soyadını girin">
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>E-posta
                        </label>
                        <input type="email" class="form-control" id="email" name="email" required
                               placeholder="ornek@sirket.com">
                        <div class="invalid-feedback">Lütfen geçerli bir e-posta adresi giriniz.</div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Şifre
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required
                                   placeholder="Güçlü bir şifre girin">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback">Şifre en az 6 karakter olmalıdır.</div>
                    </div>
                    <div class="form-group">
                        <label for="role_id" class="form-label">
                            <i class="fas fa-user-tag me-2"></i>Rol
                        </label>
                        <select class="form-select" id="role_id" name="role_id" required>
                            <option value="">Rol Seçin</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Lütfen bir rol seçiniz.</div>
                    </div>
                    <div class="form-group mb-0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                <i class="fas fa-toggle-on me-2"></i>Aktif
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>İptal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Şifre göster/gizle
    $('#togglePassword').click(function() {
        const passwordInput = $('#password');
        const icon = $(this).find('i');
        
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Form submit işlemi
    $('#createUserForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        
        // Form validasyonu
        if (!form[0].checkValidity()) {
            e.stopPropagation();
            form.addClass('was-validated');
            return;
        }
        
        // Submit butonunu devre dışı bırak
        const submitBtn = form.find('button[type="submit"]');
        submitBtn.prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin me-2"></i>Kaydediliyor...');
        
        // Form verilerini hazırla
        const formData = new FormData(form[0]);
        formData.append('is_active', $('#is_active').is(':checked') ? '1' : '0');
        
        $.ajax({
            url: '{{ route("admin.users.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createUserModal'));
                    modal.hide();
                    form[0].reset();
                    form.removeClass('was-validated');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Başarılı!',
                        text: response.message,
                        confirmButtonText: 'Tamam',
                        confirmButtonColor: '#0d6efd',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'Bir hata oluştu';
                if (xhr.responseJSON?.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                } else if (xhr.responseJSON?.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: errorMessage,
                    confirmButtonText: 'Tamam',
                    confirmButtonColor: '#dc3545',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            },
            complete: function() {
                // Submit butonunu tekrar aktif et
                submitBtn.prop('disabled', false)
                    .html('<i class="fas fa-save me-2"></i>Kaydet');
            }
        });
    });
});
</script>
@endpush 