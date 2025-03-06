@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Yorum Detayı</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">İsim</label>
                                <p>{{ $comment->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Kaynak</label>
                                <p>{{ $comment->source }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold">İçerik</label>
                        <p>{{ $comment->comment }}</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Yorum Tarihi</label>
                                <p>{{ $comment->comment_date->format('d.m.Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="font-weight-bold">Sıralama</label>
                                <p>{{ $comment->position }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <a href="{{ route('admin.comments.edit', $comment) }}" class="btn btn-primary">Düzenle</a>
                        <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">Geri Dön</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 