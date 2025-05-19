@extends('layouts.dashboard')

@section('title', 'Nouvelle Commande Pressing - KLINKLIN')

@push('styles')
<style>
    :root {
        --klin-primary: #4A148C; /* Violet */
        --klin-primary-dark: #38006b;
        --klin-secondary: #f26d50; /* Orange/Corail */
        --klin-light-bg: #f8f5fc; /* Violet très clair */
        --klin-border-color: #e0d8e7; /* Bordure violette claire */
        --klin-text-muted: #6c757d;
        --klin-success: #198754; /* Vert Bootstrap pour succès */
        --klin-warning: #ffc107;
        --klin-danger: #dc3545;
        --klin-info: #0dcaf0;
        --klin-input-bg: #fff;
    }

    /* Styles Généraux de la Page */
    .order-creation-pressing-page .display-5 {
        font-size: 2rem; /* Ajustement taille titre principal */
        color: var(--klin-primary);
    }
    .order-creation-pressing-page .text-klin-primary { color: var(--klin-primary) !important; }

    .klin-btn {
        background-color: var(--klin-primary) !important;
        border-color: var(--klin-primary) !important;
        color: white !important;
        transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        box-shadow: 0 2px 5px rgba(74,20,140,.2);
    }
    .klin-btn:hover {
        background-color: var(--klin-primary-dark) !important;
        border-color: var(--klin-primary-dark) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(74,20,140,.3);
    }
    .klin-btn-outline {
        color: var(--klin-primary) !important;
        border-color: var(--klin-primary) !important;
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        transition: background-color 0.2s ease, color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
    }
    .klin-btn-outline:hover {
        background-color: var(--klin-primary) !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(74,20,140,.2);
    }

    /* Champs de Formulaire */
    .form-label { font-weight: 500; color: #333; margin-bottom: 0.3rem; font-size: 0.9rem;}
    .form-control, .form-select {
        border-radius: 0.5rem; /* Arrondi plus prononcé */
        border: 1px solid var(--klin-border-color);
        background-color: var(--klin-input-bg);
        padding: 0.75rem 1rem; /* Padding interne augmenté */
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--klin-primary);
        box-shadow: 0 0 0 0.25rem rgba(74, 20, 140, 0.25);
    }
    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label,
    .form-floating > .form-select ~ label {
        color: var(--klin-primary);
    }


    /* Barre de Progression */
    .progress-bar-custom {
        margin-bottom: 2.5rem !important;
        padding: 1rem 0;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.07);
    }
    .progress-bar-custom .step-custom {
        flex-basis: auto; /* Ajustement pour flexibilité */
        padding: 0 0.5rem;
        color: var(--klin-text-muted);
        font-size: 0.8rem;
        position: relative;
    }
    .progress-bar-custom .step-icon {
        width: 30px; height: 30px;
        border-radius: 50%;
        background-color: var(--klin-border-color); /* Fond gris clair */
        color: var(--klin-text-muted); /* Texte gris pour les chiffres non actifs */
        display: flex; justify-content: center; align-items: center;
        margin: 0 auto 6px auto;
        border: none; /* Pas de double bordure */
        font-weight: 700;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .progress-bar-custom .step-icon span { font-size: 0.85rem; }
    .progress-bar-custom .step-custom.active .step-icon {
        background-color: var(--klin-primary);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(74,20,140,.3);
    }
    .progress-bar-custom .step-custom.active .step-text { color: var(--klin-primary); font-weight: 700; }
    .progress-bar-custom .step-custom.completed .step-icon {
        background-color: var(--klin-success);
        border-color: var(--klin-success);
        color: white;
    }
    .progress-bar-custom .step-custom.completed .step-text { color: var(--klin-success); }
    .progress-bar-custom .progress-line-custom {
        height: 3px;
        background-color: var(--klin-border-color);
        margin-top: 15px; /* Aligner avec le centre des icônes */
        transition: background-color 0.3s ease;
        flex-grow: 1; /* S'assurer qu'elle remplit l'espace */
    }
    .progress-bar-custom .progress-line-custom.active { background-color: var(--klin-primary); }


    /* Conteneur principal du formulaire */
    .order-creation-pressing-page .card.shadow-sm {
        border-radius: 0.75rem;
        border: 1px solid var(--klin-border-color);
        box-shadow: 0 0.25rem 1rem rgba(74,20,140,.07)!important;
    }
    .order-creation-pressing-page .card.shadow-sm > .card-body {
        padding: 1.5rem 2rem; /* Plus de padding */
    }


    /* Style des étapes du formulaire */
    .form-step { display: none; }
    .form-step.active { display: block; animation: fadeIn 0.5s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .form-step h3 { /* Titres de chaque étape */
        font-weight: 600;
        color: var(--klin-primary);
        margin-bottom: 2rem !important; /* Plus d'espace */
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--klin-light-bg);
    }
    .form-step h3 .bi {
        margin-right: 0.5rem;
        font-size: 1.2em; /* Icônes de titre plus grandes */
    }


    /* Cartes de Sélection de Pressing (Étape 0) */
    .pressing-card {
        border: 1px solid var(--klin-border-color);
        border-radius: 15px;
        transition: all 0.3s ease-in-out;
        cursor: pointer;
        margin-bottom: 20px;
        overflow: hidden;
        background-color: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        position: relative;
    }
    .pressing-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 20px rgba(74,20,140,0.15);
        border-color: var(--klin-primary);
    }
    .pressing-card.selected {
        border: 2px solid var(--klin-primary);
        box-shadow: 0 8px 25px rgba(74, 20, 140, 0.25);
        background-color: var(--klin-light-bg);
    }
    .pressing-card::after {
        content: '';
        position: absolute;
        top: 12px;
        right: 12px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background-color: var(--klin-light-bg);
        border: 1px solid var(--klin-border-color);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .pressing-card.selected::after {
        content: '✓';
        opacity: 1;
        background-color: var(--klin-primary);
        color: white;
        border-color: var(--klin-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    .pressing-card .card-img-container {
        height: 120px;
        background: linear-gradient(45deg, var(--klin-light-bg), #f0f0f0);
        position: relative;
        overflow: hidden;
    }
    .pressing-card .card-img-top {
        height: 100%;
        width: 100%;
        object-fit: cover;
    }
    .pressing-card .default-banner-svg {
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f7fa;
    }
    .pressing-card .default-banner-svg svg {
        height: 100%;
        width: 100%;
    }
    .pressing-card .card-logo-wrapper {
        position: absolute;
        bottom: -25px;
        left: 15px;
        background: white;
        border-radius: 50%;
        padding: 3px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        z-index: 1;
        border: 2px solid white;
    }
    .pressing-card .card-logo {
        width: 60px;
        height: 60px;
        object-fit: contain;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .pressing-card .card-body {
        padding-top: 35px;
        padding-left: 15px;
        padding-right: 15px;
        padding-bottom: 15px;
    }
    .pressing-card .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--klin-primary);
        margin-bottom: 0.25rem;
    }
    .pressing-card.selected .card-title {
        color: var(--klin-primary-dark);
    }
    .pressing-card .card-text i {
        color: var(--klin-primary);
    }
    .pressing-features {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }
    .pressing-feature {
        font-size: 0.75rem;
        background: var(--klin-border-color);
        padding: 3px 10px;
        border-radius: 20px;
        color: var(--klin-text-muted);
        transition: all 0.3s ease;
    }
    .pressing-card:hover .pressing-feature {
        background-color: rgba(74,20,140,0.1);
    }
    .pressing-card.selected .pressing-feature {
        background-color: var(--klin-primary);
        color: white;
    }
    .pressing-select-btn {
        opacity: 0;
        transition: opacity 0.3s ease;
        margin-top: 10px;
    }
    .pressing-card:hover .pressing-select-btn,
    .pressing-card.selected .pressing-select-btn {
        opacity: 1;
    }
    .pressing-card .select-pressing {
        font-size: 0.85rem;
        padding: 0.4rem 1rem;
        transition: all 0.3s ease;
    }

    /* Étapes 1 & 2 : Adresses et Dates */
    #selected_pressing_display {
        font-weight: 500; color: var(--klin-primary); padding: 0.75rem 1rem;
    }

    /* Étape 3 : Sélection des services */
    .category-tab {
        padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 0.5rem;
        cursor: pointer; transition: all 0.2s ease; border: 1px solid transparent;
    }
    .category-tab:hover { background-color: var(--klin-light-bg); border-color: var(--klin-border-color); }
    .category-tab.active {
        background-color: var(--klin-primary) !important; color: white !important;
        font-weight: 600; box-shadow: 0 2px 5px rgba(74,20,140,.2);
    }
    .category-tab.active .icon { color: white !important; }
    .category-tab .icon { font-size: 1.2rem; color: var(--klin-primary); margin-right: 0.75rem; }

    #servicesContainer .service-item .card {
        border: 1px solid var(--klin-border-color); border-radius: 0.5rem;
        transition: all 0.2s ease; background-color: #fff; margin-bottom: 1rem;
    }
    #servicesContainer .service-item .card:hover {
        transform: translateY(-2px); box-shadow: 0 0.25rem 1rem rgba(74,20,140,.1);
        border-color: var(--klin-primary);
    }
    #servicesContainer .card-title { font-size: 0.95rem; font-weight: 600; color: var(--klin-primary); margin-bottom: 0.25rem;}
    #servicesContainer .card-text small { font-size: 0.8rem; }
    #servicesContainer .badge.bg-primary { background-color: var(--klin-primary) !important; }
    #servicesContainer .quantity-selector input {
        border-left: none; border-right: none; border-radius:0;
        max-width: 50px; font-weight:500;
    }
    #servicesContainer .quantity-selector .btn {
        border-color: var(--klin-border-color); background-color: #fff; color: var(--klin-primary);
    }
    #servicesContainer .quantity-selector .btn:hover { background-color: var(--klin-light-bg); }
    #servicesContainer .sticky-top { z-index: 100; } /* Pour que le total reste visible */
    #servicesContainer .sticky-top .card { border: 1px solid var(--klin-primary); }

    /* Étape 4 : Récapitulatif */
    #step-4 .card {
        border: 1px solid var(--klin-border-color);
        border-radius: 0.5rem;
        margin-bottom: 1rem !important;
        background-color: #fff;
    }
    #step-4 .card-header {
        background-color: var(--klin-light-bg);
        border-bottom: 1px solid var(--klin-border-color);
        padding: 0.75rem 1.25rem;
    }
    #step-4 .card-header h5 { font-weight: 600; color: var(--klin-primary); font-size: 1rem; }
    #step-4 #summary-items td, #step-4 #summary-items th { padding: 0.6rem; font-size:0.85rem; }
    #step-4 #summary-total { font-size: 1.1rem; color: var(--klin-primary); font-weight: 700; }
    #step-4 .table-striped tbody tr:nth-of-type(odd) { background-color: rgba(74,20,140,.03); }


    /* Étape 5 : Paiement */
    #step-5 .form-check-label {
        display: flex; flex-direction: column; align-items: flex-start;
        font-size: 0.95rem; padding: 1rem; border: 1px solid var(--klin-border-color);
        border-radius: 0.5rem; cursor: pointer; transition: all 0.2s ease;
        background-color: #fff;
    }
    #step-5 .form-check-label:hover { background-color: var(--klin-light-bg); }
    #step-5 .form-check-input:checked + .form-check-label {
        background-color: var(--klin-light-bg);
        border-color: var(--klin-primary);
        color: var(--klin-primary);
        box-shadow: 0 0 10px rgba(74,20,140,.1);
    }
    #step-5 .form-check-input { display: none; } /* Cache le radio bouton natif */
    #step-5 .form-check-label .bi {
        font-size: 1.3rem; color: var(--klin-primary); margin-right: 0.5rem;
        margin-bottom: 0.25rem;
    }
     #step-5 .form-check-label strong { font-size: 1.05rem; }

    /* Alertes */
    .alert { border-radius: 0.5rem; padding: 1rem; }
    .alert-danger { background-color: #f8d7da; border-color: #f5c2c7; color: #721c24; }
    .alert-danger .alert-heading { color: inherit; }
    .alert-info { background-color: #cff4fc; border-color: #b6effb; color: #055160; }

    /* Style pour l'étape 5 amélioré */
    .hover-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    input[type="radio"]:checked + label .hover-card {
        border-color: var(--klin-primary);
        background-color: var(--klin-light-bg);
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(74,20,140,0.15) !important;
    }
    
    label.form-check-label {
        cursor: pointer;
    }
    
    input[type="radio"]:disabled + label {
        cursor: not-allowed;
    }
    
    #step-5 .form-check-input {
        position: absolute;
        opacity: 0;
    }
    
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(74,20,140,0.1) !important;
    }
    
</style>
@endpush

@section('content')
<div class="container-fluid order-creation-pressing-page py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-auto">
             <div style="background-color: var(--klin-light-bg); border-radius: 50%; padding: 0.6rem; display: flex; align-items: center; justify-content: center; width: 50px; height: 50px; box-shadow: 0 3px 10px rgba(74,20,140,.1);">
                <i class="bi bi-scissors fs-3 text-klin-primary"></i>
            </div>
        </div>
        <div class="col">
            <h1 class="display-5 fw-bold text-klin-primary">Nouvelle Commande Pressing</h1>
            <p class="text-muted fs-6 mb-0">Suivez les étapes pour créer votre commande de service pressing.</p>
        </div>
    </div>

    <!-- Ajout du token CSRF pour les requêtes AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Préchargement des données importantes -->
    <script>
        // Variable globale pour stocker tous les frais de livraison
        window.deliveryFeesData = @json(\App\Models\DeliveryFee::with('city')->where('is_active', true)->get());
        
        // Précharger les bons de livraison disponibles
        window.availableVouchers = @json(\App\Models\DeliveryVoucher::getAvailableForUser(Auth::id()));
        
        // Précharger les codes promo disponibles
        window.availableCoupons = @json($availableCoupons ?? []);
        
        // Variable pour stocker le coupon actif
        window.currentCoupon = null;
    </script>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-octagon-fill me-2"></i>
            <strong>Erreur :</strong> {!! nl2br(e(session('error'))) !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                <div>
                    <h5 class="alert-heading fw-bold mb-1">Erreurs de validation</h5>
                    <ul class="mb-0 ps-3" style="font-size: 0.9rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="top: 50%; transform: translateY(-50%);"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="progress-bar-custom d-flex justify-content-around align-items-center">
                <div class="step-custom text-center active" data-step="0">
                    <div class="step-icon"><span><i class="bi bi-shop"></i></span></div>
                    <div class="step-text">Pressing</div>
                </div>
                <div class="progress-line-custom"></div>
                <div class="step-custom text-center" data-step="1">
                    <div class="step-icon"><span><i class="bi bi-geo-alt-fill"></i></span></div>
                    <div class="step-text">Collecte</div>
                </div>
                <div class="progress-line-custom"></div>
                <div class="step-custom text-center" data-step="2">
                    <div class="step-icon"><span><i class="bi bi-truck"></i></span></div>
                    <div class="step-text">Livraison</div>
                </div>
                <div class="progress-line-custom"></div>
                <div class="step-custom text-center" data-step="3">
                    <div class="step-icon"><span><i class="bi bi-card-checklist"></i></span></div>
                    <div class="step-text">Services</div>
                </div>
                <div class="progress-line-custom"></div>
                <div class="step-custom text-center" data-step="4">
                    <div class="step-icon"><span><i class="bi bi-receipt"></i></span></div>
                    <div class="step-text">Récap.</div>
                </div>
                <div class="progress-line-custom"></div>
                <div class="step-custom text-center" data-step="5">
                     <div class="step-icon"><span><i class="bi bi-credit-card-fill"></i></span></div>
                    <div class="step-text">Paiement</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-2">
        <div class="card-body p-lg-4 p-3">
            <form id="orderForm" action="{{ route('orders.store') }}" method="POST">
                @csrf
                <input type="hidden" name="form_submitted" value="true">
                <input type="hidden" name="order_type" value="pressing">
                <input type="hidden" name="pressing_id" id="selected_pressing_id_field" value="{{ $pressingId ?? request()->route('id') ?? old('pressing_id') }}">

                <div class="form-step active" id="step-0">
                    <h3 class="text-klin-primary"><i class="bi bi-shop"></i> Choisissez votre pressing préféré</h3>
                    <p class="text-muted mb-4">Sélectionnez le pressing qui prendra en charge votre commande.</p>
                    <div class="row g-3">
                        @forelse($pressings as $pressing)
                        <div class="col-md-6 col-lg-4 d-flex">
                            <div class="pressing-card card w-100 {{ (isset($pressingId) && $pressingId == $pressing->id) || old('pressing_id') == $pressing->id ? 'selected' : '' }}" data-pressing-id="{{ $pressing->id }}">
                                <div class="card-img-container">
                                    @if($pressing->banner_image_url)
                                        <img src="{{ $pressing->banner_image_url }}" class="card-img-top" alt="{{ $pressing->name }}" onerror="this.src='{{ asset('images/pressing-banner-default.jpg') }}'; this.onerror='';">
                                    @else
                                        <div class="default-banner-svg">
                                            <svg width="100%" height="100%" viewBox="0 0 800 300" xmlns="http://www.w3.org/2000/svg">
                                                <defs>
                                                    <linearGradient id="grad-{{ $pressing->id }}" x1="0%" y1="0%" x2="100%" y2="100%">
                                                        <stop offset="0%" stop-color="#f8f5fc" />
                                                        <stop offset="100%" stop-color="#e0d8e7" />
                                                    </linearGradient>
                                                    <pattern id="pattern-{{ $pressing->id }}" width="40" height="40" patternUnits="userSpaceOnUse" patternTransform="rotate(45)">
                                                        <rect width="100%" height="100%" fill="url(#grad-{{ $pressing->id }})" />
                                                        <circle cx="20" cy="20" r="3" fill="#4A148C" fill-opacity="0.1" />
                                                    </pattern>
                                                </defs>
                                                <rect width="100%" height="100%" fill="url(#pattern-{{ $pressing->id }})" />
                                                <g transform="translate(20, 70)">
                                                    <path d="M10,10 L40,10 L40,60 C40,70 30,80 20,80 L10,80 Z" fill="#4A148C" fill-opacity="0.15" />
                                                    <path d="M60,10 L120,10 C130,10 140,20 140,30 L140,60 C140,70 130,80 120,80 L60,80 Z" fill="#4A148C" fill-opacity="0.2" />
                                                </g>
                                                <g transform="translate(600, 40)">
                                                    <circle cx="30" cy="30" r="25" fill="#4A148C" fill-opacity="0.15" />
                                                    <circle cx="80" cy="30" r="15" fill="#4A148C" fill-opacity="0.2" />
                                                </g>
                                                <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="Arial, sans-serif" font-size="24" fill="#4A148C" fill-opacity="0.3">KLINKLIN Pressing</text>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="card-logo-wrapper">
                                        @if($pressing->logo_url)
                                            <img src="{{ $pressing->logo_url }}" class="card-logo" alt="Logo {{ $pressing->name }}" onerror="this.onerror=''; this.parentElement.innerHTML = '<div class=\'card-logo\'><i class=\'bi bi-shop fs-3 text-klin-primary\'></i></div>';">
                                        @else
                                            <div class="card-logo">
                                                <i class="bi bi-shop fs-3 text-klin-primary"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $pressing->name }}</h5>
                                    <p class="card-text small text-muted mb-2"><i class="bi bi-geo-alt-fill me-1"></i>{{ $pressing->address }}</p>
                                    <div class="pressing-features mb-3">
                                        @if($pressing->is_express)
                                            <span class="pressing-feature"><i class="bi bi-lightning-fill me-1"></i> Express</span>
                                        @endif
                                        @if($pressing->eco_friendly)
                                            <span class="pressing-feature"><i class="bi bi-flower1 me-1"></i> Éco-responsable</span>
                                        @endif
                                        @if($pressing->has_delivery)
                                            <span class="pressing-feature"><i class="bi bi-truck me-1"></i> Livraison</span>
                                        @endif
                                    </div>
                                    <div class="text-start pressing-select-btn">
                                        <a href="{{ route('orders.create.pressing.show', $pressing->id) }}" class="btn btn-sm klin-btn select-pressing-btn" data-pressing-id="{{ $pressing->id }}">
                                            <i class="bi bi-check-circle-fill me-1"></i> Sélectionner
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-warning text-center">Aucun pressing disponible pour le moment.</div>
                        </div>
                        @endforelse
                    </div>
                     @error('pressing_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="form-step" id="step-1">
                    <h3 class="text-klin-primary"><i class="bi bi-geo-alt-fill"></i> Adresse et date de collecte</h3>
                    <div class="mb-3 form-floating">
                        <input type="text" class="form-control bg-light" id="selected_pressing_display" readonly value="{{ (isset($pressingId) && $pressings->where('id', $pressingId)->first()) ? $pressings->where('id', $pressingId)->first()->name . ' - ' . $pressings->where('id', $pressingId)->first()->address : 'Veuillez sélectionner un pressing à l\'étape précédente' }}">
                        <label for="selected_pressing_display">Pressing sélectionné</label>
                    </div>

                    <div class="mb-4">
                        <label for="collection_address_id" class="form-label">Adresse de collecte <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center">
                            <select class="form-select" name="collection_address_id" id="collection_address_id" required>
                                <option value="">Sélectionnez une adresse</option>
                                @foreach($addresses as $address)
                                    <option value="{{ $address->id }}" {{ old('collection_address_id', $tempCart['collection_address_id'] ?? '') == $address->id ? 'selected' : '' }}>
                                        {{ $address->name }} - {{ $address->address }}, {{ $address->neighborhood }}
                                    </option>
                                @endforeach
                            </select>
                            <a href="#" class="ms-2 btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#deliveryFeesModal" title="Voir les frais de livraison">
                                <i class="bi bi-info-circle"></i>
                            </a>
                        </div>
                        <div class="form-text mt-1" id="collection-address-fee-info" style="display: none;"></div>
                        <div class="form-text mt-2">
                            <a href="{{ route('addresses.create') }}" target="_blank" class="btn btn-sm btn-outline-klin me-2"><i class="bi bi-plus-circle"></i> Ajouter une nouvelle adresse</a>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control @error('collection_date') is-invalid @enderror" id="collection_date" name="collection_date" value="{{ old('collection_date', $tempCart['collection_date'] ?? now()->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}" required placeholder="Date">
                                <label for="collection_date">Date de collecte <span class="text-danger">*</span></label>
                                @error('collection_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select @error('collection_time_slot') is-invalid @enderror" id="collection_time_slot" name="collection_time_slot" required aria-label="Créneau horaire">
                                    <option value="">Sélectionner un créneau...</option>
                                    @foreach($timeSlots as $value => $label)
                                        <option value="{{ $value }}" @if(old('collection_time_slot', $tempCart['collection_time_slot'] ?? '') == $value) selected @endif>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <label for="collection_time_slot">Créneau horaire <span class="text-danger">*</span></label>
                                @error('collection_time_slot') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-step" id="step-2">
                    <h3 class="text-klin-primary"><i class="bi bi-truck"></i> Adresse et date de livraison</h3>
                    <div class="form-check mb-3 ps-0">
                        <input class="form-check-input ms-1 me-2" type="checkbox" id="same_address_for_delivery_step2" name="same_address_for_delivery" @if(old('same_address_for_delivery', $tempCart['same_address_for_delivery'] ?? false)) checked @endif style="width:1.2em; height:1.2em;">
                        <label class="form-check-label" for="same_address_for_delivery_step2" style="font-weight:500;">
                            Utiliser la même adresse que pour la collecte
                        </label>
                    </div>

                    <div id="delivery-address-fields" class="form-floating mb-3" @if(old('same_address_for_delivery', $tempCart['same_address_for_delivery'] ?? false)) style="display: none;" @endif>
                        <div class="d-flex align-items-center">
                            <select class="form-select @error('delivery_address_id') is-invalid @enderror" id="delivery_address_id" name="delivery_address_id" @if(!old('same_address_for_delivery', $tempCart['same_address_for_delivery'] ?? false)) required @endif aria-label="Adresse de livraison">
                                <option value="">Sélectionner une adresse...</option>
                                @foreach($addresses as $address)
                                    <option value="{{ $address->id }}" @if(old('delivery_address_id', $tempCart['delivery_address_id'] ?? '') == $address->id) selected @endif data-full-address="{{ $address->name }} - {{ $address->address }}, {{ $address->neighborhood }}">
                                        {{ $address->name }} - {{ $address->address }}, {{ $address->neighborhood }}
                                    </option>
                                @endforeach
                            </select>
                            <a href="#" class="ms-2 btn btn-sm btn-outline-primary" style="height: fit-content;" data-bs-toggle="modal" data-bs-target="#deliveryFeesModal" title="Voir les frais de livraison">
                                <i class="bi bi-info-circle"></i>
                            </a>
                        </div>
                        <label for="delivery_address_id">Adresse de livraison <span class="text-danger">*</span></label>
                        <div class="form-text mt-1" id="delivery-address-fee-info" style="display: none;"></div>
                        @error('delivery_address_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                           <div class="form-floating">
                                <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" id="delivery_date" name="delivery_date" value="{{ old('delivery_date', $tempCart['delivery_date'] ?? '') }}" required placeholder="Date">
                                <label for="delivery_date">Date de livraison <span class="text-danger">*</span></label>
                                <small class="text-muted ps-1">Date minimum : le lendemain de la collecte.</small>
                                @error('delivery_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select @error('delivery_time_slot') is-invalid @enderror" id="delivery_time_slot" name="delivery_time_slot" required aria-label="Créneau horaire">
                                    <option value="">Sélectionner un créneau...</option>
                                    @foreach($timeSlots as $value => $label)
                                        <option value="{{ $value }}" @if(old('delivery_time_slot', $tempCart['delivery_time_slot'] ?? '') == $value) selected @endif>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <label for="delivery_time_slot">Créneau horaire <span class="text-danger">*</span></label>
                                @error('delivery_time_slot') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mt-3">
                        <textarea class="form-control @error('delivery_notes') is-invalid @enderror" id="delivery_notes" name="delivery_notes" rows="3" placeholder="Instructions de livraison" style="height: 100px">{{ old('delivery_notes', $tempCart['delivery_notes'] ?? '') }}</textarea>
                        <label for="delivery_notes">Instructions de livraison <small class="text-muted">(facultatif)</small></label>
                        @error('delivery_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-step" id="step-3">
                    <h3 class="mb-2"><i class="bi bi-card-checklist"></i> Sélection des services de pressing</h3>
                    <p class="text-muted mb-4">Sélectionnez les vêtements et services pour votre commande pressing.</p>

                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="sticky-top" style="top: 20px;">
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-header bg-white">
                                        <strong>Catégories</strong>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="list-group list-group-flush" id="categories-list">
                                            <a href="#" class="list-group-item list-group-item-action category-tab active" data-category="all">
                                                <i class="bi bi-grid-fill icon"></i> Tous les services
                                            </a>
                                            <!-- Les catégories seront remplies via JavaScript -->
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title">Montant estimé</h5>
                                        <div class="d-flex align-items-baseline mt-3">
                                            <span class="fs-4 fw-bold text-klin-primary" id="estimatedPriceDisplay">0</span>
                                            <span class="ms-1">FCFA</span>
                                        </div>
                                        <small class="text-muted">Frais de livraison inclus</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row" id="servicesContainer">
                                <!-- Les services seront remplis via JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-step" id="step-4">
                    <h3 class="text-klin-primary"><i class="bi bi-receipt"></i> Récapitulatif de votre commande</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header"><h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Commande & Pressing</h5></div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>Type :</strong> <span class="badge rounded-pill" style="background-color: var(--klin-secondary); color:white; font-size: 0.9rem;">Commande Pressing</span></p>
                                    <p class="mb-0"><strong>Pressing :</strong> <span id="summary-pressing-name" class="fw-500">Chargement...</span></p>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header"><h5 class="mb-0"><i class="bi bi-calendar3-week me-2"></i>Planification</h5></div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                            <p class="mb-1"><strong>Collecte :</strong></p>
                                            <div id="summary-collection-date" class="small">Date à définir</div>
                                            <div id="summary-collection-time" class="small fw-500">Créneau à définir</div>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-1"><strong>Livraison :</strong></p>
                                            <div id="summary-delivery-date" class="small">Date à définir</div>
                                            <div id="summary-delivery-time" class="small fw-500">Créneau à définir</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header"><h5 class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i>Adresse de collecte</h5></div>
                                <div class="card-body">
                                    <div id="summary-collection-address" class="small">À définir</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                             <div class="card h-100">
                                <div class="card-header"><h5 class="mb-0"><i class="bi bi-truck me-2"></i>Adresse de livraison</h5></div>
                                <div class="card-body">
                                    <div id="summary-delivery-address" class="small">À définir</div>
                                     @if(isset($tempCart['delivery_notes']) && !empty($tempCart['delivery_notes']))
                                        <p class="mt-2 mb-0 small text-muted"><strong>Notes :</strong> <span id="summary-delivery-notes">{{ $tempCart['delivery_notes'] }}</span></p>
                                    @else
                                        <p class="mt-2 mb-0 small text-muted" id="summary-delivery-notes-container" style="display:none;"><strong>Notes :</strong> <span id="summary-delivery-notes"></span></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header"><h5 class="mb-0"><i class="bi bi-card-checklist me-2"></i>Services sélectionnés</h5></div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-3">Service</th>
                                            <th class="text-center">Qté</th>
                                            <th class="text-center">P.U.</th>
                                            <th class="text-end pe-3">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="summary-items">
                                        {{-- Rempli par JS --}}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end border-0 pt-1 ps-3">Sous-total :</td>
                                            <td class="text-end border-0 pt-1 pe-3" id="summary-subtotal">0 FCFA</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end border-0 pt-1 ps-3">Frais de livraison :</td>
                                            <td class="text-end border-0 pt-1 pe-3" id="summary-delivery-fees">0 FCFA</td>
                                        </tr>
                                        <tr id="summary-discount-row" style="display: none;">
                                            <td colspan="3" class="text-end border-0 pt-1 ps-3 text-success">Réduction :</td>
                                            <td class="text-end border-0 pt-1 pe-3 text-success" id="summary-discount">-0 FCFA</td>
                                        </tr>
                                        <tr class="text-klin-primary">
                                            <td colspan="3" class="text-end border-0 pt-2 ps-3 fw-bold">TOTAL :</td>
                                            <td class="text-end border-0 pt-2 pe-3 fw-bold fs-4" id="summary-total">0 FCFA</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-step" id="step-5">
                    <h3 class="text-klin-primary mb-4"><i class="bi bi-credit-card-fill"></i> Mode de paiement</h3>
                    
                    <div class="row">
                        <!-- Colonne gauche: Options de paiement -->
                        <div class="col-md-6">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-credit-card me-2 text-primary"></i>Options de paiement</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="form-check p-3 border rounded @if(old('payment_method') == 'cash') border-primary bg-light @endif">
                                            <input class="form-check-input" type="radio" name="payment_method" id="payment-cash" value="cash" checked>
                                            <label class="form-check-label w-100" for="payment-cash">
                                                <strong><i class="bi bi-cash me-2 text-success"></i> Paiement à la livraison</strong>
                                                <div class="text-muted small mt-1">Payez en espèces lorsque nous livrons votre linge.</div>
                                            </label>
                                        </div>
                                        
                                        <div id="quota-section" class="form-check p-3 border rounded @if(old('payment_method') == 'quota') border-primary bg-light @endif" style="{{ Auth::user()->getTotalAvailableQuota() > 0 ? '' : 'display: none;' }}">
                                            <input class="form-check-input" type="radio" name="payment_method" id="payment-quota" value="quota" {{ Auth::user()->getTotalAvailableQuota() > 0 ? '' : 'disabled' }}>
                                            <label class="form-check-label w-100" for="payment-quota">
                                                <strong><i class="bi bi-wallet2 me-2 text-primary"></i> Utiliser mon quota disponible</strong>
                                                <div class="text-muted small mt-1">
                                                    Quota disponible : <span id="available-quota">{{ Auth::user()->getTotalAvailableQuota() }}</span> kg
                                                    <div id="quota-message"></div>
                                                </div>
                                            </label>
                                        </div>
                                        
                                        <div class="form-check p-3 border rounded opacity-75">
                                            <input class="form-check-input" type="radio" name="payment_method" id="payment-mobile" value="mobile_money" disabled>
                                            <label class="form-check-label w-100" for="payment-mobile">
                                                <strong><i class="bi bi-phone me-2 text-muted"></i> Mobile Money</strong>
                                                <div class="text-muted small mt-1">Service temporairement indisponible.</div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="subscribe-section" style="{{ Auth::user()->getTotalAvailableQuota() > 0 ? 'display: none;' : '' }}">
                                <div class="alert alert-info d-flex align-items-center">
                                    <i class="bi bi-info-circle fs-4 me-3"></i>
                                    <div>
                                        <p class="mb-2">Vous n'avez pas d'abonnement actif ou de quota suffisant.</p>
                                        <a href="{{ route('subscriptions.index') }}" class="btn btn-sm btn-primary mt-1">Voir les abonnements disponibles</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Colonne droite: Récapitulatif des coûts et bon de livraison -->
                        <div class="col-md-6">
                            <!-- Récapitulatif des coûts -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-receipt me-2 text-primary"></i>Résumé des coûts</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-3">
                                        <span>Services :</span>
                                        <strong><span id="payment-subtotal">0</span> FCFA</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span>Frais de collecte :</span>
                                        <strong>
                                            <span id="payment-pickup-fee">0</span> FCFA
                                            <span id="payment-free-pickup-badge" style="display: none;" class="badge bg-success ms-2">Gratuit</span>
                                        </strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span>Frais de livraison :</span>
                                        <strong>
                                            <span id="payment-delivery-fee">0</span> FCFA
                                            <span id="payment-free-delivery-badge" style="display: none;" class="badge bg-success ms-2">Gratuit</span>
                                        </strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3" id="payment-discount-row" style="display: none;">
                                        <span>Réduction :</span>
                                        <strong class="text-success">
                                            <span id="payment-discount-value">0</span> FCFA
                                        </strong>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Total à payer :</span>
                                        <strong class="text-primary fs-5"><span id="payment-total">0</span> FCFA</strong>
                                    </div>
                                    
                                    <div class="alert alert-info small mt-3">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        Les frais de livraison sont calculés en fonction de votre quartier. <a href="#" data-bs-toggle="modal" data-bs-target="#deliveryFeesModal" class="alert-link">Voir la grille tarifaire</a> pour plus d'informations.
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Bons et codes promo dans une interface à onglets -->
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-tag-fill me-2"></i>Réductions et Bons</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="nav nav-tabs mb-3" id="discountTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="voucher-tab" data-bs-toggle="tab" data-bs-target="#voucher-tab-pane" type="button" role="tab" aria-selected="true">
                                                <i class="bi bi-ticket-perforated me-1"></i>Bon de livraison
                                                <span id="voucher-count-badge" class="badge bg-primary ms-1" style="display: none;">0</span>
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="coupon-tab" data-bs-toggle="tab" data-bs-target="#coupon-tab-pane" type="button" role="tab" aria-selected="false">
                                                <i class="bi bi-percent me-1"></i>Code promo
                                                <span id="coupon-count-badge" class="badge bg-success ms-1" style="display: none;">0</span>
                                            </button>
                                        </li>
                                    </ul>
                                    
                                    <div class="tab-content" id="discountTabsContent">
                                        <!-- Bon de livraison -->
                                        <div class="tab-pane fade show active" id="voucher-tab-pane" role="tabpanel" aria-labelledby="voucher-tab">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="use_delivery_voucher" name="use_delivery_voucher" value="1" 
                                                    @if(old('use_delivery_voucher', $tempCart['use_delivery_voucher'] ?? false)) checked @endif>
                                                <label class="form-check-label" for="use_delivery_voucher">
                                                    Utiliser un bon pour bénéficier d'une livraison gratuite
                                                </label>
                                            </div>
                                            
                                            <div id="voucher_fields" @if(!old('use_delivery_voucher', $tempCart['use_delivery_voucher'] ?? false)) style="display: none;" @endif>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" id="delivery_voucher_code" name="delivery_voucher_code" 
                                                        placeholder="Code du bon" value="{{ old('delivery_voucher_code', $tempCart['delivery_voucher_code'] ?? '') }}">
                                                    <button class="btn klin-btn" type="button" id="apply_voucher">
                                                        <i class="bi bi-check-circle-fill me-1"></i> Appliquer
                                                    </button>
                                                </div>
                                                <div id="voucher-status" data-valid="false"></div>
                                                <div id="delivery_voucher_message"></div>
                                                <div id="available-vouchers-list" class="mt-3" style="display: none;">
                                                    <h6 class="mb-2">Vos bons disponibles :</h6>
                                                    <div class="list-group" style="max-height: 200px; overflow-y: auto;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Code promo -->
                                        <div class="tab-pane fade" id="coupon-tab-pane" role="tabpanel" aria-labelledby="coupon-tab">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="use_coupon" name="use_coupon" value="1" 
                                                    @if(old('use_coupon', $tempCart['use_coupon'] ?? false)) checked @endif>
                                                <label class="form-check-label" for="use_coupon">
                                                    Appliquer un code promo pour bénéficier d'une réduction
                                                </label>
                                            </div>
                                            
                                            <div id="coupon_fields" @if(!old('use_coupon', $tempCart['use_coupon'] ?? false)) style="display: none;" @endif>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" id="coupon_code" name="coupon_code" 
                                                        placeholder="Code promo" value="{{ old('coupon_code', $tempCart['coupon_code'] ?? '') }}">
                                                    <button class="btn klin-btn" type="button" id="apply_coupon">
                                                        <i class="bi bi-check-circle-fill me-1"></i> Appliquer
                                                    </button>
                                                </div>
                                                <div id="coupon-status" data-valid="false"></div>
                                                <div id="coupon_message"></div>
                                                <div id="available-coupons-list" class="mt-3" style="display: none;">
                                                    <h6 class="mb-2">Codes promo disponibles :</h6>
                                                    <div class="list-group" style="max-height: 200px; overflow-y: auto;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-5 pt-4 border-top">
                    <button type="button" class="btn klin-btn-outline prev-step px-4" style="display: none;">
                        <i class="bi bi-arrow-left-circle me-2"></i>Précédent
                    </button>
                    <button type="button" class="btn klin-btn next-step ms-auto px-4 py-2" id="next-step-button">
                        Suivant<i class="bi bi-arrow-right-circle ms-2"></i>
                    </button>
                    <button type="submit" class="btn klin-btn submit-order ms-auto px-4 py-2" id="submit-order-button" style="display: none;">
                        <i class="bi bi-check-circle me-2"></i>Finaliser la commande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Le script JavaScript reste le même que dans la version précédente, car il gère la logique des étapes, 
    la sélection des services, et la mise à jour des totaux.
    Assurez-vous qu'il est bien inclus et fonctionne correctement.
    Les principales modifications de design sont dans le HTML et le CSS.
--}}
<script>
    console.log("Début du script de commande pressing - DOM Loading");
    
    // Déclaration des variables clés comme globales pour assurer l'accessibilité
    var currentStep = {{ old('current_step', $tempCart['step'] ?? 0) }};
    var totalSteps = 6;
    var pressingServices = @json($pressingServices ?? []);
    var pressingCategories = @json($serviceCategories ?? []);
    var tempCartItems = @json($tempCart['pressing_services'] ?? []);
    var currentDeliveryFee = 0;
    var deliveryFees = @json($deliveryFees ?? []);

    // Déterminer si nous sommes sur une page de pressing spécifique
    var pressingId = {{ $pressingId ?? 'null' }};
    
    // Si nous sommes sur l'URL principale sans pressing spécifique, être sûr de montrer l'étape 0
    if (pressingId === null) {
        currentStep = 0;
    }
    // Si un ID de pressing est présent, forcer l'étape 1 (collecte)
    else if (pressingId !== null) {
        currentStep = 1;
    }

    // Fonction principale de mise à jour des étapes
    function updateStep() {
        // Récupérer les éléments DOM
        const steps = document.querySelectorAll('.form-step');
        const progressSteps = document.querySelectorAll('.progress-bar-custom .step-custom');
        const progressLines = document.querySelectorAll('.progress-bar-custom .progress-line-custom');
        const prevBtn = document.querySelector('.prev-step');
        const nextBtn = document.querySelector('.next-step');
        const submitBtn = document.querySelector('.submit-order');
        
        // Mise à jour des étapes visibles
        steps.forEach((step, index) => {
            step.classList.toggle('active', index === currentStep);
        });

        // Mise à jour du bouton précédent
        prevBtn.style.display = currentStep === 0 ? 'none' : 'inline-flex';
        
        // Gestion des boutons suivant/finaliser selon l'étape
        const isLastStep = currentStep === totalSteps - 1;
        
        // Afficher le bouton approprié
        nextBtn.style.display = isLastStep ? 'none' : 'inline-flex';
        submitBtn.style.display = isLastStep ? 'inline-flex' : 'none';
        
        // Mise à jour de l'indicateur de progression
        progressSteps.forEach((step, index) => {
            const iconContainer = step.querySelector('.step-icon span i');
            step.classList.remove('active', 'completed');
            
            if (index < currentStep) {
                step.classList.add('completed');
                if(iconContainer) iconContainer.className = 'bi bi-check-lg';
            } else if (index === currentStep) {
                step.classList.add('active');
                // Remettre l'icône originale pour l'étape active
                const originalIcons = ["bi-shop", "bi-geo-alt-fill", "bi-truck", "bi-card-checklist", "bi-receipt", "bi-credit-card-fill"];
                if(iconContainer) iconContainer.className = `bi ${originalIcons[index]}`;
            } else {
                // Remettre l'icône originale pour les étapes futures
                const originalIcons = ["bi-shop", "bi-geo-alt-fill", "bi-truck", "bi-card-checklist", "bi-receipt", "bi-credit-card-fill"];
                if(iconContainer) iconContainer.className = `bi ${originalIcons[index]}`;
            }
        });

        progressLines.forEach((line, index) => {
            line.classList.toggle('active', index < currentStep);
        });

        // Initialisation spécifique en fonction des étapes
        if (currentStep === 0) initializePressingSelection();
        if (currentStep === 3) initializePressingServicesAndCategories();
        if (currentStep === 4) updateFullSummary(); // Récap
        if (currentStep === 5) {
            // Paiement - Mettre à jour les montants
            const paymentTotalDisplay = document.getElementById('payment-total');
            const paymentSubtotalDisplay = document.getElementById('payment-subtotal');
            const paymentPickupFeeDisplay = document.getElementById('payment-pickup-fee');
            const paymentDeliveryFeeDisplay = document.getElementById('payment-delivery-fee');
            
            // Récupérer les montants du récapitulatif
            const subtotal = parseFloat(document.getElementById('summary-subtotal').textContent.replace(/\s/g, '').replace('FCFA', '')) || 0;
            const deliveryFees = calculateDeliveryFee();
            const totalPrice = parseFloat(document.getElementById('summary-total').textContent.replace(/\s/g, '').replace('FCFA', '')) || 0;
            
            // Mettre à jour les affichages
            if (paymentSubtotalDisplay) paymentSubtotalDisplay.textContent = new Intl.NumberFormat('fr-FR').format(subtotal) + " FCFA";
            if (paymentPickupFeeDisplay) paymentPickupFeeDisplay.textContent = new Intl.NumberFormat('fr-FR').format(deliveryFees.pickup) + " FCFA";
            if (paymentDeliveryFeeDisplay) paymentDeliveryFeeDisplay.textContent = new Intl.NumberFormat('fr-FR').format(deliveryFees.delivery) + " FCFA";
            if (paymentTotalDisplay) paymentTotalDisplay.textContent = new Intl.NumberFormat('fr-FR').format(totalPrice) + " FCFA";
            
            // Vérifier le quota
            calculateQuotaEquivalent();
        }
    }
    
    // Fonction pour naviguer vers une étape spécifique
    function goToStep(step) {
        if (step >= 0 && step < totalSteps) {
            console.log("Tentative de navigation vers l'étape:", step, "depuis:", currentStep);
            
            // Sur la page principale, on doit toujours rester à l'étape 0
            if (window.location.pathname === '/orders/create/pressing' && step > 0) {
                console.log("Navigation bloquée - sélectionnez d'abord un pressing");
                return false;
            }
            
            // Validation requise seulement quand on avance
            if (step > currentStep && !validateStep(currentStep)) {
                return false; // Ne pas avancer si l'étape actuelle n'est pas valide
            }
            
            // Si on tente d'aller au-delà de la dernière étape, c'est pour soumettre le formulaire
            if (step >= totalSteps) {
                console.log("Soumission du formulaire");
                document.getElementById('orderForm').submit();
                return true;
            }
            
            currentStep = step;
            updateStep();
            window.scrollTo(0, 0);
            return true;
        }
        return false;
    }
    
    // Validation des étapes
    function validateStep(stepIndex) {
        let isValid = true;
        const steps = document.querySelectorAll('.form-step');
        const stepElement = steps[stepIndex];
        
        // Vérifier les champs requis
        stepElement.querySelectorAll('[required]').forEach(input => {
            if (!input.value && input.offsetParent !== null) { // Vérifier si visible
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        // Validations spécifiques par étape
        if (stepIndex === 0) { // Sélection Pressing
            const pressingId = document.getElementById('selected_pressing_id_field').value;
            if (!pressingId) {
                isValid = false;
                
                // Afficher un message d'erreur
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger mt-3';
                errorDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Erreur :</strong> Veuillez sélectionner un pressing avant de continuer.';
                
                // Vérifier si un message d'erreur existe déjà
                const existingError = document.querySelector('#step-0 .alert.alert-danger');
                if (!existingError) {
                    document.querySelector('#step-0').appendChild(errorDiv);
                    
                    // Supprimer le message après 3 secondes
                    setTimeout(() => {
                        errorDiv.remove();
                    }, 3000);
                }
            }
        }
        
        if (stepIndex === 1) { // Collecte
            // Trigger delivery fee calculation on validation
            calculateDeliveryFee();
        }
        
        if (stepIndex === 2) { // Livraison
            // Trigger delivery fee calculation on validation
            calculateDeliveryFee();
        }
        
        if (stepIndex === 3) { // Services
            let servicesSelected = false;
            document.querySelectorAll('.item-quantity').forEach(input => {
                const quantity = parseInt(input.value);
                if (quantity > 0) servicesSelected = true;
            });
            
            if (!servicesSelected) {
                isValid = false;
                
                // Afficher un message d'erreur
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger mt-3';
                errorDiv.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Erreur :</strong> Veuillez sélectionner au moins un service avant de continuer.';
                
                // Vérifier si un message d'erreur existe déjà
                const existingError = document.querySelector('#step-3 .alert.alert-danger');
                if (!existingError) {
                    document.querySelector('#step-3').appendChild(errorDiv);
                    
                    // Supprimer le message après 3 secondes
                    setTimeout(() => {
                        errorDiv.remove();
                    }, 3000);
                }
            }
        }
        
        return isValid;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Log current step for debugging
        console.log("currentStep at DOM loaded:", currentStep, "pressingId:", pressingId);
        
        // Enforce correct step based on URL
        if (window.location.pathname === '/orders/create/pressing') {
            // Main page - force step 0
            currentStep = 0;
            console.log("Enforcing step 0 for main pressing page");
        }
        
        // Mise à jour immédiate de l'étape
        updateStep();
        
        const orderForm = document.getElementById('orderForm');
        const prevBtn = document.querySelector('.prev-step');
        const nextBtn = document.querySelector('.next-step');
        const submitBtn = document.querySelector('.submit-order');
        
        // Gestionnaire d'événements pour le bouton de soumission
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Vérifier la validation de l'étape finale
            if (validateStep(currentStep)) {
                console.log("Soumission du formulaire");
                orderForm.submit();
            }
        });
        
        // Remplacer le gestionnaire du bouton suivant
        nextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            console.log("Bouton suivant cliqué, étape actuelle:", currentStep);
            
            // Pour l'étape 0 (sélection du pressing), rediriger vers la page du pressing sélectionné
            if (currentStep === 0) {
                const pressingId = document.getElementById('selected_pressing_id_field').value;
                if (pressingId) {
                    // Redirection directe vers la route du pressing
                    window.location.href = "/orders/create/pressing/" + pressingId;
                    return;
                } else {
                    alert("Veuillez d'abord sélectionner un pressing en cliquant sur une carte");
                    return;
                }
            }
            
            // Pour toutes les autres étapes, utiliser goToStep pour une validation cohérente
            goToStep(currentStep + 1);
        });

        // Gestionnaire d'événements pour le bouton précédent
        prevBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Sur la page principale pressing, ne pas permettre de revenir en arrière
            if (window.location.pathname === '/orders/create/pressing' && currentStep === 0) {
                return;
            }
            
            // Sur les autres pages, retour à l'étape précédente
            goToStep(currentStep - 1);
        });
        
        // --- Logique pour la sélection du Pressing (Étape 0) ---
        document.querySelectorAll('.pressing-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // Ignore click if it's on the select button (let the native link behavior work)
                if (e.target.closest('.select-pressing-btn')) {
                    return;
                }
                
                // Récupérer l'ID du pressing directement de l'attribut data
                const pressingId = this.dataset.pressingId;
                console.log('Carte pressing cliquée, ID:', pressingId);
                
                // Mettre à jour le champ caché
                document.getElementById('selected_pressing_id_field').value = pressingId;
                
                // Extraire et mettre à jour l'affichage du nom du pressing
                const pressingName = this.querySelector('.card-title').textContent;
                const pressingAddress = this.querySelector('.card-text.small').textContent;
                document.getElementById('selected_pressing_display').value = pressingName + " - " + pressingAddress;
                
                // Mettre à jour la sélection visuelle
                document.querySelectorAll('.pressing-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                
                // Activer le bouton "Suivant" car un pressing est maintenant sélectionné
                document.querySelector('.next-step').disabled = false;
                
                // Rediriger directement vers la page du pressing sélectionné
                window.location.href = "/orders/create/pressing/" + pressingId;
            });
        });
        
        // Gérer directement les clics sur le bouton de sélection
        document.querySelectorAll('.select-pressing-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                // Le bouton a déjà un href, pas besoin de gérer la redirection
                // Mais on peut mettre à jour l'état visuel si souhaité
                const pressingCard = this.closest('.pressing-card');
                if (pressingCard) {
                    document.querySelectorAll('.pressing-card').forEach(c => c.classList.remove('selected'));
                    pressingCard.classList.add('selected');
                }
            });
        });

        // Gérer l'affichage des champs d'adresse de livraison en fonction de la case à cocher
        const sameAddressCheckbox = document.getElementById('same_address_for_delivery_step2');
        if (sameAddressCheckbox) {
            sameAddressCheckbox.addEventListener('change', function() {
                const deliveryAddressFields = document.getElementById('delivery-address-fields');
                if (deliveryAddressFields) {
                    deliveryAddressFields.style.display = this.checked ? 'none' : 'block';
                    
                    // Gérer les attributs requis
                    const deliveryAddressSelect = document.getElementById('delivery_address_id');
                    if (deliveryAddressSelect) {
                        if (this.checked) {
                            deliveryAddressSelect.removeAttribute('required');
                        } else {
                            deliveryAddressSelect.setAttribute('required', 'required');
                        }
                    }
                }
                
                // Recalculer les frais de livraison
                calculateDeliveryFee();
            });
        }

        // Gérer les événements pour le calcul des frais de livraison
        const collectionAddressSelect = document.getElementById('collection_address_id');
        const deliveryAddressSelect = document.getElementById('delivery_address_id');
        
        if (collectionAddressSelect) {
            collectionAddressSelect.addEventListener('change', calculateDeliveryFee);
        }
        
        if (deliveryAddressSelect) {
            deliveryAddressSelect.addEventListener('change', calculateDeliveryFee);
        }
    });
    
    function initializePressingServicesAndCategories() {
        console.log("Initialisation des services et catégories du pressing");
        const pressingId = document.getElementById('selected_pressing_id_field').value;
        
        if (!pressingId) {
            showErrorInServices("Veuillez d'abord sélectionner un pressing à l'étape précédente.");
            return;
        }
        
        if (pressingServices.length === 0) {
            // Si les services ne sont pas encore chargés, les charger via AJAX
            showErrorInServices("Chargement des services en cours...");
            
            fetch(`/api/pressings/${pressingId}/new-services`)
                .then(response => {
                    console.log("Response status:", response.status);
                    return response.json();
                })
                .then(data => {
                    console.log("Data received:", data);
                    if (data.services && data.services.length > 0) {
                        pressingServices = data.services;
                        pressingCategories = data.categories || [];
                        renderCategories(pressingCategories);
                        renderServices(pressingServices, 'all');
                        loadTempCartItems();
                        updateTotals();
                    } else {
                        showErrorInServices("Aucun service disponible pour ce pressing.");
                    }
                })
                .catch(error => {
                    console.error("Erreur lors du chargement des services:", error);
                    showErrorInServices("Erreur lors du chargement des services. Veuillez réessayer.");
                });
        } else {
            // Sinon, utiliser les services déjà chargés
            renderCategories(pressingCategories);
            renderServices(pressingServices, 'all');
            loadTempCartItems();
            updateTotals();
        }
    }
    
    function renderServices(servicesToRender, categoryFilter = 'all') {
        const servicesContainer = document.getElementById('servicesContainer');
        if (!servicesContainer) {
            console.error("Conteneur de services introuvable");
            return;
        }
        
        servicesContainer.innerHTML = '';
        
        if (!servicesToRender || servicesToRender.length === 0) {
            let message = categoryFilter === 'all' 
                ? "Aucun service disponible pour ce pressing."
                : "Aucun service disponible dans cette catégorie.";
            
            showErrorInServices(message);
            return;
        }

        // Générer les éléments HTML pour chaque service
        servicesToRender.forEach(service => {
            const serviceElement = document.createElement('div');
            serviceElement.className = 'col-md-6 mb-3 service-item';
            serviceElement.dataset.categoryId = service.service_category_id || service.category_id || '';
            
            const price = parseFloat(service.price) || 0;
            const formattedPrice = new Intl.NumberFormat('fr-FR').format(price) + " FCFA";
            
            serviceElement.innerHTML = `
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title mb-1">${service.name}</h5>
                            <span class="badge rounded-pill" style="background-color: var(--klin-primary); color:white;">${formattedPrice}</span>
                        </div>
                        <p class="card-text text-muted"><small>${service.description || ''}</small></p>
                        <div class="input-group mt-auto">
                            <button type="button" class="btn btn-outline-secondary quantity-minus" aria-label="Diminuer la quantité"><i class="bi bi-dash-lg"></i></button>
                            <input type="number" class="form-control text-center item-quantity" 
                                name="pressing_services[${service.id}][quantity]" value="0" min="0" 
                                data-price="${price}" data-service-id="${service.id}" aria-label="Quantité pour ${service.name}">
                            <button type="button" class="btn btn-outline-secondary quantity-plus" aria-label="Augmenter la quantité"><i class="bi bi-plus-lg"></i></button>
                        </div>
                    </div>
                </div>`;
            
            servicesContainer.appendChild(serviceElement);
        });
        
        // Attacher les écouteurs d'événements aux boutons de quantité
        attachQuantityListeners();
    }

    // Fonction pour afficher des erreurs lors du chargement des services
    function showErrorInServices(message) {
        const servicesContainer = document.getElementById('servicesContainer');
        if (!servicesContainer) return;
        
        servicesContainer.innerHTML = `
            <div class="col-12">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> ${message}
                </div>
            </div>`;
    }
    
    function renderCategories(categories) {
        const categoriesList = document.getElementById('categories-list');
        if (!categoriesList) {
            console.error("Conteneur de catégories introuvable");
            return;
        }
        
        // Garder uniquement la catégorie "Tous les services"
        categoriesList.innerHTML = `
            <a href="#" class="list-group-item list-group-item-action category-tab active" data-category="all">
                <i class="bi bi-grid-fill icon"></i> Tous les services
            </a>`;
            
        // Ajouter les catégories
        if (categories && categories.length > 0) {
            categories.forEach(category => {
                const categoryElement = document.createElement('a');
                categoryElement.href = "#";
                categoryElement.className = "list-group-item list-group-item-action category-tab";
                categoryElement.dataset.category = category.id || category.name;
                
                categoryElement.innerHTML = `
                    <i class="bi ${category.icon || 'bi-tag'} icon"></i> ${category.name}`;
                
                categoriesList.appendChild(categoryElement);
            });
            
            // Ajouter les écouteurs d'événements pour les onglets de catégorie
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Mettre à jour la classe active
                    document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Filtrer les services par catégorie
                    const categoryId = this.dataset.category;
                    
                    if (categoryId === 'all') {
                        renderServices(pressingServices, 'all');
                    } else {
                        const filteredServices = pressingServices.filter(service => 
                            (service.category_id === categoryId) || 
                            (service.service_category_id === categoryId) ||
                            (service.category === categoryId)
                        );
                        renderServices(filteredServices, categoryId);
                    }
                });
            });
        } else {
            console.log("Aucune catégorie disponible");
        }
    }
    
    function attachQuantityListeners() {
        // Attacher les événements d'incrémentation et décrémentation
        document.querySelectorAll('.quantity-plus').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.item-quantity');
                const currentValue = parseInt(input.value) || 0;
                input.value = currentValue + 1;
                
                // Déclencher l'événement de changement pour mettre à jour les totaux
                const event = new Event('change', { bubbles: true });
                input.dispatchEvent(event);
            });
        });
        
        document.querySelectorAll('.quantity-minus').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.item-quantity');
                const currentValue = parseInt(input.value) || 0;
                if (currentValue > 0) {
                    input.value = currentValue - 1;
                    
                    // Déclencher l'événement de changement pour mettre à jour les totaux
                    const event = new Event('change', { bubbles: true });
                    input.dispatchEvent(event);
                }
            });
        });
        
        // Attacher les événements de changement pour mettre à jour les totaux
        document.querySelectorAll('.item-quantity').forEach(input => {
            input.addEventListener('change', function() {
                updateTotals();
            });
        });
    }
    
    function updateTotals() {
        let subtotal = 0;
        
        document.querySelectorAll('.item-quantity').forEach(input => {
            const quantity = parseInt(input.value) || 0;
            const price = parseFloat(input.dataset.price) || 0;
            subtotal += quantity * price;
        });
        
        let selectedServices = [];
        document.querySelectorAll('.item-quantity').forEach(input => {
            const quantity = parseInt(input.value) || 0;
            if (quantity > 0) {
                const serviceId = input.dataset.serviceId;
                const price = parseFloat(input.dataset.price) || 0;
                const serviceTitle = input.closest('.card').querySelector('.card-title').textContent;
                
                selectedServices.push({
                    id: serviceId,
                    name: serviceTitle,
                    price: price,
                    quantity: quantity,
                    total: price * quantity
                });
            }
        });
        
        window.selectedServices = selectedServices;
        
        // Calculate delivery fees
        const deliveryFees = calculateDeliveryFee();
        
        // Check if delivery voucher is applied
        let totalDeliveryFee = deliveryFees.totalFee;
        const useVoucherCheckbox = document.getElementById('use_delivery_voucher');
        const voucherStatus = document.getElementById('voucher-status');
        if (useVoucherCheckbox && useVoucherCheckbox.checked && voucherStatus && voucherStatus.dataset.valid === 'true') {
            totalDeliveryFee = 0;
        }
        
        // Check if discount code is applied
        let discountAmount = 0;
        const useCouponCheckbox = document.getElementById('use_coupon');
        const couponStatus = document.getElementById('coupon-status');
        
        if (useCouponCheckbox && useCouponCheckbox.checked && couponStatus && couponStatus.dataset.valid === 'true' && window.currentCoupon) {
            if (window.currentCoupon.type === 'percentage') {
                discountAmount = subtotal * (window.currentCoupon.value / 100);
            } else {
                discountAmount = Math.min(window.currentCoupon.value, subtotal);
            }
            
            // Apply max discount if defined
            if (window.currentCoupon.max_discount_amount && discountAmount > window.currentCoupon.max_discount_amount) {
                discountAmount = window.currentCoupon.max_discount_amount;
            }
        }
        
        // Calculate final total
        const totalPrice = subtotal + totalDeliveryFee - discountAmount;
        
        // Update price display
        const estimatedPriceDisplay = document.getElementById('estimatedPriceDisplay');
        if (estimatedPriceDisplay) {
            estimatedPriceDisplay.textContent = new Intl.NumberFormat('fr-FR').format(totalPrice);
        }
        
        // If we're at summary step, update it
        if (currentStep === 4) {
            updateFullSummary();
        }
        
        // If we're at payment step, update display
        if (currentStep === 5) {
            updatePaymentDisplay();
        }
    }
    
    function loadTempCartItems() {
        if (tempCartItems && Object.keys(tempCartItems).length > 0) {
            // Parcourir les articles du panier temporaire
            Object.keys(tempCartItems).forEach(serviceId => {
                const item = tempCartItems[serviceId];
                const quantityInput = document.querySelector(`.item-quantity[data-service-id="${serviceId}"]`);
                
                if (quantityInput && item.quantity) {
                    quantityInput.value = item.quantity;
                }
            });
            
            // Mettre à jour les totaux
            updateTotals();
        }
    }

    function initializePressingSelection() {
        // Si un ID de pressing est présent dans le champ caché, marquer la carte correspondante comme sélectionnée
        const selectedPressingId = document.getElementById('selected_pressing_id_field').value;
        
        if (selectedPressingId) {
            console.log("Sélection du pressing avec ID:", selectedPressingId);
            
            const pressingCard = document.querySelector(`.pressing-card[data-pressing-id="${selectedPressingId}"]`);
            if (pressingCard) {
                // Mettre à jour la sélection visuelle
                document.querySelectorAll('.pressing-card').forEach(c => c.classList.remove('selected'));
                pressingCard.classList.add('selected');
            }
        }
    }

    // Fonction pour normaliser les noms de quartier (enlever les accents, mettre en minuscule, etc.)
    function normalizeDistrict(district) {
        if (!district) return '';
        
        // Conversion en minuscules
        let normalized = district.toLowerCase();
        
        // Supprimer les accents
        normalized = normalized.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        
        // Supprimer les tirets et les remplacer par des espaces
        normalized = normalized.replace(/-/g, ' ');
        
        // Supprimer les caractères spéciaux et espaces multiples
        normalized = normalized.replace(/[^\w\s]/g, '').replace(/\s+/g, ' ').trim();
        
        // Gestion des cas spécifiques connus
        if (normalized.includes('poto') || normalized === 'potopoto') {
            normalized = 'poto poto';
        }
        
        console.log(`Normalisation du quartier: "${district}" -> "${normalized}"`);
        return normalized;
    }

    // Calculate delivery fees based on selected addresses
    function calculateDeliveryFee() {
        console.log("Calcul des frais de livraison");
        
        // Default base fees
        const baseFee = 1000; // Base fee in FCFA
        let pickupFee = baseFee;
        let deliveryFee = baseFee;
        
        // Get selected addresses
        const collectionAddressSelect = document.getElementById('collection_address_id');
        const deliveryAddressSelect = document.getElementById('delivery_address_id');
        const sameAddressCheckbox = document.getElementById('same_address_for_delivery_step2');
        
        // Get neighborhood from selected collection address
        let collectionNeighborhood = '';
        if (collectionAddressSelect && collectionAddressSelect.selectedIndex > 0) {
            const selectedOption = collectionAddressSelect.options[collectionAddressSelect.selectedIndex];
            const addressText = selectedOption.textContent;
            // Extract neighborhood (assuming format like "Name - Address, Neighborhood")
            const matches = addressText.match(/,\s*([^,]+)$/);
            if (matches && matches.length > 1) {
                collectionNeighborhood = matches[1].trim();
                console.log("Quartier de collecte détecté:", collectionNeighborhood);
            }
        }
        
        // Get neighborhood from selected delivery address or use collection if same
        let deliveryNeighborhood = '';
        if (sameAddressCheckbox && sameAddressCheckbox.checked) {
            // Use same neighborhood as collection
            deliveryNeighborhood = collectionNeighborhood;
            console.log("Même quartier pour la livraison:", deliveryNeighborhood);
        } else if (deliveryAddressSelect && deliveryAddressSelect.selectedIndex > 0) {
            const selectedOption = deliveryAddressSelect.options[deliveryAddressSelect.selectedIndex];
            const addressText = selectedOption.textContent;
            // Extract neighborhood
            const matches = addressText.match(/,\s*([^,]+)$/);
            if (matches && matches.length > 1) {
                deliveryNeighborhood = matches[1].trim();
                console.log("Quartier de livraison détecté:", deliveryNeighborhood);
            }
        }
        
        // Normaliser les quartiers pour une comparaison plus robuste
        const normalizedCollectionNeighborhood = normalizeDistrict(collectionNeighborhood);
        const normalizedDeliveryNeighborhood = normalizeDistrict(deliveryNeighborhood);
        
        // Check if we have preloaded delivery fees data
        console.log("Données de frais de livraison disponibles:", window.deliveryFeesData);
        if (window.deliveryFeesData && window.deliveryFeesData.length > 0) {
            // Find pickup fee for collection neighborhood
            const pickupFeeData = window.deliveryFeesData.find(fee => {
                if (!fee.district) return false;
                const normalizedFeeDistrict = normalizeDistrict(fee.district);
                return normalizedFeeDistrict === normalizedCollectionNeighborhood;
            });
            
            // Find delivery fee for delivery neighborhood
            const deliveryFeeData = window.deliveryFeesData.find(fee => {
                if (!fee.district) return false;
                const normalizedFeeDistrict = normalizeDistrict(fee.district);
                return normalizedFeeDistrict === normalizedDeliveryNeighborhood;
            });
            
            console.log("Données de frais trouvées - Collecte:", pickupFeeData, "Livraison:", deliveryFeeData);
            
            // Apply fees if found
            if (pickupFeeData) {
                pickupFee = pickupFeeData.fee;
                console.log(`Frais de collecte pour ${collectionNeighborhood}: ${pickupFee} FCFA`);
            } else {
                console.log(`Aucun frais spécifique trouvé pour ${collectionNeighborhood}, utilisation du tarif de base: ${pickupFee} FCFA`);
            }
            
            if (deliveryFeeData) {
                deliveryFee = deliveryFeeData.fee;
                console.log(`Frais de livraison pour ${deliveryNeighborhood}: ${deliveryFee} FCFA`);
            } else {
                console.log(`Aucun frais spécifique trouvé pour ${deliveryNeighborhood}, utilisation du tarif de base: ${deliveryFee} FCFA`);
            }
        } else {
            console.log("Aucune donnée de frais de livraison disponible, utilisation des tarifs par défaut");
        }
        
        // Check if a delivery voucher is applied
        const useVoucherCheckbox = document.getElementById('use_delivery_voucher');
        const voucherCodeInput = document.getElementById('delivery_voucher_code');
        let isVoucherValid = false;
        
        // Validate voucher code without AJAX
        if (useVoucherCheckbox && useVoucherCheckbox.checked && voucherCodeInput && voucherCodeInput.value) {
            const voucherCode = voucherCodeInput.value.trim();
            
            // Check if the voucher exists in the available vouchers
            if (window.availableVouchers && window.availableVouchers.length > 0) {
                const foundVoucher = window.availableVouchers.find(v => v.code === voucherCode);
                
                if (foundVoucher) {
                    // Check if the voucher is still valid (not expired)
                    const now = new Date();
                    const expiryDate = foundVoucher.expires_at ? new Date(foundVoucher.expires_at) : null;
                    
                    if (!expiryDate || expiryDate > now) {
                        console.log("Bon de livraison valide trouvé:", foundVoucher);
                        isVoucherValid = true;
                        
                        // Update voucher message
                        const voucherMessage = document.getElementById('delivery_voucher_message');
                        if (voucherMessage) {
                            voucherMessage.innerHTML = '<div class="alert alert-success mt-2"><i class="bi bi-check-circle-fill me-2"></i>Bon de livraison valide ! Livraison gratuite appliquée.</div>';
                        }
                        
                        // Update voucher status
                        const voucherStatus = document.getElementById('voucher-status');
                        if (voucherStatus) {
                            voucherStatus.dataset.valid = 'true';
                        }
                    } else {
                        console.log("Bon de livraison expiré:", foundVoucher);
                        const voucherMessage = document.getElementById('delivery_voucher_message');
                        if (voucherMessage) {
                            voucherMessage.innerHTML = '<div class="alert alert-danger mt-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Ce bon de livraison a expiré.</div>';
                        }
                    }
                } else {
                    console.log("Bon de livraison non trouvé:", voucherCode);
                    const voucherMessage = document.getElementById('delivery_voucher_message');
                    if (voucherMessage) {
                        voucherMessage.innerHTML = '<div class="alert alert-danger mt-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Code de bon invalide.</div>';
                    }
                }
            }
        }
        
        // Apply free delivery if voucher is valid
        if (isVoucherValid) {
            pickupFee = 0;
            deliveryFee = 0;
            console.log("Bon de livraison appliqué, frais de livraison gratuits");
        }
        
        // Store the results globally
        window.deliveryFeeData = {
            collectionNeighborhood: collectionNeighborhood,
            deliveryNeighborhood: deliveryNeighborhood,
            pickupFee: pickupFee,
            deliveryFee: deliveryFee,
            totalFee: pickupFee + deliveryFee,
            isVoucherApplied: isVoucherValid
        };
        
        // Display fees in the relevant elements
        const collectionFeeInfo = document.getElementById('collection-address-fee-info');
        if (collectionFeeInfo) {
            if (isVoucherValid) {
                collectionFeeInfo.innerHTML = `Frais de collecte: <span class="text-success">Gratuit</span>`;
            } else {
                collectionFeeInfo.textContent = `Frais de collecte: ${new Intl.NumberFormat('fr-FR').format(pickupFee)} FCFA`;
            }
            collectionFeeInfo.style.display = 'block';
        }
        
        const deliveryFeeInfo = document.getElementById('delivery-address-fee-info');
        if (deliveryFeeInfo) {
            if (isVoucherValid) {
                deliveryFeeInfo.innerHTML = `Frais de livraison: <span class="text-success">Gratuit</span>`;
            } else {
                deliveryFeeInfo.textContent = `Frais de livraison: ${new Intl.NumberFormat('fr-FR').format(deliveryFee)} FCFA`;
            }
            deliveryFeeInfo.style.display = 'block';
        }
        
        console.log("Frais de livraison calculés:", window.deliveryFeeData);
        return window.deliveryFeeData;
    }

    function updateFullSummary() {
        // Récupérer les valeurs des champs
        const pressing = document.getElementById('selected_pressing_display').value || 'Non sélectionné';
        
        // Adresses
        const collectionAddressSelect = document.getElementById('collection_address_id');
        const deliveryAddressSelect = document.getElementById('delivery_address_id');
        const sameAddressCheckbox = document.getElementById('same_address_for_delivery_step2');
        
        let collectionAddress = 'Non sélectionnée';
        let deliveryAddress = 'Non sélectionnée';
        
        if (collectionAddressSelect && collectionAddressSelect.selectedIndex > 0) {
            collectionAddress = collectionAddressSelect.options[collectionAddressSelect.selectedIndex].textContent;
        }
        
        if (sameAddressCheckbox && sameAddressCheckbox.checked) {
            deliveryAddress = collectionAddress + ' (même que la collecte)';
        } else if (deliveryAddressSelect && deliveryAddressSelect.selectedIndex > 0) {
            deliveryAddress = deliveryAddressSelect.options[deliveryAddressSelect.selectedIndex].textContent;
        }
        
        // Dates
        const collectionDate = document.getElementById('collection_date');
        const collectionTimeSlot = document.getElementById('collection_time_slot');
        const deliveryDate = document.getElementById('delivery_date');
        const deliveryTimeSlot = document.getElementById('delivery_time_slot');
        
        // Notes
        const deliveryNotes = document.getElementById('delivery_notes');
        
        // Mettre à jour les adresses
        document.getElementById('summary-pressing-name').textContent = pressing;
        document.getElementById('summary-collection-address').textContent = collectionAddress;
        document.getElementById('summary-delivery-address').textContent = deliveryAddress;
        
        // Mettre à jour les dates
        if (collectionDate && collectionDate.value) {
            const formattedDate = new Date(collectionDate.value).toLocaleDateString('fr-FR', { 
                day: '2-digit', month: '2-digit', year: 'numeric' 
            });
            document.getElementById('summary-collection-date').textContent = formattedDate;
        }
        
        if (collectionTimeSlot && collectionTimeSlot.selectedIndex > 0) {
            document.getElementById('summary-collection-time').textContent = collectionTimeSlot.options[collectionTimeSlot.selectedIndex].textContent;
        }
        
        if (deliveryDate && deliveryDate.value) {
            const formattedDate = new Date(deliveryDate.value).toLocaleDateString('fr-FR', { 
                day: '2-digit', month: '2-digit', year: 'numeric' 
            });
            document.getElementById('summary-delivery-date').textContent = formattedDate;
        }
        
        if (deliveryTimeSlot && deliveryTimeSlot.selectedIndex > 0) {
            document.getElementById('summary-delivery-time').textContent = deliveryTimeSlot.options[deliveryTimeSlot.selectedIndex].textContent;
        }
        
        // Afficher les notes de livraison si présentes
        if (deliveryNotes && deliveryNotes.value) {
            document.getElementById('summary-delivery-notes').textContent = deliveryNotes.value;
            document.getElementById('summary-delivery-notes-container').style.display = 'block';
        } else {
            document.getElementById('summary-delivery-notes-container').style.display = 'none';
        }
        
        // Afficher les services sélectionnés
        const summaryItems = document.getElementById('summary-items');
        if (summaryItems) {
            summaryItems.innerHTML = '';
            
            if (window.selectedServices && window.selectedServices.length > 0) {
                let subtotal = 0;
                
                window.selectedServices.forEach(service => {
                    const row = document.createElement('tr');
                    const formattedPrice = new Intl.NumberFormat('fr-FR').format(service.price);
                    const formattedTotal = new Intl.NumberFormat('fr-FR').format(service.total);
                    
                    row.innerHTML = `
                        <td class="ps-3">${service.name}</td>
                        <td class="text-center">${service.quantity}</td>
                        <td class="text-center">${formattedPrice} FCFA</td>
                        <td class="text-end pe-3">${formattedTotal} FCFA</td>
                    `;
                    
                    summaryItems.appendChild(row);
                    subtotal += service.total;
                });
                
                // Calculer les frais de livraison
                const deliveryFeeObj = calculateDeliveryFee();
                const totalPrice = subtotal + deliveryFeeObj.totalFee;
                
                // Mettre à jour les totaux
                document.getElementById('summary-subtotal').textContent = `${new Intl.NumberFormat('fr-FR').format(subtotal)} FCFA`;
                document.getElementById('summary-delivery-fees').textContent = `${new Intl.NumberFormat('fr-FR').format(deliveryFeeObj.totalFee)} FCFA`;
                document.getElementById('summary-total').textContent = `${new Intl.NumberFormat('fr-FR').format(totalPrice)} FCFA`;
                
                // Mettre à jour l'équivalent en quota
                calculateQuotaEquivalent();
                
                // Mettre à jour l'affichage des frais dans l'étape de paiement
                updatePaymentDisplay();
            } else {
                summaryItems.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center py-3">Aucun service sélectionné</td>
                    </tr>
                `;
                
                document.getElementById('summary-subtotal').textContent = '0 FCFA';
                document.getElementById('summary-delivery-fees').textContent = '0 FCFA';
                document.getElementById('summary-total').textContent = '0 FCFA';
                
                // Mettre à jour l'affichage des frais dans l'étape de paiement
                updatePaymentDisplay();
            }
        }
    }

    function calculateQuotaEquivalent() {
        // Get total price from summary
        const totalPriceElement = document.getElementById('summary-total');
        const quotaEquivalentElement = document.getElementById('quota-equivalent');
        
        if (totalPriceElement && quotaEquivalentElement) {
            const totalPrice = parseFloat(totalPriceElement.textContent.replace(/\s/g, '').replace('FCFA', '')) || 0;
            
            // Assume 1kg = 500 FCFA (This should be replaced with actual conversion from backend)
            const quotaRate = 500;
            
            // Calculate equivalent in kg
            const quotaEquivalent = totalPrice / quotaRate;
            
            // Display equivalent
            quotaEquivalentElement.textContent = `Équivalent en quota: ${quotaEquivalent.toFixed(1)} kg`;
            
            // Check if user has enough quota and enable/disable radio button
            const quotaRadio = document.getElementById('payment-quota');
            const availableQuota = {{ Auth::user()->getTotalAvailableQuota() ?? 0 }};
            
            if (quotaRadio) {
                if (availableQuota < quotaEquivalent) {
                    quotaRadio.disabled = true;
                    quotaRadio.checked = false;
                    document.getElementById('payment-cash').checked = true;
                    quotaEquivalentElement.classList.add('text-danger');
                    quotaEquivalentElement.textContent += ' (quota insuffisant)';
                } else {
                    quotaRadio.disabled = false;
                    quotaEquivalentElement.classList.remove('text-danger');
                }
            }
        }
    }
    
    // Fonction pour mettre à jour l'affichage dans l'étape de paiement
    function updatePaymentDisplay() {
        console.log("Mise à jour de l'affichage des paiements");
        
        // Récupérer les valeurs du récapitulatif
        const subtotalElement = document.getElementById('summary-subtotal');
        const deliveryFeesElement = document.getElementById('summary-delivery-fees');
        const totalElement = document.getElementById('summary-total');
        
        // Éléments d'affichage de l'étape paiement
        const paymentTotalDisplay = document.getElementById('payment-total');
        const paymentSubtotalDisplay = document.getElementById('payment-subtotal');
        const paymentPickupFeeDisplay = document.getElementById('payment-pickup-fee');
        const paymentDeliveryFeeDisplay = document.getElementById('payment-delivery-fee');
        const paymentFreePickupBadge = document.getElementById('payment-free-pickup-badge');
        const paymentFreeDeliveryBadge = document.getElementById('payment-free-delivery-badge');
        const paymentDiscountRow = document.getElementById('payment-discount-row');
        const paymentDiscountValue = document.getElementById('payment-discount-value');
        
        // Extraire les valeurs
        let subtotal = 0;
        if (subtotalElement) {
            subtotal = parseFloat(subtotalElement.textContent.replace(/\s/g, '').replace('FCFA', '')) || 0;
        }
        
        // Obtenir les frais de livraison
        let pickupFee = 0;
        let deliveryFee = 0;
        let totalDeliveryFees = 0;
        
        if (window.deliveryFeeData) {
            pickupFee = window.deliveryFeeData.pickupFee || 0;
            deliveryFee = window.deliveryFeeData.deliveryFee || 0;
            totalDeliveryFees = pickupFee + deliveryFee;
        } else {
            // Utiliser les données de l'élément si disponibles
            if (deliveryFeesElement) {
                const fees = parseFloat(deliveryFeesElement.textContent.replace(/\s/g, '').replace('FCFA', '')) || 0;
                totalDeliveryFees = fees;
                pickupFee = Math.round(fees / 2);
                deliveryFee = fees - pickupFee;
            }
        }
        
        // Vérifier si un bon de livraison est utilisé
        const useVoucherCheckbox = document.getElementById('use_delivery_voucher');
        const isDeliveryFree = useVoucherCheckbox && useVoucherCheckbox.checked;
        
        if (isDeliveryFree) {
            pickupFee = 0;
            deliveryFee = 0;
            totalDeliveryFees = 0;
            
            if (paymentFreePickupBadge) paymentFreePickupBadge.style.display = 'inline-block';
            if (paymentFreeDeliveryBadge) paymentFreeDeliveryBadge.style.display = 'inline-block';
        } else {
            if (paymentFreePickupBadge) paymentFreePickupBadge.style.display = 'none';
            if (paymentFreeDeliveryBadge) paymentFreeDeliveryBadge.style.display = 'none';
        }
        
        // Vérifier si un code promo est utilisé
        let discountAmount = 0;
        const useCouponCheckbox = document.getElementById('use_coupon');
        const isCouponUsed = useCouponCheckbox && useCouponCheckbox.checked;
        
        if (isCouponUsed && window.currentCoupon) {
            if (window.currentCoupon.type === 'percentage') {
                discountAmount = subtotal * (window.currentCoupon.value / 100);
            } else {
                discountAmount = Math.min(window.currentCoupon.value, subtotal);
            }
            
            // Appliquer le plafond de remise si défini
            if (window.currentCoupon.max_discount_amount && discountAmount > window.currentCoupon.max_discount_amount) {
                discountAmount = window.currentCoupon.max_discount_amount;
            }
            
            if (paymentDiscountRow) paymentDiscountRow.style.display = 'flex';
            if (paymentDiscountValue) paymentDiscountValue.textContent = `-${new Intl.NumberFormat('fr-FR').format(discountAmount)}`;
        } else {
            if (paymentDiscountRow) paymentDiscountRow.style.display = 'none';
        }
        
        // Calculer le total
        const totalPrice = subtotal + totalDeliveryFees - discountAmount;
        
        // Mettre à jour les affichages
        if (paymentSubtotalDisplay) paymentSubtotalDisplay.textContent = new Intl.NumberFormat('fr-FR').format(subtotal);
        if (paymentPickupFeeDisplay) paymentPickupFeeDisplay.textContent = new Intl.NumberFormat('fr-FR').format(pickupFee);
        if (paymentDeliveryFeeDisplay) paymentDeliveryFeeDisplay.textContent = new Intl.NumberFormat('fr-FR').format(deliveryFee);
        if (paymentTotalDisplay) paymentTotalDisplay.textContent = new Intl.NumberFormat('fr-FR').format(totalPrice);
        
        // Mettre à jour l'équivalent en quota
        calculateQuotaEquivalent(totalPrice);
    }
    
    // Fonction pour gérer les bons de livraison
    function setupVoucherHandling() {
        const voucherCodeInput = document.getElementById('delivery_voucher_code');
        const applyVoucherBtn = document.getElementById('apply_voucher');
        const useVoucherCheckbox = document.getElementById('use_delivery_voucher');
        const voucherMessage = document.getElementById('delivery_voucher_message');
        const voucherFields = document.getElementById('voucher_fields');
        const voucherStatus = document.getElementById('voucher-status');
        const availableVouchersList = document.getElementById('available-vouchers-list');
        
        console.log("Initialisation de la gestion des bons de livraison");
        console.log("Bons disponibles:", window.availableVouchers);
        
        // Afficher/masquer les champs de bon quand la case est cochée/décochée
        if (useVoucherCheckbox) {
            useVoucherCheckbox.addEventListener('change', function() {
                if (voucherFields) {
                    voucherFields.style.display = this.checked ? 'block' : 'none';
                }
                
                // Réinitialiser le message si on décoche
                if (!this.checked && voucherMessage) {
                    voucherMessage.innerHTML = '';
                    if (voucherStatus) voucherStatus.dataset.valid = 'false';
                }
                
                // Mettre à jour les totaux pour refléter l'utilisation ou non du bon
                updateTotals();
            });
        }
        
        // Appliquer un bon de livraison
        if (applyVoucherBtn && voucherCodeInput) {
            applyVoucherBtn.addEventListener('click', function() {
                const code = voucherCodeInput.value.trim();
                if (!code) {
                    voucherMessage.innerHTML = '<div class="alert alert-danger mt-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Veuillez saisir un code</div>';
                    return;
                }
                
                console.log("Vérification du bon de livraison:", code);
                
                // Vérifier la validité du bon en utilisant les données préchargées
                if (window.availableVouchers && window.availableVouchers.length > 0) {
                    // Rechercher le bon dans les bons disponibles
                    const foundVoucher = window.availableVouchers.find(v => v.code === code);
                    
                    if (foundVoucher) {
                        console.log("Bon de livraison trouvé:", foundVoucher);
                        
                        // Vérifier si le bon n'est pas expiré
                        const now = new Date();
                        const expiryDate = foundVoucher.expires_at ? new Date(foundVoucher.expires_at) : null;
                        
                        if (!expiryDate || expiryDate > now) {
                            // Bon valide
                            voucherMessage.innerHTML = '<div class="alert alert-success mt-2"><i class="bi bi-check-circle-fill me-2"></i>Bon de livraison valide ! Livraison gratuite appliquée.</div>';
                            voucherStatus.dataset.valid = 'true';
                            
                            // Mettre à jour les totaux
                            updateTotals();
                        } else {
                            voucherMessage.innerHTML = '<div class="alert alert-danger mt-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Ce bon de livraison a expiré.</div>';
                            voucherStatus.dataset.valid = 'false';
                        }
                    } else {
                        voucherMessage.innerHTML = '<div class="alert alert-danger mt-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Code de bon invalide ou indisponible.</div>';
                        voucherStatus.dataset.valid = 'false';
                    }
                } else {
                    voucherMessage.innerHTML = '<div class="alert alert-info mt-2"><i class="bi bi-info-circle-fill me-2"></i>Aucun bon de livraison disponible pour le moment.</div>';
                    voucherStatus.dataset.valid = 'false';
                }
            });
        }
        
        // Afficher les bons disponibles
        if (window.availableVouchers && window.availableVouchers.length > 0 && availableVouchersList) {
            const voucherCountBadge = document.getElementById('voucher-count-badge');
            if (voucherCountBadge) {
                voucherCountBadge.textContent = window.availableVouchers.length;
                voucherCountBadge.style.display = 'inline-block';
            }
            
            const listGroup = availableVouchersList.querySelector('.list-group');
            if (listGroup) {
                window.availableVouchers.forEach(voucher => {
                    const listItem = document.createElement('button');
                    listItem.className = 'list-group-item list-group-item-action';
                    listItem.type = 'button';
                    
                    // Créer le contenu du voucher
                    let expiryDate = 'Non spécifiée';
                    if (voucher.expires_at) {
                        const date = new Date(voucher.expires_at);
                        expiryDate = date.toLocaleDateString('fr-FR');
                    }
                    
                    listItem.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${voucher.code}</strong>
                                <small class="d-block text-muted">Expire le: ${expiryDate}</small>
                            </div>
                            <span class="badge bg-primary">Livraison gratuite</span>
                        </div>`;
                    
                    // Ajouter un gestionnaire d'événements pour sélectionner ce bon
                    listItem.addEventListener('click', function() {
                        voucherCodeInput.value = voucher.code;
                        applyVoucherBtn.click();
                    });
                    
                    listGroup.appendChild(listItem);
                });
                
                availableVouchersList.style.display = 'block';
            }
        }
    }
    
    // Fonction pour gérer les codes promo
    function setupCouponHandling() {
        const couponCodeInput = document.getElementById('coupon_code');
        const applyCouponBtn = document.getElementById('apply_coupon');
        const useCouponCheckbox = document.getElementById('use_coupon');
        const couponMessage = document.getElementById('coupon_message');
        const couponFields = document.getElementById('coupon_fields');
        const couponStatus = document.getElementById('coupon-status');
        const availableCouponsList = document.getElementById('available-coupons-list');
        
        // Variable globale pour stocker le coupon actif
        window.currentCoupon = null;
        
        // Afficher/masquer les champs de coupon quand la case est cochée/décochée
        if (useCouponCheckbox) {
            useCouponCheckbox.addEventListener('change', function() {
                if (couponFields) {
                    couponFields.style.display = this.checked ? 'block' : 'none';
                }
                
                // Réinitialiser le message si on décoche
                if (!this.checked && couponMessage) {
                    couponMessage.innerHTML = '';
                    window.currentCoupon = null;
                    couponStatus.dataset.valid = 'false';
                }
                
                // Mettre à jour les totaux
                updateTotals();
            });
        }
        
        // Appliquer un code promo
        if (applyCouponBtn && couponCodeInput) {
            applyCouponBtn.addEventListener('click', function() {
                const code = couponCodeInput.value.trim();
                if (!code) {
                    couponMessage.innerHTML = '<div class="alert alert-danger mt-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Veuillez saisir un code</div>';
                    return;
                }
                
                console.log("Vérification du code promo:", code);
                
                // Validation du code promo en utilisant les données préchargées
                if (window.availableCoupons && window.availableCoupons.length > 0) {
                    // Rechercher le code dans les coupons disponibles
                    const foundCoupon = window.availableCoupons.find(c => c.code === code);
                    
                    if (foundCoupon) {
                        console.log("Code promo trouvé:", foundCoupon);
                        
                        // Vérifier si le coupon n'est pas expiré
                        const now = new Date();
                        const expiryDate = foundCoupon.expires_at ? new Date(foundCoupon.expires_at) : null;
                        
                        if (!expiryDate || expiryDate > now) {
                            // Coupon valide, stocker les informations
                            window.currentCoupon = foundCoupon;
                            couponStatus.dataset.valid = 'true';
                            
                            // Afficher le message de succès
                            if (foundCoupon.type === 'percentage') {
                                couponMessage.innerHTML = `<div class="alert alert-success mt-2"><i class="bi bi-check-circle-fill me-2"></i>Code valide: Remise de ${foundCoupon.value}% appliquée</div>`;
                            } else {
                                couponMessage.innerHTML = `<div class="alert alert-success mt-2"><i class="bi bi-check-circle-fill me-2"></i>Code valide: Remise de ${new Intl.NumberFormat('fr-FR').format(foundCoupon.value)} FCFA appliquée</div>`;
                            }
                            
                            // Mettre à jour les totaux
                            updateTotals();
                        } else {
                            couponMessage.innerHTML = '<div class="alert alert-danger mt-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Ce code promo a expiré</div>';
                            couponStatus.dataset.valid = 'false';
                            window.currentCoupon = null;
                        }
                    } else {
                        couponMessage.innerHTML = '<div class="alert alert-danger mt-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Code promo invalide ou indisponible</div>';
                        couponStatus.dataset.valid = 'false';
                        window.currentCoupon = null;
                    }
                } else {
                    couponMessage.innerHTML = '<div class="alert alert-info mt-2"><i class="bi bi-info-circle-fill me-2"></i>Aucun code promo disponible pour le moment</div>';
                    couponStatus.dataset.valid = 'false';
                    window.currentCoupon = null;
                }
            });
        }
        
        // Afficher les coupons disponibles
        if (window.availableCoupons && window.availableCoupons.length > 0 && availableCouponsList) {
            const couponCountBadge = document.getElementById('coupon-count-badge');
            if (couponCountBadge) {
                couponCountBadge.textContent = window.availableCoupons.length;
                couponCountBadge.style.display = 'inline-block';
            }
            
            const listGroup = availableCouponsList.querySelector('.list-group');
            if (listGroup) {
                window.availableCoupons.forEach(coupon => {
                    const listItem = document.createElement('button');
                    listItem.className = 'list-group-item list-group-item-action';
                    listItem.type = 'button';
                    
                    // Créer le contenu du coupon
                    let expiryDate = 'Non spécifiée';
                    if (coupon.expires_at) {
                        const date = new Date(coupon.expires_at);
                        expiryDate = date.toLocaleDateString('fr-FR');
                    }
                    
                    const discountText = coupon.type === 'percentage' 
                        ? `${coupon.value}% de réduction`
                        : `${new Intl.NumberFormat('fr-FR').format(coupon.value)} FCFA de réduction`;
                    
                    listItem.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${coupon.code}</strong>
                                <small class="d-block text-muted">Expire le: ${expiryDate}</small>
                            </div>
                            <span class="badge bg-success">${discountText}</span>
                        </div>`;
                    
                    // Ajouter un gestionnaire d'événements pour sélectionner ce coupon
                    listItem.addEventListener('click', function() {
                        couponCodeInput.value = coupon.code;
                        applyCouponBtn.click();
                    });
                    
                    listGroup.appendChild(listItem);
                });
                
                availableCouponsList.style.display = 'block';
            }
        }
    }
    
    // Fonction pour calculer l'équivalent en quota
    function calculateQuotaEquivalent(totalPrice) {
        const quotaEquivalentElement = document.getElementById('quota-message');
        const quotaRadio = document.getElementById('payment-quota');
        const availableQuota = {{ Auth::user()->getTotalAvailableQuota() ?? 0 }};
        
        if (!quotaEquivalentElement || !quotaRadio) return;
        
        // Taux de conversion (à ajuster selon votre logique métier)
        const quotaRate = 500; // 1kg = 500 FCFA
        
        // Calculer l'équivalent en kg
        const quotaEquivalent = totalPrice / quotaRate;
        
        // Afficher l'équivalent
        quotaEquivalentElement.innerHTML = `Équivalent en quota : <strong>${quotaEquivalent.toFixed(1)} kg</strong>`;
        
        // Vérifier si l'utilisateur a assez de quota
        if (availableQuota < quotaEquivalent) {
            quotaRadio.disabled = true;
            quotaRadio.checked = false;
            document.getElementById('payment-cash').checked = true;
            quotaEquivalentElement.classList.add('text-danger');
            quotaEquivalentElement.innerHTML += ' <small class="text-danger">(quota insuffisant)</small>';
        } else {
            quotaRadio.disabled = false;
            quotaEquivalentElement.classList.remove('text-danger');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser la gestion des bons et des codes promo
        setupVoucherHandling();
        setupCouponHandling();
        
        // Ajouter à la fonction updateStep existante pour mettre à jour l'affichage des paiements
        const originalUpdateStep = updateStep;
        updateStep = function() {
            // Appeler la fonction d'origine
            if (typeof originalUpdateStep === 'function') {
                originalUpdateStep();
            }
            
            // Mettre à jour l'affichage des paiements si nous sommes à l'étape 5
            if (currentStep === 5) {
                updatePaymentDisplay();
            }
        };
        
        // Remplir la modal des frais de livraison
        fillDeliveryFeesModal();
    });
    
    // Fonction pour remplir la modal des frais de livraison
    function fillDeliveryFeesModal() {
        const tableBody = document.getElementById('delivery-fees-table-body');
        if (!tableBody) return;
        
        // Vider le tableau
        tableBody.innerHTML = '';
        
        // Vérifier si les données de frais de livraison sont disponibles
        if (window.deliveryFeesData && window.deliveryFeesData.length > 0) {
            console.log("Nombre de frais de livraison:", window.deliveryFeesData.length);
            
            // Trier les quartiers par ordre alphabétique
            const sortedFees = [...window.deliveryFeesData].sort((a, b) => {
                return a.district.localeCompare(b.district);
            });
            
            // Stocker les données triées globalement pour la recherche
            window.sortedDeliveryFees = sortedFees;
            
            // Ajouter chaque quartier au tableau
            sortedFees.forEach(fee => {
                if (!fee.district || !fee.is_active) return;
                
                const row = document.createElement('tr');
                row.dataset.district = fee.district.toLowerCase();
                row.innerHTML = `
                    <td>${fee.district}</td>
                    <td class="text-end fw-bold">${new Intl.NumberFormat('fr-FR').format(fee.fee)} FCFA</td>
                `;
                tableBody.appendChild(row);
            });
            
            // Ajouter une entrée pour le tarif par défaut
            const defaultRow = document.createElement('tr');
            defaultRow.classList.add('table-info');
            defaultRow.dataset.district = "default";
            defaultRow.innerHTML = `
                <td><em>Autres quartiers (tarif par défaut)</em></td>
                <td class="text-end fw-bold">1000 FCFA</td>
            `;
            tableBody.appendChild(defaultRow);
            
            // Ajouter la fonctionnalité de recherche
            setupDeliveryFeesSearch();
        } else {
            // Message si aucune donnée n'est disponible
            const row = document.createElement('tr');
            row.innerHTML = `
                <td colspan="2" class="text-center">Aucun frais de livraison disponible pour le moment.</td>
            `;
            tableBody.appendChild(row);
        }
    }
    
    // Fonction pour configurer la recherche dans la modal des frais de livraison
    function setupDeliveryFeesSearch() {
        const searchInput = document.getElementById('delivery-fees-search');
        if (!searchInput) return;
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('#delivery-fees-table-body tr');
            
            rows.forEach(row => {
                const district = row.dataset.district;
                if (!district) return;
                
                // Afficher/masquer les lignes en fonction du terme de recherche
                if (searchTerm === '') {
                    row.style.display = '';
                } else if (district.includes(searchTerm) || (district === 'default' && searchTerm === 'autres')) {
                    row.style.display = '';
                    // Mettre en surbrillance le terme trouvé
                    if (district !== 'default') {
                        const districtCell = row.querySelector('td:first-child');
                        const districtText = districtCell.innerText;
                        const highlightedText = districtText.replace(
                            new RegExp(searchTerm, 'gi'),
                            match => `<mark>${match}</mark>`
                        );
                        districtCell.innerHTML = highlightedText;
                    }
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Afficher un message si aucun résultat n'est trouvé
            const visibleRows = document.querySelectorAll('#delivery-fees-table-body tr[style=""]');
            const noResultsRow = document.querySelector('#delivery-fees-table-body tr.no-results');
            
            if (visibleRows.length === 0 && searchTerm !== '') {
                if (!noResultsRow) {
                    const tableBody = document.getElementById('delivery-fees-table-body');
                    const row = document.createElement('tr');
                    row.classList.add('no-results');
                    row.innerHTML = `
                        <td colspan="2" class="text-center text-muted">Aucun quartier trouvé pour "${searchTerm}"</td>
                    `;
                    tableBody.appendChild(row);
                } else {
                    noResultsRow.style.display = '';
                    noResultsRow.querySelector('td').innerText = `Aucun quartier trouvé pour "${searchTerm}"`;
                }
            } else if (noResultsRow) {
                noResultsRow.style.display = 'none';
            }
        });
        
        // Réinitialiser la recherche lorsque la modal est fermée
        const modal = document.getElementById('deliveryFeesModal');
        modal.addEventListener('hidden.bs.modal', function() {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
        });
    }
</script>
@endpush

<!-- Modal pour afficher les frais de livraison -->
<div class="modal fade" id="deliveryFeesModal" tabindex="-1" aria-labelledby="deliveryFeesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="deliveryFeesModalLabel">
                    <i class="bi bi-truck me-2"></i> Frais de Livraison par Quartier
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Les frais de livraison sont calculés en fonction du quartier de collecte et de livraison.</p>
                
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" id="delivery-fees-search" placeholder="Rechercher un quartier..." aria-label="Rechercher un quartier">
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Quartier</th>
                                <th class="text-end">Frais (FCFA)</th>
                            </tr>
                        </thead>
                        <tbody id="delivery-fees-table-body">
                            <!-- Le contenu sera rempli dynamiquement via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
