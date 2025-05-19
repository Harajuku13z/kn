@extends('layouts.dashboard')

@section('title', 'Modifier mon mot de passe - KLINKLIN')

@section('content')
<div class="container-fluid profile-edit-page">
    <div class="row mb-4">
        <div class="col">
            <a href="{{ route('profile.index') }}" class="btn btn-link text-decoration-none ps-0">
                <i class="bi bi-arrow-left me-1"></i> Retour au profil
            </a>
            <h1 class="display-5 fw-bold text-klin-primary mb-2">Modifier mon mot de passe</h1>
            <p class="text-muted fs-5">Mettez à jour votre mot de passe pour sécuriser votre compte</p>
        </div>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Votre mot de passe a été modifié avec succès.
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
                    <h5 class="mb-0 fw-bold"><i class="bi bi-shield-lock-fill me-2 text-klin-primary"></i> Sécurité du compte</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="current_password" class="form-label fw-semibold">Mot de passe actuel <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required autofocus placeholder="Entrez votre mot de passe actuel">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">Nouveau mot de passe <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Entrez votre nouveau mot de passe">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Le mot de passe doit comporter au moins 8 caractères.</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Confirmer le nouveau mot de passe <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Confirmez votre nouveau mot de passe">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="password-strength-meter mt-3 mb-4">
                            <h6 class="mb-2 fw-semibold">Force du mot de passe :</h6>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 0%" id="passwordStrengthBar"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <span class="small text-muted">Faible</span>
                                <span class="small text-muted">Fort</span>
                            </div>
                            <div id="passwordFeedback" class="form-text mt-2"></div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('profile.index') }}" class="btn btn-light me-2">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2 me-1"></i> Modifier mon mot de passe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 mb-4 bg-light">
                <div class="card-body p-4">
                    <h5 class="mb-3 fw-semibold"><i class="bi bi-info-circle me-2"></i> Conseils de sécurité</h5>
                    <p class="mb-3">Pour un mot de passe fort :</p>
                    <ul class="mb-4">
                        <li class="mb-2">Utilisez au moins 8 caractères</li>
                        <li class="mb-2">Incluez des lettres majuscules et minuscules</li>
                        <li class="mb-2">Ajoutez des chiffres et des caractères spéciaux</li>
                        <li class="mb-2">Évitez les informations personnelles facilement devinables</li>
                        <li>Ne réutilisez pas un mot de passe déjà utilisé</li>
                    </ul>
                    
                    <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Important:</strong> Après avoir changé votre mot de passe, vous devrez vous reconnecter sur tous vos appareils.
                    </div>
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
    
    // Fonction pour évaluer la force du mot de passe
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('passwordStrengthBar');
    const feedback = document.getElementById('passwordFeedback');
    
    if (passwordInput && strengthBar && feedback) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let message = '';
            
            // Longueur minimum
            if (password.length >= 8) {
                strength += 25;
            }
            
            // Présence de lettres minuscules et majuscules
            if (password.match(/[a-z]+/) && password.match(/[A-Z]+/)) {
                strength += 25;
            }
            
            // Présence de nombres
            if (password.match(/[0-9]+/)) {
                strength += 25;
            }
            
            // Présence de caractères spéciaux
            if (password.match(/[$@#&!?%*()_+^~{}"':;|<>,.`´=\-[\]\\]/)) {
                strength += 25;
            }
            
            // Mise à jour de la barre de progression
            strengthBar.style.width = strength + '%';
            
            // Changer la couleur de la barre selon la force
            if (strength < 25) {
                strengthBar.className = 'progress-bar bg-danger';
                message = 'Très faible';
            } else if (strength < 50) {
                strengthBar.className = 'progress-bar bg-warning';
                message = 'Faible';
            } else if (strength < 75) {
                strengthBar.className = 'progress-bar bg-info';
                message = 'Moyen';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                message = 'Fort';
            }
            
            feedback.textContent = password.length > 0 ? `Force du mot de passe: ${message}` : '';
        });
    }
});
</script>
@endpush 