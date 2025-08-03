@extends('adminlte::page')

@section('title', '403 - Akses Ditolak')

@section('content_header')
    <h1>403 - Akses Ditolak</h1>
@stop

@section('content')
<div class="error-page">
    <h2 class="headline text-warning">403</h2>
    <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Akses Ditolak.</h3>
        <p>
            Anda tidak memiliki izin untuk mengakses halaman ini.
            Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.
        </p>
        <div class="mt-4">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Kembali ke Dashboard
            </a>
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
.error-page {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 400px;
}

.headline {
    font-size: 100px;
    font-weight: 300;
    margin-right: 20px;
}

.error-content {
    padding-left: 20px;
}

.error-content h3 {
    font-weight: 300;
    font-size: 25px;
}

@media (max-width: 768px) {
    .error-page {
        flex-direction: column;
        text-align: center;
    }
    
    .headline {
        margin-right: 0;
        margin-bottom: 20px;
    }
    
    .error-content {
        padding-left: 0;
    }
}
</style>
@stop
