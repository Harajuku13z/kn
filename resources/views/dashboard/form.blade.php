@extends('layouts.dashboard')

@section('title', '@yield("page-title")')

@section('content')
<div class="dashboard-content">
    <div class="d-flex align-items-center mb-4">
        <a href="@yield('back-url')" class="btn btn-outline-secondary me-3 d-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> Retour
        </a>
        <div>
            <h1 class="mb-1">@yield('page-heading')</h1>
            <p class="text-muted mb-0">@yield('page-description')</p>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            @yield('form-content')
        </div>
    </div>
</div>
@endsection 