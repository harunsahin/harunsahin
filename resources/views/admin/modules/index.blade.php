@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Modüller</h1>
            <p class="text-muted">Sistem modüllerini yönetin</p>
        </div>
        <div>
            <button class="btn btn-primary" onclick="window.location.href='{{ route('admin.module-generator.index') }}'">
                <i class="fas fa-plus me-2"></i>Yeni Modül
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Modül Adı</th>
                            <th class="border-0">Alanlar</th>
                            <th class="border-0">Oluşturulma Tarihi</th>
                            <th class="border-0 text-end">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($modules as $module)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle me-3">
                                            <span class="avatar-text text-primary">{{ strtoupper(substr($module['name'], 0, 2)) }}</span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $module['name'] }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($module['fields'] as $field)
                                            <span class="badge bg-info bg-opacity-10 text-info">{{ $field }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>{{ date('d.m.Y H:i', $module['created_at']) }}</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-sm btn-primary edit-module" 
                                                data-module="{{ json_encode($module) }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModuleModal"
                                                title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-module"
                                                data-module-name="{{ $module['name'] }}"
                                                title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-cubes fa-2x mb-3 opacity-50"></i>
                                        <p class="mb-0">Henüz modül bulunmuyor</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modül Düzenleme Modal -->
@include('admin.modules.partials.edit-modal')

@push('styles')
<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-text {
    font-size: 14px;
    font-weight: 600;
}

.badge {
    font-weight: 500;
    padding: 6px 10px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Modül düzenleme modalını aç
    $('.edit-module').click(function() {
        const moduleData = $(this).data('module');
        $('#moduleId').val(moduleData.name);
        $('#editModuleName').val(moduleData.name);
        
        // Mevcut alanları temizle
        $('#fieldsContainer').empty();
        
        // Mevcut alanları ekle
        if (moduleData.fields && moduleData.fields.length > 0) {
            moduleData.fields.forEach((field) => {
                addField(field);
            });
        }
    });

    // Yeni alan ekleme
    $('#addField').click(function() {
        addField();
    });

    // Alan ekleme fonksiyonu
    function addField(existingField = '') {
        const fieldHtml = `
            <div class="card mb-3 field-row">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Alan Adı (DB)</label>
                            <input type="text" class="form-control" name="fields[][name]" value="${existingField}" placeholder="ornek_alan_adi">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Form Etiketi</label>
                            <input type="text" class="form-control" name="fields[][label]" value="${existingField ? toTitleCase(existingField.replace(/_/g, ' ')) : ''}" placeholder="Örnek Alan Adı">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Alan Türü</label>
                            <select class="form-select" name="fields[][type]">
                                <option value="string" ${existingField ? 'selected' : ''}>Metin (string)</option>
                                <option value="text">Uzun Metin (text)</option>
                                <option value="integer">Tam Sayı (integer)</option>
                                <option value="decimal">Ondalıklı Sayı (decimal)</option>
                                <option value="boolean">Evet/Hayır (boolean)</option>
                                <option value="date">Tarih (date)</option>
                                <option value="datetime">Tarih/Saat (datetime)</option>
                                <option value="time">Saat (time)</option>
                                <option value="email">E-posta (email)</option>
                                <option value="password">Şifre (password)</option>
                                <option value="file">Dosya (file)</option>
                                <option value="image">Resim (image)</option>
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-danger remove-field w-100">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#fieldsContainer').append(fieldHtml);
    }

    // Metin düzenleme yardımcı fonksiyonu
    function toTitleCase(str) {
        return str.replace(/\w\S*/g, function(txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    }

    // Alan silme
    $(document).on('click', '.remove-field', function() {
        $(this).closest('.field-row').remove();
    });

    // Modül silme
    $('.delete-module').click(function() {
        const moduleName = $(this).data('module-name');
        
        Swal.fire({
            title: 'Emin misiniz?',
            text: "Bu modülü silmek istediğinizden emin misiniz?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/modules/${moduleName}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Silindi!',
                                'Modül başarıyla silindi.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Hata!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Hata!',
                            xhr.responseJSON ? xhr.responseJSON.message : 'Bir hata oluştu',
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Form gönderimi
    $('#editModuleForm').submit(function(e) {
        e.preventDefault();
        
        const moduleId = $('#moduleId').val();
        console.log('Modül ID:', moduleId);
        
        const fields = [];
        
        // Tüm alanları topla
        const fieldRows = $('#fieldsContainer .field-row');
        fieldRows.each(function() {
            const name = $(this).find('input[name="fields[][name]"]').val().trim();
            const label = $(this).find('input[name="fields[][label]"]').val().trim();
            const type = $(this).find('select[name="fields[][type]"]').val();
            
            if (name) {
                fields.push({
                    name: name,
                    label: label || toTitleCase(name.replace(/_/g, ' ')),
                    type: type
                });
            }
        });

        console.log('Gönderilen alanlar:', fields);

        // Loading göster
        Swal.fire({
            title: 'İşleniyor...',
            text: 'Lütfen bekleyin',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // AJAX isteği
        $.ajax({
            url: `{{ route('admin.modules.update.structure', ['module' => ':module']) }}`.replace(':module', moduleId),
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { 
                module_id: moduleId,
                fields: fields 
            },
            success: function(response) {
                Swal.fire({
                    title: response.title || 'Başarılı!',
                    text: response.message,
                    icon: response.icon || 'success',
                    confirmButtonText: 'Tamam'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                console.error('AJAX Hatası:', xhr);
                Swal.fire({
                    title: xhr.responseJSON?.title || 'Hata!',
                    text: xhr.responseJSON?.message || 'Bir hata oluştu',
                    icon: 'error',
                    confirmButtonText: 'Tamam'
                });
            }
        });
    });
});
</script>
@endpush
@endsection 