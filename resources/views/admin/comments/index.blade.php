@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Yorumlar</h3>
                        <div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCommentModal">
                                <i class="fas fa-plus"></i> Yeni Ekle
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" placeholder="Ara...">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="sourceFilter">
                                <option value="">Tüm Kaynaklar</option>
                                <option value="Tripadvisor">Tripadvisor</option>
                                <option value="Booking.com">Booking.com</option>
                                <option value="Google">Google</option>
                                <option value="Hotels.com">Hotels.com</option>
                                <option value="Otelpuan">Otelpuan</option>
                            </select>
                        </div>
                        <div class="col-md-4 text-right">
                            <button id="bulkDeleteBtn" class="btn btn-danger" style="display: none;">
                                <i class="fas fa-trash"></i> Seçilenleri Sil
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="commentsTable">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>ID</th>
                                    <th>İsim</th>
                                    <th>Yorum</th>
                                    <th>Kaynak</th>
                                    <th>Tarih</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comments as $comment)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="comment-checkbox" value="{{ $comment->id }}">
                                        </td>
                                        <td>{{ $comment->id }}</td>
                                        <td>{{ $comment->name }}</td>
                                        <td>{{ Str::limit($comment->comment, 30) }}</td>
                                        <td>{{ $comment->source }}</td>
                                        <td>{{ $comment->comment_date->format('j.n.Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-sm view-comment" data-id="{{ $comment->id }}" title="Görüntüle">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-primary btn-sm edit-comment" data-id="{{ $comment->id }}" title="Düzenle">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm delete-comment" data-id="{{ $comment->id }}" title="Sil">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Henüz yorum bulunmamaktadır.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $comments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Yeni Yorum Ekleme Modal -->
<div class="modal fade" id="createCommentModal" tabindex="-1" aria-labelledby="createCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createCommentModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Yeni Yorum Ekle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Kapat"></button>
            </div>
            <div class="modal-body">
                <form id="createCommentForm" role="form" aria-labelledby="createCommentModalLabel">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-2"></i>Müşteri Adı
                                </label>
                                <input type="text" class="form-control" id="name" name="name" required aria-required="true">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="source" class="form-label">
                                    <i class="fas fa-globe me-2"></i>Kaynak
                                </label>
                                <select class="form-select" id="source" name="source" required aria-required="true">
                                    <option value="">Kaynak Seçiniz...</option>
                                    <option value="Tripadvisor">Tripadvisor</option>
                                    <option value="Booking.com">Booking.com</option>
                                    <option value="Google">Google</option>
                                    <option value="Hotels.com">Hotels.com</option>
                                    <option value="Otelpuan">Otelpuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">
                            <i class="fas fa-comment me-2"></i>Yorum
                        </label>
                        <textarea class="form-control" id="comment" name="comment" rows="5" required aria-required="true"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="comment_date" class="form-label">
                            <i class="fas fa-calendar-alt me-2"></i>Yorum Tarihi
                        </label>
                        <input type="date" class="form-control" id="comment_date" name="comment_date" value="{{ date('Y-m-d') }}" required aria-required="true">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" aria-label="İptal">
                    <i class="fas fa-times me-2"></i>İptal
                </button>
                <button type="button" class="btn btn-primary" id="saveComment" aria-label="Kaydet">
                    <i class="fas fa-save me-2"></i>Kaydet
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Yorum Düzenleme Modal -->
<div class="modal fade" id="editCommentModal" tabindex="-1" aria-labelledby="editCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editCommentModalLabel">
                    <i class="fas fa-edit me-2"></i>Yorum Düzenle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCommentForm">
                    @csrf
                    <input type="hidden" id="edit_comment_id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">
                                    <i class="fas fa-user me-2"></i>Müşteri Adı
                                </label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_source" class="form-label">
                                    <i class="fas fa-globe me-2"></i>Kaynak
                                </label>
                                <select class="form-select" id="edit_source" name="source" required>
                                    <option value="">Kaynak Seçiniz...</option>
                                    <option value="Tripadvisor">Tripadvisor</option>
                                    <option value="Booking.com">Booking.com</option>
                                    <option value="Google">Google</option>
                                    <option value="Hotels.com">Hotels.com</option>
                                    <option value="Otelpuan">Otelpuan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_comment" class="form-label">
                            <i class="fas fa-comment me-2"></i>Yorum
                        </label>
                        <textarea class="form-control" id="edit_comment" name="comment" rows="5" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_comment_date" class="form-label">
                                    <i class="fas fa-calendar-alt me-2"></i>Yorum Tarihi
                                </label>
                                <input type="date" class="form-control" id="edit_comment_date" name="comment_date" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>İptal
                </button>
                <button type="button" class="btn btn-primary" id="updateComment">
                    <i class="fas fa-save me-2"></i>Güncelle
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Yorum Detay Modal -->
<div class="modal fade" id="viewCommentModal" tabindex="-1" aria-labelledby="viewCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="viewCommentModalLabel">
                    <i class="fas fa-eye me-2"></i>Yorum Detayı
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Müşteri Adı</label>
                            <p id="view_name" class="mb-0 fs-5"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Yorum Platformu</label>
                            <p id="view_source" class="mb-0 fs-5"></p>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Yorum</label>
                    <p id="view_comment" class="mb-0 fs-5"></p>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Yorum Tarihi</label>
                            <p id="view_comment_date" class="mb-0 fs-5"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Sıralama</label>
                            <p id="view_position" class="mb-0 fs-5"></p>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <h6>Log Kayıtları</h6>
                    <div id="view_logs"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Kapat
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Silme Onay Modal -->
<div class="modal fade" id="deleteCommentModal" tabindex="-1" aria-labelledby="deleteCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteCommentModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Yorum Sil
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Bu yorumu silmek istediğinizden emin misiniz?</p>
                <p class="text-muted small mt-2">Bu işlem geri alınamaz.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>İptal
                </button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fas fa-trash me-2"></i>Sil
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$(document).ready(function() {
    // CSRF token'ı tüm AJAX isteklerine ekle
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Tümünü seç/kaldır
    $('#selectAll').change(function() {
        $('.comment-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkDeleteButton();
    });

    // Tekil checkbox değişikliği
    $('.comment-checkbox').change(function() {
        updateBulkDeleteButton();
    });

    // Toplu silme butonu görünürlüğü
    function updateBulkDeleteButton() {
        if ($('.comment-checkbox:checked').length > 0) {
            $('#bulkDeleteBtn').show();
        } else {
            $('#bulkDeleteBtn').hide();
        }
    }

    // Tarih alanlarını Türkçe formata çevir
    function formatDate(date) {
        var d = new Date(date);
        var day = d.getDate().toString().padStart(2, '0');
        var month = (d.getMonth() + 1).toString().padStart(2, '0');
        var year = d.getFullYear();
        return day + '.' + month + '.' + year;
    }

    // Flatpickr ayarları
    const flatpickrConfig = {
        locale: 'tr',
        dateFormat: 'd.m.Y',
        allowInput: true,
        altInput: true,
        altFormat: 'd.m.Y',
        theme: 'material_blue'
    };

    // Yeni yorum modalı için Flatpickr
    flatpickr("#comment_date", flatpickrConfig);

    // Düzenleme modalı için Flatpickr
    flatpickr("#edit_comment_date", flatpickrConfig);

    // Yeni yorum ekleme
    $('#saveComment').click(function() {
        var formData = $('#createCommentForm').serialize();
        
        // Form validasyonu
        var source = $('#source').val();
        if (!source) {
            Swal.fire({
                title: 'Hata!',
                text: 'Lütfen bir kaynak seçiniz.',
                icon: 'error',
                confirmButtonText: 'Tamam'
            });
            return;
        }

        var comment = $('#comment').val();
        if (!comment) {
            Swal.fire({
                title: 'Hata!',
                text: 'Lütfen yorum alanını doldurunuz.',
                icon: 'error',
                confirmButtonText: 'Tamam'
            });
            return;
        }

        var name = $('#name').val();
        if (!name) {
            Swal.fire({
                title: 'Hata!',
                text: 'Lütfen müşteri adını doldurunuz.',
                icon: 'error',
                confirmButtonText: 'Tamam'
            });
            return;
        }

        $.ajax({
            url: '{{ route("admin.comments.store") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    var modal = bootstrap.Modal.getInstance(document.getElementById('createCommentModal'));
                    modal.hide();
                    Swal.fire({
                        title: 'Başarılı!',
                        text: 'Yorum başarıyla eklendi.',
                        icon: 'success',
                        confirmButtonText: 'Tamam'
                    }).then((result) => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Hata!',
                        text: response.message || 'Bir hata oluştu!',
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    });
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';
                    for (var key in errors) {
                        errorMessage += errors[key][0] + '\n';
                    }
                    Swal.fire({
                        title: 'Hata!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    });
                } else {
                    Swal.fire({
                        title: 'Hata!',
                        text: 'Bir hata oluştu! Lütfen tekrar deneyin.',
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    });
                }
            }
        });
    });

    // Yorum düzenleme modalını aç
    $('.edit-comment').click(function() {
        var id = $(this).data('id');
        $.ajax({
            url: '{{ url("admin/comments") }}/' + id + '/edit',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var comment = response.data;
                    $('#edit_comment_id').val(comment.id);
                    $('#edit_name').val(comment.name);
                    $('#edit_source').val(comment.source);
                    $('#edit_comment').val(comment.comment);
                    
                    // Tarih formatını düzelt
                    var commentDate = new Date(comment.comment_date);
                    var formattedDate = commentDate.toISOString().split('T')[0];
                    $('#edit_comment_date').val(formattedDate);
                    
                    var modal = new bootstrap.Modal(document.getElementById('editCommentModal'));
                    modal.show();
                } else {
                    Swal.fire({
                        title: 'Hata!',
                        text: 'Yorum bilgileri alınamadı!',
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    });
                }
            },
            error: function(xhr) {
                console.error('Hata:', xhr);
                Swal.fire({
                    title: 'Hata!',
                    text: 'Yorum bilgileri alınamadı! Lütfen tekrar deneyin.',
                    icon: 'error',
                    confirmButtonText: 'Tamam'
                });
            }
        });
    });

    // Yorum güncelleme
    $('#updateComment').click(function() {
        var id = $('#edit_comment_id').val();
        var formData = new FormData($('#editCommentForm')[0]);
        
        // Yorum kaynağı kontrolü
        if (!$('#edit_source').val()) {
            Swal.fire({
                title: 'Hata!',
                text: 'Lütfen yorum kaynağını seçin.',
                icon: 'error',
                confirmButtonText: 'Tamam'
            });
            return;
        }
        
        $.ajax({
            url: '{{ url("admin/comments") }}/' + id,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var modal = bootstrap.Modal.getInstance(document.getElementById('editCommentModal'));
                modal.hide();
                Swal.fire({
                    title: 'Başarılı!',
                    text: 'Yorum başarıyla güncellendi.',
                    icon: 'success',
                    confirmButtonText: 'Tamam'
                }).then((result) => {
                    location.reload();
                });
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';
                    for (var key in errors) {
                        errorMessage += errors[key][0] + '\n';
                    }
                    Swal.fire({
                        title: 'Hata!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    });
                } else {
                    Swal.fire({
                        title: 'Hata!',
                        text: 'Bir hata oluştu!',
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    });
                }
            }
        });
    });

    // Yorum detay modalını aç
    $('.view-comment').click(function() {
        var id = $(this).data('id');
        $.ajax({
            url: '{{ url("admin/comments") }}/' + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var comment = response.data;
                    $('#view_name').text(comment.name);
                    $('#view_source').text(comment.source);
                    $('#view_comment').text(comment.comment);
                    $('#view_comment_date').text(new Date(comment.comment_date).toLocaleDateString('tr-TR', {
                        day: 'numeric',
                        month: 'numeric',
                        year: 'numeric'
                    }));
                    $('#view_position').text(comment.position);

                    // Log kayıtlarını yükle
                    $.ajax({
                        url: '{{ url("admin/comments") }}/' + id + '/logs',
                        type: 'GET',
                        success: function(logResponse) {
                            if (logResponse.success) {
                                var logs = logResponse.data;
                                var logHtml = '';
                                logs.forEach(function(log) {
                                    logHtml += `
                                        <div class="log-item mb-2">
                                            <small class="text-muted">${new Date(log.created_at).toLocaleString('tr-TR')}</small>
                                            <p class="mb-0">${log.message}</p>
                                        </div>
                                    `;
                                });
                                $('#view_logs').html(logHtml || '<p class="text-muted">Henüz log kaydı bulunmamaktadır.</p>');
                            }
                        },
                        error: function(xhr) {
                            console.error('Log yükleme hatası:', xhr);
                            $('#view_logs').html('<p class="text-danger">Log kayıtları yüklenirken bir hata oluştu.</p>');
                        }
                    });

                    var modal = new bootstrap.Modal(document.getElementById('viewCommentModal'));
                    modal.show();
                } else {
                    Swal.fire({
                        title: 'Hata!',
                        text: 'Yorum bilgileri alınamadı!',
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    });
                }
            },
            error: function(xhr) {
                console.error('Hata:', xhr);
                Swal.fire({
                    title: 'Hata!',
                    text: 'Yorum bilgileri alınamadı! Lütfen tekrar deneyin.',
                    icon: 'error',
                    confirmButtonText: 'Tamam'
                });
            }
        });
    });

    // Silme butonuna tıklandığında
    $('.delete-comment').click(function() {
        var id = $(this).data('id');
        $('#confirmDelete').data('id', id);
        var modal = new bootstrap.Modal(document.getElementById('deleteCommentModal'));
        modal.show();
    });

    // Silme işlemini onayla
    $('#confirmDelete').click(function() {
        var id = $(this).data('id');
        $.ajax({
            url: '{{ url("admin/comments") }}/' + id,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                var modal = bootstrap.Modal.getInstance(document.getElementById('deleteCommentModal'));
                modal.hide();
                Swal.fire({
                    title: 'Başarılı!',
                    text: 'Yorum başarıyla silindi.',
                    icon: 'success',
                    confirmButtonText: 'Tamam'
                }).then((result) => {
                    window.location.href = '{{ route("admin.comments.index") }}';
                });
            },
            error: function(xhr) {
                console.error('Hata:', xhr);
                Swal.fire({
                    title: 'Hata!',
                    text: 'Yorum silinirken bir hata oluştu. Lütfen tekrar deneyin.',
                    icon: 'error',
                    confirmButtonText: 'Tamam'
                });
            }
        });
    });

    // Toplu silme işlemi
    $('#bulkDeleteBtn').click(function() {
        var ids = [];
        $('.comment-checkbox:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length > 0) {
            Swal.fire({
                title: 'Emin misiniz?',
                text: 'Seçili yorumları silmek istediğinizden emin misiniz?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.comments.bulk-delete") }}',
                        type: 'POST',
                        data: { ids: ids },
                        success: function(response) {
                            Swal.fire({
                                title: 'Başarılı!',
                                text: 'Seçili yorumlar başarıyla silindi.',
                                icon: 'success',
                                confirmButtonText: 'Tamam'
                            }).then((result) => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Hata!',
                                text: 'Bir hata oluştu!',
                                icon: 'error',
                                confirmButtonText: 'Tamam'
                            });
                        }
                    });
                }
            });
        }
    });

    // Arama işlemi
    $('#searchInput, #sourceFilter').on('keyup change', function() {
        var searchValue = $('#searchInput').val().toLowerCase();
        var sourceValue = $('#sourceFilter').val().toLowerCase();
        
        $('table tbody tr').each(function() {
            var row = $(this);
            var name = row.find('td:nth-child(3)').text().toLowerCase();
            var comment = row.find('td:nth-child(4)').text().toLowerCase();
            var source = row.find('td:nth-child(5)').text().toLowerCase();
            
            var matchesSearch = name.indexOf(searchValue) > -1 || 
                              comment.indexOf(searchValue) > -1;
            var matchesSource = !sourceValue || source === sourceValue;
            
            row.toggle(matchesSearch && matchesSource);
        });
    });

    // Modal kapatma butonları
    $('.modal .close, .modal .btn-secondary').click(function() {
        var modal = bootstrap.Modal.getInstance($(this).closest('.modal')[0]);
        modal.hide();
    });

    // Modal dışına tıklandığında kapatma
    $('.modal').click(function(event) {
        if ($(event.target).is('.modal')) {
            var modal = bootstrap.Modal.getInstance(this);
            modal.hide();
        }
    });

    // ESC tuşu ile modal kapatma
    $(document).keyup(function(event) {
        if (event.key === "Escape") {
            $('.modal').each(function() {
                var modal = bootstrap.Modal.getInstance(this);
                if (modal) {
                    modal.hide();
                }
            });
        }
    });

    // Sürükle-bırak sıralama
    $("#commentsTable tbody").sortable({
        handle: "td:not(:first-child):not(:last-child)",
        update: function(event, ui) {
            var order = [];
            $("#commentsTable tbody tr").each(function(index) {
                order.push({
                    id: $(this).find('input[type="checkbox"]').val(),
                    position: index + 1
                });
            });

            $.ajax({
                url: '{{ route("admin.comments.reorder") }}',
                type: 'POST',
                data: { order: order },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Başarılı!',
                            text: 'Sıralama güncellendi.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Hata!',
                        text: 'Sıralama güncellenirken bir hata oluştu.',
                        icon: 'error',
                        confirmButtonText: 'Tamam'
                    });
                }
            });
        }
    }).disableSelection();
});
</script>
@endpush 