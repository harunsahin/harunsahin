@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Yeni Yedekleme Oluştur
                    </h5>
                </div>
                <div class="card-body">
                    <form id="backupForm">
                        @csrf
                        
                        <!-- Yedekleme Konumu -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-folder-open me-2"></i>Yedekleme Konumu
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-select" name="backup_location" id="backupLocation">
                                        @foreach($backupLocations as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6" id="customPathContainer" style="display: none;">
                                    <input type="text" class="form-control" name="custom_path" 
                                           placeholder="Örn: C:\Yedekler\CrudApp">
                                </div>
                            </div>
                        </div>

                        <!-- Veritabanı Tabloları -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-database me-2"></i>Veritabanı Tabloları
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 30px;">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="selectAllTables">
                                                </div>
                                            </th>
                                            <th>Tablo Adı</th>
                                            <th class="text-end">Satır Sayısı</th>
                                            <th class="text-end">Boyut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tableData as $table)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input table-checkbox" 
                                                           name="tables[]" value="{{ $table['name'] }}">
                                                </div>
                                            </td>
                                            <td>{{ $table['name'] }}</td>
                                            <td class="text-end">{{ number_format($table['rows']) }}</td>
                                            <td class="text-end">{{ number_format($table['size'], 2) }} MB</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Klasörler -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fas fa-folder me-2"></i>Dosya ve Klasörler
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 30px;">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="selectAllDirs">
                                                </div>
                                            </th>
                                            <th>Klasör</th>
                                            <th>Açıklama</th>
                                            <th class="text-end">Dosya Sayısı</th>
                                            <th class="text-end">Boyut</th>
                                            <th class="text-center">Durum</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($directories as $dir)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input dir-checkbox"
                                                           name="directories[]" value="{{ $dir['path'] }}"
                                                           {{ !$dir['exists'] ? 'disabled' : '' }}>
                                                </div>
                                            </td>
                                            <td>{{ $dir['path'] }}</td>
                                            <td>{{ $dir['description'] }}</td>
                                            <td class="text-end">{{ number_format($dir['files']) }}</td>
                                            <td class="text-end">{{ formatBytes($dir['size']) }}</td>
                                            <td class="text-center">
                                                @if($dir['exists'])
                                                    <span class="badge bg-success">Mevcut</span>
                                                @else
                                                    <span class="badge bg-danger">Bulunamadı</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Yedekleme işlemi seçilen öğelerin boyutuna göre biraz zaman alabilir.
                            Lütfen işlem tamamlanana kadar bekleyin.
                        </div>

                        <div class="text-end">
                            <a href="{{ route('admin.backups.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>İptal
                            </a>
                            <button type="submit" class="btn btn-primary" id="startBackup">
                                <i class="fas fa-save me-1"></i>Yedeklemeyi Başlat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Yedekleme İlerleme Modalı -->
<div class="modal fade" id="backupProgressModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-sync-alt me-2"></i>Yedekleme İşlemi
                </h5>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
                <div class="progress mb-3">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                         role="progressbar" style="width: 0%"></div>
                </div>
                <p class="text-center mb-0" id="backupStatus">Yedekleme başlatılıyor...</p>
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-danger" id="cancelBackup" style="display: none;">
                        <i class="fas fa-times me-1"></i>İptal Et
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Yedekleme konumu değiştiğinde
    $('#backupLocation').change(function() {
        if ($(this).val() === 'custom') {
            $('#customPathContainer').show();
        } else {
            $('#customPathContainer').hide();
        }
    });

    // Tümünü Seç - Tabloları
    $('#selectAllTables').change(function() {
        $('.table-checkbox').prop('checked', $(this).is(':checked'));
    });

    // Tümünü Seç - Klasörleri
    $('#selectAllDirs').change(function() {
        $('.dir-checkbox:not(:disabled)').prop('checked', $(this).is(':checked'));
    });

    // Form gönderimi
    $('#backupForm').on('submit', function(e) {
        e.preventDefault();

        const selectedTables = $('input[name="tables[]"]:checked').length;
        const selectedDirs = $('input[name="directories[]"]:checked').length;

        if (selectedTables === 0 && selectedDirs === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Uyarı!',
                text: 'Lütfen en az bir tablo veya klasör seçin.'
            });
            return;
        }

        // Özel konum kontrolü
        if ($('#backupLocation').val() === 'custom' && !$('input[name="custom_path"]').val().trim()) {
            Swal.fire({
                icon: 'warning',
                title: 'Uyarı!',
                text: 'Lütfen özel yedekleme konumunu belirtin.'
            });
            return;
        }

        // Modal'ı göster
        const modal = $('#backupProgressModal');
        modal.modal('show');

        // Form verilerini gönder
        $.ajax({
            url: '{{ route("admin.backups.store") }}',
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    startProgressCheck();
                } else {
                    modal.modal('hide');
                    Swal.fire({
                        icon: 'error',
                        title: 'Hata!',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                modal.modal('hide');
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: xhr.responseJSON?.message || 'Yedekleme başlatılırken bir hata oluştu.'
                });
            }
        });
    });

    // İlerleme kontrolü
    let progressInterval;
    function startProgressCheck() {
        progressInterval = setInterval(checkProgress, 1000);
    }

    function checkProgress() {
        $.get('{{ route("admin.backups.progress") }}', function(response) {
            $('.progress-bar').css('width', response.progress + '%');
            $('#backupStatus').text(response.message);

            // İptal butonunu göster/gizle
            if (response.can_cancel) {
                $('#cancelBackup').show();
            } else {
                $('#cancelBackup').hide();
            }

            // İşlem tamamlandıysa
            if (response.progress === 100) {
                clearInterval(progressInterval);
                setTimeout(function() {
                    $('#backupProgressModal').modal('hide');
                    window.location.href = '{{ route("admin.backups.index") }}';
                }, 1500);
            }
        });
    }

    // İptal butonu işlemi
    $('#cancelBackup').on('click', function() {
        $.ajax({
            url: '{{ route("admin.backups.cancel") }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    clearInterval(progressInterval);
                    $('#backupProgressModal').modal('hide');
                    Swal.fire({
                        icon: 'info',
                        title: 'Bilgi',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: xhr.responseJSON?.message || 'Yedekleme iptal edilirken bir hata oluştu.'
                });
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.table > :not(caption) > * > * {
    padding: 0.5rem;
}

.progress {
    height: 10px;
}

#backupStatus {
    font-size: 14px;
    color: #666;
}

.badge {
    font-weight: 500;
}

#customPathContainer input {
    height: 38px;
}
</style>
@endpush 