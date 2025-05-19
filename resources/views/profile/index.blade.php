@extends('layouts.dashboard')

@section('title', 'Mon Profil - KLINKLIN')

@section('content')
<div class="container-fluid profile-page-v2 py-4">
    {{-- En-tête de la page (Titre général) --}}
    <div class="page-header mb-4">
        <h1 class="page-title display-5 fw-bolder text-klin-primary mb-1">Mon Profil</h1>
        <p class="page-subtitle text-muted fs-5">Gérez vos informations personnelles et paramètres de compte.</p>
    </div>

    {{-- Messages de statut et d'erreur --}}
    @if (session('status') === 'profile-updated')
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Votre profil a été mis à jour avec succès.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->userDeletion->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Une erreur est survenue lors de la tentative de suppression du compte. Veuillez vérifier votre mot de passe.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Section En-tête du Profil Utilisateur --}}
    <div class="card profile-header-card rounded-4 border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-auto text-center text-md-start mb-3 mb-md-0">
                    <div class="profile-avatar-wrapper-v2 mx-auto mx-md-0">
                        @if(isset($user->avatar_settings['avatar_type']) && $user->avatar_settings['avatar_type'] === 'gravatar')
                            <?php 
                                $style = $user->avatar_settings['gravatar_style'] ?? 'retro';
                                $gravatarUrl = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?s=200&d=' . $style . '&r=g';
                            ?>
                            <img src="{{ $gravatarUrl }}" alt="Avatar de {{ $user->name }}" class="profile-avatar-img-v2 rounded-circle">
                        @else
                            <div class="avatar-initials-circle-v2">
                                <span class="initials">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <a href="{{ route('profile.edit.avatar') }}" class="btn btn-sm btn-light rounded-circle edit-avatar-btn shadow-sm" title="Modifier l'avatar">
                            <i class="bi bi-camera-fill"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md">
                    <h2 class="profile-name-v2 h3 fw-bold mb-1">{{ $user->name }}</h2>
                    <p class="profile-email-v2 text-muted mb-2">{{ $user->email }}</p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-success-soft text-success-emphasis fw-medium"><i class="bi bi-person-check-fill me-1"></i>Actif</span>
                        @if($user->email_verified_at)
                            <span class="badge bg-primary-soft-klin text-primary-klin-emphasis fw-medium"><i class="bi bi-envelope-check-fill me-1"></i>Email vérifié</span>
                        @else
                            <span class="badge bg-warning-soft text-warning-emphasis fw-medium"><i class="bi bi-envelope-exclamation-fill me-1"></i>Email non vérifié</span>
                            <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none fw-medium">
                                    (Renvoyer email)
                                </button>
                            </form>
                        @endif
                    </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- Navigation par Onglets --}}
    <div class="card rounded-4 border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom p-0 profile-tabs-header">
            <ul class="nav nav-tabs nav-tabs-profile" id="profileTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="info-perso-tab" data-bs-toggle="tab" data-bs-target="#info-perso-content" type="button" role="tab" aria-controls="info-perso-content" aria-selected="true">
                        <i class="bi bi-person-lines-fill me-2"></i>Informations Personnelles
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security-content" type="button" role="tab" aria-controls="security-content" aria-selected="false">
                        <i class="bi bi-shield-lock-fill me-2"></i>Sécurité
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="login-history-tab" data-bs-toggle="tab" data-bs-target="#login-history-content" type="button" role="tab" aria-controls="login-history-content" aria-selected="false">
                        <i class="bi bi-clock-history me-2"></i>Activité du compte
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-danger" id="danger-zone-tab" data-bs-toggle="tab" data-bs-target="#danger-zone-content" type="button" role="tab" aria-controls="danger-zone-content" aria-selected="false">
                        <i class="bi bi-exclamation-octagon-fill me-2"></i>Zone Dangereuse
                    </button>
                </li>
            </ul>
                </div>
        <div class="card-body p-0">
            <div class="tab-content" id="profileTabContent">
                {{-- Onglet: Informations Personnelles --}}
                <div class="tab-pane fade show active p-4" id="info-perso-content" role="tabpanel" aria-labelledby="info-perso-tab">
                    @php
                        $profileItems = [
                            ['label' => 'Nom complet', 'value' => $user->name, 'route' => route('profile.edit.name')],
                            ['label' => 'Adresse email', 'value' => $user->email, 'route' => route('profile.edit.email')],
                            ['label' => 'Numéro de téléphone', 'value' => $user->phone ?? '<span class="text-muted fst-italic">Non renseigné</span>', 'route' => route('profile.edit.phone')],
                        ];
                    @endphp
                    @foreach($profileItems as $item)
                    <div class="row data-item-row-v2 py-3 align-items-center">
                        <div class="col-md-3"><span class="data-label-v2 fw-medium">{{ $item['label'] }}</span></div>
                        <div class="col-md-7"><span class="data-value-v2">{!! $item['value'] !!}</span></div>
                        <div class="col-md-2 text-md-end mt-2 mt-md-0">
                            <a href="{{ $item['route'] }}" class="btn btn-sm btn-outline-klin-primary rounded-pill edit-button-v2">
                                <i class="bi bi-pencil-fill me-1"></i> Modifier
                            </a>
                        </div>
                    </div>
                    @if(!$loop->last) <hr class="my-1"> @endif
                    @endforeach
                </div>
                
                {{-- Onglet: Sécurité --}}
                <div class="tab-pane fade p-4" id="security-content" role="tabpanel" aria-labelledby="security-tab">
                    <div class="row data-item-row-v2 py-3 align-items-center">
                        <div class="col-md-3"><span class="data-label-v2 fw-medium">Mot de passe</span></div>
                        <div class="col-md-7"><span class="data-value-v2">••••••••••••</span></div>
                        <div class="col-md-2 text-md-end mt-2 mt-md-0">
                            <a href="{{ route('profile.edit.password') }}" class="btn btn-sm btn-outline-klin-primary rounded-pill edit-button-v2">
                                <i class="bi bi-pencil-fill me-1"></i> Modifier
                            </a>
                        </div>
                    </div>
                    {{-- Autres options de sécurité ici --}}
                </div>
                
                {{-- Onglet: Dernières Connexions --}}
                <div class="tab-pane fade p-0" id="login-history-content" role="tabpanel" aria-labelledby="login-history-tab">
                     @if(isset($loginHistory) && count($loginHistory) > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover-rows-v2 mb-0">
                                <thead class="text-muted bg-light">
                                    <tr>
                                        <th class="py-3 px-4 fw-semibold">Date</th>
                                        <th class="py-3 px-4 fw-semibold">Adresse IP</th>
                                        <th class="py-3 px-4 fw-semibold">Appareil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loginHistory->take(10) as $login)
                                    <tr>
                                        <td class="py-3 px-4">{{ $login->login_at->isoFormat('DD MMM YY à HH[h]mm') }}</td>
                                        <td class="py-3 px-4">{{ $login->ip_address }}</td>
                                        <td class="py-3 px-4 text-truncate" style="max-width: 250px;" title="{{ $login->user_agent }}">{{ Str::limit($login->user_agent, 50) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($loginHistory->count() > 10)
                        <div class="p-3 text-center border-top">
                             <a href="{{ route('profile.login-history') }}" class="btn btn-sm btn-outline-klin-primary rounded-pill">
                                <i class="bi bi-list-ul me-1"></i> Voir tout l'historique ({{ $loginHistory->count() }})
                            </a>
                        </div>
                        @endif
                    @else
                        <p class="text-muted mb-0 p-4">Aucun historique de connexion disponible.</p>
                    @endif
                </div>
                
                {{-- Onglet: Zone Dangereuse --}}
                <div class="tab-pane fade p-4" id="danger-zone-content" role="tabpanel" aria-labelledby="danger-zone-tab">
                    <h4 class="text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Attention, zone sensible</h4>
                    <p class="text-muted mb-3">Les actions dans cette section peuvent avoir des conséquences permanentes sur votre compte. Procédez avec prudence.</p>
                    <hr class="my-4">
                    <div>
                        <h5 class="fw-semibold">Suppression du compte</h5>
                        <p class="text-muted mb-2">La suppression de votre compte est une action définitive et irréversible. Toutes vos données associées à KLINKLIN (profil, commandes, abonnements, etc.) seront perdues.</p>
                        <button class="btn btn-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="bi bi-trash3-fill me-1"></i> Supprimer mon compte KLINKLIN
                            </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Suppression Compte (inchangé par rapport à la version précédente, sauf peut-être le style) --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow-lg">
            <div class="modal-header bg-light border-bottom-0">
                <h5 class="modal-title fw-bold" id="deleteAccountModalLabel">Confirmer la suppression du compte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <i class="bi bi-exclamation-triangle-fill text-danger display-4"></i>
                </div>
                <p class="text-danger fw-bold text-center fs-5">Attention : Cette action est irréversible !</p>
                <p class="text-muted">Toutes vos données personnelles, historiques de commandes, abonnements et autres informations associées à votre compte seront définitivement supprimées.</p>
                <form method="post" action="{{ route('profile.destroy') }}" id="delete-account-form" class="mt-3">
                    @csrf
                    @method('delete')
                    <div class="mb-3">
                        <label for="password_delete_v2" class="form-label fw-medium">Veuillez entrer votre mot de passe pour confirmer :</label>
                        <input type="password" class="form-control form-control-lg rounded-2" id="password_delete_v2" name="password" required>
                        @error('password', 'userDeletion')
                            <div class="text-danger mt-1 ms-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" form="delete-account-form" class="btn btn-danger rounded-pill px-4">Supprimer définitivement</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if ($errors->userDeletion->has('password'))
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
        deleteModal.show();
    @endif

    // Activer l'onglet qui était actif avant un rechargement de page (par exemple après une modification)
    // Ou si on arrive sur la page avec un hash dans l'URL
    var hash = window.location.hash;
    if (hash) {
        var triggerEl = document.querySelector('.nav-tabs-profile button[data-bs-target="' + hash + '"]');
        if (triggerEl) {
            var tab = new bootstrap.Tab(triggerEl);
            tab.show();
            // Faire défiler jusqu'aux onglets pour une meilleure visibilité
            // document.querySelector('.profile-tabs-header').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Mettre à jour le hash de l'URL lors du changement d'onglet pour la persistance et le partage
    var tabTriggerList = [].slice.call(document.querySelectorAll('.nav-tabs-profile button[data-bs-toggle="tab"]'));
    tabTriggerList.forEach(function (tabTriggerEl) {
        tabTriggerEl.addEventListener('shown.bs.tab', function (event) {
            var newHash = event.target.getAttribute('data-bs-target');
            // history.replaceState(null, null, newHash); // Met à jour sans ajouter à l'historique
             // Optionnel: Pour une meilleure expérience, faire défiler vers le haut du contenu de l'onglet
            //  document.querySelector(newHash).scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
});
</script>
@endpush

@push('styles')
<style>
    :root {
        --bs-success-soft: rgba(var(--bs-success-rgb), 0.1);
        --bs-warning-soft: rgba(var(--bs-warning-rgb), 0.1);
        --bs-danger-soft: rgba(var(--bs-danger-rgb), 0.1);
        /* Assurez-vous que --primary-purple et --klin-primary-rgb sont définis globalement */
        /* --primary-purple: #4c2975; */
        /* --klin-primary-rgb: 76, 41, 117; */
        --bs-primary-soft-klin: rgba(var(--klin-primary-rgb, 76, 41, 117), 0.1);
        --text-primary-klin-emphasis: var(--primary-purple, #4c2975);
    }

    .profile-page-v2 .text-klin-primary { color: var(--primary-purple) !important; }
    .profile-page-v2 .btn-outline-klin-primary {
        border-color: var(--primary-purple);
        color: var(--primary-purple);
        transition: all 0.3s ease;
    }
    .profile-page-v2 .btn-outline-klin-primary:hover,
    .profile-page-v2 .btn-outline-klin-primary:focus {
        background-color: var(--primary-purple);
        color: white;
        box-shadow: 0 4px 12px rgba(var(--klin-primary-rgb, 76, 41, 117), 0.2);
    }
    .profile-page-v2 .badge.bg-primary-soft-klin { background-color: var(--bs-primary-soft-klin); }
    .profile-page-v2 .badge.text-primary-klin-emphasis { color: var(--text-primary-klin-emphasis); }
    .profile-page-v2 .badge.bg-success-soft { background-color: var(--bs-success-soft); }
    .profile-page-v2 .badge.bg-warning-soft { background-color: var(--bs-warning-soft); }

    /* En-tête du profil utilisateur */
    .profile-page-v2 .profile-header-card {
        /* background: linear-gradient(135deg, var(--bs-primary-soft-klin) 0%, #ffffff 100%); */
    }
    .profile-page-v2 .profile-avatar-wrapper-v2 {
        position: relative;
        width: 100px; /* Taille de l'avatar */
        height: 100px;
        display: inline-block; /* Pour que le mx-auto fonctionne */
    }
    .profile-page-v2 .profile-avatar-img-v2,
    .profile-page-v2 .avatar-initials-circle-v2 {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border: 4px solid white; /* Bordure pour détacher */
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    }
    .profile-page-v2 .avatar-initials-circle-v2 {
        background-color: var(--main-profile-icon-bg, var(--bs-primary-soft-klin));
        color: var(--main-profile-icon-color, var(--primary-purple));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.8rem;
        font-weight: 600;
        border-radius: 50%;
    }
    .profile-page-v2 .edit-avatar-btn {
        position: absolute;
        bottom: 0px;
        right: 0px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        border: 2px solid white;
    }
    .profile-page-v2 .profile-name-v2 { color: var(--heading-color); }
    .profile-page-v2 .profile-header-card .badge { padding: 0.5em 0.8em; font-size: 0.85rem; }

    /* Styles pour les onglets */
    .profile-page-v2 .profile-tabs-header {
        background-color: #fff; /* Ou var(--bs-gray-100) pour un léger contraste */
    }
    .profile-page-v2 .nav-tabs-profile {
        border-bottom: none; /* Supprimer la bordure par défaut des nav-tabs */
        padding: 0 0.5rem; /* Léger padding pour espacer du bord de la carte */
    }
    .profile-page-v2 .nav-tabs-profile .nav-item {
        margin-bottom: 0; /* Bootstrap peut ajouter -1px, on le retire */
    }
    .profile-page-v2 .nav-tabs-profile .nav-link {
        border: none;
        border-bottom: 3px solid transparent; /* Ligne de base pour l'inactif */
        padding: 1rem 1.25rem;
        font-weight: 600;
        color: var(--bs-gray-600, #6c757d);
        transition: color 0.2s ease, border-color 0.2s ease;
    }
    .profile-page-v2 .nav-tabs-profile .nav-link:hover {
        color: var(--primary-purple);
        border-bottom-color: var(--bs-gray-300, #dee2e6);
    }
    .profile-page-v2 .nav-tabs-profile .nav-link.active,
    .profile-page-v2 .nav-tabs-profile .nav-link.active:hover {
        color: var(--primary-purple);
        background-color: transparent; /* Pas de fond pour l'onglet actif */
        border-bottom: 3px solid var(--primary-purple);
    }
    .profile-page-v2 .nav-tabs-profile .nav-link i {
        margin-right: 0.5rem; /* Espace entre icône et texte */
        font-size: 1.1rem;
    }
    .profile-page-v2 .nav-tabs-profile .nav-link.text-danger,
    .profile-page-v2 .nav-tabs-profile .nav-link.text-danger.active,
    .profile-page-v2 .nav-tabs-profile .nav-link.text-danger:hover {
        color: var(--bs-danger) !important;
    }
    .profile-page-v2 .nav-tabs-profile .nav-link.text-danger.active {
        border-bottom-color: var(--bs-danger);
    }
     .profile-page-v2 .nav-tabs-profile .nav-link.text-danger:hover {
        border-bottom-color: var(--bs-danger-text-emphasis);
    }


    /* Style pour le contenu des onglets */
    .profile-page-v2 .tab-content {
        /* min-height: 300px; */ /* Optionnel: hauteur minimale pour le contenu des onglets */
    }
    .profile-page-v2 .data-item-row-v2 {
        /* transition: background-color 0.2s ease-in-out; */
    }
    /* .profile-page-v2 .data-item-row-v2:hover {
        background-color: var(--bs-gray-100, #f8f9fa_light);
    } */
    .profile-page-v2 .data-label-v2 { color: var(--bs-gray-700, #495057); }
    .profile-page-v2 .data-value-v2 { color: var(--bs-gray-900, #212529); font-weight: 500; }
    .profile-page-v2 .edit-button-v2 { padding: 0.25rem 0.75rem; font-size: 0.8rem; }

    /* Table des dernières connexions v2 */
    .profile-page-v2 .table-hover-rows-v2 tbody tr:hover {
        background-color: var(--bs-primary-soft-klin); /* Hover avec la couleur primaire douce */
    }
    .profile-page-v2 .table thead th {
        font-weight: 600;
        color: var(--bs-gray-600);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background-color: var(--bs-gray-100, #f8f9fa) !important; /* Fond pour les en-têtes de tableau */
    }
    .profile-page-v2 .table td {
        vertical-align: middle;
        font-size: 0.9rem;
        color: var(--bs-gray-700, #495057);
    }
    
    /* Ajustements responsifs */
    @media (max-width: 767.98px) {
        .profile-page-v2 .profile-header-card .row {
            text-align: center;
        }
        .profile-page-v2 .profile-avatar-wrapper-v2 {
            margin-bottom: 1rem;
        }
        .profile-page-v2 .nav-tabs-profile {
            flex-wrap: nowrap; /* Empêcher le retour à la ligne */
            overflow-x: auto; /* Permettre le défilement horizontal */
            overflow-y: hidden;
            padding-bottom: 1px; /* Pour cacher la scrollbar si seulement horizontale */
            -webkit-overflow-scrolling: touch; /* Défilement fluide sur iOS */
        }
        .profile-page-v2 .nav-tabs-profile .nav-item {
            white-space: nowrap; /* Empêcher le texte de l'onglet de se casser */
        }
        .profile-page-v2 .data-item-row-v2 .col-md-2.text-md-end {
            margin-top: 0.5rem;
            text-align: left !important;
        }
        .profile-page-v2 .edit-button-v2 {
            width: 100%;
        }
    }
    /* Masquer la scrollbar des onglets sur mobile si possible */
    .profile-page-v2 .nav-tabs-profile::-webkit-scrollbar { display: none; }
    .profile-page-v2 .nav-tabs-profile { -ms-overflow-style: none; scrollbar-width: none; }

</style>
@endpush 