@extends('layouts.dashboard')

@section('title', 'Créer un ticket - KLINKLIN')

@section('content')
<div class="dashboard-content">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('support.index') }}" class="btn btn-outline-secondary me-3 d-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> Retour
        </a>
        <div>
            <h1 class="mb-1">Nouveau ticket de support</h1>
            <p class="text-muted mb-0">Créez un ticket pour obtenir de l'aide de notre équipe</p>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
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
            <form action="{{ route('support.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="subject" class="form-label">Sujet <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                    @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="category" class="form-label">Catégorie <span class="text-danger">*</span></label>
                    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                        <option value="">Sélectionnez une catégorie</option>
                        @foreach($categories as $value => $label)
                            <option value="{{ $value }}" {{ old('category') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Décrivez votre problème en détail pour nous aider à vous répondre plus efficacement.</small>
                </div>
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-paper-plane me-2"></i> Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection