@extends('layouts.dashboard')

@section('title', 'Modifier mon nom - KLINKLIN')

@section('content')
<div class="container-fluid profile-edit-page">
    <div class="row mb-4">
        <div class="col">
            <a href="{{ route('profile.index') }}" class="btn btn-link text-decoration-none ps-0">
                <i class="bi bi-arrow-left me-1"></i> Retour au profil
            </a>
            <h1 class="display-5 fw-bold text-klin-primary mb-2">Modifier mon nom</h1>
            <p class="text-muted fs-5">Mettez à jour votre nom complet</p>
        </div>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Votre nom a été mis à jour avec succès.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-person-fill me-2 text-klin-primary"></i> Informations personnelles</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Ce nom sera utilisé pour toutes vos communications et commandes.</div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('profile.index') }}" class="btn btn-light me-2">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2 me-1"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 mb-4 bg-light">
                <div class="card-body p-4">
                    <h5 class="mb-3 fw-semibold"><i class="bi bi-info-circle me-2"></i> Informations</h5>
                    <p class="mb-0">Votre nom complet permet de personnaliser vos commandes et facilite l'identification de vos articles par nos équipes.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-edit-page .form-control:focus {
        border-color: var(--primary-purple);
        box-shadow: 0 0 0 0.25rem rgba(76, 41, 117, 0.25);
    }
    
    .profile-edit-page .input-group-text {
        background-color: #fff;
    }
    
    .profile-edit-page .input-group-text i {
        color: var(--primary-purple);
    }
    
    .profile-edit-page .btn-primary {
        background-color: var(--primary-purple);
        border-color: var(--primary-purple);
    }
    
    .profile-edit-page .btn-primary:hover {
        background-color: var(--primary-purple-darker);
        border-color: var(--primary-purple-darker);
    }
    
    .profile-edit-page .text-klin-primary {
        color: var(--primary-purple);
    }
</style>
@endpush 