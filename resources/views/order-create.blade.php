<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KLIN KLIN - Planifier une commande</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary-purple: #46186f;
            --primary-purple-light: #f2eef5;
            --secondary-orange: #f25f4d;
            --accent-yellow: #fbe133;
            --accent-mint: #89ffcb;
            --text-color: #333;
            --light-bg: #f8f9fa;
            --border-color: #e9ecef;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-color);
            padding-bottom: 2rem;
        }
        
        /* Card styles */
        .custom-card {
            border-radius: 8px;
            box-shadow: none;
            border: 1px solid var(--border-color);
            transition: transform 0.2s ease;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        
        .section-card {
            border-left: 3px solid var(--primary-purple) !important;
        }
        
        .custom-card .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 0.8rem 1rem;
        }
        
        .card-title {
            font-weight: 600;
            font-size: 1rem;
            color: var(--primary-purple);
            margin-bottom: 0;
        }
        
        /* Articles cards */
        .article-card {
            border-left: 3px solid var(--secondary-orange) !important;
            margin-bottom: 1rem;
            height: 100%;
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
        }
        
        .quantity-control button {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--light-bg);
            border: 1px solid var(--border-color);
            color: var(--text-color);
            font-weight: 600;
            padding: 0;
        }
        
        .quantity-control input {
            width: 40px;
            text-align: center;
            border: 1px solid var(--border-color);
            margin: 0 5px;
            height: 30px;
            padding: 0;
        }
        
        /* Buttons */
        .btn-primary {
            background-color: var(--primary-purple);
            border-color: var(--primary-purple);
        }
        
        .btn-primary:hover {
            background-color: #3a1459;
            border-color: #3a1459;
        }
        
        .btn-outline-primary {
            color: var(--primary-purple);
            border-color: var(--primary-purple);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-purple);
            border-color: var(--primary-purple);
            color: white;
        }
        
        /* Summary section */
        .summary-box {
            background-color: var(--primary-purple-light);
            border-radius: 8px;
            padding: 1.5rem;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        
        .summary-total {
            font-weight: 700;
            font-size: 1.1rem;
            border-top: 1px solid var(--border-color);
            padding-top: 0.5rem;
            margin-top: 0.5rem;
        }
        
        /* Search box */
        .search-box {
            position: relative;
            margin-bottom: 1rem;
        }
        
        .search-box input {
            padding-left: 40px;
            border-radius: 20px;
        }
        
        .search-box .bi-search {
            position: absolute;
            left: 15px;
            top: 10px;
            color: #6c757d;
        }
        
        /* Category sections */
        .category-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--primary-purple);
            margin: 1rem 0 0.5rem 0;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="text-center mb-4" style="color: var(--primary-purple)">Nouvelle commande</h1>
                
                @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Erreurs de validation:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <form id="orderForm" method="POST" action="{{ route('orders.store') }}">
                    @csrf
                    
                    <input type="hidden" name="total_amount" id="input_total_amount" value="0">
                    <input type="hidden" name="discount_amount" id="input_discount_amount" value="0">
                    <input type="hidden" name="service_fee" id="input_service_fee" value="0">
                    <input type="hidden" name="applied_promo_code" id="input_applied_promo_code" value="">
                    <input type="hidden" name="selected_articles_data" id="input_selected_articles_data" value="">
                    <input type="hidden" name="use_quota" id="input_use_quota" value="0">
                    <input type="hidden" name="order_status" id="input_order_status" value="pending">
                    
                    <!-- Section 1: Informations de collecte -->
                    <div class="card custom-card section-card">
                        <div class="card-header">
                            <h5 class="card-title"><i class="bi bi-geo-alt-fill me-2"></i>Informations de collecte</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="pickup_address" class="form-label">Adresse de collecte</label>
                                <select class="form-select" id="pickup_address" name="pickup_address_id" required>
                                    <option value="" disabled selected>Choisissez une adresse de collecte</option>
                                    @foreach($addresses as $address)
                                        <option value="{{ $address->id }}" {{ $defaultAddress && $defaultAddress->id == $address->id ? 'selected' : '' }}>
                                            {{ $address->name }} - {{ $address->address }}, {{ $address->neighborhood }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="pickup_complement" class="form-label">Complément d'adresse (optionnel)</label>
                                <input type="text" class="form-control" id="pickup_complement" name="pickup_complement" placeholder="Appartement, étage, code d'accès...">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="pickup_date" class="form-label">Date de collecte</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                        <input type="date" class="form-control" id="pickup_date" name="pickup_date" required min="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="pickup_time" class="form-label">Créneau horaire</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                        <select class="form-select" id="pickup_time" name="pickup_time" required>
                                            <option value="">Choisir un créneau...</option>
                                            @foreach($timeSlots as $slot)
                                                <option value="{{ $slot }}">{{ $slot }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="same_address_for_delivery" name="same_address_for_delivery" checked>
                                <label class="form-check-label" for="same_address_for_delivery">
                                    Utiliser la même adresse pour la livraison
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section 2: Articles -->
                    <div class="card custom-card section-card">
                        <div class="card-header">
                            <h5 class="card-title"><i class="bi bi-bag-fill me-2"></i>Sélection des articles</h5>
                        </div>
                        <div class="card-body">
                            <div class="search-box">
                                <i class="bi bi-search"></i>
                                <input type="text" class="form-control" id="searchArticles" placeholder="Rechercher un article...">
                            </div>
                            
                            <!-- Vêtements -->
                            <h6 class="category-title">Vêtements</h6>
                            <div class="row">
                                @foreach($groupedLaundryItems['clothes'] as $key => $item)
                                <div class="col-md-6 col-lg-4 mb-3 article-item" data-id="{{ $key }}" data-name="{{ $item['name'] }}" data-price="{{ $item['price'] }}" data-weight="{{ $item['weight'] }}">
                                    <div class="card article-card">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="mb-1">{{ $item['name'] }}</h6>
                                                    <p class="mb-0 small text-muted">{{ $item['price'] }} FCFA</p>
                                                </div>
                                                <div class="quantity-control">
                                                    <button type="button" class="btn-qty-minus">-</button>
                                                    <input type="number" class="item-quantity" min="0" value="0" readonly>
                                                    <button type="button" class="btn-qty-plus">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Linge de maison -->
                            <h6 class="category-title">Linge de maison</h6>
                            <div class="row">
                                @foreach($groupedLaundryItems['homeLinens'] as $key => $item)
                                <div class="col-md-6 col-lg-4 mb-3 article-item" data-id="{{ $key }}" data-name="{{ $item['name'] }}" data-price="{{ $item['price'] }}" data-weight="{{ $item['weight'] }}">
                                    <div class="card article-card">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="mb-1">{{ $item['name'] }}</h6>
                                                    <p class="mb-0 small text-muted">{{ $item['price'] }} FCFA</p>
                                                </div>
                                                <div class="quantity-control">
                                                    <button type="button" class="btn-qty-minus">-</button>
                                                    <input type="number" class="item-quantity" min="0" value="0" readonly>
                                                    <button type="button" class="btn-qty-plus">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Autres -->
                            <h6 class="category-title">Autres articles</h6>
                            <div class="row">
                                @foreach($groupedLaundryItems['others'] as $key => $item)
                                <div class="col-md-6 col-lg-4 mb-3 article-item" data-id="{{ $key }}" data-name="{{ $item['name'] }}" data-price="{{ $item['price'] }}" data-weight="{{ $item['weight'] }}">
                                    <div class="card article-card">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="mb-1">{{ $item['name'] }}</h6>
                                                    <p class="mb-0 small text-muted">{{ $item['price'] }} FCFA</p>
                                                </div>
                                                <div class="quantity-control">
                                                    <button type="button" class="btn-qty-minus">-</button>
                                                    <input type="number" class="item-quantity" min="0" value="0" readonly>
                                                    <button type="button" class="btn-qty-plus">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section 3: Instructions spéciales -->
                    <div class="card custom-card section-card">
                        <div class="card-header">
                            <h5 class="card-title"><i class="bi bi-chat-left-text-fill me-2"></i>Instructions spéciales</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="special_instructions" class="form-label">Instructions spéciales (optionnel)</label>
                                <textarea class="form-control" id="special_instructions" name="special_instructions" rows="3" placeholder="Informations supplémentaires pour le traitement de votre commande..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section 4: Résumé et paiement -->
                    <div class="card custom-card section-card">
                        <div class="card-header">
                            <h5 class="card-title"><i class="bi bi-receipt me-2"></i>Résumé de la commande</h5>
                        </div>
                        <div class="card-body">
                            <div class="summary-box mb-4">
                                <div class="summary-item">
                                    <span>Sous-total articles:</span>
                                    <span id="summary-articles-total">0 FCFA</span>
                                </div>
                                <div class="summary-item summary-total">
                                    <span>Total:</span>
                                    <span id="summary-grand-total">0 FCFA</span>
                                </div>
                            </div>
                            
                            @if($totalAvailableQuota > 0)
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="1" id="use_quota" name="use_quota">
                                <label class="form-check-label" for="use_quota">
                                    Utiliser mon quota disponible ({{ number_format($totalAvailableQuota, 2) }} kg)
                                </label>
                            </div>
                            @endif
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" id="submit-order-btn">
                                    <i class="bi bi-check-circle-fill me-2"></i>Confirmer ma commande
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Constantes
        const PRICE_PER_KG = 500; // 500 FCFA par kg
        let currentArticlesData = {};
        
        // Gestion des quantités
        document.querySelectorAll('.btn-qty-minus').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.item-quantity');
                const currentValue = parseInt(input.value) || 0;
                if (currentValue > 0) {
                    input.value = currentValue - 1;
                    input.dispatchEvent(new Event('change'));
                }
            });
        });
        
        document.querySelectorAll('.btn-qty-plus').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.item-quantity');
                const currentValue = parseInt(input.value) || 0;
                input.value = currentValue + 1;
                input.dispatchEvent(new Event('change'));
            });
        });
        
        document.querySelectorAll('.item-quantity').forEach(input => {
            input.addEventListener('change', function() {
                calculateAndUpdatePrices();
            });
        });
        
        // Gestion de l'adresse de livraison
        document.getElementById('same_address_for_delivery').addEventListener('change', function() {
            const deliveryAddressSection = document.getElementById('delivery_address_section');
            if (deliveryAddressSection) {
                deliveryAddressSection.style.display = this.checked ? 'none' : 'block';
            }
        });
        
        // Calcul du poids total
        function calculateTotalWeight() {
            let totalWeight = 0;
            
            document.querySelectorAll('.article-item').forEach(article => {
                const weight = parseFloat(article.dataset.weight);
                const quantity = parseInt(article.querySelector('.item-quantity').value) || 0;
                
                totalWeight += weight * quantity;
            });
            
            return totalWeight;
        }
        
        // Collecter les données des articles
        function collectArticlesData() {
            currentArticlesData = {};
            
            document.querySelectorAll('.article-item').forEach(article => {
                const id = article.dataset.id;
                const name = article.dataset.name;
                const price = parseFloat(article.dataset.price);
                const weight = parseFloat(article.dataset.weight || 0.5);
                const quantity = parseInt(article.querySelector('.item-quantity').value) || 0;
                
                if (quantity > 0) {
                    currentArticlesData[id] = {
                        id: id,
                        name: name,
                        price: price,
                        weight: weight,
                        quantity: quantity
                    };
                }
            });
            
            // Mettre à jour le champ caché
            document.getElementById('input_selected_articles_data').value = JSON.stringify(currentArticlesData);
            
            return currentArticlesData;
        }
        
        // Calculer et mettre à jour les prix affichés
        function calculateAndUpdatePrices() {
            // Collecter les données des articles
            collectArticlesData();
            
            // Récupérer le poids total
            const totalWeight = calculateTotalWeight();
            const displayWeight = Math.max(totalWeight, 0.10);
            
            // Calculer le prix total basé sur le poids
            const articlesTotal = Math.round(displayWeight * PRICE_PER_KG);
            
            // Calculer le total général (pas de frais de service)
            const serviceFee = 0;
            const grandTotal = articlesTotal + serviceFee;
            
            // Mettre à jour les affichages
            document.getElementById('summary-articles-total').textContent = articlesTotal.toLocaleString('fr-FR') + ' FCFA';
            document.getElementById('summary-grand-total').textContent = grandTotal.toLocaleString('fr-FR') + ' FCFA';
            
            // Mettre à jour les champs cachés du formulaire
            document.getElementById('input_total_amount').value = grandTotal;
            document.getElementById('input_service_fee').value = serviceFee;
            
            return {
                totalItemsPrice: articlesTotal,
                serviceFee: serviceFee,
                grandTotal: grandTotal
            };
        }
        
        // Recherche d'articles
        document.getElementById('searchArticles').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            document.querySelectorAll('.article-item').forEach(item => {
                const articleName = item.dataset.name.toLowerCase();
                item.style.display = articleName.includes(searchTerm) ? 'block' : 'none';
            });
        });
        
        // Soumission du formulaire
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            // Vérifier qu'il y a des articles sélectionnés
            collectArticlesData();
            if (Object.keys(currentArticlesData).length === 0) {
                e.preventDefault();
                alert("Veuillez sélectionner au moins un article avant de continuer.");
                return false;
            }
            
            // Mettre à jour le statut du quota
            document.getElementById('input_use_quota').value = document.getElementById('use_quota')?.checked ? '1' : '0';
            
            // Désactiver le bouton pour éviter les soumissions multiples
            document.getElementById('submit-order-btn').disabled = true;
            document.getElementById('submit-order-btn').innerHTML = '<span class="spinner-border spinner-border-sm"></span> Traitement en cours...';
        });
        
        // Initialisation
        calculateTotalWeight();
        calculateAndUpdatePrices();
        document.getElementById('same_address_for_delivery').dispatchEvent(new Event('change'));
    });
    </script>
</body>
</html> 