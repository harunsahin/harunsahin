@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Yorum Düzenle</h3>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.comments.update', $comment) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name">İsim</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $comment->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="source">Kaynak</label>
                                    <select class="form-control @error('source') is-invalid @enderror" id="source" name="source" required>
                                        <option value="">Kaynak Seçin</option>
                                        <option value="Tripadvisor" {{ old('source', $comment->source) == 'Tripadvisor' ? 'selected' : '' }}>Tripadvisor</option>
                                        <option value="Booking.com" {{ old('source', $comment->source) == 'Booking.com' ? 'selected' : '' }}>Booking.com</option>
                                        <option value="Google" {{ old('source', $comment->source) == 'Google' ? 'selected' : '' }}>Google</option>
                                        <option value="Hotels.com" {{ old('source', $comment->source) == 'Hotels.com' ? 'selected' : '' }}>Hotels.com</option>
                                        <option value="Otelpuan" {{ old('source', $comment->source) == 'Otelpuan' ? 'selected' : '' }}>Otelpuan</option>
                                    </select>
                                    @error('source')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="content">İçerik</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="5" required>{{ old('content', $comment->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="comment_date">Yorum Tarihi</label>
                                    <input type="date" class="form-control @error('comment_date') is-invalid @enderror" id="comment_date" name="comment_date" value="{{ old('comment_date', $comment->comment_date->format('Y-m-d')) }}" required>
                                    @error('comment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="position">Sıralama</label>
                                    <input type="number" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position', $comment->position) }}" required>
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Güncelle</button>
                            <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">İptal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 