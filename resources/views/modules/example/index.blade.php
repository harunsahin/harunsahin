@extends('components.module-base')

@section('content')
@php
$columns = [
    ['field' => 'name', 'label' => 'Ad', 'type' => 'text'],
    ['field' => 'description', 'label' => 'Açıklama', 'type' => 'text'],
    ['field' => 'created_at', 'label' => 'Oluşturulma', 'type' => 'datetime'],
    ['field' => 'status', 'label' => 'Durum', 'type' => 'status'],
    ['field' => 'files', 'label' => 'Dosyalar', 'type' => 'files']
];
@endphp

<x-module-base 
    :title="'Örnekler'"
    :buttonText="'Örnek'"
    :columns="$columns"
    :items="$items"
    :statuses="$statuses"
    routePrefix="admin.examples"
    :partialView="'modules.example.partials.table-rows'"
    :viewModal="'modules.example.modals.view'"
/>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Görüntüleme modalı
    $(document).on('click', '.view-item', function() {
        const id = $(this).data('id');
        
        // Modal içeriğini temizle
        $('#viewName, #viewDescription, #viewStatus').text('-');
        $('#viewFiles').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Yükleniyor...</div>');
        
        // AJAX isteği
        $.ajax({
            url: `/admin/examples/${id}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const item = response.data;
                    
                    // Form alanlarını doldur
                    $('#viewName').text(item.name || '-');
                    $('#viewDescription').text(item.description || '-');
                    
                    // Durumu badge olarak göster
                    if (item.status) {
                        $('#viewStatus').html(`
                            <span class="badge" style="background-color: ${item.status.color}">
                                ${item.status.name}
                            </span>
                        `);
                    }
                    
                    // Dosyaları listele
                    const filesDiv = $('#viewFiles');
                    filesDiv.empty();
                    
                    if (item.files && item.files.length > 0) {
                        const fileList = $('<div class="list-group list-group-flush"></div>');
                        
                        item.files.forEach(file => {
                            fileList.append(`
                                <a href="${file.url}" 
                                   class="list-group-item list-group-item-action d-flex align-items-center py-2" 
                                   target="_blank"
                                   title="İndirmek için tıklayın">
                                    <i class="fas ${getFileIcon(file.mime_type)} me-2 ${getFileColor(file.mime_type)}"></i>
                                    <div class="flex-grow-1">
                                        <div class="text-truncate" style="max-width: 200px;">${file.original_name}</div>
                                        <small class="text-muted">${formatFileSize(file.file_size)}</small>
                                    </div>
                                    <i class="fas fa-download ms-2 text-primary"></i>
                                </a>
                            `);
                        });
                        
                        filesDiv.append(fileList);
                    } else {
                        filesDiv.html(`
                            <div class="text-muted text-center py-3">
                                <i class="fas fa-folder-open mb-2 d-block" style="font-size: 1.5rem;"></i>
                                <small>Dosya bulunmamaktadır</small>
                            </div>
                        `);
                    }

                    // Oluşturan ve güncelleyen bilgilerini güncelle
                    let creatorInfo = '';
                    if (item.creator) {
                        creatorInfo = `<i class="fas fa-user-edit me-1"></i>${item.creator.name} tarafından ${moment(item.created_at).format('DD.MM.YYYY HH:mm')} tarihinde oluşturuldu`;
                        
                        if (item.updater && item.updater.id !== item.creator.id) {
                            creatorInfo += `<br><i class="fas fa-user-edit me-1"></i>${item.updater.name} tarafından ${moment(item.updated_at).format('DD.MM.YYYY HH:mm')} tarihinde güncellendi`;
                        }
                    }
                    $('#viewCreatorInfo').html(creatorInfo || '-');
                }
            }
        });
    });

    // Dosya tipi ikonları
    function getFileIcon(mimeType) {
        if (mimeType.includes('pdf')) {
            return 'fa-file-pdf';
        } else if (mimeType.includes('word')) {
            return 'fa-file-word';
        } else if (mimeType.includes('spreadsheet') || mimeType.includes('excel')) {
            return 'fa-file-excel';
        }
        return 'fa-file';
    }

    // Dosya tipi renkleri
    function getFileColor(mimeType) {
        if (mimeType.includes('pdf')) {
            return 'text-danger';
        } else if (mimeType.includes('word')) {
            return 'text-primary';
        } else if (mimeType.includes('spreadsheet') || mimeType.includes('excel')) {
            return 'text-success';
        }
        return 'text-secondary';
    }

    // Dosya boyutu formatı
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
});
</script>
@endpush 