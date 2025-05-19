@extends('layouts.admin')

@section('title', 'Créer une commande - KLINKLIN Admin')

{{-- Utilisation de @section('page_title') si votre layout admin le gère spécifiquement --}}
@section('page_title')
    <div class="d-sm-flex align-items-center justify-content-between">
        <h1 class="h3 mb-0 text-gray-800">Nouvelle Commande</h1>
        {{-- Peut-être un bouton retour ici si pertinent dans le layout admin --}}
        {{-- <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-klin-primary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a> --}}
    </div>
@endsection


@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shopping-cart me-2"></i> Sélectionnez le type de commande
                    </h6>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Erreurs de validation</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row justify-content-center g-4 my-4">
                        <div class="col-lg-6 col-md-10 col-sm-11 col-12 d-flex">
                            <div class="order-type-card card-kilo w-100">
                                <div class="price-tag">{{ number_format(App\Models\PriceConfiguration::getCurrentPricePerKg(1000), 0, ',', ' ') }} FCFA / kg</div>
                                <div class="card-body">
                                    <div class="card-body-content">
                                        <div class="icon-circle">
                                            <i class="fas fa-box-open"></i>
                                        </div>
                                        <h3 class="card-title">Au Kilo par KlinKlin</h3>
                                        <p class="card-text">La solution idéale et économique pour le linge du quotidien : vêtements de tous les jours, draps, serviettes, etc.</p>
                                        <ul class="features-list">
                                            <li>Tarification <strong>transparente et avantageuse</strong> au poids.</li>
                                            <li>Idéal pour les <strong>grandes lessives</strong> et les besoins réguliers.</li>
                                            <li>Vêtements triés, lavés, séchés et <strong>soigneusement pliés</strong>.</li>
                                            <li>Service rapide : collecte et livraison en <strong>24 à 48 heures</strong>.</li>
                                        </ul>
                                    </div>
                                    <div class="button-container">
                                        <a href="{{ route('admin.orders.create.quota.select-user') }}" class="btn btn-choose btn-lg d-block">
                                            <i class="fas fa-arrow-right me-2"></i>Choisir Linge au Kilo
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-md-10 col-sm-11 col-12 d-flex">
                            <div class="order-type-card card-pressing w-100">
                                <div class="price-tag">Tarifs par Article</div>
                                <div class="card-body">
                                    <div class="card-body-content">
                                        <div class="icon-circle">
                                            <i class="fas fa-tshirt"></i>
                                        </div>
                                        <h3 class="card-title">Service Pressing Expert</h3>
                                        <p class="card-text">Pour les articles délicats ou nécessitant un traitement spécifique (costumes, robes de soirée, manteaux, soie, cachemire...).</p>
                                        <ul class="features-list">
                                            <li>Nettoyage <strong>à sec ou aquanettoyage professionnel</strong> adapté.</li>
                                            <li>Parfait pour les <strong>vêtements délicats</strong> ou de cérémonie.</li>
                                            <li>Détachage professionnel et <strong>repassage de haute qualité</strong>.</li>
                                            <li>Réseau de <strong>pressings partenaires qualifiés</strong> et reconnus.</li>
                                        </ul>
                                    </div>
                                    <div class="button-container">
                                        <a href="{{ route('admin.orders.create.pressing.select-user') }}" class="btn btn-choose btn-lg d-block">
                                            <i class="fas fa-arrow-right me-2"></i>Choisir Service Pressing
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
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
        font-family: "Font Awesome 5 Free", "FontAwesome", sans-serif !important;
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
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderTypeKiloRadio = document.getElementById('order_type_kilogram');
    const orderTypePressingRadio = document.getElementById('order_type_pressing');
    const pressingSelectionDiv = document.getElementById('pressing_selection');
    const pressingIdSelect = document.getElementById('pressing_id');

    // Conteneurs des radio buttons pour changer le style
    const kiloContainer = orderTypeKiloRadio.closest('.form-check');
    const pressingContainer = orderTypePressingRadio.closest('.form-check');

    function updateRadioStyles() {
        if (orderTypeKiloRadio.checked) {
            kiloContainer.style.backgroundColor = 'var(--klin-accent-pickup, #e6f2ff)';
            kiloContainer.style.borderColor = 'var(--klin-accent-pickup-text, #005cb9)';
            kiloContainer.querySelector('strong').style.color = 'var(--klin-accent-pickup-text, #005cb9)';

            pressingContainer.style.backgroundColor = 'var(--klin-light-bg, #f8f5fc)';
            pressingContainer.style.borderColor = 'var(--klin-border-color, #e0d8e7)';
            pressingContainer.querySelector('strong').style.color = 'var(--klin-primary, #4A148C)';
        } else if (orderTypePressingRadio.checked) {
            pressingContainer.style.backgroundColor = 'var(--klin-accent-delivery, #e4f8f0)';
            pressingContainer.style.borderColor = 'var(--klin-accent-delivery-text, #006a4e)';
            pressingContainer.querySelector('strong').style.color = 'var(--klin-accent-delivery-text, #006a4e)';

            kiloContainer.style.backgroundColor = 'var(--klin-light-bg, #f8f5fc)';
            kiloContainer.style.borderColor = 'var(--klin-border-color, #e0d8e7)';
            kiloContainer.querySelector('strong').style.color = 'var(--klin-primary, #4A148C)';
        }
    }

    function togglePressingSelection() {
        if (orderTypePressingRadio.checked) {
            pressingSelectionDiv.style.display = 'block';
            pressingIdSelect.setAttribute('required', 'required');
        } else {
            pressingSelectionDiv.style.display = 'none';
            pressingIdSelect.removeAttribute('required');
            pressingIdSelect.value = ''; // Réinitialiser la sélection
        }
        updateRadioStyles(); // Mettre à jour les styles des radios
    }

    orderTypeKiloRadio.addEventListener('change', togglePressingSelection);
    orderTypePressingRadio.addEventListener('change', togglePressingSelection);

    // Initialiser l'état et les styles au chargement
    togglePressingSelection();
});
</script>
@endpush