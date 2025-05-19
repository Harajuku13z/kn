@extends('layouts.admin')

@section('title', 'Créer une commande au kilo - KLINKLIN Admin')

@section('page_title')
    <div class="d-sm-flex align-items-center justify-content-between">
        <h1 class="h3 mb-0 text-gray-800">Nouvelle Commande au Kilo</h1>
        <a href="{{ route('admin.orders.create.quota.select-user') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-box-open me-2"></i> Commande au kilo pour {{ $user->name }}
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

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        
                        @if(session('address_id') && session('delivery_fee'))
                            <input type="hidden" id="new_address_id" value="{{ session('address_id') }}">
                            <input type="hidden" id="new_address_fee" value="{{ session('delivery_fee') }}">
                        @endif
                    @endif

                    <form action="{{ route('admin.orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_type" value="kilogram">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="fas fa-user me-2"></i> Informations client</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nom</label>
                                            <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Téléphone</label>
                                            <input type="text" class="form-control" value="{{ $user->phone ?? 'Non renseigné' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i> Informations de paiement</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="payment_status" class="form-label">Statut de paiement <span class="text-danger">*</span></label>
                                            <select name="payment_status" id="payment_status" class="form-control" required>
                                                <option value="pending">En attente</option>
                                                <option value="paid">Payé</option>
                                                <option value="failed">Échoué</option>
                                                <option value="refunded">Remboursé</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="payment_method" class="form-label">Méthode de paiement</label>
                                            <select name="payment_method" id="payment_method" class="form-control">
                                                <option value="cash">Espèces</option>
                                                <option value="quota">Quota</option>
                                                <option value="mobile_money">Mobile Money</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i> Adresses</h5>
                                        <a href="{{ route('admin.orders.address.create', ['userId' => $user->id, 'return_type' => 'quota']) }}" class="btn btn-sm btn-light">
                                            <i class="fas fa-plus me-1"></i> Ajouter une adresse
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        @if($addresses->count() > 0)
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="pickup_address" class="form-label">Adresse de collecte <span class="text-danger">*</span></label>
                                                        <select name="pickup_address" id="pickup_address" class="form-control" required>
                                                            <option value="">Sélectionner une adresse</option>
                                                            @foreach($addresses as $address)
                                                                <option value="{{ $address->id }}" {{ $address->is_default ? 'selected' : '' }}>
                                                                    {{ $address->name }} - {{ $address->address }}, {{ $address->district }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="delivery_address" class="form-label">Adresse de livraison <span class="text-danger">*</span></label>
                                                        <select name="delivery_address" id="delivery_address" class="form-control" required>
                                                            <option value="">Sélectionner une adresse</option>
                                                            @foreach($addresses as $address)
                                                                <option value="{{ $address->id }}" {{ $address->is_default ? 'selected' : '' }}>
                                                                    {{ $address->name }} - {{ $address->address }}, {{ $address->district }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i> Aucune adresse n'est enregistrée pour ce client. 
                                                <a href="{{ route('admin.orders.address.create', ['userId' => $user->id, 'return_type' => 'quota']) }}" class="alert-link">
                                                    Ajouter une adresse
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Collecte</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="pickup_date" class="form-label">Date de collecte <span class="text-danger">*</span></label>
                                            <input type="date" name="pickup_date" id="pickup_date" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pickup_time_slot" class="form-label">Créneau horaire <span class="text-danger">*</span></label>
                                            <select name="pickup_time_slot" id="pickup_time_slot" class="form-control" required>
                                                <option value="08:00-12:00">Matin (08:00 - 12:00)</option>
                                                <option value="12:00-18:00">Après-midi (12:00 - 18:00)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Livraison</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="delivery_date" class="form-label">Date de livraison <span class="text-danger">*</span></label>
                                            <input type="date" name="delivery_date" id="delivery_date" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="delivery_time_slot" class="form-label">Créneau horaire <span class="text-danger">*</span></label>
                                            <select name="delivery_time_slot" id="delivery_time_slot" class="form-control" required>
                                                <option value="08:00-12:00">Matin (08:00 - 12:00)</option>
                                                <option value="12:00-18:00">Après-midi (12:00 - 18:00)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card bg-light mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informations supplémentaires</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Statut de la commande <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="pending">En attente</option>
                                        <option value="collected">Collecté</option>
                                        <option value="in_transit">En transit</option>
                                        <option value="washing">Lavage</option>
                                        <option value="ironing">Repassage</option>
                                        <option value="ready_for_delivery">Prêt pour livraison</option>
                                        <option value="delivering">En cours de livraison</option>
                                        <option value="delivered">Livré</option>
                                        <option value="cancelled">Annulé</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card bg-light mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-tshirt me-2"></i> Articles et estimation</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">Articles</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Estimation du poids (kg) <span class="text-danger">*</span></label>
                                                    <input type="number" name="weight" id="weight" class="form-control" min="0.5" step="0.5" value="3" required>
                                                </div>
                                                
                                                <!-- Onglets pour les catégories -->
                                                <ul class="nav nav-tabs mb-3" role="tablist">
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#clothes" type="button">Vêtements</button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#household" type="button">Linge de maison</button>
                                                    </li>
                                                </ul>
                                                
                                                <!-- Contenu des onglets -->
                                                <div class="tab-content">
                                                    <!-- Vêtements -->
                                                    <div class="tab-pane fade show active" id="clothes">
                                                        <div class="article-list">
                                                            @if($clothingArticles->count() > 0)
                                                                @foreach($clothingArticles as $article)
                                                                <div class="article-item d-flex align-items-center mb-2 p-2 border-bottom">
                                                                    <div class="form-check flex-grow-1">
                                                                        <input class="form-check-input article-checkbox" type="checkbox" 
                                                                               value="{{ $article->name }}" 
                                                                               id="article_{{ $article->id }}" 
                                                                               name="articles[]" 
                                                                               data-weight="{{ $article->average_weight }}"
                                                                               data-id="{{ $article->id }}">
                                                                        <label class="form-check-label" for="article_{{ $article->id }}">
                                                                            {{ $article->name }} 
                                                                            <small class="text-muted">({{ number_format($article->average_weight, 1, ',', ' ') }} kg)</small>
                                                                        </label>
                                                                    </div>
                                                                    <div class="article-quantity" style="display: none;">
                                                                        <div class="input-group input-group-sm" style="width: 100px;">
                                                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                                                            <input type="number" class="form-control text-center quantity-input" 
                                                                                   name="article_qty[{{ $article->id }}]"
                                                                                   value="1" min="1" max="20">
                                                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            @else
                                                                <div class="form-check mb-2">
                                                                    <input class="form-check-input article-checkbox" type="checkbox" value="t-shirts" id="article_tshirts" name="articles[]" checked>
                                                                    <label class="form-check-label" for="article_tshirts">
                                                                        T-shirts
                                                                    </label>
                                                                </div>
                                                                <div class="form-check mb-2">
                                                                    <input class="form-check-input article-checkbox" type="checkbox" value="pantalons" id="article_pantalons" name="articles[]" checked>
                                                                    <label class="form-check-label" for="article_pantalons">
                                                                        Pantalons
                                                                    </label>
                                                                </div>
                                                                <div class="form-check mb-2">
                                                                    <input class="form-check-input article-checkbox" type="checkbox" value="chemises" id="article_chemises" name="articles[]">
                                                                    <label class="form-check-label" for="article_chemises">
                                                                        Chemises
                                                                    </label>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Linge de maison -->
                                                    <div class="tab-pane fade" id="household">
                                                        <div class="article-list">
                                                            @if($householdArticles->count() > 0)
                                                                @foreach($householdArticles as $article)
                                                                <div class="article-item d-flex align-items-center mb-2 p-2 border-bottom">
                                                                    <div class="form-check flex-grow-1">
                                                                        <input class="form-check-input article-checkbox" type="checkbox" 
                                                                               value="{{ $article->name }}" 
                                                                               id="article_{{ $article->id }}" 
                                                                               name="articles[]" 
                                                                               data-weight="{{ $article->average_weight }}"
                                                                               data-id="{{ $article->id }}">
                                                                        <label class="form-check-label" for="article_{{ $article->id }}">
                                                                            {{ $article->name }} 
                                                                            <small class="text-muted">({{ number_format($article->average_weight, 1, ',', ' ') }} kg)</small>
                                                                        </label>
                                                                    </div>
                                                                    <div class="article-quantity" style="display: none;">
                                                                        <div class="input-group input-group-sm" style="width: 100px;">
                                                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                                                            <input type="number" class="form-control text-center quantity-input" 
                                                                                   name="article_qty[{{ $article->id }}]"
                                                                                   value="1" min="1" max="20">
                                                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            @else
                                                                <div class="form-check mb-2">
                                                                    <input class="form-check-input article-checkbox" type="checkbox" value="draps" id="article_draps" name="articles[]">
                                                                    <label class="form-check-label" for="article_draps">
                                                                        Draps et linge de maison
                                                                    </label>
                                                                </div>
                                                                <div class="form-check mb-2">
                                                                    <input class="form-check-input article-checkbox" type="checkbox" value="serviettes" id="article_serviettes" name="articles[]">
                                                                    <label class="form-check-label" for="article_serviettes">
                                                                        Serviettes
                                                                    </label>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">Estimation des coûts</h6>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-sm">
                                                    <tbody>
                                                        <tr>
                                                            <td>Prix au kilo</td>
                                                            <td class="text-end">{{ number_format($currentPrice, 0, ',', ' ') }} FCFA</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Poids estimé</td>
                                                            <td class="text-end"><span id="display_weight">3</span> kg</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sous-total</td>
                                                            <td class="text-end"><span id="subtotal">{{ number_format($currentPrice * 3, 0, ',', ' ') }}</span> FCFA</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Frais de collecte</td>
                                                            <td class="text-end">
                                                                <input type="number" name="pickup_fee" id="pickup_fee" class="form-control form-control-sm text-end" value="{{ $defaultDeliveryFee }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Frais de livraison</td>
                                                            <td class="text-end">
                                                                <input type="number" name="drop_fee" id="drop_fee" class="form-control form-control-sm text-end" value="{{ $defaultDeliveryFee }}">
                                                            </td>
                                                        </tr>
                                                        <tr class="table-active">
                                                            <th>Total estimé</th>
                                                            <th class="text-end"><span id="total">{{ number_format($currentPrice * 3 + ($defaultDeliveryFee * 2), 0, ',', ' ') }}</span> FCFA</th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <input type="hidden" name="estimated_price" id="estimated_price" value="{{ $currentPrice * 3 }}">
                                                <div class="alert alert-info small mt-2 mb-0">
                                                    <i class="fas fa-info-circle me-1"></i> Le prix final sera calculé en fonction du poids réel du linge.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.orders.create.quota.select-user') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Enregistrer la commande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter les données des frais de livraison par district
    const deliveryFeesByDistrict = @json($deliveryFeesByDistrict);
    
    // Set default dates
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    // Format dates as YYYY-MM-DD
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };
    
    document.getElementById('pickup_date').value = formatDate(today);
    document.getElementById('delivery_date').value = formatDate(tomorrow);

    // Copy pickup address to delivery address if they are the same
    const pickupAddressSelect = document.getElementById('pickup_address');
    const deliveryAddressSelect = document.getElementById('delivery_address');
    
    pickupAddressSelect.addEventListener('change', function() {
        if (confirm('Utiliser la même adresse pour la livraison?')) {
            deliveryAddressSelect.value = pickupAddressSelect.value;
            
            // Update delivery fees based on the selected addresses
            updateDeliveryFees();
        }
    });
    
    // Price calculation
    const pricePerKg = {{ $currentPrice }};
    const weightInput = document.getElementById('weight');
    const displayWeight = document.getElementById('display_weight');
    const subtotalDisplay = document.getElementById('subtotal');
    const totalDisplay = document.getElementById('total');
    const estimatedPriceInput = document.getElementById('estimated_price');
    const pickupFeeInput = document.getElementById('pickup_fee');
    const dropFeeInput = document.getElementById('drop_fee');
    const articleCheckboxes = document.querySelectorAll('.article-checkbox');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    
    // Gérer l'affichage des contrôles de quantité lorsqu'un article est sélectionné
    articleCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const articleItem = this.closest('.article-item');
            const quantityDiv = articleItem.querySelector('.article-quantity');
            
            if (this.checked) {
                quantityDiv.style.display = 'block';
            } else {
                quantityDiv.style.display = 'none';
            }
            
            // Mettre à jour le calcul du poids et du prix
            updateArticleSelection();
        });
    });
    
    // Gérer les boutons d'augmentation/diminution de quantité
    quantityBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.dataset.action;
            const inputGroup = this.closest('.input-group');
            const input = inputGroup.querySelector('.quantity-input');
            let value = parseInt(input.value);
            
            if (action === 'increase') {
                if (value < parseInt(input.max)) {
                    input.value = value + 1;
                }
            } else if (action === 'decrease') {
                if (value > parseInt(input.min)) {
                    input.value = value - 1;
                }
            }
            
            // Mettre à jour le calcul du poids et du prix
            updateArticleSelection();
        });
    });
    
    // Mettre à jour le calcul lorsque la quantité change
    quantityInputs.forEach(input => {
        input.addEventListener('change', updateArticleSelection);
    });
    
    // Récupérer les frais de livraison en fonction du district
    function getDeliveryFeeForAddress(addressId) {
        const addressSelect = document.querySelector(`select option[value="${addressId}"]`);
        if (!addressSelect) return {{ $defaultDeliveryFee }};
        
        const addressText = addressSelect.textContent.trim();
        const districtMatch = addressText.match(/,\s*([^,]+)$/);
        
        if (!districtMatch) return {{ $defaultDeliveryFee }};
        
        const district = districtMatch[1].trim().toLowerCase();
        
        // Récupérer l'ID de la ville si disponible
        let cityId = null;
        @foreach($addresses as $address)
            if ('{{ $address->id }}' === addressId && {{ $address->city_id ?? 'null' }}) {
                cityId = {{ $address->city_id ?? 'null' }};
            }
        @endforeach
        
        // Chercher le frais correspondant au district et à la ville
        const key = (cityId ? cityId + '-' : '') + district;
        
        // Essayer d'abord avec la ville et le district
        if (cityId && deliveryFeesByDistrict[cityId + '-' + district]) {
            return deliveryFeesByDistrict[cityId + '-' + district];
        }
        
        // Essayer juste avec le district
        for (const [feeKey, fee] of Object.entries(deliveryFeesByDistrict)) {
            if (feeKey.endsWith('-' + district)) {
                return fee;
            }
        }
        
        // Valeur par défaut si aucun frais n'est trouvé
        return {{ $defaultDeliveryFee }};
    }
    
    // Update delivery fees based on selected addresses
    function updateDeliveryFees() {
        const pickupAddressId = pickupAddressSelect.value;
        const deliveryAddressId = deliveryAddressSelect.value;
        
        if (pickupAddressId && deliveryAddressId) {
            // Récupérer les frais pour chaque adresse
            const pickupFee = getDeliveryFeeForAddress(pickupAddressId);
            const deliveryFee = getDeliveryFeeForAddress(deliveryAddressId);
            
            // If pickup and delivery are the same address
            if (pickupAddressId === deliveryAddressId) {
                // Appliquer un seul frais pour les deux (collecte et livraison)
                pickupFeeInput.value = pickupFee;
                dropFeeInput.value = 0;
            } else {
                // Appliquer des frais distincts pour la collecte et la livraison
                pickupFeeInput.value = pickupFee;
                dropFeeInput.value = deliveryFee;
            }
            
            // Update total price
            updatePrices();
        }
    }
    
    // Calculate weight based on selected articles and quantities
    function calculateWeight() {
        let calculatedWeight = 0;
        
        // Add weight for each selected article
        articleCheckboxes.forEach(checkbox => {
            if (checkbox.checked && checkbox.dataset.weight) {
                const articleId = checkbox.dataset.id;
                const quantityInput = document.querySelector(`input[name="article_qty[${articleId}]"]`);
                const quantity = quantityInput ? parseInt(quantityInput.value) : 1;
                
                calculatedWeight += parseFloat(checkbox.dataset.weight) * quantity;
            }
        });
        
        // Ensure minimum weight of 0.5 kg
        return Math.max(calculatedWeight, 0.5);
    }
    
    // Update prices when article selection changes
    function updateArticleSelection() {
        const calculatedWeight = calculateWeight();
        
        // Only update the weight input if articles are selected
        if (calculatedWeight > 0.5) {
            weightInput.value = calculatedWeight.toFixed(1);
        }
        
        // Update all prices
        updatePrices();
    }
    
    function updatePrices() {
        const weight = parseFloat(weightInput.value);
        const pickupFee = parseFloat(pickupFeeInput.value) || 0;
        const dropFee = parseFloat(dropFeeInput.value) || 0;
        
        // Update display weight
        displayWeight.textContent = weight.toFixed(1);
        
        // Calculate subtotal
        const subtotal = weight * pricePerKg;
        estimatedPriceInput.value = subtotal;
        subtotalDisplay.textContent = formatNumber(subtotal);
        
        // Calculate total
        const total = subtotal + pickupFee + dropFee;
        totalDisplay.textContent = formatNumber(total);
    }
    
    function formatNumber(number) {
        return new Intl.NumberFormat('fr-FR').format(number);
    }
    
    // Add event listeners
    weightInput.addEventListener('input', updatePrices);
    pickupFeeInput.addEventListener('input', updatePrices);
    dropFeeInput.addEventListener('input', updatePrices);
    
    // Add event listeners to article checkboxes
    articleCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateArticleSelection);
    });
    
    // Add event listeners to address selects
    pickupAddressSelect.addEventListener('change', updateDeliveryFees);
    deliveryAddressSelect.addEventListener('change', updateDeliveryFees);
    
    // Initialize prices and delivery fees
    updateDeliveryFees();
    updatePrices();
});
</script>
@endpush 