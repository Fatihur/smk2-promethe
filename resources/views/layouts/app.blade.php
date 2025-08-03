@extends('adminlte::page')

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@stop

@section('adminlte_js')
    <script src="{{ asset('js/custom-adminlte.js') }}"></script>
    @stack('js')
@stop

@section('adminlte_css')
    @stack('css')
@stop

@push('js')
<script>
    // Global JavaScript variables
    window.appConfig = {
        baseUrl: '{{ url('/') }}',
        csrfToken: '{{ csrf_token() }}',
        user: @json(auth()->user()),
        locale: '{{ app()->getLocale() }}'
    };
    
    // Initialize application
    $(document).ready(function() {
        // Add fade-in animation to content
        $('.content-wrapper').addClass('fade-in');
        
        // Initialize tooltips globally
        $('[data-toggle="tooltip"]').tooltip();
        
        // Auto-hide flash messages
        $('.alert:not(.alert-permanent)').delay(5000).fadeOut('slow');
        
        // Confirm delete actions
        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            const message = $(this).data('message') || 'Yakin ingin menghapus data ini?';
            if (confirm(message)) {
                $(this).closest('form').submit();
            }
        });
    });
</script>
@endpush

@push('css')
<style>
    /* Additional custom styles can be added here */
    .content-wrapper {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: calc(100vh - 57px);
    }
    
    .main-header {
        border-bottom: 1px solid #dee2e6;
    }
    
    .brand-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white !important;
    }
    
    .brand-text {
        color: white !important;
        font-weight: 600;
    }
    
    .main-sidebar {
        background: #343a40;
    }
    
    .nav-sidebar .nav-header {
        background: rgba(255,255,255,0.1);
        color: #adb5bd;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .nav-sidebar .nav-link {
        color: #adb5bd;
    }
    
    .nav-sidebar .nav-link:hover {
        background: rgba(255,255,255,0.1);
        color: white;
    }
    
    .nav-sidebar .nav-link.active {
        background: #007bff;
        color: white;
    }
    
    .card {
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    
    .small-box {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .btn {
        border-radius: 6px;
    }
    
    .form-control {
        border-radius: 6px;
    }
    
    .table {
        border-radius: 8px;
        overflow: hidden;
    }
</style>
@endpush
