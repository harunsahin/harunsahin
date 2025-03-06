@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', 'Giriş Yap')

@section('auth_body')
    <form action="{{ route('login') }}" method="post">
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

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                   placeholder="Şifre">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Login field --}}
        <div class="row">
            <div class="col-7">
                <div class="icheck-primary">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Beni Hatırla</label>
                </div>
            </div>

            <div class="col-5">
                <button type=submit class="btn btn-block btn-primary">
                    Giriş Yap
                </button>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    {{-- Password reset link --}}
    @if (Route::has('password.request'))
        <p class="my-0">
            <a href="{{ route('password.request') }}">
                Şifremi Unuttum
            </a>
        </p>
    @endif
@stop
