@extends('adminlte::page')

@section('title', '500 - Server Error')

@section('content_header')
    <h1>500 - Server Error</h1>
@stop

@section('content')
<div class="error-page">
    <h2 class="headline text-danger">500</h2>
    <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Terjadi kesalahan server.</h3>
        <p>
            Terjadi kesalahan internal pada server.
            Tim teknis kami telah diberitahu dan sedang menangani masalah ini.
            Silakan coba lagi dalam beberapa saat.
        </p>
        <div class="mt-4">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Kembali ke Dashboard
            </a>
            <a href="javascript:location.reload()" class="btn btn-secondary">
                <i class="fas fa-refresh"></i> Muat Ulang
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
