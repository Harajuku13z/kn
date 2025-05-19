@extends('layouts.dashboard')

@section('title', 'Mes Abonnements - KLINKLIN')

@section('content')
<div class="dashboard-content subscriptions-page container">
    {{-- En-tête de la page avec animation --}}
    <div class="row mb-4 align-items-center fade-in">
        <div class="col-auto">
            <div class="notification-icon-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-journal-bookmark-fill text-klin-primary"></i>
            </div>
        </div>
        <div class="col">
            <h1 class="display-5 fw-bold text-klin-primary mb-1">Mes Abonnements</h1>
            <p class="text-muted fs-5 mb-0">Consultez vos formules, quotas et historique.</p>
        </div>
        <div class="col-auto text-end">
            <a href="{{ route('subscriptions.create') }}" class="btn btn-lg klin-btn rounded-pill shadow-sm hover-lift">
                <i class="bi bi-plus-circle-fill me-2"></i> Acheter une Formule
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm animate__animated animate__fadeIn" role="alert" style="border-left: 4px solid var(--klin-success, #198754);">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Section des quotas avec animation et design amélioré --}}
    <div class="mb-5 pt-3 fade-in">
        <h2 class="h4 fw-bold text-muted mb-4 text-center text-md-start">
            <i class="bi bi-speedometer2 me-2"></i>Votre Solde de Quota
        </h2>
        <div class="row gy-3 justify-content-center">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="quota-card d-flex align-items-center p-4 rounded-4 shadow-sm h-100 hover-lift" 
                     style="background-color: var(--klin-light-bg, #f8f5fc); border: 1px solid var(--klin-border-color, #e0d8e7);">
                    <i class="bi bi-graph-up-arrow fs-1 me-3 text-klin-primary"></i>
                    <div>
                        <h6 class="mb-1 text-muted">Total Disponible</h6>
                        <p class="fs-3 fw-bolder text-klin-primary mb-0">{{ number_format($totalQuota, 1) }} kg</p>
                    </div>
                </div>
                        </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="quota-card d-flex align-items-center p-4 rounded-4 shadow-sm h-100 hover-lift" 
                     style="background-color: var(--klin-accent-delivery, #e4f8f0); border: 1px solid var(--klin-accent-delivery-text, #006a4e);">
                    <i class="bi bi-wallet2 fs-1 me-3" style="color: var(--klin-accent-delivery-text, #006a4e);"></i>
                    <div>
                        <h6 class="mb-1" style="color: var(--klin-text-muted, #6c757d);">Payé</h6>
                        <p class="fs-3 fw-bolder mb-0" style="color: var(--klin-accent-delivery-text, #006a4e);">{{ number_format($paidQuota, 1) }} kg</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="quota-card d-flex align-items-center p-4 rounded-4 shadow-sm h-100 hover-lift" 
                     style="background-color: var(--klin-light-warning-bg, #fff8e1); border: 1px solid var(--klin-warning-text, #ffc107);">
                    <i class="bi bi-hourglass-split fs-1 me-3" style="color: var(--klin-warning-text, #ffc107);"></i>
                    <div>
                        <h6 class="mb-1" style="color: var(--klin-text-muted, #6c757d);">En Attente</h6>
                        <p class="fs-3 fw-bolder mb-0" style="color: var(--klin-warning-text, #ffc107);">{{ number_format($pendingQuota, 1) }} kg</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Section des formules actives avec design amélioré --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pt-4 border-top fade-in">
        <div class="mb-3 mb-md-0 text-center text-md-start">
            <h2 class="fw-bold text-klin-primary mb-1">
                <i class="bi bi-collection-fill me-2"></i> Vos Formules Actives
            </h2>
            <p class="text-muted mb-0">Retrouvez ici les détails de vos souscriptions en cours.</p>
        </div>
    </div>

    {{-- Abonnements Actifs avec animation et design amélioré --}}
    @if($activeSubscriptions->isNotEmpty())
        <div class="row gy-4 mb-5">
            @foreach($activeSubscriptions as $subscription)
                <div class="col-lg-6 col-md-12">
                    <div class="card subscription-card h-100 shadow-sm is-active-subscription hover-lift">
                        <div class="active-subscription-banner">
                            <i class="bi bi-star-fill me-1"></i> Formule Active
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title subscription-name mb-1 fs-4 fw-bold">
                                        <i class="bi bi-patch-check-fill me-2 subscription-name-icon"></i>
                                        {{ $subscription->subscriptionType->name ?? 'Formule Personnalisée' }}
                                    </h5>
                                    <p class="text-muted mb-0"><small>Acheté le {{ $subscription->purchase_date->format('d M Y') }}</small></p>
                        </div>
                            </div>
                            <div class="subscription-details-row">
                                <div class="subscription-details-col quota-display mb-0 text-center">
                                    <p class="subscription-quota-main fs-1 fw-bolder mb-2">
                                        {{ number_format($subscription->quota_purchased, 1) }} kg
                                    </p>
                            <div class="progress" style="height: 8px;">
                                        @php
                                            $usagePercentage = (($subscription->quota_purchased - $subscription->remaining_quota) / $subscription->quota_purchased) * 100;
                                        @endphp
                                        <div class="progress-bar bg-klin-primary" role="progressbar" 
                                             style="width: {{ $usagePercentage }}%" 
                                             aria-valuenow="{{ $usagePercentage }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                            </div>
                        </div>
                                    <small class="text-muted mt-2 d-block">Utilisation du quota</small>
                                </div>
                                <div class="subscription-details-col">
                                    <ul class="list-unstyled subscription-details-list mb-0">
                                        <li class="mb-2">
                                            <i class="bi bi-box-seam me-2 list-icon"></i>
                                            <span><strong>Quota restant :</strong> <span class="fw-bold fs-6 text-klin-primary">{{ number_format($subscription->remaining_quota, 1) }} kg</span></span>
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-credit-card me-2 list-icon"></i>
                                            <span><strong>Montant payé :</strong> {{ number_format($subscription->amount_paid) }} FCFA</span>
                                        </li>
                                        <li class="mb-2">
                                            <i class="bi bi-wallet2 me-2 list-icon"></i>
                                            <span><strong>Paiement :</strong> {{ $subscription->formatted_payment_method }}</span>
                                        </li>
                                        @if($subscription->subscriptionType && $subscription->subscriptionType->service_level)
                                        <li class="mb-2">
                                            <i class="bi bi-stars me-2 list-icon"></i>
                                            <span><strong>Niveau de service :</strong> 
                                                @if($subscription->subscriptionType->service_level == 'standard')
                                                    <span class="badge bg-secondary text-white">Standard</span>
                                                @elseif($subscription->subscriptionType->service_level == 'priority')
                                                    <span class="badge bg-info text-white">Prioritaire</span>
                                                @elseif($subscription->subscriptionType->service_level == 'express')
                                                    <span class="badge bg-warning text-white">Express</span>
                                                @else
                                                    <span class="badge bg-secondary text-white">{{ $subscription->subscriptionType->formatted_service_level }}</span>
                                                @endif
                                            </span>
                                        </li>
                                        @endif
                        @if($subscription->expiration_date)
                                            <li class="mb-2 text-danger">
                                                <i class="bi bi-calendar-x me-2 list-icon"></i>
                                                <span><strong>Expire le :</strong> {{ $subscription->expiration_date->format('d M Y') }}</span>
                                            </li>
                        @endif
                                    </ul>
                                </div>
                            </div>
                            
                            @if($subscription->subscriptionType && $subscription->subscriptionType->service_features && count($subscription->subscriptionType->service_features) > 0)
                            <div class="service-features mt-3 pt-3 border-top">
                                <p class="mb-2 fw-bold"><i class="bi bi-check-circle-fill me-2 text-klin-primary"></i>Caractéristiques incluses :</p>
                                <div class="row">
                                    @foreach($subscription->subscriptionType->service_features as $feature)
                                    <div class="col-md-6">
                                        <p class="small mb-1"><i class="bi bi-check me-2 text-success"></i>{{ $feature }}</p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <a href="{{ route('subscriptions.show', $subscription) }}" class="btn btn-outline-klin-primary w-100 fw-bold mt-3 rounded-pill hover-lift">
                                <i class="bi bi-eye-fill me-2"></i> Voir les détails
                            </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    {{-- État Vide avec animation --}}
    @if($activeSubscriptions->isEmpty() && $expiredSubscriptions->isEmpty())
        <div class="card custom-card-empty text-center shadow-sm animate__animated animate__fadeIn">
            <div class="card-body p-lg-5 p-4">
                <i class="bi bi-journal-bookmark-fill display-1 text-klin-primary opacity-50 mb-3"></i>
                <h4 class="fw-bold">Aucun abonnement pour le moment</h4>
                <p class="text-muted mb-4 fs-5">Découvrez nos formules flexibles et simplifiez-vous la vie !</p>
                <a href="{{ route('subscriptions.create') }}" class="btn klin-btn btn-lg rounded-pill hover-lift">
                    <i class="bi bi-plus-circle-fill me-2"></i> Découvrir les Formules
                </a>
            </div>
        </div>
    @endif

    {{-- Section historique avec design amélioré --}}
    @if($activeSubscriptions->isNotEmpty() || $expiredSubscriptions->isNotEmpty())
    <div id="historique" class="mt-5 pt-4 border-top fade-in">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-muted mb-1">
                    <i class="bi bi-clock-history me-2"></i> Historique d'achats
                </h2>
                <p class="text-muted mb-0">Retrouvez toutes vos transactions passées.</p>
                </div>
        </div>
        <div class="card shadow-sm rounded-4" style="overflow: hidden; background-color: #fff;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-responsive-stack" id="historyTable" style="font-size: 0.95rem;">
                    <thead style="background-color: var(--klin-light-bg, #f8f5fc);">
                        <tr>
                            <th scope="col" class="py-3 px-3">Date</th>
                            <th scope="col" class="py-3 px-3">Formule</th>
                            <th scope="col" class="py-3 px-3 text-end">Montant</th>
                            <th scope="col" class="py-3 px-3 text-center d-none d-lg-table-cell">Quota Acheté</th>
                            <th scope="col" class="py-3 px-3 text-center d-none d-lg-table-cell">Quota Utilisé</th>
                            <th scope="col" class="py-3 px-3 text-center">Quota Restant</th>
                            <th scope="col" class="py-3 px-3 text-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeSubscriptions->merge($expiredSubscriptions)->sortByDesc('purchase_date') as $subscription)
                        <tr class="hover-lift">
                            <td class="py-3 px-3" data-label="Date">{{ $subscription->purchase_date->format('d/m/Y') }}</td>
                            <td class="py-3 px-3 fw-medium" data-label="Formule">{{ $subscription->subscriptionType->name ?? '-' }}</td>
                            <td class="py-3 px-3 text-end" data-label="Montant">{{ number_format($subscription->amount_paid) }} <small>FCFA</small></td>
                            <td class="py-3 px-3 text-center d-none d-lg-table-cell" data-label="Quota Acheté">{{ number_format($subscription->quota_purchased, 1) }} <small>kg</small></td>
                            <td class="py-3 px-3 text-center d-none d-lg-table-cell" data-label="Quota Utilisé">{{ number_format($subscription->quota_purchased - $subscription->remaining_quota, 1) }} <small>kg</small></td>
                            <td class="py-3 px-3 text-center fw-bold" style="{{ $subscription->remaining_quota > 0 ? 'color: var(--klin-accent-delivery-text, #006a4e);' : 'color: var(--klin-text-muted, #6c757d);' }}" data-label="Quota Restant">{{ number_format($subscription->remaining_quota, 1) }} <small>kg</small></td>
                            <td class="py-3 px-3 text-center" data-label="Statut">
                                <span class="badge rounded-pill px-3 py-2 fw-normal {{ $subscription->is_active ? 'bg-klin-accent-delivery text-klin-accent-delivery-text' : 'bg-klin-accent-expired text-klin-accent-expired-text' }}">
                                    <i class="bi {{ $subscription->is_active ? 'bi-play-circle-fill' : 'bi-stop-circle-fill' }} me-1"></i>
                                    {{ $subscription->is_active ? 'En cours' : 'Expiré' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    :root {
        --klin-primary: #4A148C;
        --klin-primary-dark: #38006b;
        --klin-secondary: #f26d50;
        --klin-light-bg: #f8f5fc; 
        --klin-border-color: #4A148C;
        --klin-text-muted: #6c757d;
        --klin-accent-pickup: #e6f2ff;
        --klin-accent-pickup-text: #005cb9;
        --klin-accent-delivery: #e4f8f0;
        --klin-accent-delivery-text: #006a4e;
        --klin-light-warning-bg: #fff3cd;
        --klin-warning-text: #856404;
        --klin-accent-expired: #f8d7da;
        --klin-accent-expired-text: #721c24;
        --klin-success: #198754;
    }

    /* Animations et transitions */
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
    }

    /* Styles Généraux */
    .subscriptions-page .display-5 { 
        font-size: 2.25rem; 
        color: var(--klin-primary);
        margin-bottom: 0.5rem;
    }

    .text-klin-primary { color: var(--klin-primary) !important; }

    .klin-btn {
        background-color: var(--klin-primary) !important;
        border-color: var(--klin-primary) !important;
        color: white !important;
        transition: all 0.3s ease;
    }

    .klin-btn:hover {
        background-color: var(--klin-primary-dark) !important;
        border-color: var(--klin-primary-dark) !important;
        transform: translateY(-2px);
    }

    .btn-outline-klin-primary {
        color: var(--klin-primary);
        border-color: var(--klin-primary);
        transition: all 0.3s ease;
    }

    .btn-outline-klin-primary:hover {
        background-color: var(--klin-primary);
        color: white;
        transform: translateY(-2px);
    }

    /* Cartes de quota */
    .quota-card, .subscription-card {
        background: #fff;
        border: 1px solid var(--klin-border-color);
        border-left: 10px solid #4A148C;
        border-radius: 1rem;
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
        padding: 1.25rem !important;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }
    .quota-card:hover, .subscription-card:hover {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
        transform: translateY(-5px);
    }
    .subscription-card .card-body,
    .quota-card .card-body {
        padding: 0 !important;
    }

    .active-subscription-banner {
        position: absolute;
        top: 0.75rem;
        right: 1rem;
        left: auto;
        background-color: var(--klin-accent-delivery-text);
        color: white;
        padding: 0.3rem 0.9rem;
        border-radius: 0.75rem 0.75rem 0.75rem 0.25rem;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        z-index: 10;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .subscription-name-icon { 
        color: var(--klin-primary);
        opacity: 0.8;
    }

    .subscription-name { 
        color: var(--klin-accent-delivery-text);
    }

    .subscription-details-row {
        display: flex;
        gap: 1.5rem;
    }
    .subscription-details-col {
        flex: 1 1 0;
    }

    .subscription-details-col.quota-display {
        background: none !important;
        padding-left: 0 !important;
        color: inherit !important;
    }

    .subscription-card .card-title {
        font-size: 1.15rem !important;
        margin-bottom: 0.3rem !important;
    }

    .progress {
        background-color: var(--klin-border-color);
        border-radius: 1rem;
        overflow: hidden;
    }

    .progress-bar {
        background-color: var(--klin-accent-delivery-text);
        transition: width 0.6s ease;
    }

    .subscription-details-list {
        font-size: 0.93rem;
    }

    .subscription-details-list li {
        display: flex;
        align-items: flex-start;
        padding: 0.4rem 0;
    }

    .subscription-details-list .list-icon {
        color: var(--klin-primary);
        opacity: 0.7;
        margin-right: 0.75rem !important;
        font-size: 1.1em;
        margin-top: 0.15em;
        flex-shrink: 0;
    }

    .subscription-details-list .text-danger .list-icon { 
        color: var(--bs-danger);
        opacity: 1;
    }

    /* Badges de statut */
    .badge {
        padding: 0.5rem 1rem;
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .badge.bg-klin-accent-delivery {
        background-color: var(--klin-accent-delivery) !important;
        color: var(--klin-accent-delivery-text) !important;
    }

    .badge.bg-klin-accent-expired {
        background-color: var(--klin-accent-expired) !important;
        color: var(--klin-accent-expired-text) !important;
    }

    /* Tableau responsive */
    @media screen and (max-width: 767px) {
        .table-responsive-stack thead { 
            display: none; 
        }

        .table-responsive-stack tbody tr {
            display: block;
            border: 1px solid var(--klin-border-color);
            border-radius: 1rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background-color: #fff;
            box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
        }

        .table-responsive-stack tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0.5rem;
            border-bottom: 1px dotted #eee;
            text-align: right;
        }

        .table-responsive-stack tbody td:last-child { 
            border-bottom: none; 
        }

        .table-responsive-stack tbody td::before {
            content: attr(data-label);
            font-weight: bold;
            text-align: left;
            margin-right: 1rem;
            flex-shrink: 0;
            color: var(--klin-text-muted);
        }

        .table-responsive-stack tbody td .badge { 
            width: auto;
            margin-left: auto;
        }

        .table-responsive-stack tbody td[data-label="Montant"] { 
            justify-content: space-between;
        }

        .table-responsive-stack tbody td[data-label="Montant"]::before { 
            flex-grow: 0;
        }
    }

    @media (max-width: 991px) {
        .subscription-card {
            max-width: 100%;
        }
    }

    @media (max-width: 767px) {
        .subscription-details-row {
            display: block;
        }
        .subscription-details-col {
            width: 100%;
        }
    }

    .notification-icon-circle {
        width: 5rem;
        height: 5rem;
        background: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(74,20,140,0.10);
        border: 2px solid var(--klin-primary);
        margin-right: 1rem;
    }

    .notification-icon-circle i {
        color: var(--klin-primary);
        font-size: 2.8rem;
    }

    @media (max-width: 768px) {
        .notification-icon-circle {
            width: 4rem;
            height: 4rem;
            margin-right: 0.75rem;
        }
        .notification-icon-circle i {
            font-size: 2.2rem;
        }
        .display-5 {
            font-size: 1.75rem !important;
        }
        .fs-5 {
            font-size: 1rem !important;
        }
        .col-auto.text-end {
            margin-top: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Animation des cartes au scroll
    const observerOptions = {
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.subscription-card, .quota-card').forEach(card => {
        observer.observe(card);
    });

    // Initialisation de DataTables pour le tableau d'historique
    if (document.getElementById('historyTable')) {
        new DataTable('#historyTable', {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
            },
            pageLength: 5,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Tous"]],
            order: [[0, 'desc']],
            responsive: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
            pagingType: 'full_numbers',
            lengthChange: true,
            info: true,
            searching: true
        });
    }

    // Fonction pour déplacer les alertes dans le conteneur flottant
    function floatAlerts() {
        const alerts = document.querySelectorAll('.alert');
        const container = document.querySelector('.floating-notifications-container');
        if (container && alerts.length > 0) {
            alerts.forEach(alert => {
                container.appendChild(alert);
                setTimeout(() => {
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 1000);
                }, 4000); // 4 secondes d'affichage
            });
        }
    }
    floatAlerts();

    // Si AJAX navigation, refaire le flottement après chaque chargement
    document.addEventListener('ajaxContentLoaded', floatAlerts);
    });
</script>
@endpush