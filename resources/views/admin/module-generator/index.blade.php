@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Modül Oluşturucu</h1>
            <p class="text-muted">Yeni bir modül oluşturmak için aşağıdaki formu doldurun.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form id="moduleForm" class="needs-validation" novalidate>
                        @csrf
                        <div class="row">
                            <!-- Modül Bilgileri -->
                            <div class="col-md-4">
                                <div class="border rounded-3 p-4 h-100 bg-light">
                                    <h5 class="mb-4 text-primary">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Modül Bilgileri
                                    </h5>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Modül Adı <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg" name="name" required 
                                               placeholder="Örn: Products, Categories, Users">
                                        <div class="form-text">
                                            <i class="fas fa-lightbulb text-warning me-1"></i>
                                            İngilizce ve çoğul formda yazın
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Açıklama</label>
                                        <textarea class="form-control" name="description" rows="3" 
                                                  placeholder="Modül hakkında kısa bir açıklama"></textarea>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" name="is_modal" id="isModal">
                                        <label class="form-check-label fw-bold" for="isModal">
                                            Modal Form Kullan
                                        </label>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle text-info me-1"></i>
                                            Formları modal pencerede gösterir
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Alan Yönetimi -->
                            <div class="col-md-8">
                                <div class="border rounded-3 p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h5 class="mb-0 text-primary">
                                            <i class="fas fa-list me-2"></i>
                                            Alan Yönetimi
                                        </h5>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="addField()">
                                            <i class="fas fa-plus me-1"></i>
                                            Alan Ekle
                                        </button>
                                    </div>
                                    
                                    <div id="fields" class="fields-container">
                                        <!-- Alanlar buraya dinamik olarak eklenecek -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-magic me-2"></i>
                                Modülü Oluştur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alan Template -->
<template id="fieldTemplate">
    <div class="field-item border rounded-3 p-4 mb-3 bg-light position-relative">
        <div class="position-absolute top-0 end-0 p-3">
            <button type="button" class="btn btn-danger btn-sm rounded-circle" onclick="removeField(this)" title="Alanı Sil">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <h6 class="mb-4 text-primary d-flex align-items-center">
            <i class="fas fa-layer-group me-2"></i>
            Alan #<span class="field-number ms-1"></span>
        </h6>
        
        <div class="row g-4">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">Alan Adı <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="fields[][name]" required 
                           placeholder="Örn: title, description, price">
                    <div class="form-text">
                        <i class="fas fa-info-circle text-info me-1"></i>
                        Veritabanı sütun adı
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Etiket <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="fields[][label]" required 
                           placeholder="Örn: Başlık, Açıklama, Fiyat">
                    <div class="form-text">
                        <i class="fas fa-tag text-info me-1"></i>
                        Formda görünecek isim
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">Alan Tipi <span class="text-danger">*</span></label>
                    <select class="form-select" name="fields[][type]" required onchange="toggleOptions(this)">
                        <optgroup label="Temel Tipler">
                            @forelse($fieldTypes ?? [] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @empty
                                <option value="">Alan tipleri yüklenemedi</option>
                            @endforelse
                        </optgroup>
                    </select>
                </div>
                <div class="mb-3 options-container d-none">
                    <label class="form-label fw-bold">Seçenekler</label>
                    <textarea class="form-control" name="fields[][options]" rows="3" 
                             placeholder="Her satıra bir seçenek yazın"></textarea>
                    <div class="form-text">
                        <i class="fas fa-list text-info me-1"></i>
                        Her satıra bir seçenek gelecek şekilde yazın
                    </div>
                </div>
            </div>
            
            <div class="col-12">
                <label class="form-label fw-bold mb-3">Doğrulama Kuralları</label>
                <div class="validation-rules row g-2">
                    @foreach($validationRules as $value => $label)
                        <div class="col-auto">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="fields[][validation][]" value="{{ $value }}">
                                <label class="form-check-label">{{ $label }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</template>

@push('styles')
<style>
.fields-container {
    max-height: 600px;
    overflow-y: auto;
    padding-right: 10px;
}

.fields-container::-webkit-scrollbar {
    width: 8px;
}

.fields-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.fields-container::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.fields-container::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.field-item {
    transition: all 0.3s ease;
}

.field-item:hover {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transform: translateY(-2px);
}

.validation-rules {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    background-color: #fff;
}

.form-control:focus, .form-select:focus {
    border-color: #7367f0;
    box-shadow: 0 0 0 0.2rem rgba(115, 103, 240, 0.25);
}

.btn-primary {
    background: linear-gradient(118deg, #7367f0, #9e95f5);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(118deg, #6254ef, #8d84f4);
}
</style>
@endpush

@push('scripts')
<script>
let fieldCount = 0;

function addField() {
    fieldCount++;
    const template = document.getElementById('fieldTemplate');
    const clone = template.content.cloneNode(true);
    
    // Alan numarasını güncelle
    clone.querySelector('.field-number').textContent = fieldCount;
    
    // name ve id attributelerini güncelle
    const inputs = clone.querySelectorAll('[name^="fields[]"]');
    inputs.forEach(input => {
        const fieldIndex = fieldCount - 1;
        input.name = input.name.replace('fields[]', `fields[${fieldIndex}]`);
        
        // Benzersiz ID'ler oluştur
        if (input.type === 'checkbox') {
            const value = input.value;
            const uniqueId = `rule_${value}_${fieldIndex}`;
            input.id = uniqueId;
            input.closest('.form-check').querySelector('label').setAttribute('for', uniqueId);
        }
    });
    
    // Animasyonlu ekleme
    const container = document.getElementById('fields');
    const fieldItem = document.createElement('div');
    fieldItem.style.opacity = '0';
    fieldItem.appendChild(clone);
    container.appendChild(fieldItem);
    
    requestAnimationFrame(() => {
        fieldItem.style.transition = 'opacity 0.3s ease';
        fieldItem.style.opacity = '1';
    });
}

function removeField(button) {
    const fieldItem = button.closest('.field-item');
    fieldItem.style.opacity = '0';
    setTimeout(() => fieldItem.remove(), 300);
}

function toggleOptions(select) {
    const optionsContainer = select.closest('.col-md-6').querySelector('.options-container');
    optionsContainer.classList.toggle('d-none', !['select', 'radio', 'checkbox'].includes(select.value));
}

document.getElementById('moduleForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>İşleniyor...';
    
    try {
        const formData = new FormData(this);
        const fields = [];
        
        // Alan verilerini topla
        document.querySelectorAll('.field-item').forEach((item, index) => {
            const field = {
                name: item.querySelector('[name$="[name]"]').value,
                label: item.querySelector('[name$="[label]"]').value,
                type: item.querySelector('[name$="[type]"]').value,
                validation: []
            };
            
            // Seçili validasyon kurallarını al
            item.querySelectorAll('[name$="[validation][]"]:checked').forEach(checkbox => {
                field.validation.push(checkbox.value);
            });
            
            fields.push(field);
        });
        
        // Form verilerini hazırla
        const data = {
            name: formData.get('name'),
            description: formData.get('description'),
            is_modal: formData.get('is_modal') === 'on',
            fields: fields
        };
        
        const response = await fetch('{{ route("admin.module-generator.generate") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            toastr.success(result.message);
            if (result.redirect) {
                window.location.href = result.redirect;
            }
        } else {
            toastr.error(result.message);
        }
    } catch (error) {
        console.error('Hata:', error);
        toastr.error('Bir hata oluştu. Lütfen tekrar deneyin.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
});

// Sayfa yüklendiğinde ilk alanı ekle
document.addEventListener('DOMContentLoaded', addField);
</script>
@endpush

@endsection 