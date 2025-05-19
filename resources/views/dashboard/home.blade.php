@extends('layouts.dashboard')

@section('title', 'Tableau de bord')

@section('content')
<div class="dashboard-content">
    <div class="greeting-header mb-4"> 
        <div class="main-profile-icon-wrapper"> 
            @if(Auth::user()->profile_image)
                <img src="{{ asset('img/profil/' . Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            @else
                <i class="bi bi-person"></i> 
            @endif
        </div>
        <div>
            <h1>Bonjour {{ Auth::user()->name }} <span class="wave">👋</span></h1>
        </div>
    </div>
    
    @if(!Auth::user()->hasVerifiedEmail())
    <div class="alert alert-warning mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Votre email n'est pas vérifié.</strong>
        <form method="POST" action="{{ route('verification.send') }}" class="d-inline ms-2">
            @csrf
            <button type="submit" class="btn btn-link p-0 text-warning text-decoration-underline">
                Renvoyer le mail de vérification
            </button>
        </form>
    </div>
    @endif
    
    <p class="lead mb-5">
        Bienvenue sur votre tableau de bord KLIN KLIN, où vous pouvez gérer vos services en toute simplicité :
        planifier une collecte, suivre vos commandes et ajuster vos informations ou votre abonnement.
    </p>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card custom-card card-plan">
                <div class="card-icon-wrapper">
                    <i class="bi bi-calendar-plus-fill"></i>
                </div>
                <div class="card-body-content">
                    <h5 class="card-title">Planifier une collecte</h5>
                    <p class="card-text">Dites-nous quand passer, on vient chercher votre linge où que vous soyez !</p>
                </div>
                <a href="{{ route('orders.create') }}" class="btn btn-primary-custom">Nouvelle collecte <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card custom-card card-orders">
                <div class="card-icon-wrapper">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <div class="card-body-content">
                    <h5 class="card-title">Mes commandes</h5>
                    <p class="card-text">Visualiser vos commandes en cours</p>
                </div>
                <a href="{{ route('orders.index') }}" class="btn btn-primary-custom">Voir mes commandes <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card custom-card card-history">
                <div class="card-icon-wrapper">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="card-body-content">
                    <h5 class="card-title">Mon historique</h5>
                    <p class="card-text">Retrouvez toutes vos collectes passées en un clin d'œil.</p>
                </div>
                <a href="{{ route('history.index') }}?status=completed" class="btn btn-primary-custom">Voir mes historiques <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card custom-card card-activities">
                <div class="card-icon-wrapper">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="card-body-content">
                    <h5 class="card-title">Mes activités</h5>
                    <p class="card-text">Factures, collectes, paiements... tout est ici pour vous simplifier la vie.</p>
                </div>
                <a href="{{ route('activities.index') }}" class="btn btn-primary-custom">Voir mes abonnements <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>
@endsection 