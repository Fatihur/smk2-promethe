@extends('adminlte::auth.login')

@section('title', 'Login - SMK2 PROMETHEE')

@section('auth_header', 'SMK2 PROMETHEE')

@section('auth_body')
<form action="{{ route('login') }}" method="post">
    @csrf
    
    {{-- Username field --}}
    <div class="input-group mb-3">
        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
               placeholder="Username" value="{{ old('username') }}" required autofocus>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-user"></span>
            </div>
        </div>
        @error('username')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    {{-- Password field --}}
    <div class="input-group mb-3">
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
               placeholder="Password" required>
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

    {{-- Remember me checkbox --}}
    <div class="row">
        <div class="col-8">
            <div class="icheck-primary">
                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">
                    Ingat Saya
                </label>
            </div>
        </div>
        {{-- Login button --}}
        <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
        </div>
    </div>
</form>
@stop

@section('auth_footer')
<p class="mb-1 text-center">
    <small>Sistem Penentuan Konsentrasi Keahlian</small>
</p>
<p class="mb-0 text-center">
    <small>&copy; {{ date('Y') }} SMK Negeri 2 Sumbawa Besar</small>
</p>
@stop
