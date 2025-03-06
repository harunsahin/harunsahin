@extends('admin.layouts.app')

@section('title', 'Aktiviteler')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aktiviteler</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Kullanıcı</th>
                                    <th>Modül</th>
                                    <th>Tür</th>
                                    <th>Açıklama</th>
                                    <th>IP Adresi</th>
                                    <th>Tarih</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities as $activity)
                                <tr>
                                    <td>{{ $activity->id }}</td>
                                    <td>{{ $activity->user->name }}</td>
                                    <td>{{ $activity->module }}</td>
                                    <td>{{ $activity->type }}</td>
                                    <td>{{ $activity->description }}</td>
                                    <td>{{ $activity->ip_address }}</td>
                                    <td>{{ $activity->created_at->format('d.m.Y H:i:s') }}</td>
                                    <td>
                                        <a href="{{ route('admin.activities.show', $activity->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm delete-activity" data-id="{{ $activity->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Aktivite bulunamadı.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.delete-activity').click(function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Emin misiniz?',
            text: "Bu aktivite silinecek!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Evet, sil!',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/activities/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Silindi!',
                                response.message,
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
                    error: function() {
                        Swal.fire(
                            'Hata!',
                            'Bir hata oluştu.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>
@endpush
@endsection 