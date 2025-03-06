@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Gönderiler</h1>
        <a href="{{ route('posts.create') }}" class="btn btn-primary">Yeni Gönderi Ekle</a>
    </div>

    {{-- Eğer gönderiler varsa, tabloyu göster --}}
    @if($posts->count() > 0)
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Başlık</th>
                    <th>Aksiyonlar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->title }}</td>
                        <td>
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">Düzenle</a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bu gönderiyi silmek istediğinize emin misiniz?');">
                                    Sil
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="alert alert-warning">Henüz hiç gönderi eklenmedi.</p>
    @endif
@endsection
