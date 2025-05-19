@extends('layouts.dashboard')

@section('title', 'Créer une nouvelle commande - KLINKLIN')

@push('styles')
<style>
    :root {
        --klin-primary: #4A148C;
        --klin-primary-dark: #38006b;
        --klin-secondary: #f26d50; /* Orange/Corail */
        --klin-light-bg: #f8f5fc;
        --klin-border-color: #e0d8e7;
        --klin-text-muted: #6c757d;
        --klin-success: #28a745;
        --klin-warning: #ffc107;
        --klin-danger: #dc3545;
        --klin-info: #0dcaf0;
        --klin-input-bg: #fff; /* Fond des inputs */
    }
    .order-creation-page .display-5 { font-size: 2.25rem; }
    .text-klin-primary { color: var(--klin-primary) !important; }
    .klin-btn {
        background-color: var(--klin-primary) !important;
        border-color: var(--klin-primary) !important;
        color: white !important;
        transition: background-color 0.2s ease, transform 0.2s ease;
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
    }
    .klin-btn:hover {
        background-color: var(--klin-primary-dark) !important;
        border-color: var(--klin-primary-dark) !important;
        transform: translateY(-2px);
    }
    .klin-btn-outline {
        color: var(--klin-primary) !important;
        border-color: var(--klin-primary) !important;
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
    }
    .klin-btn-outline:hover {
        background-color: var(--klin-primary) !important;
        color: white !important;
    }
    .form-step h2 .bi { color: var(--klin-primary); }
    .form-label { font-weight: 500; color: #333; }
    .form-control, .form-select {
        border-radius: 0.375rem;
        border: 1px solid var(--klin-border-color);
        background-color: var(--klin-input-bg);
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--klin-primary);
        box-shadow: 0 0 0 0.25rem rgba(74, 20, 140, 0.25);
    }
    .progress-bar-custom { margin-bottom: 3rem !important; }
    .progress-bar-custom .step-custom {
        flex-basis: 100px; flex-shrink: 0; color: var(--klin-text-muted); font-size: 0.85em;
    }
    .progress-bar-custom .step-icon {
        width: 35px; height: 35px; border-radius: 50%; background-color: #e0e0e0;
        color: white; display: flex; justify-content: center; align-items: center;
        margin: 0 auto 8px auto; border: 2px solid #e0e0e0; font-weight: bold;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }
    .progress-bar-custom .step-icon span { font-size: 0.9rem; }
    .progress-bar-custom .step-custom.active .step-icon { background-color: var(--klin-primary); border-color: var(--klin-primary); }
    .progress-bar-custom .step-custom.active .step-text { color: var(--klin-primary); font-weight: bold; }
    .progress-bar-custom .step-custom.completed .step-icon { background-color: var(--klin-success); border-color: var(--klin-success); }
    .progress-bar-custom .step-custom.completed .step-text { color: var(--klin-success); }
    .progress-bar-custom .progress-line-custom {
        height: 4px; background-color: #e0e0e0; margin-top: 17px;
        transition: background-color 0.3s ease;
    }
    .progress-bar-custom .progress-line-custom.active { background-color: var(--klin-primary); }
    
    /* Style pour les services de pressing */
    #servicesContainer .service-item .card {
        border: 1px solid var(--klin-border-color);
        border-radius: 0.5rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    #servicesContainer .service-item .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.1);
    }
    #servicesContainer .card-title { font-size: 1rem; font-weight: 600; color: var(--klin-primary);}
    #servicesContainer .quantity-selector input { border-left: none; border-right: none;}
    #servicesContainer .quantity-selector .btn { border-color: #ced4da; }
    
    /* Style pour le récapitulatif */
    #step-4 .card { border-left: 3px solid var(--klin-primary); margin-bottom: 1.5rem !important; }
    #step-4 .card-header { background-color: var(--klin-light-bg); border-bottom: 1px solid var(--klin-border-color); }
    #step-4 .card-header h5 { font-weight: 600; color: var(--klin-primary); }
    #summary-items td, #summary-items th { padding: 0.5rem; font-size:0.9rem; }
    #summary-total { font-size: 1.25rem; color: var(--klin-primary); }
    
    /* Style pour le paiement */
    #step-5 .form-check-label { display: flex; align-items: center; font-size: 1rem; padding: 0.75rem; border: 1px solid var(--klin-border-color); border-radius: 0.375rem; cursor: pointer; transition: background-color 0.2s, border-color 0.2s; }
    #step-5 .form-check-label:hover { background-color: var(--klin-light-bg); }
    #step-5 .form-check-input:checked + .form-check-label { background-color: var(--klin-light-bg); border-color: var(--klin-primary); color: var(--klin-primary); font-weight: 500; }
    #step-5 .form-check-input { display: none; }
    #step-5 .form-check-label .bi { font-size: 1.5rem; color: var(--klin-primary); }
    
    .card.shadow-sm { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; }
    
    /* Animations pour les étapes */
    .form-step { display: none; }
    .form-step.active { display: block; animation: fadeIn 0.5s; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    
    /* Style pour les sélecteurs d'adresse */
    .address-selector { margin-bottom: 1rem; }
    .address-selector .form-select { margin-bottom: 0.5rem; }
    
    /* Style pour les formulaires */
    .new-address-form {
        display: none;
        margin-top: 1rem;
        padding: 1rem;
        border: 1px solid var(--klin-border-color);
        border-radius: 0.5rem;
        background-color: var(--klin-light-bg);
    }
    .new-address-form.active { display: block; }
    .btn-add-address { margin-top: 0.5rem; }

    /* Style pour les messages d'erreur de validation */
    .invalid-feedback.d-block {
        display: block !important;
        width: 100%;
        margin-top: .25rem;
        font-size: .875em;
        color: #dc3545;
    }
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545 !important;
    }
    
    /* Style spécifique pour les catégories de pressing */
    .category-tab {
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .category-tab:hover,
    .category-tab.active {
        background-color: var(--klin-light-bg);
        border-left: 3px solid var(--klin-primary);
    }
    .category-tab.active {
        font-weight: 600;
    }
    .category-tab .icon {
        font-size: 1.5rem;
        color: var(--klin-primary);
        margin-right: 10px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid order-creation-page">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h1 class="display-5 fw-bold text-klin-primary">Créer une Commande Pressing</h1>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            <strong>Erreur :</strong> {!! nl2br(e(session('error'))) !!}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Erreurs de validation :</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Barre de progression -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="progress-bar-custom d-flex justify-content-between align-items-center">
                <div class="step-custom text-center active" data-step="1">
                    <div class="step-text">Collecte</div>
                    <div class="step-icon"><span>1</span></div>
                </div>
                <div class="progress-line-custom flex-grow-1"></div>
                <div class="step-custom text-center" data-step="2">
                    <div class="step-text">Livraison</div>
                    <div class="step-icon"><span>2</span></div>
                </div>
                <div class="progress-line-custom flex-grow-1"></div>
                <div class="step-custom text-center" data-step="3">
                    <div class="step-text">Services</div>
                    <div class="step-icon"><span>3</span></div>
                </div>
                <div class="progress-line-custom flex-grow-1"></div>
                <div class="step-custom text-center" data-step="4">
                    <div class="step-text">Récapitulatif</div>
                    <div class="step-icon"><span>4</span></div>
                </div>
                <div class="progress-line-custom flex-grow-1"></div>
                <div class="step-custom text-center" data-step="5">
                    <div class="step-text">Paiement</div>
                    <div class="step-icon"><span>5</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-lg-5 p-4">
            <form id="orderForm" action="{{ route('orders.store') }}" method="POST">
                @csrf
                <input type="hidden" name="form_submitted" value="true">
                <input type="hidden" name="order_type" value="pressing">
                <input type="hidden" name="pressing_id" value="{{ $tempCart['pressing_id'] ?? ($pressings->first()->id ?? 1) }}">
                
                <!-- Étape 1: Collecte -->
                <div class="form-step active" id="step-1">
                    <h3 class="mb-4 text-center">Adresse et date de collecte</h3>
                    
                    <div class="mb-3">
                        <label for="pressing_id_selector" class="form-label">Sélectionner un pressing <span class="text-danger">*</span></label>
                        <select class="form-select @error('pressing_id_selector') is-invalid @enderror" id="pressing_id_selector">
                            @foreach($pressings as $pressing)
                                <option value="{{ $pressing->id }}" @if(old('pressing_id_selector') == $pressing->id || (isset($tempCart['pressing_id']) && $tempCart['pressing_id'] == $pressing->id)) selected @endif>
                                    {{ $pressing->name }} - {{ $pressing->address }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Choisissez le pressing auquel vous souhaitez confier vos vêtements.</small>
                        @error('pressing_id_selector')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="collection_address_id" class="form-label">Adresse de collecte <span class="text-danger">*</span></label>
                        <select class="form-select @error('collection_address_id') is-invalid @enderror" id="collection_address_id" name="collection_address_id" required>
                            <option value="">Sélectionner une adresse</option>
                            @foreach($addresses as $address)
                                <option value="{{ $address->id }}" @if(old('collection_address_id') == $address->id || (isset($tempCart['collection_address_id']) && $tempCart['collection_address_id'] == $address->id)) selected @endif>
                                    {{ $address->name }} - {{ $address->address }}, {{ $address->neighborhood }}
                                </option>
                            @endforeach
                        </select>
                        @error('collection_address_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="mt-2">
                            <a href="{{ route('addresses.create', ['redirect_to_order_creation' => true]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus-circle"></i> Ajouter une nouvelle adresse
                            </a>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <label for="collection_date" class="form-label">Date de collecte <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('collection_date') is-invalid @enderror" id="collection_date" name="collection_date" value="{{ old('collection_date') ?? $tempCart['collection_date'] ?? date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                            @error('collection_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="collection_time_slot" class="form-label">Créneau horaire <span class="text-danger">*</span></label>
                            <select class="form-select @error('collection_time_slot') is-invalid @enderror" id="collection_time_slot" name="collection_time_slot" required>
                                <option value="">Sélectionner un créneau</option>
                                @foreach($timeSlots as $value => $label)
                                    <option value="{{ $value }}" @if(old('collection_time_slot') == $value || (isset($tempCart['collection_time_slot']) && $tempCart['collection_time_slot'] == $value)) selected @endif>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('collection_time_slot')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Étape 2: Livraison -->
                <div class="form-step" id="step-2">
                    <h3 class="mb-4 text-center">Adresse et date de livraison</h3>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="same_address_for_delivery" name="same_address_for_delivery" @if(old('same_address_for_delivery') || (isset($tempCart['same_address_for_delivery']) && $tempCart['same_address_for_delivery'])) checked @endif>
                            <label class="form-check-label" for="same_address_for_delivery">
                                Même adresse que pour la collecte
                            </label>
                        </div>
                    </div>
                    
                    <div id="delivery-address-fields" class="mb-3" @if(old('same_address_for_delivery') || (isset($tempCart['same_address_for_delivery']) && $tempCart['same_address_for_delivery'])) style="display: none;" @endif>
                        <label for="delivery_address_id" class="form-label">Adresse de livraison <span class="text-danger">*</span></label>
                        <select class="form-select @error('delivery_address_id') is-invalid @enderror" id="delivery_address_id" name="delivery_address_id" @if(!old('same_address_for_delivery') && !(isset($tempCart['same_address_for_delivery']) && $tempCart['same_address_for_delivery'])) required @endif>
                            <option value="">Sélectionner une adresse</option>
                            @foreach($addresses as $address)
                                <option value="{{ $address->id }}" @if(old('delivery_address_id') == $address->id || (isset($tempCart['delivery_address_id']) && $tempCart['delivery_address_id'] == $address->id)) selected @endif>
                                    {{ $address->name }} - {{ $address->address }}, {{ $address->neighborhood }}
                                </option>
                            @endforeach
                        </select>
                        @error('delivery_address_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <label for="delivery_date" class="form-label">Date de livraison <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" id="delivery_date" name="delivery_date" value="{{ old('delivery_date') ?? $tempCart['delivery_date'] ?? '' }}" required>
                            <small class="text-muted">Date minimum : le lendemain de la collecte</small>
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="delivery_time_slot" class="form-label">Créneau horaire <span class="text-danger">*</span></label>
                            <select class="form-select @error('delivery_time_slot') is-invalid @enderror" id="delivery_time_slot" name="delivery_time_slot" required>
                                <option value="">Sélectionner un créneau</option>
                                @foreach($timeSlots as $value => $label)
                                    <option value="{{ $value }}" @if(old('delivery_time_slot') == $value || (isset($tempCart['delivery_time_slot']) && $tempCart['delivery_time_slot'] == $value)) selected @endif>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('delivery_time_slot')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="delivery_notes" class="form-label">Instructions de livraison <small class="text-muted">(facultatif)</small></label>
                        <textarea class="form-control @error('delivery_notes') is-invalid @enderror" id="delivery_notes" name="delivery_notes" rows="3" placeholder="Instructions spéciales pour la livraison, code d'entrée, étage, etc.">{{ old('delivery_notes') ?? $tempCart['delivery_notes'] ?? '' }}</textarea>
                        <small class="text-muted">Ces informations aideront notre livreur à vous retrouver plus facilement.</small>
                        @error('delivery_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Étape 3: Sélection des services -->
                <div class="form-step" id="step-3">
                    <h3 class="mb-4 text-center">Sélection des services de pressing</h3>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <!-- Catégories de services -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Catégories</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush" id="categories-list">
                                        <a href="#cat-all" class="category-tab d-flex align-items-center active" data-category="all">
                                            <i class="bi bi-grid-fill icon"></i>
                                            <span>Tous les services</span>
                                        </a>
                                        <!-- Les catégories seront chargées dynamiquement via JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-9">
                            <!-- Liste des services -->
                            <div id="servicesContainer" class="row">
                                <div class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Chargement...</span>
                                    </div>
                                    <p class="mt-2">Chargement des services...</p>
                                </div>
                            </div>
                            
                            <!-- Calculateur de prix -->
                            <div class="card sticky-top mt-4" style="top: 20px;">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0"><i class="bi bi-calculator me-2"></i>Total estimé</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0">Prix total:</h5>
                                        <h4 class="mb-0"><span id="estimatedPriceDisplay">0</span> FCFA</h4>
                                    </div>
                                    <hr>
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Les prix peuvent varier selon l'état des vêtements.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Étape 4: Récapitulatif -->
                <div class="form-step" id="step-4">
                    <h3 class="mb-4 text-center">Récapitulatif de votre commande</h3>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-box me-2"></i>Type de commande</h5>
                                </div>
                                <div class="card-body">
                                    <div id="summary-order-type" class="mb-3">
                                        <span class="badge bg-primary p-2 fs-6">Commande Pressing</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Dates</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <p class="mb-1"><strong>Collecte :</strong></p>
                                            <p id="summary-collection-date">Date à définir</p>
                                        </div>
                                        <div class="col-6">
                                            <p class="mb-1"><strong>Livraison :</strong></p>
                                            <p id="summary-delivery-date">Date à définir</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Adresse de collecte</h5>
                                </div>
                                <div class="card-body">
                                    <div id="summary-collection-address">À définir</div>
                                    <div class="mt-2" id="summary-collection-time">À définir</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Adresse de livraison</h5>
                                </div>
                                <div class="card-body">
                                    <div id="summary-delivery-address">À définir</div>
                                    <div class="mt-2" id="summary-delivery-time">À définir</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Résumé des services de pressing -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-shop me-2"></i>Services de pressing sélectionnés</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Service</th>
                                            <th class="text-center">Quantité</th>
                                            <th class="text-center">Prix unitaire</th>
                                            <th class="text-end">Prix total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="summary-items">
                                        <tr>
                                            <td colspan="4" class="text-center">Aucun service sélectionné</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">Total</th>
                                            <th class="text-end"><span id="summary-total">0</span> FCFA</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Étape 5: Paiement -->
                <div class="form-step" id="step-5">
                    <h3 class="mb-4 text-center">Mode de paiement</h3>
                    
                    <div class="text-center mb-4">
                        <h4>Total à payer : <span id="payment-total" class="text-primary fw-bold">0</span> FCFA</h4>
                    </div>
                    
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Choisissez votre méthode de paiement</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-3 p-3 border rounded @if(old('payment_method') == 'cash') border-primary bg-light @endif">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment-cash" value="cash" checked>
                                        <label class="form-check-label w-100" for="payment-cash">
                                            <strong><i class="bi bi-cash me-2"></i> Paiement à la livraison</strong>
                                            <div class="text-muted small">Payez en espèces lorsque nous livrons votre linge propre.</div>
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-3 p-3 border rounded opacity-75">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment-mobile" value="mobile_money" disabled>
                                        <label class="form-check-label w-100" for="payment-mobile">
                                            <strong><i class="bi bi-phone me-2"></i> Mobile Money</strong>
                                            <div class="text-muted small">Service temporairement indisponible.</div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons de navigation -->
                <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                    <button type="button" class="btn klin-btn-outline prev-step" style="display: none;">
                        <i class="bi bi-arrow-left me-2"></i>Précédent
                    </button>
                    <button type="button" class="btn klin-btn next-step ms-auto">
                        Suivant<i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Définir les variables globales
        let currentStep = 1;
        const totalSteps = 5;
        const orderForm = document.getElementById('orderForm');
        const prevBtn = document.querySelector('.prev-step');
        const nextBtn = document.querySelector('.next-step');
        const steps = document.querySelectorAll('.form-step');
        const progressSteps = document.querySelectorAll('.step-custom');
        const progressLines = document.querySelectorAll('.progress-line-custom');
        let pressingServices = []; // Pour stocker les services chargés
        let categories = new Set(); // Pour stocker les catégories uniques
        
        // Fonction pour mettre à jour l'affichage des étapes
        function updateStep() {
            // Masquer toutes les étapes
            steps.forEach(step => step.classList.remove('active'));
            
            // Afficher l'étape courante
            steps[currentStep - 1].classList.add('active');
            
            // Mettre à jour l'affichage des boutons de navigation
            if (currentStep === 1) {
                prevBtn.style.display = 'none';
            } else {
                prevBtn.style.display = 'block';
            }
            
            if (currentStep === totalSteps) {
                nextBtn.innerHTML = 'Finaliser la commande <i class="bi bi-check-circle ms-2"></i>';
            } else {
                nextBtn.innerHTML = 'Suivant <i class="bi bi-arrow-right ms-2"></i>';
            }
            
            // Mettre à jour la barre de progression
            progressSteps.forEach((step, index) => {
                if (index < currentStep) {
                    step.classList.add('completed');
                    step.classList.remove('active');
                } else if (index === currentStep - 1) {
                    step.classList.add('active');
                    step.classList.remove('completed');
                } else {
                    step.classList.remove('active', 'completed');
                }
            });
            
            progressLines.forEach((line, index) => {
                if (index < currentStep - 1) {
                    line.classList.add('active');
                } else {
                    line.classList.remove('active');
                }
            });
            
            // Si nous passons à l'étape 3, charger les services
            if (currentStep === 3) {
                loadPressingServices();
            }
        }
        
        // Fonction pour charger les services de pressing
        function loadPressingServices() {
            const pressingId = document.querySelector('input[name="pressing_id"]').value;
            if (!pressingId) {
                showError('Veuillez sélectionner un pressing avant de continuer.');
                return;
            }
            
            const servicesContainer = document.getElementById('servicesContainer');
            servicesContainer.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement des services...</p>
                </div>
            `;
            
            // Appel à l'API pour récupérer les services du pressing avec notre nouveau modèle
            fetch(`/api/pressings/${pressingId}/new-services`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Impossible de charger les services. Veuillez réessayer.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.services) {
                        pressingServices = data.services;
                        renderServices(pressingServices);
                        
                        // Construire la liste des catégories en utilisant les catégories fournies
                        if (data.categories) {
                            const categoriesList = document.getElementById('categories-list');
                            categoriesList.innerHTML = `
                                <a href="#cat-all" class="category-tab d-flex align-items-center active" data-category="all">
                                    <i class="bi bi-grid-fill icon"></i>
                                    <span>Tous les services</span>
                                </a>
                            `;
                            
                            data.categories.forEach(category => {
                                const tabHtml = `
                                    <a href="#cat-${category.name}" class="category-tab d-flex align-items-center" data-category="${category.name}">
                                        <i class="${category.icon || 'bi-tag'} icon"></i>
                                        <span>${category.name}</span>
                                    </a>
                                `;
                                categoriesList.insertAdjacentHTML('beforeend', tabHtml);
                            });
                            
                            // Attacher les écouteurs d'événements pour le filtrage par catégorie
                            attachCategoryListeners();
                        }
                    } else {
                        throw new Error('Aucun service disponible pour ce pressing.');
                    }
                })
                .catch(error => {
                    servicesContainer.innerHTML = `
                        <div class="alert alert-danger col-12">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            ${error.message || 'Une erreur est survenue lors du chargement des services.'}
                        </div>
                    `;
                });
        }
        
        // Fonction pour afficher les services
        function renderServices(services, category = 'all') {
            const servicesContainer = document.getElementById('servicesContainer');
            servicesContainer.innerHTML = '';
            
            if (services.length === 0) {
                servicesContainer.innerHTML = `
                    <div class="alert alert-info col-12">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Aucun service disponible dans cette catégorie.
                    </div>
                `;
                return;
            }
            
            services.forEach(service => {
                // Si un filtre de catégorie est appliqué et que ce n'est pas 'all'
                if (category !== 'all' && service.category !== category) {
                    return;
                }
                
                const serviceHtml = `
                    <div class="col-md-6 mb-4 service-item" data-category="${service.category}">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between">
                                    <span>${service.name}</span>
                                    <span class="badge bg-primary">${service.formatted_price}</span>
                                </h5>
                                <p class="card-text">
                                    <small class="text-muted">${service.description || 'Aucune description disponible'}</small>
                                </p>
                                <div class="input-group mt-3">
                                    <button type="button" class="btn btn-outline-secondary quantity-minus">-</button>
                                    <input type="number" class="form-control text-center item-quantity" 
                                        name="pressing_services[${service.id}][quantity]" value="0" min="0" 
                                        data-price="${service.price}" data-service-id="${service.id}">
                                    <button type="button" class="btn btn-outline-secondary quantity-plus">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                servicesContainer.insertAdjacentHTML('beforeend', serviceHtml);
            });
            
            // Réattacher les écouteurs d'événements pour les boutons de quantité
            attachQuantityListeners();
        }
        
        // Attacher les écouteurs d'événements pour les onglets de catégorie
        function attachCategoryListeners() {
            const categoryTabs = document.querySelectorAll('.category-tab');
            categoryTabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Supprimer la classe active de tous les onglets
                    categoryTabs.forEach(t => t.classList.remove('active'));
                    
                    // Ajouter la classe active à l'onglet courant
                    this.classList.add('active');
                    
                    const category = this.dataset.category;
                    renderServices(pressingServices, category);
                });
            });
        }
        
        // Attacher les écouteurs d'événements pour les boutons de quantité
        function attachQuantityListeners() {
            const quantityButtons = document.querySelectorAll('.quantity-plus, .quantity-minus');
            quantityButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.parentNode.querySelector('input');
                    let value = parseInt(input.value || 0);
                    
                    if (this.classList.contains('quantity-plus')) {
                        value++;
                    } else if (this.classList.contains('quantity-minus') && value > 0) {
                        value--;
                    }
                    
                    input.value = value;
                    updateTotals();
                });
            });
            
            // Écouter les changements de quantité directs sur les inputs
            document.querySelectorAll('.item-quantity').forEach(input => {
                input.addEventListener('change', updateTotals);
            });
        }
        
        // Fonction pour afficher une erreur
        function showError(message) {
            const servicesContainer = document.getElementById('servicesContainer');
            servicesContainer.innerHTML = `
                <div class="alert alert-danger col-12">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    ${message}
                </div>
            `;
        }
        
        // Fonction pour valider l'étape courante
        function validateCurrentStep() {
            let isValid = true;
            
            // Validation spécifique pour l'étape 3 (Services)
            if (currentStep === 3) {
                // Vérifier que des services pressing sont sélectionnés
                let hasServices = false;
                document.querySelectorAll('.item-quantity').forEach(input => {
                    if (parseInt(input.value || 0) > 0) {
                        hasServices = true;
                    }
                });
                
                if (!hasServices) {
                    isValid = false;
                    alert('Veuillez sélectionner au moins un service');
                }
            }
            
            return isValid;
        }
        
        // Navigation vers l'étape suivante
        nextBtn.addEventListener('click', function() {
            if (validateCurrentStep()) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateStep();
                    window.scrollTo(0, 0);
                } else {
                    // Dernière étape - Soumettre le formulaire
                    orderForm.submit();
                }
            }
        });
        
        // Navigation vers l'étape précédente
        prevBtn.addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                updateStep();
                window.scrollTo(0, 0);
            }
        });
        
        // Mise à jour des totaux
        function updateTotals() {
            let totalPrice = 0;
            
            // Parcourir tous les inputs de quantité et calculer le total
            document.querySelectorAll('.item-quantity').forEach(input => {
                const quantity = parseInt(input.value || 0);
                if (quantity > 0) {
                    const price = parseInt(input.dataset.price || 0);
                    totalPrice += price * quantity;
                }
            });
            
            // Mettre à jour les affichages
            document.getElementById('estimatedPriceDisplay').textContent = new Intl.NumberFormat('fr-FR').format(totalPrice);
            
            // Mettre à jour également le total dans le récapitulatif et paiement
            document.getElementById('summary-total').textContent = new Intl.NumberFormat('fr-FR').format(totalPrice);
            document.getElementById('payment-total').textContent = new Intl.NumberFormat('fr-FR').format(totalPrice);
            
            // Mettre à jour le récapitulatif des services si visible
            if (currentStep === 4) {
                updateServicesSummary();
            }
        }
        
        // Initialiser l'affichage
        updateStep();

        // Écouter les changements d'adresse et de même adresse pour la livraison
        document.getElementById('same_address_for_delivery').addEventListener('change', function() {
            const deliveryAddressFields = document.getElementById('delivery-address-fields');
            if (this.checked) {
                deliveryAddressFields.style.display = 'none';
                document.getElementById('delivery_address_id').removeAttribute('required');
            } else {
                deliveryAddressFields.style.display = 'block';
                document.getElementById('delivery_address_id').setAttribute('required', 'required');
            }
            updateAddressSummary();
        });
        
        // Écouter les changements du sélecteur de pressing
        document.getElementById('pressing_id_selector').addEventListener('change', function() {
            // Mettre à jour le champ caché
            document.querySelector('input[name="pressing_id"]').value = this.value;
            
            // Si on est à l'étape des services, recharger les services
            if (currentStep === 3) {
                loadPressingServices();
            }
        });
        
        // Mise à jour du récapitulatif des adresses et dates
        function updateAddressSummary() {
            // Adresse de collecte
            const collectionAddressId = document.getElementById('collection_address_id');
            const collectionAddressText = collectionAddressId.options[collectionAddressId.selectedIndex]?.text || 'À définir';
            document.getElementById('summary-collection-address').innerHTML = collectionAddressText;
            
            // Date et créneau de collecte
            const collectionDate = document.getElementById('collection_date').value;
            const collectionTimeSlot = document.getElementById('collection_time_slot');
            const collectionTimeSlotText = collectionTimeSlot.options[collectionTimeSlot.selectedIndex]?.text || 'À définir';
            
            let formattedCollectionDate = 'À définir';
            if (collectionDate) {
                const date = new Date(collectionDate);
                formattedCollectionDate = date.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            }
            
            document.getElementById('summary-collection-date').textContent = formattedCollectionDate;
            document.getElementById('summary-collection-time').textContent = collectionTimeSlotText;
            
            // Adresse de livraison
            const sameAddress = document.getElementById('same_address_for_delivery').checked;
            let deliveryAddressText = 'À définir';
            
            if (sameAddress) {
                deliveryAddressText = collectionAddressText;
            } else {
                const deliveryAddressId = document.getElementById('delivery_address_id');
                deliveryAddressText = deliveryAddressId.options[deliveryAddressId.selectedIndex]?.text || 'À définir';
            }
            
            document.getElementById('summary-delivery-address').textContent = deliveryAddressText;
            
            // Date et créneau de livraison
            const deliveryDate = document.getElementById('delivery_date').value;
            const deliveryTimeSlot = document.getElementById('delivery_time_slot');
            const deliveryTimeSlotText = deliveryTimeSlot.options[deliveryTimeSlot.selectedIndex]?.text || 'À définir';
            
            let formattedDeliveryDate = 'À définir';
            if (deliveryDate) {
                const date = new Date(deliveryDate);
                formattedDeliveryDate = date.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            }
            
            document.getElementById('summary-delivery-date').textContent = formattedDeliveryDate;
            document.getElementById('summary-delivery-time').textContent = deliveryTimeSlotText;
        }
        
        // Mise à jour du récapitulatif des services
        function updateServicesSummary() {
            const summaryItems = document.getElementById('summary-items');
            let html = '';
            let hasItems = false;
            let totalPrice = 0;
            
            document.querySelectorAll('.item-quantity').forEach(input => {
                const quantity = parseInt(input.value || 0);
                if (quantity > 0) {
                    hasItems = true;
                    const serviceId = input.dataset.serviceId;
                    const service = pressingServices.find(s => s.id == serviceId);
                    
                    if (service) {
                        const unitPrice = service.price;
                        const totalItemPrice = unitPrice * quantity;
                        
                        html += `
                            <tr>
                                <td>${service.name}</td>
                                <td class="text-center">${quantity}</td>
                                <td class="text-center">${service.formatted_price}</td>
                                <td class="text-end">${new Intl.NumberFormat('fr-FR').format(totalItemPrice)} FCFA</td>
                            </tr>
                        `;
                        
                        // Ajouter au total
                        totalPrice += totalItemPrice;
                    }
                }
            });
            
            if (!hasItems) {
                html = '<tr><td colspan="4" class="text-center">Aucun service sélectionné</td></tr>';
            }
            
            summaryItems.innerHTML = html;
            document.getElementById('summary-total').textContent = new Intl.NumberFormat('fr-FR').format(totalPrice);
        }
        
        // Ajouter les écouteurs d'événements pour les champs d'adresse et de date
        document.getElementById('collection_address_id').addEventListener('change', updateAddressSummary);
        document.getElementById('collection_date').addEventListener('change', updateAddressSummary);
        document.getElementById('collection_time_slot').addEventListener('change', updateAddressSummary);
        document.getElementById('delivery_address_id').addEventListener('change', updateAddressSummary);
        document.getElementById('delivery_date').addEventListener('change', updateAddressSummary);
        document.getElementById('delivery_time_slot').addEventListener('change', updateAddressSummary);
        
        // Fonction pour mettre à jour le récapitulatif complet
        function updateFullSummary() {
            updateAddressSummary();
            updateServicesSummary();
        }
        
        // Mettre à jour le récapitulatif lorsqu'on arrive à l'étape 4
        const oldUpdateStep = updateStep;
        updateStep = function() {
            oldUpdateStep();
            
            if (currentStep === 4) {
                updateFullSummary();
            }
        };
        
        // Démarrer la validation des dates
        const collectionDateInput = document.getElementById('collection_date');
        const deliveryDateInput = document.getElementById('delivery_date');
        
        // Définir la date minimale de livraison à J+1 par rapport à la collecte
        collectionDateInput.addEventListener('change', function() {
            const collectionDate = new Date(this.value);
            const nextDay = new Date(collectionDate);
            nextDay.setDate(collectionDate.getDate() + 1);
            
            // Formater la date au format YYYY-MM-DD
            const formattedDate = nextDay.toISOString().split('T')[0];
            deliveryDateInput.min = formattedDate;
            
            // Si la date de livraison est antérieure à la nouvelle date minimale, la réinitialiser
            if (deliveryDateInput.value && new Date(deliveryDateInput.value) < nextDay) {
                deliveryDateInput.value = formattedDate;
            }
        });
        
        // Déclencher l'événement change pour initialiser la date minimale
        collectionDateInput.dispatchEvent(new Event('change'));
        
        // Initialiser le récapitulatif
        updateFullSummary();
    });
</script>
@endpush
@endsection 