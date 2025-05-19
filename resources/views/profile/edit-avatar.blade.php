@extends('layouts.dashboard')

@section('title', 'Modifier mon avatar - KLINKLIN')

@section('content')
<div class="container-fluid profile-edit-page">
    <div class="row mb-4">
        <div class="col">
            <a href="{{ route('profile.index') }}" class="btn btn-link text-decoration-none ps-0">
                <i class="bi bi-arrow-left me-1"></i> Retour au profil
            </a>
            <h1 class="display-5 fw-bold text-klin-primary mb-2">Personnaliser mon avatar</h1>
            <p class="text-muted fs-5">Choisissez votre avatar parmi les options disponibles</p>
        </div>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Votre avatar a été modifié avec succès.
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
                    <h5 class="mb-0 fw-bold"><i class="bi bi-person-circle me-2 text-klin-primary"></i> Choisir un avatar</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST" id="avatar-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="avatar_type" id="avatar_type" value="{{ $user->avatar_settings['avatar_type'] ?? 'initial' }}">
                        <input type="hidden" name="gravatar_style" id="gravatar_style" value="{{ $user->avatar_settings['gravatar_style'] ?? 'retro' }}">
                        <input type="hidden" name="icon_type" id="icon_type" value="{{ $user->avatar_settings['icon_type'] ?? 'person' }}">
                        
                        <div class="mb-4 text-center">
                            <div class="current-avatar-preview">
                                @if(isset($user->avatar_settings['avatar_type']) && $user->avatar_settings['avatar_type'] === 'gravatar')
                                    <?php 
                                        $style = $user->avatar_settings['gravatar_style'] ?? 'retro';
                                        $gravatarUrl = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?s=150&d=' . $style;
                                    ?>
                                    <img src="{{ $gravatarUrl }}" alt="{{ $user->name }}" class="img-fluid rounded-circle avatar-preview-img">
                                @else
                                    <div class="avatar-circle-preview">
                                        <span class="initials">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <h6 class="mt-3">Avatar actuel</h6>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="section-title">Générer un avatar avec votre email</h6>
                                <p class="text-muted mb-3 small">Ces avatars sont générés automatiquement à partir de votre adresse email via Gravatar.</p>
                                
                                <div class="avatar-options-container">
                                    <div class="d-flex flex-wrap justify-content-center gap-3">
                                        <div class="avatar-option @if(isset($user->avatar_settings['avatar_type']) && $user->avatar_settings['avatar_type'] === 'gravatar' && isset($user->avatar_settings['gravatar_style']) && $user->avatar_settings['gravatar_style'] === 'identicon') selected @endif" data-avatar-type="gravatar" data-gravatar-style="identicon">
                                            <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=80&d=identicon" class="rounded-circle" alt="Identicon">
                                            <span>Identicon</span>
                                        </div>
                                        <div class="avatar-option @if(isset($user->avatar_settings['avatar_type']) && $user->avatar_settings['avatar_type'] === 'gravatar' && isset($user->avatar_settings['gravatar_style']) && $user->avatar_settings['gravatar_style'] === 'monsterid') selected @endif" data-avatar-type="gravatar" data-gravatar-style="monsterid">
                                            <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=80&d=monsterid" class="rounded-circle" alt="Monster">
                                            <span>Monster</span>
                                        </div>
                                        <div class="avatar-option @if(isset($user->avatar_settings['avatar_type']) && $user->avatar_settings['avatar_type'] === 'gravatar' && isset($user->avatar_settings['gravatar_style']) && $user->avatar_settings['gravatar_style'] === 'wavatar') selected @endif" data-avatar-type="gravatar" data-gravatar-style="wavatar">
                                            <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=80&d=wavatar" class="rounded-circle" alt="Wavatar">
                                            <span>Wavatar</span>
                                        </div>
                                        <div class="avatar-option @if(isset($user->avatar_settings['avatar_type']) && $user->avatar_settings['avatar_type'] === 'gravatar' && isset($user->avatar_settings['gravatar_style']) && $user->avatar_settings['gravatar_style'] === 'retro') selected @endif" data-avatar-type="gravatar" data-gravatar-style="retro">
                                            <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=80&d=retro" class="rounded-circle" alt="Retro">
                                            <span>Retro</span>
                                        </div>
                                        <div class="avatar-option @if(isset($user->avatar_settings['avatar_type']) && $user->avatar_settings['avatar_type'] === 'gravatar' && isset($user->avatar_settings['gravatar_style']) && $user->avatar_settings['gravatar_style'] === 'robohash') selected @endif" data-avatar-type="gravatar" data-gravatar-style="robohash">
                                            <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=80&d=robohash" class="rounded-circle" alt="Robot">
                                            <span>Robot</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 mb-4 bg-light">
                <div class="card-body p-4">
                    <h5 class="mb-3 fw-semibold"><i class="bi bi-info-circle me-2"></i> À propos des avatars</h5>
                    <p class="mb-3">Votre avatar est visible par :</p>
                    <ul class="mb-4">
                        <li class="mb-2">Les administrateurs de KLINKLIN</li>
                        <li class="mb-2">Nos équipes de service client</li>
                        <li>Nos livreurs et partenaires quand vous passez une commande</li>
                    </ul>
                    
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-lightbulb-fill me-2"></i>
                        Si vous utilisez déjà Gravatar avec votre email, votre avatar Gravatar existant sera automatiquement utilisé par l'option correspondante.
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
    
    .current-avatar-preview {
        margin-bottom: 20px;
    }
    
    .avatar-preview-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        margin: 0 auto;
    }
    
    .avatar-circle-preview {
        width: 120px;
        height: 120px;
        background-color: var(--main-profile-icon-bg);
        color: var(--main-profile-icon-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        margin: 0 auto;
    }
    
    .section-title {
        font-weight: 600;
        color: var(--primary-purple);
        margin-bottom: 0.5rem;
    }
    
    .avatar-options-container {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
    }
    
    .avatar-option {
        cursor: pointer;
        text-align: center;
        padding: 10px;
        border-radius: 8px;
        transition: all 0.2s ease;
        width: 100px;
        border: 2px solid transparent;
    }
    
    .avatar-option:hover {
        background-color: rgba(76, 41, 117, 0.1);
    }
    
    .avatar-option.selected {
        background-color: rgba(76, 41, 117, 0.1);
        border: 2px solid var(--primary-purple);
    }
    
    .avatar-option img {
        width: 60px;
        height: 60px;
        margin-bottom: 8px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .avatar-option span {
        display: block;
        font-size: 0.8rem;
        color: var(--text-color);
    }
    
    .avatar-icon-option {
        width: 60px;
        height: 60px;
        background-color: var(--primary-purple);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto 8px;
    }
    
    .initial-avatar {
        font-size: 1.5rem;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gérer la sélection d'avatar
    const avatarOptions = document.querySelectorAll('.avatar-option');
    const avatarTypeInput = document.getElementById('avatar_type');
    const gravatarStyleInput = document.getElementById('gravatar_style');
    const iconTypeInput = document.getElementById('icon_type');
    const avatarForm = document.getElementById('avatar-form');
    
    // Sélection des avatars
    avatarOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Supprimer la classe selected de toutes les options
            avatarOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Ajouter la classe selected à l'option cliquée
            this.classList.add('selected');
            
            // Mettre à jour les valeurs des champs cachés
            const avatarType = this.getAttribute('data-avatar-type');
            avatarTypeInput.value = avatarType;
            
            if (avatarType === 'gravatar') {
                gravatarStyleInput.value = this.getAttribute('data-gravatar-style');
            } else if (avatarType === 'icon') {
                iconTypeInput.value = this.getAttribute('data-icon-type');
            }
            
            // Mettre à jour l'aperçu de l'avatar
            updateAvatarPreview();
        });
    });
    
    // Fonction pour mettre à jour l'aperçu de l'avatar
    function updateAvatarPreview() {
        const previewContainer = document.querySelector('.current-avatar-preview');
        const avatarType = avatarTypeInput.value;
        
        if (avatarType === 'gravatar') {
            const style = gravatarStyleInput.value;
            const gravatarUrl = 'https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=150&d=' + style;
            
            previewContainer.innerHTML = `
                <img src="${gravatarUrl}" alt="{{ $user->name }}" class="img-fluid rounded-circle avatar-preview-img">
                <h6 class="mt-3">Aperçu de votre nouvel avatar</h6>
            `;
        } else if (avatarType === 'initial') {
            previewContainer.innerHTML = `
                <div class="avatar-circle-preview">
                    <span class="initials">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <h6 class="mt-3">Aperçu de votre nouvel avatar</h6>
            `;
        } else if (avatarType === 'icon') {
            const iconType = iconTypeInput.value;
            previewContainer.innerHTML = `
                <div class="avatar-circle-preview" style="font-size: 2.5rem;">
                    <i class="bi bi-${iconType}-fill"></i>
                </div>
                <h6 class="mt-3">Aperçu de votre nouvel avatar</h6>
            `;
        }
    }
    
    // Mise à jour de l'avatar dans la barre latérale après soumission réussie
    if (avatarForm) {
        avatarForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Récupérer les données du formulaire
            const formData = new FormData(this);
            
            // Envoyer le formulaire via AJAX
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mise à jour réussie
                    showNotification({
                        title: 'Avatar mis à jour',
                        message: 'Votre avatar a été modifié avec succès.',
                        type: 'success',
                        icon: 'check-circle-fill',
                        autoHide: true
                    });
                    
                    // Mettre à jour l'avatar dans la barre latérale
                    if (window.updateUserAvatar) {
                        const avatarSettings = {
                            avatar_type: avatarTypeInput.value,
                            gravatar_style: gravatarStyleInput.value,
                            icon_type: iconTypeInput.value
                        };
                        window.updateUserAvatar(avatarSettings);
                    }
                } else {
                    // Erreur
                    showNotification({
                        title: 'Erreur',
                        message: data.message || 'Une erreur est survenue lors de la mise à jour de votre avatar.',
                        type: 'danger',
                        icon: 'exclamation-triangle-fill',
                        autoHide: true
                    });
                }
            })
            .catch(error => {
                console.error('Erreur lors de la mise à jour de l\'avatar:', error);
                showNotification({
                    title: 'Erreur',
                    message: 'Une erreur est survenue lors de la communication avec le serveur.',
                    type: 'danger',
                    icon: 'exclamation-triangle-fill',
                    autoHide: true
                });
            });
        });
    }
});
</script>
@endpush 