@extends('admin.layouts.app')

@section('title', 'Aktivite Detayı')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aktivite Detayı</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID</th>
                                    <td>{{ $activity->id }}</td>
                                </tr>
                                <tr>
                                    <th>Kullanıcı</th>
                                    <td>{{ $activity->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Modül</th>
                                    <td>{{ $activity->module }}</td>
                                </tr>
                                <tr>
                                    <th>Tür</th>
                                    <td>{{ $activity->type }}</td>
                                </tr>
                                <tr>
                                    <th>Açıklama</th>
                                    <td>{{ $activity->description }}</td>
                                </tr>
                                <tr>
                                    <th>IP Adresi</th>
                                    <td>{{ $activity->ip_address }}</td>
                                </tr>
                                <tr>
                                    <th>Tarayıcı</th>
                                    <td>{{ $activity->user_agent }}</td>
                                </tr>
                                <tr>
                                    <th>Oluşturulma Tarihi</th>
                                    <td>{{ $activity->created_at->format('d.m.Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Güncellenme Tarihi</th>
                                    <td>{{ $activity->updated_at->format('d.m.Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Geri Dön
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 