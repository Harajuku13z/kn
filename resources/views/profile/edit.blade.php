@extends('layouts.dashboard')

@section('title', 'Mon Profil - KLINKLIN')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center profile-header mb-4">
                <div class="position-relative d-inline-block me-4" id="profile-avatar-container">
                    @if($user->profile_image)
                        <img src="{{ asset('img/profil/' . $user->profile_image) }}" 
                             alt="{{ $user->name }}" 
                             class="rounded-circle img-thumbnail"
                             style="width: 60px; height: 60px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                             style="width: 60px; height: 60px; font-size: 24px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <button type="button" 
                            class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle"
                            data-bs-toggle="modal" data-bs-target="#avatarSelectionModal">
                        <i class="bi bi-image"></i>
                    </button>
                </div>
                <div>
                    <h1 class="h2 mb-0">Mon Profil</h1>
                    <p class="text-muted mb-0">Gérez vos informations personnelles et vos préférences.</p>
                </div>
            </div>

            <ul class="nav nav-tabs mb-0" id="profileTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="infos-perso-tab" data-bs-toggle="tab" data-bs-target="#infos-perso-pane" type="button" role="tab" aria-controls="infos-perso-pane" aria-selected="true">
                        <i class="bi bi-person-badge"></i>Informations personnelles
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="securite-tab" data-bs-toggle="tab" data-bs-target="#securite-pane" type="button" role="tab" aria-controls="securite-pane" aria-selected="false">
                        <i class="bi bi-shield-lock"></i>Sécurité
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="connexions-tab" data-bs-toggle="tab" data-bs-target="#connexions-pane" type="button" role="tab" aria-controls="connexions-pane" aria-selected="false">
                        <i class="bi bi-clock-history"></i>Historique de connexion
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="profileTabContent">
                <div class="tab-pane fade show active" id="infos-perso-pane" role="tabpanel" aria-labelledby="infos-perso-tab">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom complet</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required>
                            </div>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Numéro de téléphone</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}" 
                                       required>
                            </div>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Enregistrer les modifications
                        </button>
                    </form>
            </div>
            
                <div class="tab-pane fade" id="securite-pane" role="tabpanel" aria-labelledby="securite-tab">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password">
                                <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password">
                                <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation">
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-shield-lock me-1"></i> Mettre à jour le mot de passe
                        </button>
                    </form>
                </div>

                <div class="tab-pane fade" id="connexions-pane" role="tabpanel" aria-labelledby="connexions-tab">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Dernières connexions</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Appareil</th>
                                            <th>Adresse IP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($loginHistory as $login)
                                            <tr>
                                                <td>{{ $login->login_at->format('d/m/Y à H:i') }}</td>
                                                <td>
                                                    @if($login->device_type == 'Mobile')
                                                        <i class="bi bi-phone me-1"></i>
                                                    @elseif($login->device_type == 'Tablet')
                                                        <i class="bi bi-tablet me-1"></i>
                                                    @else
                                                        <i class="bi bi-laptop me-1"></i>
                                                    @endif
                                                    {{ $login->device_type }}
                                                </td>
                                                <td>{{ $login->ip_address }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">Aucun historique de connexion disponible.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <p class="small text-muted mt-3">
                                <i class="bi bi-info-circle me-1"></i>
                                Nous envoyons une notification par email à chaque nouvelle connexion à votre compte pour votre sécurité.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
                    </div>
                </div>
            </div>
            
<!-- Modal pour la sélection d'avatar -->
<div class="modal fade" id="avatarSelectionModal" tabindex="-1" aria-labelledby="avatarSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avatarSelectionModalLabel">Choisir un avatar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-12">
                        <h6>Générer un avatar avec votre email</h6>
                        <p class="text-muted small">Ces avatars sont générés automatiquement à partir de votre adresse email via Gravatar.</p>
                        <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
                            <div class="avatar-option" data-avatar-type="gravatar" data-avatar-style="identicon">
                                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=80&d=identicon" class="rounded" alt="Identicon">
                                <span>Identicon</span>
                            </div>
                            <div class="avatar-option" data-avatar-type="gravatar" data-avatar-style="monsterid">
                                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=80&d=monsterid" class="rounded" alt="Monster">
                                <span>Monster</span>
                            </div>
                            <div class="avatar-option" data-avatar-type="gravatar" data-avatar-style="wavatar">
                                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=80&d=wavatar" class="rounded" alt="Wavatar">
                                <span>Wavatar</span>
                            </div>
                            <div class="avatar-option" data-avatar-type="gravatar" data-avatar-style="retro">
                                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=80&d=retro" class="rounded" alt="Retro">
                                <span>Retro</span>
                            </div>
                            <div class="avatar-option" data-avatar-type="gravatar" data-avatar-style="robohash">
                                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=80&d=robohash" class="rounded" alt="Robot">
                                <span>Robot</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-12">
                        <h6>Avatars prédéfinis</h6>
                        <p class="text-muted small">Sélectionnez l'un de nos avatars personnalisés.</p>
                        <div class="d-flex flex-wrap justify-content-center gap-3 mt-3">
                            <div class="avatar-option" data-avatar-type="initial">
                                <div class="avatar-initial rounded">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                <span>Initiale</span>
                            </div>
                            <div class="avatar-option" data-avatar-type="icon" data-avatar-icon="person">
                                <div class="avatar-icon rounded">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <span>Personne</span>
                            </div>
                            <div class="avatar-option" data-avatar-type="icon" data-avatar-icon="star">
                                <div class="avatar-icon rounded">
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <span>Étoile</span>
                            </div>
                            <div class="avatar-option" data-avatar-type="icon" data-avatar-icon="emoji">
                                <div class="avatar-icon rounded">
                                    <i class="bi bi-emoji-smile-fill"></i>
                                </div>
                                <span>Emoji</span>
                            </div>
                            <div class="avatar-option" data-avatar-type="icon" data-avatar-icon="diamond">
                                <div class="avatar-icon rounded">
                                    <i class="bi bi-gem"></i>
                                </div>
                                <span>Diamant</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="saveAvatarBtn">Enregistrer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<style>
:root {
    --primary-color: #6f42c1;
    --primary-hover: #5a359e;
    --secondary-color: #6c757d;
    --border-color: #dee2e6;
    --bg-light: #f8f9fa;
}

.profile-header {
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 1.5rem;
}

.nav-tabs .nav-link {
    color: var(--secondary-color);
    border-top-left-radius: .35rem;
    border-top-right-radius: .35rem;
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
}

.nav-tabs .nav-link.active {
    color: var(--primary-color);
    background-color: #fff;
    border-color: var(--border-color) var(--border-color) #fff;
        font-weight: 500;
}

.nav-tabs .nav-link i {
    margin-right: 0.5rem;
    color: var(--primary-color);
}

.nav-tabs .nav-link:not(.active) i {
    color: var(--secondary-color);
}

.tab-content > .tab-pane {
    background-color: #fff;
    border: 1px solid var(--border-color);
    border-top: none;
    border-radius: 0 0 .35rem .35rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    padding: 2rem;
}

.img-container {
    max-height: 400px;
        overflow: hidden;
}

#cropImage {
    max-width: 100%;
}

.input-group-text {
    background-color: var(--bg-light);
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    background-color: var(--primary-hover);
    border-color: var(--primary-hover);
}

.btn-outline-secondary {
    color: var(--secondary-color);
    border-color: var(--border-color);
}

.btn-outline-secondary:hover {
    background-color: var(--bg-light);
    color: var(--secondary-color);
    border-color: var(--border-color);
    }

/* Avatar selection styles */
.avatar-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    padding: 10px;
    border-radius: 8px;
    transition: all 0.2s;
}

.avatar-option:hover {
    background-color: rgba(111, 66, 193, 0.1);
}

.avatar-option.selected {
    background-color: rgba(111, 66, 193, 0.2);
    border: 2px solid var(--primary-color);
}

.avatar-option img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    margin-bottom: 8px;
}

.avatar-option span {
    font-size: 0.8rem;
    color: var(--secondary-color);
}

.avatar-initial, .avatar-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--primary-color);
    color: white;
    font-size: 24px;
    margin-bottom: 8px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du recadrage d'image
    let cropper;
    const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
    const cropImage = document.getElementById('cropImage');
    const cropButton = document.getElementById('cropButton');
    const profileImageInput = document.getElementById('profile_image');

    profileImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                cropImage.src = e.target.result;
                cropModal.show();
            };
            reader.readAsDataURL(file);
        }
    });

    cropModal._element.addEventListener('shown.bs.modal', function() {
        cropper = new Cropper(cropImage, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 1,
            responsive: true,
            restore: false
        });
    });

    cropButton.addEventListener('click', function() {
        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300
        });

        canvas.toBlob(function(blob) {
            const formData = new FormData();
            formData.append('profile_image', blob, 'profile.jpg');
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT');

            fetch('{{ route('profile.update') }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Erreur lors du téléchargement de l\'image');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors du téléchargement de l\'image');
            });

            cropModal.hide();
        }, 'image/jpeg', 0.9);
    });

    cropModal._element.addEventListener('hidden.bs.modal', function() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });

    // Avatar selection functionality
    const avatarOptions = document.querySelectorAll('.avatar-option');
    const saveAvatarBtn = document.getElementById('saveAvatarBtn');
    let selectedAvatar = null;
    
    // Select avatar on click
    avatarOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            avatarOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Add selected class to clicked option
            this.classList.add('selected');
            
            // Store selected avatar info
            selectedAvatar = {
                avatar_type: this.dataset.avatarType
            };
            
            // Add specific data based on avatar type
            if (selectedAvatar.avatar_type === 'gravatar') {
                selectedAvatar.gravatar_style = this.dataset.avatarStyle;
            } else if (selectedAvatar.avatar_type === 'icon') {
                selectedAvatar.icon_type = this.dataset.avatarIcon;
            }
        });
    });
    
    // Save selected avatar
    saveAvatarBtn.addEventListener('click', function() {
        if (!selectedAvatar) {
            alert('Veuillez sélectionner un avatar');
            return;
        }
        
        // Send AJAX request to update avatar
        fetch('{{ route('profile.update') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                _method: 'PUT',
                avatar_type: selectedAvatar.avatar_type,
                gravatar_style: selectedAvatar.gravatar_style,
                icon_type: selectedAvatar.icon_type
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update avatar display
                updateAvatarDisplay(selectedAvatar, data.avatar_url);
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('avatarSelectionModal'));
                modal.hide();
                
                // Show success message
                showAlert('success', 'Avatar mis à jour avec succès.');
            } else {
                showAlert('danger', 'Erreur: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Une erreur est survenue.');
        });
    });
    
    // Function to update avatar display
    function updateAvatarDisplay(avatarData, avatarUrl) {
        const avatarContainer = document.getElementById('profile-avatar-container');
        const currentAvatar = avatarContainer.querySelector('img, div.rounded-circle');
        
        if (currentAvatar) {
            currentAvatar.remove();
        }
        
        let newAvatar;
        
        if (avatarData.avatar_type === 'gravatar' && avatarUrl) {
            newAvatar = document.createElement('img');
            newAvatar.src = avatarUrl;
            newAvatar.alt = '{{ $user->name }}';
            newAvatar.className = 'rounded-circle img-thumbnail';
            newAvatar.style = 'width: 60px; height: 60px; object-fit: cover;';
        } else if (avatarData.avatar_type === 'initial') {
            newAvatar = document.createElement('div');
            newAvatar.className = 'rounded-circle bg-primary text-white d-flex align-items-center justify-content-center';
            newAvatar.style = 'width: 60px; height: 60px; font-size: 24px;';
            newAvatar.innerHTML = '{{ strtoupper(substr($user->name, 0, 1)) }}';
        } else if (avatarData.avatar_type === 'icon') {
            newAvatar = document.createElement('div');
            newAvatar.className = 'rounded-circle bg-primary text-white d-flex align-items-center justify-content-center';
            newAvatar.style = 'width: 60px; height: 60px; font-size: 24px;';
            
            const icon = document.createElement('i');
            icon.className = 'bi bi-' + avatarData.icon_type + '-fill';
            newAvatar.appendChild(icon);
        }
        
        if (newAvatar) {
            avatarContainer.insertBefore(newAvatar, avatarContainer.firstChild);
        }
    }
    
    // Function to show alerts
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        const container = document.querySelector('.container-fluid');
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 150);
        }, 5000);
    }
    
    // Setup password toggles
    function setupPasswordToggle(buttonId, inputId) {
        const button = document.getElementById(buttonId);
        const input = document.getElementById(inputId);
        
        if (button && input) {
        const icon = button.querySelector('i');

        button.addEventListener('click', function() {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
        }
    }

    setupPasswordToggle('toggleCurrentPassword', 'current_password');
    setupPasswordToggle('toggleNewPassword', 'password');
    setupPasswordToggle('toggleConfirmPassword', 'password_confirmation');
});
</script>
@endpush 