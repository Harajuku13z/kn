@extends('layouts.dashboard')

@section('title', 'Modifier mon email - KLINKLIN')

@section('content')
<div class="container-fluid profile-edit-page">
    <div class="row mb-4">
        <div class="col">
            <a href="{{ route('profile.index') }}" class="btn btn-link text-decoration-none ps-0">
                <i class="bi bi-arrow-left me-1"></i> Retour au profil
            </a>
            <h1 class="display-5 fw-bold text-klin-primary mb-2">Modifier mon adresse email</h1>
            <p class="text-muted fs-5">Mettez à jour votre adresse email - Une vérification sera nécessaire</p>
        </div>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Votre adresse email a été mise à jour. Veuillez vérifier votre nouvelle adresse email.
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
                    <h5 class="mb-0 fw-bold"><i class="bi bi-envelope-fill me-2 text-klin-primary"></i> Adresse email actuelle</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3">
                            <span class="d-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 48px; height: 48px;">
                                <i class="bi bi-envelope-check text-klin-primary fs-4"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $user->email }}</h6>
                            <p class="text-muted mb-0">
                                @if($user->email_verified_at)
                                    <span class="badge bg-success">Email vérifié <i class="bi bi-check-circle-fill ms-1"></i></span>
                                @else
                                    <span class="badge bg-warning text-dark">Email non vérifié <i class="bi bi-exclamation-circle-fill ms-1"></i></span>
                                @endif
                            </p>
                        </div>
                    </div>
                
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Nouvelle adresse email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Entrez votre nouvelle adresse email">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Un email de vérification sera envoyé à cette nouvelle adresse.</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="current_password" class="form-label fw-semibold">Mot de passe actuel <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required placeholder="Entrez votre mot de passe actuel">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Pour des raisons de sécurité, veuillez confirmer votre mot de passe.</div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('profile.index') }}" class="btn btn-light me-2">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2 me-1"></i> Modifier mon email
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 mb-4 bg-light">
                <div class="card-body p-4">
                    <h5 class="mb-3 fw-semibold"><i class="bi bi-info-circle me-2"></i> Important</h5>
                    <p class="mb-3">Lorsque vous modifiez votre adresse email :</p>
                    <ul class="mb-0">
                        <li class="mb-2">Un email de vérification sera envoyé à votre nouvelle adresse</li>
                        <li class="mb-2">Vous devrez cliquer sur le lien dans cet email pour confirmer</li>
                        <li class="mb-2">Votre ancienne adresse recevra également une notification</li>
                        <li>L'adresse email est utilisée pour vous connecter à votre compte</li>
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
    
    .toggle-password {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour basculer l'affichage du mot de passe
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
});
</script>
@endpush 