@extends('layouts.dashboard')

@section('title', 'Nouvel Abonnement - KLINKLIN')

@section('content')
<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-7 col-12">
            <h1 class="display-4 fw-bold mb-0" style="color: #4CB690;">Choisissez la formule qui vous convient</h1>
            <p class="lead text-muted mb-0">Des offres flexibles pour vous simplifier la vie au quotidien.</p>
        </div>
        <div class="col-md-5 col-12 text-md-end text-start mt-3 mt-md-0">
            @if(isset($totalQuota))
                <span class="fs-5 fw-bold" style="color: #004AAD;">Quota disponible : {{ number_format($totalQuota, 1) }} kg</span>
            @endif
        </div>
    </div>

    <div class="row justify-content-center g-4">
        @foreach($subscriptionTypes as $type)
        <div class="col-12 col-md-4 d-flex align-items-stretch" style="min-width: 320px; max-width: 370px;">
            <div class="card w-100 h-100 d-flex flex-column justify-content-between" style="border-radius: 20px; border: 2px solid #4CB690; box-shadow: none;">
                <div class="card-body p-4 d-flex flex-column">
                    @if($loop->first)
                    <div class="text-end mb-2">
                        <span class="badge rounded-pill px-3 py-2" style="background-color: #4CB690; color: white;">
                            <i class="bi bi-star-fill me-1"></i> Populaire
                        </span>
                    </div>
                    @endif
                    <h5 class="fw-bold mb-1" style="color: #222;">{{ $type->name }}</h5>
                    <p class="display-5 fw-bold mb-2" style="color: #222;">{{ number_format($type->price) }} FCFA <span class="fs-5 fw-normal">/ mois</span></p>
                    
                    @if($type->service_level)
                    <div class="mb-2">
                        <span class="me-1 fw-bold">Niveau de service:</span>
                        @if($type->service_level == 'standard')
                            <span class="badge bg-secondary">Standard</span>
                        @elseif($type->service_level == 'priority')
                            <span class="badge bg-info">Prioritaire</span>
                        @elseif($type->service_level == 'express')
                            <span class="badge bg-warning text-dark">Express</span>
                        @else
                            <span class="badge bg-secondary">{{ $type->formatted_service_level }}</span>
                        @endif
                    </div>
                    @endif
                    
                    <p class="text-muted mb-3">{{ $type->description }}</p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2 fs-5" style="color: #4CB690;"></i> {{ $type->quota }} kg de quota inclus
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-calendar-check-fill me-2 fs-5" style="color: #4CB690;"></i> Valide {{ $type->duration }} jours
                        </li>
                        
                        @if($type->service_features && count($type->service_features) > 0)
                            @foreach($type->service_features as $feature)
                            <li class="mb-2 d-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-2 fs-5" style="color: #4CB690;"></i> {{ $feature }}
                            </li>
                            @endforeach
                        @endif
                    </ul>
                    <a href="{{ route('subscriptions.payement.form', $type->id) }}?payment_method=cash" 
                       class="btn {{ $loop->first ? 'btn-success' : 'btn-outline-success' }} w-100 fw-bold py-2"
                       style="border-radius: 50px; font-size: 1.1rem;">
                        @if($loop->first)
                            Commencez avec Plus
                        @else
                            Souscrire
                        @endif
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="text-center mt-5">
        <a href="{{ route('subscriptions.index') }}" class="btn btn-outline-secondary" style="border-radius: 50px; padding: 10px 25px;">
            <i class="bi bi-arrow-left me-1"></i> Retour à Mes Abonnements
        </a>
    </div>
</div>

<style>
    @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css");
    .display-4, .display-5 { font-weight: 700 !important; }
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-5px); }
    .btn { transition: background-color 0.2s, color 0.2s, transform 0.1s; }
    .btn:hover { transform: scale(1.02); }
    .row.g-4 { gap: 2rem 0 !important; }
    @media (max-width: 991.98px) {
        .row.g-4 { gap: 1rem 0 !important; }
    }
    
    /* Card variations */
    .card:nth-child(3n+1) {
        background: #E9FBF2 !important;
        border-color: #4CB690 !important;
    }
    .card:nth-child(3n+2) {
        background: #E6F2FF !important;
        border-color: #0066CC !important;
    }
    .card:nth-child(3n+3) {
        background: #FFF3E0 !important;
        border-color: #FF9800 !important;
    }
    
    .card:nth-child(3n+2) i {
        color: #0066CC !important;
    }
    .card:nth-child(3n+3) i {
        color: #FF9800 !important;
    }
</style>
@endsection