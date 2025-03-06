@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', 'Şifre Sıfırlama')

@section('auth_body')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="post">
        @csrf

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="E-posta" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Send reset link button --}}
        <button type="submit" class="btn btn-block btn-primary">
            Şifre Sıfırlama Bağlantısı Gönder
        </button>
    </form>
@stop

@section('auth_footer')
    <p class="my-0">
        <a href="{{ route('login') }}">
            Giriş Sayfasına Dön
        </a>
    </p>
@stop
