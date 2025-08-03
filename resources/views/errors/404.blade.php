@extends('adminlte::page')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content_header')
    <h1>404 - Halaman Tidak Ditemukan</h1>
@stop

@section('content')
<div class="error-page">
    <h2 class="headline text-warning">404</h2>
    <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Halaman tidak ditemukan.</h3>
        <p>
            Halaman yang Anda cari tidak dapat ditemukan.
            Mungkin halaman telah dipindahkan atau URL yang Anda masukkan salah.
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
