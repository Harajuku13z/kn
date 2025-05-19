@extends('orders-management.layout')

@section('title', 'Créer une nouvelle commande')

@section('content')
<div class="container-bootstrap">
    <h1 class="text-center mb-4 main-title">Créer une nouvelle commande</h1>

    <div class="progress-bar-custom d-flex justify-content-between align-items-start mb-5">
        <div class="step-custom text-center active" data-step-name="Collecte">
            <div class="step-icon"><span>1</span></div>
            <div class="step-text">Informations de collecte</div>
        </div>
        <div class="progress-line-custom flex-grow-1"></div>
        <div class="step-custom text-center" data-step-name="Livraison">
            <div class="step-icon"><span>2</span></div>
            <div class="step-text">Livraison</div>
        </div>
        <div class="progress-line-custom flex-grow-1"></div>
        <div class="step-custom text-center" data-step-name="Articles">
            <div class="step-icon"><span>3</span></div>
            <div class="step-text">Articles</div>
        </div>
        <div class="progress-line-custom flex-grow-1"></div>
        <div class="step-custom text-center" data-step-name="Récapitulatif">
            <div class="step-icon"><span>4</span></div>
            <div class="step-text">Récapitulatifs</div>
        </div>
        <div class="progress-line-custom flex-grow-1"></div>
        <div class="step-custom text-center" data-step-name="Paiement">
            <div class="step-icon"><span>5</span></div>
            <div class="step-text">Paiement</div>
        </div>
    </div>

    <form id="multiStepForm" action="{{ route('orders-management.store') }}" method="POST">
        @csrf
        <div class="form-step active" id="step1">
            <h2 class="mb-1">Planifier une collecte <i class="bi bi-emoji-wave"></i></h2>
            <p class="text-muted mb-4">Choisissez la date, le créneau horaire et l'adresse pour planifier votre prochaine collecte de linge.</p>
            
            <div class="mb-3">
                <label for="collection_address" class="form-label">Adresse</label>
                <input type="text" class="form-control @error('collection_address') is-invalid @enderror" id="collection_address" name="collection_address" value="{{ old('collection_address') }}" required>
                @error('collection_address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="collection_address_complement" class="form-label">Complément d'adresse</label>
                <input type="text" class="form-control @error('collection_address_complement') is-invalid @enderror" id="collection_address_complement" name="collection_address_complement" value="{{ old('collection_address_complement') }}" placeholder="Appartement, suite, etc.">
                @error('collection_address_complement')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="collection_city" class="form-label">Ville</label>
                    <input type="text" class="form-control @error('collection_city') is-invalid @enderror" id="collection_city" name="collection_city" value="{{ old('collection_city') }}" required>
                    @error('collection_city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="collection_postal_code" class="form-label">Code postal</label>
                    <input type="text" class="form-control @error('collection_postal_code') is-invalid @enderror" id="collection_postal_code" name="collection_postal_code" value="{{ old('collection_postal_code') }}" required>
                    @error('collection_postal_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="collection_date" class="form-label">Date</label>
                    <input type="date" class="form-control @error('collection_date') is-invalid @enderror" id="collection_date" name="collection_date" value="{{ old('collection_date') }}" required>
                    @error('collection_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="collection_time_slot" class="form-label">Créneau horaire</label>
                    <select class="form-select @error('collection_time_slot') is-invalid @enderror" id="collection_time_slot" name="collection_time_slot" required>
                        <option value="">Sélectionnez un créneau</option>
                        <option value="08h-10h" {{ old('collection_time_slot') == '08h-10h' ? 'selected' : '' }}>08h - 10h</option>
                        <option value="10h-12h" {{ old('collection_time_slot') == '10h-12h' ? 'selected' : '' }}>10h - 12h</option>
                        <option value="12h-14h" {{ old('collection_time_slot') == '12h-14h' ? 'selected' : '' }}>12h - 14h</option>
                        <option value="14h-16h" {{ old('collection_time_slot') == '14h-16h' ? 'selected' : '' }}>14h - 16h</option>
                        <option value="16h-18h" {{ old('collection_time_slot') == '16h-18h' ? 'selected' : '' }}>16h - 18h</option>
                    </select>
                    @error('collection_time_slot')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-primary-custom next-step">Continuer vers la livraison</button>
            </div>
        </div>

        <div class="form-step" id="step2">
            <h2 class="mb-1">Informations de livraison <i class="bi bi-truck"></i></h2>
            <p class="text-muted mb-4">Où souhaitez-vous que votre linge propre soit livré ?</p>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="same_address_for_delivery" name="same_address_for_delivery" {{ old('same_address_for_delivery') ? 'checked' : '' }}>
                <label class="form-check-label" for="same_address_for_delivery">
                    Utiliser la même adresse que pour la collecte.
                </label>
            </div>
            <div id="delivery-address-fields">
                <div class="mb-3">
                    <label for="delivery_address" class="form-label">Adresse</label>
                    <input type="text" class="form-control @error('delivery_address') is-invalid @enderror" id="delivery_address" name="delivery_address" value="{{ old('delivery_address') }}" placeholder="Rue, numéro">
                    @error('delivery_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="delivery_address_complement" class="form-label">Complément d'adresse</label>
                    <input type="text" class="form-control @error('delivery_address_complement') is-invalid @enderror" id="delivery_address_complement" name="delivery_address_complement" value="{{ old('delivery_address_complement') }}" placeholder="Appartement, suite, etc.">
                    @error('delivery_address_complement')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="delivery_city" class="form-label">Ville</label>
                        <input type="text" class="form-control @error('delivery_city') is-invalid @enderror" id="delivery_city" name="delivery_city" value="{{ old('delivery_city') }}" placeholder="Votre ville">
                        @error('delivery_city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="delivery_postal_code" class="form-label">Code postal</label>
                        <input type="text" class="form-control @error('delivery_postal_code') is-invalid @enderror" id="delivery_postal_code" name="delivery_postal_code" value="{{ old('delivery_postal_code') }}" placeholder="Votre code postal">
                        @error('delivery_postal_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="delivery_instructions" class="form-label">Instructions de livraison (optionnel)</label>
                    <textarea class="form-control @error('delivery_instructions') is-invalid @enderror" id="delivery_instructions" name="delivery_instructions" rows="3" placeholder="Ex: Laisser chez le gardien, code portail 1234...">{{ old('delivery_instructions') }}</textarea>
                    @error('delivery_instructions')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-secondary-custom prev-step">Retour</button>
                <button type="button" class="btn btn-primary-custom next-step">Continuer vers les articles</button>
            </div>
        </div>

        <div class="form-step" id="step3">
            <h2 class="mb-1">Vous choisissez ce que nous lavons ! <i class="bi bi-basket3-fill"></i></h2>
            <p class="text-muted mb-4">Ajoutez vos articles un par un et découvrez instantanément le tarif estimé pour votre commande.</p>
            <div class="row gy-4" id="articlesContainer">
                <div class="col-md-6 article-item" data-id="chemise" data-name="Chemise" data-price="10">
                    <div class="card">
                        <div class="row g-0">
                            <div class="col-4">
                                <img src="https://via.placeholder.com/150x120?text=Chemise" class="img-fluid rounded-start h-100 object-fit-cover" alt="Chemise">
                            </div>
                            <div class="col-8">
                                <div class="card-body">
                                    <h5 class="card-title">Chemise</h5>
                                    <p class="card-text"><small class="text-muted">10€ | 0,8 - 1,5 kg</small></p>
                                    <div class="input-group input-group-sm quantity-selector">
                                        <button class="btn btn-outline-secondary quantity-minus" type="button">-</button>
                                        <input type="number" class="form-control text-center item-quantity" value="0" min="0" aria-label="Quantité Chemise" name="items[chemise][quantity]">
                                        <input type="hidden" name="items[chemise][name]" value="Chemise">
                                        <input type="hidden" name="items[chemise][price]" value="10">
                                        <button class="btn btn-outline-secondary quantity-plus" type="button">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 article-item" data-id="jeans" data-name="Jeans" data-price="25">
                    <div class="card">
                        <div class="row g-0">
                            <div class="col-4">
                                <img src="https://via.placeholder.com/150x120?text=Jeans" class="img-fluid rounded-start h-100 object-fit-cover" alt="Jeans">
                            </div>
                            <div class="col-8">
                                <div class="card-body">
                                    <h5 class="card-title">Jeans</h5>
                                    <p class="card-text"><small class="text-muted">25€ | 0,8 - 1,5 kg</small></p>
                                    <div class="input-group input-group-sm quantity-selector">
                                        <button class="btn btn-outline-secondary quantity-minus" type="button">-</button>
                                        <input type="number" class="form-control text-center item-quantity" value="0" min="0" aria-label="Quantité Jeans" name="items[jeans][quantity]">
                                        <input type="hidden" name="items[jeans][name]" value="Jeans">
                                        <input type="hidden" name="items[jeans][price]" value="25">
                                        <button class="btn btn-outline-secondary quantity-plus" type="button">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 article-item" data-id="veste" data-name="Veste" data-price="15">
                    <div class="card">
                        <div class="row g-0">
                            <div class="col-4">
                                <img src="https://via.placeholder.com/150x120?text=Veste" class="img-fluid rounded-start h-100 object-fit-cover" alt="Veste">
                            </div>
                            <div class="col-8">
                                <div class="card-body">
                                    <h5 class="card-title">Veste</h5>
                                    <p class="card-text"><small class="text-muted">15€ | 1-3 kg</small></p>
                                    <div class="input-group input-group-sm quantity-selector">
                                        <button class="btn btn-outline-secondary quantity-minus" type="button">-</button>
                                        <input type="number" class="form-control text-center item-quantity" value="0" min="0" aria-label="Quantité Veste" name="items[veste][quantity]">
                                        <input type="hidden" name="items[veste][name]" value="Veste">
                                        <input type="hidden" name="items[veste][price]" value="15">
                                        <button class="btn btn-outline-secondary quantity-plus" type="button">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 article-item" data-id="robe" data-name="Robe" data-price="20">
                    <div class="card">
                        <div class="row g-0">
                            <div class="col-4">
                                <img src="https://via.placeholder.com/150x120?text=Robe" class="img-fluid rounded-start h-100 object-fit-cover" alt="Robe">
                            </div>
                            <div class="col-8">
                                <div class="card-body">
                                    <h5 class="card-title">Robe</h5>
                                    <p class="card-text"><small class="text-muted">20€ | 0.8-1.8 kg</small></p>
                                    <div class="input-group input-group-sm quantity-selector">
                                        <button class="btn btn-outline-secondary quantity-minus" type="button">-</button>
                                        <input type="number" class="form-control text-center item-quantity" value="0" min="0" aria-label="Quantité Robe" name="items[robe][quantity]">
                                        <input type="hidden" name="items[robe][name]" value="Robe">
                                        <input type="hidden" name="items[robe][price]" value="20">
                                        <button class="btn btn-outline-secondary quantity-plus" type="button">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="h5 mt-4"><strong>Prix estimé (articles) : <span id="estimatedPrice">0</span>€</strong></p>
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-secondary-custom prev-step">Retour</button>
                <button type="button" class="btn btn-primary-custom next-step">Poursuivre ma commande</button>
            </div>
        </div>

        <div class="form-step" id="step4">
            <h2 class="mb-1">Récapitulatifs de la commande <i class="bi bi-receipt"></i></h2>
            <p class="text-muted mb-4">Vérifiez les informations de votre commande.</p>

            <div class="border rounded p-3 mb-3">
                <p><strong>Date & créneau horaire (collecte):</strong> <span id="summary-collecte-datetime">À définir</span></p>
                <p class="mb-0"><strong>Adresse de Livraison:</strong> <span id="summary-livraison-address">À définir</span>
                   <button type="button" class="btn btn-link btn-sm edit-step p-0 ms-2" data-step="2">Modifier</button>
                </p>
            </div>

            <div class="border rounded p-3 mb-4">
                <h5>Articles sélectionnés :</h5>
                <ul class="list-unstyled" id="summary-articles-list">
                    <li>Aucun article sélectionné.</li>
                </ul>
                <hr>
                <p class="h5 text-end"><strong>Total articles : <span id="summary-articles-total-price">0</span>€</strong></p>
            </div>
            
            <div class="row">
                <div class="col-md-7">
                     <label for="promo-code-recap" class="form-label">Code promo</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="promo-code-recap" placeholder="Entrer le code">
                        <button class="btn btn-outline-primary" type="button">Appliquer</button>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="bg-light p-3 rounded">
                        <h5 class="text-primary-custom">Récapitulatif Final</h5>
                        <ul class="list-unstyled">
                            <li class="d-flex justify-content-between"><span>Sous-total (articles)</span> <span id="summary-subtotal">0 €</span></li>
                            <li class="d-flex justify-content-between"><span>Expédition</span> <span id="summary-shipping">10 €</span></li>
                            <li class="d-flex justify-content-between"><span>Taxe estimée</span> <span id="summary-tax">5 €</span></li>
                        </ul>
                        <hr>
                        <div class="d-flex justify-content-between h5"><strong>Total</strong> <strong id="summary-total">15 €</strong></div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-secondary-custom prev-step">Retour</button>
                <div>
                    <button type="button" class="btn btn-cancel-custom me-2">Annuler</button>
                    <button type="button" class="btn btn-primary-custom next-step">Passer au paiement</button>
                </div>
            </div>
        </div>

        <div class="form-step" id="step5">
            <h2 class="mb-1">Effectuer le paiement <i class="bi bi-credit-card-fill"></i></h2>
             <p class="text-muted mb-4">Sélectionnez votre méthode de paiement et renseignez les informations.</p>

            <div class="btn-group w-100 mb-4" role="group" aria-label="Méthodes de paiement">
                <input type="radio" class="btn-check" name="payment_method" id="paymentVisa" value="visa" autocomplete="off" checked>
                <label class="btn btn-outline-primary" for="paymentVisa"><i class="bi bi-credit-card"></i> Visa/MasterCard</label>

                <input type="radio" class="btn-check" name="payment_method" id="paymentMobile" value="mobile" autocomplete="off">
                <label class="btn btn-outline-primary" for="paymentMobile"><i class="bi bi-phone"></i> Mobile money</label>

                <input type="radio" class="btn-check" name="payment_method" id="paymentAirtel" value="airtel" autocomplete="off">
                <label class="btn btn-outline-primary" for="paymentAirtel"><i class="bi bi-wifi"></i> Airtel Money</label>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="card-number" class="form-label">Numéro de carte*</label>
                    <input type="text" class="form-control" id="card-number" placeholder="•••• •••• •••• ••••">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="expiry-date" class="form-label">Date d'expiration*</label>
                    <input type="text" class="form-control" id="expiry-date" placeholder="MM/AA">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cvv" class="form-label">CVV*</label>
                    <input type="text" class="form-control" id="cvv" placeholder="123">
                </div>
            </div>
             <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="billing-same-as-delivery" name="billing-same-as-delivery" checked>
                <label class="form-check-label" for="billing-same-as-delivery">
                    Utiliser l'adresse de livraison comme adresse de facturation.
                </label>
            </div>

            <div class="row">
                 <div class="col-md-7">
                </div>
                <div class="col-md-5">
                     <div class="bg-light p-3 rounded mb-3">
                        <h5 class="text-primary-custom">Récapitulatif</h5>
                         <ul class="list-unstyled">
                            <li class="d-flex justify-content-between"><span>Sous-total (articles)</span> <span id="payment-subtotal">0 €</span></li>
                            <li class="d-flex justify-content-between"><span>Expédition</span> <span id="payment-shipping">10 €</span></li>
                            <li class="d-flex justify-content-between"><span>Taxe estimée</span> <span id="payment-tax">5 €</span></li>
                        </ul>
                        <hr>
                        <div class="d-flex justify-content-between h5"><strong>Total</strong> <strong id="payment-total">15 €</strong></div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-secondary-custom prev-step">Retour</button>
                <button type="submit" class="btn btn-primary-custom" id="payButton">Payer 15€</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const multiStepForm = document.getElementById('multiStepForm');
        const formSteps = Array.from(multiStepForm.querySelectorAll('.form-step'));
        const progressSteps = Array.from(document.querySelectorAll('.progress-bar-custom .step-custom'));
        const progressLines = Array.from(document.querySelectorAll('.progress-bar-custom .progress-line-custom'));

        const articlesContainer = document.getElementById('articlesContainer');
        const estimatedPriceSpan = document.getElementById('estimatedPrice'); 

        const summaryArticlesListUl = document.getElementById('summary-articles-list');
        const summaryArticlesTotalPriceSpan = document.getElementById('summary-articles-total-price');
        const summarySubtotalSpan = document.getElementById('summary-subtotal');
        const summaryShippingSpan = document.getElementById('summary-shipping');
        const summaryTaxSpan = document.getElementById('summary-tax');
        const summaryTotalSpan = document.getElementById('summary-total');

        const paymentSubtotalSpan = document.getElementById('payment-subtotal');
        const paymentShippingSpan = document.getElementById('payment-shipping');
        const paymentTaxSpan = document.getElementById('payment-tax');
        const paymentTotalSpan = document.getElementById('payment-total');
        const payButton = document.getElementById('payButton');

        const summaryCollecteDatetime = document.getElementById('summary-collecte-datetime');
        const summaryLivraisonAddress = document.getElementById('summary-livraison-address');

        const SHIPPING_COST = 10; 
        const TAX_COST = 5;       

        let currentStep = 0;
        let articlesData = {}; 

        function updateFormSteps() {
            formSteps.forEach((step, index) => {
                step.classList.toggle('active', index === currentStep);
            });
            
            // Update summary when on step 4 (récapitulatif)
            if (currentStep === 3) {
                updateSummary();
            }
            
            // Update price calculations for both step 3 (articles) and step 4 (récapitulatif)
            if (currentStep === 2 || currentStep === 3 || currentStep === 4) { 
                calculateAndUpdatePrices();
            }
        }

        function updateProgressBar() {
            progressSteps.forEach((step, index) => {
                const stepIconSpan = step.querySelector('.step-icon span');
                if (index < currentStep) {
                    step.classList.add('completed');
                    step.classList.remove('active');
                    if (stepIconSpan) stepIconSpan.innerHTML = '&#10003;';
                } else if (index === currentStep) {
                    step.classList.add('active');
                    step.classList.remove('completed');
                    if (stepIconSpan) stepIconSpan.textContent = index + 1;
                } else {
                    step.classList.remove('active');
                    step.classList.remove('completed');
                    if (stepIconSpan) stepIconSpan.textContent = index + 1;
                }
            });

            progressLines.forEach((line, index) => {
                line.classList.toggle('active', index < currentStep);
            });
        }

        function updateSummary() {
            // Update collection date and time
            const collectionDate = document.getElementById('collection_date');
            const collectionTimeSlot = document.getElementById('collection_time_slot');
            
            if (collectionDate && collectionDate.value && collectionTimeSlot && collectionTimeSlot.value) {
                const formattedDate = new Date(collectionDate.value).toLocaleDateString('fr-FR');
                summaryCollecteDatetime.textContent = `${formattedDate} - ${collectionTimeSlot.value}`;
            }

            // Update delivery address
            const sameAddressForDelivery = document.getElementById('same_address_for_delivery').checked;
            let addressText = '';
            
            if (sameAddressForDelivery) {
                const collectionAddress = document.getElementById('collection_address').value;
                const collectionCity = document.getElementById('collection_city').value;
                const collectionPostalCode = document.getElementById('collection_postal_code').value;
                
                addressText = `${collectionAddress}, ${collectionCity} ${collectionPostalCode}`;
            } else {
                const deliveryAddress = document.getElementById('delivery_address').value;
                const deliveryCity = document.getElementById('delivery_city').value;
                const deliveryPostalCode = document.getElementById('delivery_postal_code').value;
                
                addressText = `${deliveryAddress}, ${deliveryCity} ${deliveryPostalCode}`;
            }
            
            summaryLivraisonAddress.textContent = addressText;
        }

        function calculateAndUpdatePrices() {
            let currentArticlesTotal = 0;
            articlesData = {}; 

            if (articlesContainer) {
                const articleItems = articlesContainer.querySelectorAll('.article-item');
                articleItems.forEach(item => {
                    const id = item.dataset.id;
                    const name = item.dataset.name;
                    const price = parseFloat(item.dataset.price);
                    const quantityInput = item.querySelector('.item-quantity');
                    const quantity = parseInt(quantityInput.value, 10);

                    if (quantity > 0) {
                        currentArticlesTotal += price * quantity;
                        articlesData[id] = { name, price, quantity };
                    }
                });
            }

            if (estimatedPriceSpan) {
                estimatedPriceSpan.textContent = currentArticlesTotal.toFixed(2);
            }

            if (summaryArticlesListUl) {
                summaryArticlesListUl.innerHTML = ''; 
                if (Object.keys(articlesData).length === 0) {
                    summaryArticlesListUl.innerHTML = '<li>Aucun article sélectionné.</li>';
                } else {
                    for (const id in articlesData) {
                        const article = articlesData[id];
                        const listItem = document.createElement('li');
                        listItem.textContent = `${article.name} - ${article.quantity} x ${article.price.toFixed(2)}€ = ${(article.quantity * article.price).toFixed(2)}€`;
                        summaryArticlesListUl.appendChild(listItem);
                    }
                }
            }
            
            if (summaryArticlesTotalPriceSpan) { 
                summaryArticlesTotalPriceSpan.textContent = currentArticlesTotal.toFixed(2);
            }

            const finalSubtotal = currentArticlesTotal;
            const finalTotal = finalSubtotal + SHIPPING_COST + TAX_COST;

            if (summarySubtotalSpan) summarySubtotalSpan.textContent = finalSubtotal.toFixed(2) + ' €';
            if (summaryShippingSpan) summaryShippingSpan.textContent = SHIPPING_COST.toFixed(2) + ' €'; 
            if (summaryTaxSpan) summaryTaxSpan.textContent = TAX_COST.toFixed(2) + ' €'; 
            if (summaryTotalSpan) summaryTotalSpan.textContent = finalTotal.toFixed(2) + ' €';

            if (paymentSubtotalSpan) paymentSubtotalSpan.textContent = finalSubtotal.toFixed(2) + ' €';
            if (paymentShippingSpan) paymentShippingSpan.textContent = SHIPPING_COST.toFixed(2) + ' €';
            if (paymentTaxSpan) paymentTaxSpan.textContent = TAX_COST.toFixed(2) + ' €';
            if (paymentTotalSpan) paymentTotalSpan.textContent = finalTotal.toFixed(2) + ' €';
            if (payButton) payButton.textContent = `Payer ${finalTotal.toFixed(2)}€`;
        }

        function handleQuantityChange(event) {
            const button = event.target.closest('.quantity-minus, .quantity-plus');
            const inputField = event.target.closest('.quantity-selector')?.querySelector('.item-quantity');

            if (button && inputField) {
                let currentValue = parseInt(inputField.value, 10);
                if (isNaN(currentValue)) currentValue = 0;

                if (button.classList.contains('quantity-plus')) {
                    currentValue++;
                } else if (button.classList.contains('quantity-minus') && currentValue > 0) {
                    currentValue--;
                }
                inputField.value = currentValue;
            }
            calculateAndUpdatePrices();
        }
        
        if (articlesContainer) {
            articlesContainer.addEventListener('click', handleQuantityChange);
            articlesContainer.addEventListener('input', (event) => { 
                if (event.target.classList.contains('item-quantity')) {
                    let val = parseInt(event.target.value, 10);
                    if (isNaN(val) || val < 0) {
                        event.target.value = 0;
                    }
                    calculateAndUpdatePrices();
                }
            });
        }

        multiStepForm.addEventListener('click', (e) => {
            let buttonClicked = null;
            if (e.target.matches('.next-step') || e.target.closest('.next-step')) {
                buttonClicked = e.target.matches('.next-step') ? e.target : e.target.closest('.next-step');
            } else if (e.target.matches('.prev-step') || e.target.closest('.prev-step')) {
                buttonClicked = e.target.matches('.prev-step') ? e.target : e.target.closest('.prev-step');
            } else if (e.target.matches('.edit-step') || e.target.closest('.edit-step')) {
                buttonClicked = e.target.matches('.edit-step') ? e.target : e.target.closest('.edit-step');
            }

            if (buttonClicked) {
                if (buttonClicked.classList.contains('next-step')) {
                    if (currentStep < formSteps.length - 1) {
                        currentStep++;
                        updateFormSteps(); 
                        updateProgressBar();
                    }
                } else if (buttonClicked.classList.contains('prev-step')) {
                    if (currentStep > 0) {
                        currentStep--;
                        updateFormSteps();
                        updateProgressBar();
                    }
                } else if (buttonClicked.classList.contains('edit-step')) {
                    const stepToEdit = parseInt(buttonClicked.dataset.step, 10);
                    if (!isNaN(stepToEdit) && stepToEdit > 0 && stepToEdit <= formSteps.length) {
                        currentStep = stepToEdit - 1;
                        updateFormSteps();
                        updateProgressBar();
                    }
                }
            }
        });

        const sameAddressCheckbox = document.getElementById('same_address_for_delivery');
        const deliveryAddressFields = document.getElementById('delivery-address-fields');
        if (sameAddressCheckbox && deliveryAddressFields) {
            sameAddressCheckbox.addEventListener('change', () => {
                deliveryAddressFields.style.display = sameAddressCheckbox.checked ? 'none' : 'block';
            });
            // Initialize based on initial state
            deliveryAddressFields.style.display = sameAddressCheckbox.checked ? 'none' : 'block';
        }

        // Initialize the form
        updateFormSteps();
        updateProgressBar();
        calculateAndUpdatePrices();
    });
</script>
@endsection 