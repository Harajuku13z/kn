@extends('layouts.dashboard')

@section('title', '@yield("page-title")')

@section('content')
<div class="dashboard-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="@yield('back-url')" class="btn btn-outline-secondary me-3 d-flex align-items-center">
                <i class="bi bi-arrow-left me-2"></i> Retour
            </a>
            <div>
                <h1 class="mb-1">@yield('page-heading')</h1>
                <p class="text-muted mb-0">@yield('page-description')</p>
            </div>
        </div>
        @yield('page-actions')
    </div>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            @yield('detail-content')
        </div>
    </div>
    
    @yield('additional-content')
</div>
@endsection 