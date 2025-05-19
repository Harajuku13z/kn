@extends('layouts.dashboard')

@section('title', 'Modifier mon téléphone - KLINKLIN')

@section('content')
<div class="container-fluid profile-edit-page">
    <div class="row mb-4">
        <div class="col">
            <a href="{{ route('profile.index') }}" class="btn btn-link text-decoration-none ps-0">
                <i class="bi bi-arrow-left me-1"></i> Retour au profil
            </a>
            <h1 class="display-5 fw-bold text-klin-primary mb-2">Modifier mon numéro de téléphone</h1>
            <p class="text-muted fs-5">Mettez à jour votre numéro de téléphone pour les livraisons et notifications</p>
        </div>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Votre numéro de téléphone a été mis à jour avec succès.
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
                    <h5 class="mb-0 fw-bold"><i class="bi bi-telephone-fill me-2 text-klin-primary"></i> Coordonnées téléphoniques</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3">
                            <span class="d-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 48px; height: 48px;">
                                <i class="bi bi-telephone text-klin-primary fs-4"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-1">Numéro actuel</h6>
                            <p class="text-muted mb-0">{{ $user->phone ?? 'Non renseigné' }}</p>
                        </div>
                    </div>
                
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-semibold">Nouveau numéro de téléphone <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <img src="https://cdnjs.cloudflare.com/ajax/libs/twemoji/14.0.2/72x72/1f1e8-1f1ec.png" alt="CG" style="width: 20px; height: auto; margin-right: 5px;"> 
                                    <i class="bi bi-chevron-down" style="font-size: 0.7rem;"></i>
                                </span>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required autofocus placeholder="Entrez votre numéro de téléphone">
                            </div>
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Format recommandé: 0XX XXX XXXX</div>
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
                    <h5 class="mb-3 fw-semibold"><i class="bi bi-info-circle me-2"></i> Pourquoi indiquer un numéro ?</h5>
                    <p class="mb-3">Votre numéro de téléphone sera utilisé pour :</p>
                    <ul class="mb-0">
                        <li class="mb-2">Vous contacter en cas de problème avec votre commande</li>
                        <li class="mb-2">Permettre au livreur de vous joindre lors de la livraison</li>
                        <li class="mb-2">Recevoir des notifications importantes par SMS (optionnel)</li>
                        <li>Faciliter le processus de récupération de compte si nécessaire</li>
                    </ul>
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