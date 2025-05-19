@extends('layouts.dashboard')

@section('title', 'Créer une nouvelle commande - KLINKLIN')

@push('styles')
{{-- ASSUREZ-VOUS QUE FONT AWESOME EST CHARGÉ --}}
{{-- Exemple avec Font Awesome 6 --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    :root {
        --klin-primary: #4A148C; /* Violet */
        --klin-primary-dark: #38006b;
        --klin-text-muted: #6c757d;
        --klin-light-bg: #f8f5fc;
        --klin-border-color: #e0d8e7;

        /* --- Palette pour CARTE 1: COMMANDE AU KILO (Ex: Thème Violet) --- */
        --card1-bg: #ffffff;
        --card1-border: var(--klin-primary);
        --card1-icon-bg: var(--klin-primary);
        --card1-icon-text: white;
        --card1-title-text: var(--klin-primary);
        --card1-price-tag-bg: var(--klin-primary);
        --card1-features-icon-color: var(--klin-primary);
        --card1-button-bg: var(--klin-primary);
        --card1-button-text: white;
        --card1-button-hover-bg: var(--klin-primary-dark);

        /* --- Palette pour CARTE 2: COMMANDE PRESSING (Ex: Thème Orange/Corail Secondaire) --- */
        --klin-secondary-color: #f26d50;
        --klin-secondary-color-dark: #e05a3a;

        --card2-bg: #ffffff;
        --card2-border: var(--klin-secondary-color);
        --card2-icon-bg: var(--klin-secondary-color);
        --card2-icon-text: white;
        --card2-title-text: var(--klin-secondary-color);
        --card2-price-tag-bg: var(--klin-secondary-color);
        --card2-features-icon-color: var(--klin-secondary-color);
        --card2-button-bg: var(--klin-secondary-color);
        --card2-button-text: white;
        --card2-button-hover-bg: var(--klin-secondary-color-dark);
    }

    .order-selection-page .display-4 { font-size: 2.5rem; }
    .order-selection-page .text-muted { color: var(--klin-text-muted) !important; }
    .order-selection-page .btn-outline-primary {
        color: var(--klin-primary) !important; border-color: var(--klin-primary) !important;
    }
    .order-selection-page .btn-outline-primary:hover {
        background-color: var(--klin-primary) !important; color: white !important;
    }

    .order-type-card {
        transition: all 0.3s ease-in-out; cursor: pointer;
        border-width: 1px; border-style: solid; border-radius: 1rem;
        overflow: hidden; height: 100%; position: relative;
        box-shadow: 0 6px 18px rgba(74,20,140,.08);
        display: flex; flex-direction: column;
    }
    .order-type-card:hover {
        transform: translateY(-8px) scale(1.015);
        box-shadow: 0 12px 28px rgba(74,20,140,.15);
    }
    .order-type-card .card-body-content { text-align: left; padding: 1.5rem; }
    .order-type-card .icon-circle {
        width: 64px; height: 64px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 1.25rem; color: white; font-size: 2.25rem;
        transition: transform 0.3s ease; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .order-type-card:hover .icon-circle { transform: scale(1.1); }
    .order-type-card .card-title {
        font-size: 1.5rem; font-weight: 700; margin-bottom: 0.75rem;
    }
    .order-type-card .card-text {
        color: #555; margin-bottom: 1.25rem; font-size: 0.95rem; line-height: 1.6;
    }
    .order-type-card .btn-choose {
        border-radius: 50px; padding: 0.75rem 1.75rem; font-weight: 600;
        transition: all 0.2s ease; font-size: 1rem; border: none;
    }
    .order-type-card .card-body {
        display: flex; flex-direction: column; flex-grow: 1; padding: 0;
    }
    .order-type-card .button-container {
        padding: 0 1.5rem 1.5rem 1.5rem; margin-top: auto;
    }
    .order-type-card .btn-choose:hover {
        transform: translateY(-2px); box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    .order-type-card .price-tag {
        position: absolute; top: 1rem; right: 1rem; color: white;
        padding: 0.5rem 1rem; border-radius: 50px; font-weight: 700;
        font-size: 0.8rem; box-shadow: 0 2px 6px rgba(0,0,0,0.2); z-index: 10;
    }
    
    .features-list {
        margin: 1.25rem 0;
        padding-left: 0;
        list-style-type: none !important;
    }
    .features-list li {
        padding: 0.5rem 0;
        position: relative;
        padding-left: 2.2rem;
        font-size: 0.9rem;
        color: #444;
        line-height: 1.5;
        list-style-type: none !important;
    }
    .features-list li::before {
        content: "\f00c"; /* fa-check */
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Solid", "FontAwesome", sans-serif !important;
        font-weight: 900; /* Pour les icônes solides */
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1em; /* Ajuster si nécessaire, 1em = taille du texte du li */
        line-height: 1;
    }
    .features-list li strong { font-weight: 600; color: inherit; }

    .order-type-card.card-kilo { background-color: var(--card1-bg); border-color: var(--card1-border); }
    .order-type-card.card-kilo .icon-circle { background-color: var(--card1-icon-bg); color: var(--card1-icon-text); }
    .order-type-card.card-kilo .card-title { color: var(--card1-title-text); }
    .order-type-card.card-kilo .price-tag { background-color: var(--card1-price-tag-bg); }
    .order-type-card.card-kilo .features-list li::before { color: var(--card1-features-icon-color); }
    .order-type-card.card-kilo .features-list li strong { color: var(--card1-features-icon-color); }
    .order-type-card.card-kilo .btn-choose { background-color: var(--card1-button-bg); color: var(--card1-button-text); }
    .order-type-card.card-kilo .btn-choose:hover { background-color: var(--card1-button-hover-bg); }

    .order-type-card.card-pressing { background-color: var(--card2-bg); border-color: var(--card2-border); }
    .order-type-card.card-pressing .icon-circle { background-color: var(--card2-icon-bg); color: var(--card2-icon-text); }
    .order-type-card.card-pressing .card-title { color: var(--card2-title-text); }
    .order-type-card.card-pressing .price-tag { background-color: var(--card2-price-tag-bg); }
    .order-type-card.card-pressing .features-list li::before { color: var(--card2-features-icon-color); }
    .order-type-card.card-pressing .features-list li strong { color: var(--card2-features-icon-color); }
    .order-type-card.card-pressing .btn-choose { background-color: var(--card2-button-bg); color: var(--card2-button-text); }
    .order-type-card.card-pressing .btn-choose:hover { background-color: var(--card2-button-hover-bg); }

</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endpush

@section('content')
<div class="container order-selection-page py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-auto">
            <div style="background-color: var(--klin-light-bg); border-radius: 50%; padding: 0.6rem; display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; box-shadow: 0 3px 10px rgba(74,20,140,.1);">
                <i class="bi bi-basket-fill fs-2" style="color: var(--klin-primary) !important;"></i>
            </div>
        </div>
        <div class="col">
            <h1 class="display-4 fw-bolder mb-1" style="color: var(--klin-primary);">Créer une commande</h1>
            <p class="text-muted fs-5 mb-0">Sélectionnez le type de commande qui vous convient.</p>
        </div>
        <div class="col-12 col-md-auto text-md-end mt-3 mt-md-0">
            <a href="{{ route('orders.index') }}" class="btn btn-lg rounded-pill shadow-sm px-4 py-2" style="border-color: var(--klin-primary); color: var(--klin-primary);">
                <i class="bi bi-list-ul me-2"></i> Mes commandes
            </a>
        </div>
    </div>
    
    <div class="row justify-content-center g-md-5 g-4 my-4">
        <div class="col-lg-6 col-md-10 col-sm-11 col-12 d-flex">
            <div class="order-type-card card-kilo animate__animated animate__fadeInLeft w-100">
                <div class="price-tag">{{ number_format(App\Models\PriceConfiguration::getCurrentPricePerKg(1000), 0, ',', ' ') }} FCFA / kg</div>
                <div class="card-body">
                    <div class="card-body-content">
                    <div class="icon-circle">
                            <i class="bi bi-box-seam"></i> {{-- Vous pouvez remplacer par une icône Font Awesome ici aussi si vous le souhaitez, ex: <i class="fas fa-box-open"></i> --}}
                        </div>
                        <h3 class="card-title">Au Kilo par KlinKlin</h3>
                        <p class="card-text">La solution idéale et économique pour votre linge du quotidien : vêtements de tous les jours, draps, serviettes, etc. Nous nous occupons de tout avec le plus grand soin.</p>
                        <ul class="features-list">
                            <li>Tarification <strong>transparente et avantageuse</strong> au poids.</li>
                            <li>Idéal pour les <strong>grandes lessives</strong> et les besoins réguliers de la famille.</li>
                            <li>Vos vêtements sont triés, lavés, séchés et <strong>soigneusement pliés</strong>.</li>
                            <li>Service rapide : collecte et livraison généralement effectuées en <strong>24 à 48 heures</strong>.</li>
                            <li>Respect des fibres et des couleurs pour une propreté impeccable.</li>
                            <li>Suivi de votre commande en temps réel via votre espace client.</li>
                        </ul>
                    </div>
                    <div class="button-container">
                        <a href="{{ route('orders.create.quota') }}" class="btn btn-choose btn-lg d-block">
                            <i class="bi bi-arrow-right-circle me-2"></i>Choisir Linge au Kilo
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 col-md-10 col-sm-11 col-12 d-flex">
            <div class="order-type-card card-pressing animate__animated animate__fadeInRight w-100">
                <div class="price-tag">Tarifs par Article</div>
                <div class="card-body">
                    <div class="card-body-content">
                    <div class="icon-circle">
                            <i class="bi bi-shop"></i> {{-- Vous pouvez remplacer par une icône Font Awesome ici aussi, ex: <i class="fas fa-tshirt"></i> --}}
                        </div>
                        <h3 class="card-title">Service Pressing Expert</h3>
                        <p class="card-text">Confiez-nous vos articles les plus délicats ou nécessitant un traitement spécifique (costumes, robes de soirée, manteaux, soie, cachemire...). Confiez-les à nos experts.</p>
                        <ul class="features-list">
                            <li>Nettoyage <strong>à sec ou aquanettoyage professionnel</strong> adapté à chaque textile.</li>
                            <li>Parfait pour les <strong>vêtements délicats</strong>, de cérémonie, ou professionnels.</li>
                            <li>Détachage professionnel et <strong>repassage de haute qualité</strong>.</li>
                            <li>Collaboration avec un réseau de <strong>pressings partenaires qualifiés</strong> et reconnus.</li>
                            <li>Prolongez la durée de vie et l'éclat de vos plus belles pièces.</li>
                            <li>Options de traitement anti-acariens ou imperméabilisant disponibles.</li>
                        </ul>
                    </div>
                     <div class="button-container">
                        <a href="{{ route('orders.create.pressing') }}" class="btn btn-choose btn-lg d-block">
                            <i class="bi bi-arrow-right-circle me-2"></i>Choisir Service Pressing
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-5 mb-4 py-3">
        <p class="text-muted fs-5">
            <i class="bi bi-info-circle-fill me-2" style="color: var(--klin-primary); opacity:0.7;"></i>
            Besoin d'aide pour choisir ? Contactez notre service client au <strong style="color: var(--klin-primary);">+242 06 934 91 60</strong>
        </p>
    </div>
</div>
@endsection 