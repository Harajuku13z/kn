@extends('layouts.dashboard')

@section('title', 'Créer une nouvelle commande pressing - KLINKLIN')

@push('styles')
{{-- <link rel="stylesheet" href="{{ asset('css/orders.css') }}"> --}}
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
    #articlesContainer .article-item .card {
        border: 1px solid var(--klin-border-color);
        border-radius: 0.5rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    #articlesContainer .article-item .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.1);
    }
    #articlesContainer .card-title { font-size: 1rem; font-weight: 600; color: var(--klin-primary);}
    #articlesContainer .quantity-selector input { border-left: none; border-right: none;}
    #articlesContainer .quantity-selector .btn { border-color: #ced4da; }
    #step-4 .card { border-left: 3px solid var(--klin-primary); margin-bottom: 1.5rem !important; }
    #step-4 .card-header { background-color: var(--klin-light-bg); border-bottom: 1px solid var(--klin-border-color); }
    #step-4 .card-header h5 { font-weight: 600; color: var(--klin-primary); }
    #summary-items td, #summary-items th { padding: 0.5rem; font-size:0.9rem; }
    #summary-total { font-size: 1.25rem; color: var(--klin-primary); }
    #step-5 .form-check-label { display: flex; align-items: center; font-size: 1rem; padding: 0.75rem; border: 1px solid var(--klin-border-color); border-radius: 0.375rem; cursor: pointer; transition: background-color 0.2s, border-color 0.2s; }
    #step-5 .form-check-label:hover { background-color: var(--klin-light-bg); }
    #step-5 .form-check-input:checked + .form-check-label { background-color: var(--klin-light-bg); border-color: var(--klin-primary); color: var(--klin-primary); font-weight: 500; }
    #step-5 .form-check-input { display: none; }
    #step-5 .form-check-label .bi { font-size: 1.5rem; color: var(--klin-primary); }
    .card.shadow-sm { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; }
    .form-step { display: none; }
    .form-step.active { display: block; animation: fadeIn 0.5s; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .address-selector { margin-bottom: 1rem; }
    .address-selector .form-select { margin-bottom: 0.5rem; }
    .new-address-form { /* Ce style pourrait ne plus être utilisé si les formulaires inline sont abandonnés */
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
        display: block !important; /* S'assurer que le message d'erreur Bootstrap est visible */
        width: 100%;
        margin-top: .25rem;
        font-size: .875em;
        color: #dc3545; /* Couleur de danger Bootstrap */
    }
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545 !important;
    }
    
    /* Styles pour les cartes de pressing */
    .pressing-select-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .pressing-select-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    
    .pressing-select-card.border-primary {
        border-color: var(--klin-primary);
        box-shadow: 0 5px 15px rgba(74, 20, 140, 0.2);
    }
    
    .pressing-select-card .card-title {
        font-weight: 600;
        color: var(--klin-primary);
        font-size: 1.1rem;
        text-align: center;
        margin-bottom: 0.75rem;
    }
    
    .pressing-select-card .badge {
        font-size: 0.7rem;
        padding: 0.35rem 0.5rem;
        margin-right: 0.2rem;
    }
    
    .pressing-select-card .card-img-container {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa;
    }
    
    .pressing-select-card .card-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1.25rem 1rem;
    }
    
    .pressing-select-card .rating {
        margin-bottom: 0.75rem;
    }
    
    /* Animation pour la sélection des cartes */
    .pressing-select-card.border-primary::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 40px 40px 0;
        border-color: transparent var(--klin-primary) transparent transparent;
        z-index: 1;
    }
    
    .pressing-select-card.border-primary::before {
        content: '✓';
        position: absolute;
        top: 5px;
        right: 5px;
        color: white;
        font-weight: bold;
        font-size: 14px;
        z-index: 2;
    }
</style>
@endpush

@section('content')
<div class="container-fluid order-creation-page">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h1 class="display-5 fw-bold text-klin-primary">Créer une Nouvelle Commande Pressing</h1>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            <strong>Erreur :</strong> {!! nl2br(e(session('error'))) !!}
        </div>
    @endif

    @if($errors->any()) {{-- Sera utile si la soumission AJAX échoue et qu'une redirection classique se produit --}}
        <div class="alert alert-danger">
            <strong>Erreurs de validation :</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Ajout du token CSRF qui sera utilisé par toutes les requêtes AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Préchargement des frais de livraison -->
    <script>
        // Variable globale pour stocker tous les frais de livraison
        window.deliveryFeesData = @json(\App\Models\DeliveryFee::with('city')->where('is_active', true)->get());
        
        @php
        use Illuminate\Support\Facades\Auth;
        @endphp
        
        // Précharger les bons de livraison disponibles
        window.availableVouchers = @json(\App\Models\DeliveryVoucher::getAvailableForUser(Auth::id()));
        
        // Précharger les codes promo disponibles
        window.availableCoupons = @json($availableCoupons);
        
        // Précharger tous les services de pressing disponibles
        @php
        $pressingServices = \App\Models\Pressing::with(['services' => function($query) {
            $query->where('is_available', true)
                  ->orderBy('category')
                  ->orderBy('name');
        }])
        ->where('is_active', true)
        ->get()
        ->mapWithKeys(function($pressing) {
            return [
                $pressing->id => [
                    'name' => $pressing->name,
                    'services' => $pressing->services->map(function($service) {
                        return [
                            'id' => $service->id,
                            'name' => $service->name,
                            'description' => $service->description,
                            'price' => $service->price,
                            'category' => $service->category,
                            'formatted_price' => number_format($service->price, 0, ',', ' ') . ' FCFA'
                        ];
                    })->toArray()
                ]
            ];
        });
        @endphp
        window.allPressingServices = @json($pressingServices);
        
        // Variables de prix
        window.laundryPricePerKg = {{ $laundryPricePerKg ?? 1000 }};
    </script>

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
            {{-- Assurez-vous d'avoir la balise meta CSRF dans votre layout principal (ex: layouts.dashboard) --}}
            {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
            <form id="orderForm" action="{{ route('orders.store') }}" method="POST">
                @csrf
                <input type="hidden" name="form_submitted" value="true">
                <input type="hidden" name="order_type" value="pressing">
                <input type="hidden" name="pressing_id" id="selected_pressing_id_field" value="{{ $pressingId ?? request()->route('id') ?? old('pressing_id') }}">
                
                <!-- Étape 1: Collecte -->
                <div class="form-step active" id="step-1" data-form-step="1">
                    <h3 class="mb-4 text-center">Adresse et date de collecte</h3>
                    
                    <div class="mb-4">
                        <label for="selected_pressing_id" class="form-label">Pressing sélectionné <span class="text-danger">*</span></label>
                        
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-3">
                            @foreach($pressings as $pressing)
                                <div class="col">
                                    <div class="card h-100 pressing-select-card {{ old('pressing_id', $pressingId ?? '') == $pressing->id ? 'border-primary' : '' }}" data-pressing-id="{{ $pressing->id }}">
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <div class="form-check">
                                                <input class="form-check-input pressing-radio visually-hidden" type="radio" name="pressing_id" id="pressing_{{ $pressing->id }}" value="{{ $pressing->id }}" {{ old('pressing_id', $pressingId ?? '') == $pressing->id ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="pressing_{{ $pressing->id }}"></label>
                                            </div>
                                        </div>
                                        
                                        <div class="card-img-container" style="height: 150px; overflow: hidden;">
                                            @if($pressing->image)
                                                <img src="{{ asset('storage/' . $pressing->image) }}" class="card-img-top" alt="{{ $pressing->name }}" style="object-fit: cover; height: 100%; width: 100%;" onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\'d-flex justify-content-center align-items-center h-100 bg-light\'><i class=\'bi bi-shop fs-1 text-primary\'></i></div>';">
                                            @else
                                                <div class="d-flex justify-content-center align-items-center h-100 bg-light">
                                                    @if($pressing->logo)
                                                        <img src="{{ asset('storage/' . $pressing->logo) }}" alt="{{ $pressing->name }}" style="max-height: 80px; max-width: 80%;" onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\'bi bi-shop fs-1 text-primary\'></i>';">
                                                    @else
                                                        <i class="bi bi-shop fs-1 text-primary"></i>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $pressing->name }}</h5>
                                            
                                            <div class="mb-2">
                                                <div class="rating text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= ($pressing->rating ?? 0))
                                                            <i class="bi bi-star-fill"></i>
                                                        @elseif($i - 0.5 <= ($pressing->rating ?? 0))
                                                            <i class="bi bi-star-half"></i>
                                                        @else
                                                            <i class="bi bi-star"></i>
                                                        @endif
                                                    @endfor
                                                    <span class="text-muted ms-1">({{ $pressing->reviews_count ?? 0 }})</span>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-2">
                                                @if($pressing->is_express ?? false)
                                                    <span class="badge bg-danger">Express</span>
                                                @endif
                                                @if($pressing->has_delivery ?? false)
                                                    <span class="badge bg-success">Livraison</span>
                                                @endif
                                                @if($pressing->eco_friendly ?? false)
                                                    <span class="badge bg-info">Éco-responsable</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('pressing_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="collection_address_id" class="form-label">Adresse de collecte <span class="text-danger">*</span></label>
                        <select class="form-select @error('collection_address_id') is-invalid @enderror" id="collection_address_id" name="collection_address_id" required>
                            <option value="">Sélectionner une adresse</option>
                            @foreach($addresses as $address)
                                <option value="{{ $address->id }}" @if(old('collection_address_id') == $address->id || (isset($tempCart['collection_address_id']) && $tempCart['collection_address_id'] == $address->id)) selected @endif>
                                    {{ $address->name }} - {{ $address->address }}, {{ $address->district }}
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
                    
                    <!-- Navigation buttons for step 1 -->
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn klin-btn next-step">
                            Suivant<i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Étape 2: Livraison -->
                <div class="form-step" id="step-2" data-form-step="2">
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
                                    {{ $address->name }} - {{ $address->address }}, {{ $address->district }}
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
                    
                    <!-- Navigation buttons for step 2 -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn klin-btn-outline prev-step">
                            <i class="bi bi-arrow-left me-2"></i>Précédent
                        </button>
                        <button type="button" class="btn klin-btn next-step">
                            Suivant<i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Étape 3: Services -->
                <div class="form-step" id="step-3" data-form-step="3">
                    <h3 class="mb-4 text-center">Sélection des services pressing</h3>
                    
                    <!-- Conteneur pour les services pressing -->
                    <div id="services-container" class="row">
                        <div class="col-md-8">
                            <div id="servicesContainer" class="row">
                                @if(isset($pressingServices) && !empty($pressingServices))
                                    @foreach($pressingServices as $service)
                                        <div class="col-md-6 mb-4 service-item">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $service->name ?? $service['name'] ?? 'Service' }}</h5>
                                                    <p class="card-text">
                                                        <small class="text-muted">{{ $service->description ?? $service['description'] ?? '' }}</small><br>
                                                        <strong>{{ number_format($service->price ?? $service['price'] ?? 0, 0, ',', ' ') }} FCFA</strong>
                                                    </p>
                                                    <div class="input-group mt-3">
                                                        <button type="button" class="btn btn-outline-secondary quantity-minus">-</button>
                                                        <input type="number" class="form-control text-center item-quantity" 
                                                            name="pressing_services[{{ $service->id ?? $service['id'] ?? 0 }}][quantity]" 
                                                            value="{{ old('pressing_services.'.($service->id ?? $service['id'] ?? 0).'.quantity') ?? (isset($tempCart['pressing_services'][$service->id ?? $service['id'] ?? 0]['quantity']) ? $tempCart['pressing_services'][$service->id ?? $service['id'] ?? 0]['quantity'] : 0) }}" 
                                                            min="0" data-price="{{ $service->price ?? $service['price'] ?? 0 }}">
                                                        <button type="button" class="btn btn-outline-secondary quantity-plus">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-12">
                                        <div class="alert alert-warning">
                                            Aucun service disponible pour ce pressing pour le moment.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card sticky-top" style="top: 20px;">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0"><i class="bi bi-calculator me-2"></i>Simulateur de prix</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Services sélectionnés:</label>
                                        <div id="selected-services-count" class="mb-2">0 service(s)</div>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <label class="form-label fw-bold">Prix des services:</label>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-tag me-2 text-primary" style="font-size: 1.5rem;"></i>
                                            <h5 class="mb-0"><span id="servicesPrice">0</span> FCFA</h5>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <label class="form-label fw-bold">Frais de livraison:</label>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-truck me-2 text-primary" style="font-size: 1.5rem;"></i>
                                            <h5 class="mb-0">
                                                <span id="deliveryFeeDisplay">2000</span> FCFA
                                                <span id="freeDeliveryBadge" style="display: none;" class="badge bg-success ms-2">Gratuit</span>
                                            </h5>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="mb-2">
                                        <label class="form-label fw-bold">Prix total estimé:</label>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-currency-exchange me-2 text-primary" style="font-size: 2rem;"></i>
                                            <h4 class="mb-0 text-primary"><span id="estimatedPriceDisplay">0</span> FCFA</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation buttons for step 3 -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn klin-btn-outline prev-step">
                            <i class="bi bi-arrow-left me-2"></i>Précédent
                        </button>
                        <button type="button" class="btn klin-btn next-step">
                            Suivant<i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Étape 4: Récapitulatif -->
                <div class="form-step" id="step-4" data-form-step="4">
                    <h3 class="mb-4 text-center">Récapitulatif de votre commande</h3>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-box me-2"></i>Type de commande</h5>
                                </div>
                                <div class="card-body">
                                    <div id="summary-order-type" class="mb-3">
                                        <span class="badge bg-primary p-2 fs-6">Commande au kilo</span>
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
                    
                    <!-- Articles au kilo -->
                    <div class="card mb-3" id="kilogram-summary">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-basket3-fill me-2"></i>Articles sélectionnés</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Article</th>
                                            <th class="text-center">Quantité</th>
                                            <th class="text-end">Prix (FCFA)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="summary-items">
                                        <tr>
                                            <td colspan="3" class="text-center">Aucun service sélectionné</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" class="text-end">Sous-total services</th>
                                            <th class="text-end"><span id="summary-articles-price">0</span> FCFA</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="text-end">Frais de livraison</th>
                                            <th class="text-end">
                                                <span id="summary-delivery-fee">0</span> FCFA
                                                <span id="summary-free-delivery-badge" style="display: none;" class="badge bg-success ms-2">Gratuit</span>
                                            </th>
                                        </tr>
                                        <tr id="summary-discount-row" style="display: none;">
                                            <th colspan="2" class="text-end">Réduction</th>
                                            <th class="text-end text-success">
                                                <span id="summary-discount-value">0</span> FCFA
                                            </th>
                                        </tr>
                                        <tr class="table-active">
                                            <th colspan="2" class="text-end">Total</th>
                                            <th class="text-end"><span id="summary-total">0</span> FCFA</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation buttons for step 4 -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn klin-btn-outline prev-step">
                            <i class="bi bi-arrow-left me-2"></i>Précédent
                        </button>
                        <button type="button" class="btn klin-btn next-step">
                            Suivant<i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Étape 5: Paiement -->
                <div class="form-step" id="step-5" data-form-step="5">
                    <h3 class="mb-4 text-center">Mode de paiement</h3>
                    
                    <div class="row">
                        <!-- Colonne de gauche: Options de paiement -->
                        <div class="col-md-6">
                            <div class="card shadow-sm mb-3">
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
                                        
                                        <div id="quota-section" class="form-check p-3 border rounded @if(old('payment_method') == 'quota') border-primary bg-light @endif" style="{{ $totalQuota > 0 ? '' : 'display: none;' }}">
                                            <input class="form-check-input" type="radio" name="payment_method" id="payment-quota" value="quota">
                                            <label class="form-check-label w-100" for="payment-quota">
                                                <strong><i class="bi bi-wallet2 me-2 text-primary"></i> Utiliser mon quota disponible</strong>
                                                <div class="text-muted small mt-1">
                                                    Quota disponible : <span id="available-quota">{{ $totalQuota }}</span> kg
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
                            
                            <div id="subscribe-section" style="{{ $totalQuota > 0 ? 'display: none;' : '' }}">
                                <div class="alert alert-info d-flex align-items-center">
                                    <i class="bi bi-info-circle fs-4 me-3"></i>
                                    <div>
                                        <p class="mb-2">Vous n'avez pas d'abonnement actif ou de quota suffisant.</p>
                                        <a href="{{ route('subscriptions.index') }}" class="btn btn-sm btn-primary mt-1">Voir les abonnements disponibles</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Colonne de droite: Résumé des coûts et bon de livraison -->
                        <div class="col-md-6">
                            <!-- Résumé des coûts -->
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-receipt me-2 text-primary"></i>Résumé des coûts</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-3">
                                        <span>Articles:</span>
                                        <strong><span id="payment-articles-price">0</span> FCFA</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span>Frais de livraison:</span>
                                        <strong>
                                            <span id="payment-delivery-fee">0</span> FCFA
                                            <span id="payment-free-delivery-badge" style="display: none;" class="badge bg-success ms-2">Gratuit</span>
                                        </strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3" id="payment-discount-row" style="display: none;">
                                        <span>Réduction:</span>
                                        <strong class="text-success">
                                            <span id="payment-discount-value">0</span> FCFA
                                        </strong>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Total à payer:</span>
                                        <strong class="text-primary fs-5"><span id="payment-total">0</span> FCFA</strong>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Bon de livraison -->
                            <div class="card mb-4 shadow-sm">
                                <div class="card-header bg-light d-flex align-items-center">
                                    <i class="bi bi-ticket-perforated text-primary me-2 fs-4"></i>
                                    <h5 class="mb-0">Bon de livraison</h5>
                                    <span id="voucher-count-badge" class="badge bg-primary ms-2" style="display: none;">0</span>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="use_delivery_voucher" name="use_delivery_voucher" value="1" @if(old('use_delivery_voucher', $tempCart['use_delivery_voucher'] ?? false)) checked @endif>
                                        <label class="form-check-label" for="use_delivery_voucher">
                                            Utiliser un bon de livraison pour bénéficier d'une livraison gratuite
                                        </label>
                                    </div>
                                    
                                    <div id="voucher_fields" @if(!old('use_delivery_voucher', $tempCart['use_delivery_voucher'] ?? false)) style="display: none;" @endif>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="delivery_voucher_code" name="delivery_voucher_code" placeholder="Code du bon" value="{{ old('delivery_voucher_code', $tempCart['delivery_voucher_code'] ?? '') }}">
                                            <button class="btn btn-primary" type="button" id="apply_voucher">
                                                <i class="bi bi-check-circle-fill me-1"></i> Appliquer
                                            </button>
                                        </div>
                                        <div id="voucher-status" data-valid="false"></div>
                                        <div id="delivery_voucher_message"></div>
                                        <div id="available-vouchers-list" class="mt-3" style="display: none;">
                                            <h6 class="mb-2">Vos bons disponibles:</h6>
                                            <div class="list-group" style="max-height: 200px; overflow-y: auto;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Code Promo -->
                            <div class="card mb-4 shadow-sm">
                                <div class="card-header bg-light d-flex align-items-center">
                                    <i class="bi bi-percent text-success me-2 fs-4"></i>
                                    <h5 class="mb-0">Code promo</h5>
                                    <span id="coupon-count-badge" class="badge bg-success ms-2" style="display: none;">0</span>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="use_coupon" name="use_coupon" value="1" @if(old('use_coupon', $tempCart['use_coupon'] ?? false)) checked @endif>
                                        <label class="form-check-label" for="use_coupon">
                                            Appliquer un code promo pour bénéficier d'une réduction
                                        </label>
                                    </div>
                                    
                                    <div id="coupon_fields" @if(!old('use_coupon', $tempCart['use_coupon'] ?? false)) style="display: none;" @endif>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="coupon_code" name="coupon_code" placeholder="Code promo" value="{{ old('coupon_code', $tempCart['coupon_code'] ?? '') }}">
                                            <button class="btn btn-success" type="button" id="apply_coupon">
                                                <i class="bi bi-check-circle-fill me-1"></i> Appliquer
                                            </button>
                                        </div>
                                        <div id="coupon-status" data-valid="false"></div>
                                        <div id="coupon_message"></div>
                                        <div id="available-coupons-list" class="mt-3" style="display: none;">
                                            <h6 class="mb-2">Codes promo disponibles ({{ count($availableCoupons) }}):</h6>
                                            <div class="list-group" style="max-height: 200px; overflow-y: auto;">
                                                @if(count($availableCoupons) > 0)
                                                    @foreach($availableCoupons as $coupon)
                                                    <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center coupon-item" 
                                                            data-code="{{ $coupon['code'] }}">
                                                        <div>
                                                            <strong>{{ $coupon['code'] }}</strong>
                                                            <small class="d-block text-muted">{{ $coupon['description'] ?? 'Code promo' }}</small>
                                        </div>
                                                        <div class="text-end">
                                                            <span class="badge bg-success rounded-pill">{{ $coupon['formatted_value'] }}</span>
                                                            <small class="d-block text-muted">{{ $coupon['valid_until_formatted'] }}</small>
                                                        </div>
                                                    </button>
                                                    @endforeach
                                                @else
                                                    <div class="list-group-item">Aucun code promo disponible</div>
                                                @endif
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <!-- Navigation buttons for step 5 -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn klin-btn-outline prev-step">
                        <i class="bi bi-arrow-left me-2"></i>Précédent
                    </button>
                        <button type="button" class="btn klin-btn next-step">
                            Finaliser la commande<i class="bi bi-check-circle ms-2"></i>
                    </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Define the price per kg as a JavaScript variable before including the script
    window.laundryPricePerKg = {{ $laundryPricePerKg ?? 1000 }};
</script>

{{-- Utiliser notre nouveau script complet pour la navigation et autres fonctions --}}
<script src="{{ asset('js/order_pressing.js') }}"></script>

{{-- Script pour la gestion des services pressing et du simulateur --}}
<script>
// Script pour gérer l'affichage des services en fonction du pressing sélectionné
document.addEventListener('DOMContentLoaded', function() {
    // Sélecteur de pressing
    const pressingSelect = document.getElementById('selected_pressing_id');
    const servicesContainer = document.getElementById('servicesContainer');
    const pressingCards = document.querySelectorAll('.pressing-select-card');
    const pressingRadios = document.querySelectorAll('.pressing-radio');
    const selectedPressingIdField = document.getElementById('selected_pressing_id_field');
    
    // Gestion de la sélection des cartes de pressing
    pressingCards.forEach(card => {
        card.addEventListener('click', function() {
            const pressingId = this.dataset.pressingId;
            
            // Réinitialiser toutes les cartes
            pressingCards.forEach(c => c.classList.remove('border-primary'));
            
            // Marquer cette carte comme sélectionnée
            this.classList.add('border-primary');
            
            // Mettre à jour le radio button correspondant
            pressingRadios.forEach(radio => {
                if (radio.value === pressingId) {
                    radio.checked = true;
                } else {
                    radio.checked = false;
                }
            });
            
            // Mettre à jour le champ caché
            if (selectedPressingIdField) {
                selectedPressingIdField.value = pressingId;
            }
            
            // Afficher les services de ce pressing
            displayPressingServices(pressingId);
            
            console.log('Pressing sélectionné:', pressingId);
        });
    });
    
    // Fonction pour afficher les services d'un pressing
    function displayPressingServices(pressingId) {
        if (!pressingId) {
            // Si aucun pressing n'est sélectionné, afficher un message
            servicesContainer.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-info">
                        Veuillez sélectionner un pressing pour voir les services disponibles.
                    </div>
                </div>
            `;
            return;
        }
        
        // Mettre à jour le champ caché avec l'ID du pressing
        const hiddenPressingField = document.getElementById('selected_pressing_id_field');
        if (hiddenPressingField) {
            hiddenPressingField.value = pressingId;
        }
        
        // Récupérer les services pour ce pressing
        const pressingData = window.allPressingServices[pressingId];
        
        if (pressingData && pressingData.services && pressingData.services.length > 0) {
            // Générer le HTML pour les services
            let servicesHTML = '';
            
            pressingData.services.forEach(service => {
                servicesHTML += `
                    <div class="col-md-6 mb-4 service-item">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">${service.name}</h5>
                                <p class="card-text">
                                    <small class="text-muted">${service.description || ''}</small><br>
                                    <strong>${service.formatted_price}</strong>
                                </p>
                                <div class="input-group mt-3">
                                    <button type="button" class="btn btn-outline-secondary quantity-minus">-</button>
                                    <input type="number" class="form-control text-center item-quantity" 
                                        name="pressing_services[${service.id}][quantity]" 
                                        value="0" min="0" data-price="${service.price}">
                                    <button type="button" class="btn btn-outline-secondary quantity-plus">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            servicesContainer.innerHTML = servicesHTML;
            
            // Réattacher les gestionnaires d'événements pour les boutons + et -
            attachQuantityEvents();
            
            // Calculer les totaux immédiatement
            calculateServicesTotals();
            
        } else {
            // Pas de services disponibles
            servicesContainer.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-warning">
                        Aucun service disponible pour ce pressing pour le moment.
                    </div>
                </div>
            `;
        }
    }
    
    // Attacher l'écouteur d'événements au changement de pressing
    if (pressingSelect) {
        pressingSelect.addEventListener('change', function() {
            const pressingId = this.value;
            displayPressingServices(pressingId);
        });
        
        // Afficher les services du pressing pré-sélectionné
        const pressingId = selectedPressingIdField.value;
        if (pressingId) {
            displayPressingServices(pressingId);
            
            // Mettre à jour l'affichage visuel de la carte sélectionnée
            pressingCards.forEach(card => {
                if (card.dataset.pressingId === pressingId) {
                    card.classList.add('border-primary');
                } else {
                    card.classList.remove('border-primary');
                }
            });
        }
    }
    
    // Fonction pour attacher les gestionnaires d'événements aux boutons de quantité
    function attachQuantityEvents() {
        // Gestion des boutons "+" pour augmenter la quantité
        document.querySelectorAll('.quantity-plus').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('input');
                input.value = parseInt(input.value || 0) + 1;
                calculateServicesTotals();
            });
        });
        
        // Gestion des boutons "-" pour diminuer la quantité
        document.querySelectorAll('.quantity-minus').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('input');
                const value = parseInt(input.value || 0);
                if (value > 0) {
                    input.value = value - 1;
                    calculateServicesTotals();
                }
            });
        });
        
        // Gestion des changements de valeur directement dans les inputs
        document.querySelectorAll('.item-quantity').forEach(input => {
            input.addEventListener('change', calculateServicesTotals);
            input.addEventListener('input', calculateServicesTotals);
        });
    }
    
    // Fonction pour calculer les totaux des services
    function calculateServicesTotals() {
        // Calculer le total des services et le nombre de services
        let totalServicesPrice = 0;
        let servicesCount = 0;
        
        document.querySelectorAll('.item-quantity').forEach(input => {
            const quantity = parseInt(input.value || 0);
            if (quantity > 0) {
                servicesCount += quantity;
                const price = parseFloat(input.dataset.price || 0);
                totalServicesPrice += quantity * price;
            }
        });
        
        // Mettre à jour le compteur de services
        const servicesCountEl = document.getElementById('selected-services-count');
        if (servicesCountEl) {
            servicesCountEl.textContent = servicesCount + ' service(s)';
        }
        
        // Mettre à jour le prix des services
        const servicesPriceEl = document.getElementById('servicesPrice');
        if (servicesPriceEl) {
            servicesPriceEl.textContent = formatMoney(totalServicesPrice);
        }
        
        // Calculer les frais de livraison
        let pickupFee = 1000; // Valeur par défaut
        let deliveryFee = 1000; // Valeur par défaut
        
        // Obtenir district de collecte
        const collectionAddressSelect = document.getElementById('collection_address_id');
        let collectionDistrict = '';
        
        if (collectionAddressSelect && collectionAddressSelect.selectedIndex > 0) {
            const selectedOption = collectionAddressSelect.options[collectionAddressSelect.selectedIndex];
            const addressText = selectedOption.textContent;
            const matches = addressText.match(/,\s*([^,]+)$/);
            if (matches && matches.length > 1) {
                collectionDistrict = matches[1].trim();
                pickupFee = getDeliveryFee(collectionDistrict);
            }
        }
        
        // Obtenir district de livraison
        const sameAddressCheckbox = document.getElementById('same_address_for_delivery');
        const sameAddress = sameAddressCheckbox && sameAddressCheckbox.checked;
        let deliveryDistrict = '';
        
        if (sameAddress) {
            deliveryDistrict = collectionDistrict;
            deliveryFee = pickupFee;
        } else {
            const deliveryAddressSelect = document.getElementById('delivery_address_id');
            if (deliveryAddressSelect && deliveryAddressSelect.selectedIndex > 0) {
                const selectedOption = deliveryAddressSelect.options[deliveryAddressSelect.selectedIndex];
                const addressText = selectedOption.textContent;
                const matches = addressText.match(/,\s*([^,]+)$/);
                if (matches && matches.length > 1) {
                    deliveryDistrict = matches[1].trim();
                    deliveryFee = getDeliveryFee(deliveryDistrict);
                }
            }
        }
        
        // Appliquer bon de livraison si coché
        let totalDeliveryFee = pickupFee + deliveryFee;
        
        const useVoucherCheckbox = document.getElementById('use_delivery_voucher');
        if (useVoucherCheckbox && useVoucherCheckbox.checked) {
            const voucherCode = document.getElementById('delivery_voucher_code');
            if (voucherCode && voucherCode.value) {
                // Vérifier si le code existe dans les bons disponibles
                const voucher = window.availableVouchers ? window.availableVouchers.find(v => v.code === voucherCode.value) : null;
                if (voucher) {
                    totalDeliveryFee = 0;
                    const freeDeliveryBadge = document.getElementById('freeDeliveryBadge');
                    if (freeDeliveryBadge) {
                        freeDeliveryBadge.style.display = 'inline-block';
                    }
                }
            }
        }
        
        // Mettre à jour l'affichage des frais de livraison
        const deliveryFeeEl = document.getElementById('deliveryFeeDisplay');
        if (deliveryFeeEl) {
            deliveryFeeEl.textContent = formatMoney(totalDeliveryFee);
        }
        
        // Calculer le total
        const totalPrice = totalServicesPrice + totalDeliveryFee;
        
        // Mettre à jour le prix total
        const totalPriceEl = document.getElementById('estimatedPriceDisplay');
        if (totalPriceEl) {
            totalPriceEl.textContent = formatMoney(totalPrice);
        }
    }
    
    // Fonction pour obtenir les frais de livraison d'un district
    function getDeliveryFee(district) {
        if (!district || !window.deliveryFeesData) return 1000; // Valeur par défaut
        
        const fee = window.deliveryFeesData.find(item => 
            item.district && item.district.toLowerCase() === district.toLowerCase()
        );
        
        if (fee && fee.fee) {
            return parseInt(fee.fee);
        } else {
            return 1000; // Valeur par défaut
        }
    }
    
    // Fonction utilitaire pour formater la monnaie
    function formatMoney(amount) {
        return new Intl.NumberFormat('fr-FR').format(Math.round(amount));
    }
    
    // Attacher les événements aux boutons existants
    attachQuantityEvents();
    
    // Calculer les totaux initiaux
    calculateServicesTotals();
    
    // Vérifier s'il y a un pressing présélectionné
    const preselectedPressingId = selectedPressingIdField.value;
    if (preselectedPressingId) {
        displayPressingServices(preselectedPressingId);
    }
});
</script>
@endpush