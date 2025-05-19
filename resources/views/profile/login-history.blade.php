@extends('layouts.dashboard')

@section('title', 'Historique des connexions - KLINKLIN')

@section('content')
<div class="container-fluid profile-login-history-page">
    <div class="row mb-4">
        <div class="col">
            <a href="{{ route('profile.index') }}" class="btn btn-link text-decoration-none ps-0">
                <i class="bi bi-arrow-left me-1"></i> Retour au profil
            </a>
            <h1 class="display-5 fw-bold text-klin-primary mb-2">Historique de connexion</h1>
            <p class="text-muted fs-5">Consultez l'ensemble de vos activités de connexion</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-shield-check me-2 text-klin-primary"></i> Toutes vos connexions</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Nous suivons vos connexions pour assurer la sécurité de votre compte. Si vous remarquez une activité suspecte, changez immédiatement votre mot de passe.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    @if(count($loginHistory) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Appareil</th>
                                        <th>Adresse IP</th>
                                        <th>Localisation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loginHistory as $login)
                                    <tr>
                                        <td>{{ $login->login_at->format('d/m/Y H:i') }}</td>
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
                                        <td>{{ $login->location ?? 'Inconnue' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $loginHistory->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-clock-history text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3 mb-0">Aucun historique de connexion disponible.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-question-circle me-2 text-klin-primary"></i> Informations sur l'historique</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-shield-lock text-klin-primary fs-2"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="fw-bold mb-1">Sécurité renforcée</h6>
                                    <p class="text-muted mb-0">Nous vous informons de toute nouvelle connexion à votre compte.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-envelope-check text-klin-primary fs-2"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="fw-bold mb-1">Notifications par email</h6>
                                    <p class="text-muted mb-0">Un email est envoyé à chaque nouvelle connexion à votre compte.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-clock-history text-klin-primary fs-2"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="fw-bold mb-1">Historique complet</h6>
                                    <p class="text-muted mb-0">Vérifiez l'historique complet pour détecter toute activité suspecte.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .profile-login-history-page .text-klin-primary { color: var(--klin-primary) !important; }
    
    .profile-login-history-page .table th {
        font-weight: 600;
        background-color: var(--klin-light-bg);
    }
    
    .profile-login-history-page .pagination {
        --bs-pagination-active-bg: var(--klin-primary);
        --bs-pagination-active-border-color: var(--klin-primary);
    }
</style>
@endpush 