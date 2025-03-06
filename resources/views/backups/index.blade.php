@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Yedekler</h1>
        <form action="{{ route('backups.create') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yeni Yedek Oluştur
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Dosya Adı</th>
                            <th>Boyut</th>
                            <th>Tarih</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($backups as $backup)
                            <tr>
                                <td>{{ $backup['name'] }}</td>
                                <td>{{ number_format($backup['size'] / 1024, 2) }} KB</td>
                                <td>{{ $backup['date'] }}</td>
                                <td>
                                    <a href="{{ route('backups.download', basename($backup['path'])) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <form action="{{ route('backups.destroy', basename($backup['path'])) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Bu yedeği silmek istediğinize emin misiniz?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Henüz yedek oluşturulmamış.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 