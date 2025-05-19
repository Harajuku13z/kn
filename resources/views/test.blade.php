<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Commande Bootstrap (Complet Modifié)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* CSS Intégré */
        :root {
            --app-primary-color: #4A148C; /* Violet principal */
            --app-secondary-color: #6c757d; 
            --app-cancel-color: #FF7043;   /* Orange/Corail */
            --app-light-bg: #f8f9fa;      
            --app-progress-inactive: #e0e0e0;
            --app-progress-text-inactive: #aaa;
        }

        body {
            background-color: #f4f5f7; 
        }

        .form-container-bootstrap {
            background-color: #fff;
        }

        .main-title {
            color: var(--app-primary-color);
        }

        h2 { 
            font-weight: bold;
            font-size: 1.8em; 
            color: #333;
        }

        .progress-bar-custom .step-custom {
            flex-basis: 100px; 
            flex-shrink: 0;
            color: var(--app-progress-text-inactive);
            font-size: 0.85em;
        }

        .progress-bar-custom .step-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: var(--app-progress-inactive);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 8px auto;
            border: 2px solid var(--app-progress-inactive);
            font-weight: bold;
        }

        .progress-bar-custom .step-custom.active .step-icon {
            background-color: var(--app-primary-color);
            border-color: var(--app-primary-color);
        }
        .progress-bar-custom .step-custom.active .step-text {
            color: var(--app-primary-color);
            font-weight: bold;
        }

        .progress-bar-custom .step-custom.completed .step-icon {
            background-color: var(--app-primary-color); 
            border-color: var(--app-primary-color);
            color: white;
        }
        .progress-bar-custom .step-custom.completed .step-text {
            color: var(--app-primary-color); 
        }

        .progress-bar-custom .progress-line-custom {
            height: 4px;
            background-color: var(--app-progress-inactive);
            margin-top: 15px; 
        }

        .progress-bar-custom .progress-line-custom.active {
            background-color: var(--app-primary-color);
        }

        .form-step {
            display: none; 
        }
        .form-step.active {
            display: block;
            animation: fadeIn 0.5s; 
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-primary-custom, .btn-pay-custom, .btn-apply-custom {
            background-color: var(--app-primary-color);
            border-color: var(--app-primary-color);
            color: white;
        }
        .btn-primary-custom:hover, .btn-pay-custom:hover, .btn-apply-custom:hover {
            background-color: #38006B; 
            border-color: #38006B;
            color: white;
        }

        .btn-secondary-custom {
            background-color: #f0f0f0; 
            border-color: #ddd;
            color: #333;
        }
        .btn-secondary-custom:hover {
            background-color: #e0e0e0;
        }

        .btn-cancel-custom {
            background-color: var(--app-cancel-color);
            border-color: var(--app-cancel-color);
            color: white;
        }
        .btn-cancel-custom:hover {
            background-color: #F4511E; 
            border-color: #F4511E;
            color: white;
        }

        .card-title {
            margin-bottom: 0.25rem;
        }
        .card-text small {
            font-size: 0.85em;
        }
        .object-fit-cover { 
            object-fit: cover;
        }

        .btn-check:checked + .btn-outline-primary-custom, .btn-check:active + .btn-outline-primary-custom {
            color: #fff;
            background-color: var(--app-primary-color);
            border-color: var(--app-primary-color);
            box-shadow: 0 0 0 0.25rem rgba(74, 20, 140, 0.5); 
        }
        .btn-outline-primary-custom {
            color: var(--app-primary-color);
            border-color: var(--app-primary-color);
        }
        .btn-outline-primary-custom:hover {
            color: #fff;
            background-color: var(--app-primary-color);
            border-color: var(--app-primary-color);
        }

        .text-primary-custom {
            color: var(--app-primary-color) !important;
        }

        @media (max-width: 768px) {
            .progress-bar-custom .step-text {
                font-size: 0.75em; 
            }
            .progress-bar-custom .step-icon {
                width: 25px;
                height: 25px;
                font-size: 0.9em;
            }
             .progress-bar-custom .progress-line-custom {
                margin-top: 12px; 
            }
        }
        @media (max-width: 576px) {
            .progress-bar-custom .step-text {
                display: none; 
            }
            .progress-bar-custom .step-custom {
                flex-basis: auto; 
            }
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="form-container-bootstrap p-4 p-md-5 shadow rounded">
            <h1 class="text-center mb-4 main-title">Votre Commande de Pressing</h1>

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

            <form id="multiStepForm">
                <div class="form-step active" id="step1">
                    <h2 class="mb-1">Planifier une collecte <i class="bi bi-emoji-wave"></i></h2>
                    <p class="text-muted mb-4">Choisissez la date, le créneau horaire et l'adresse pour planifier votre prochaine collecte de linge.</p>
                    
                    <div class="mb-3">
                        <label for="collecte-adresse" class="form-label">Adresse</label>
                        <select class="form-select" id="collecte-adresse" name="collecte-adresse">
                            <option value="95 Rue Pointe Noire, Congo">95 Rue Pointe Noire, Congo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="collecte-complement" class="form-label">Complément d'adresse</label>
                        <input type="text" class="form-control" id="collecte-complement" name="collecte-complement" placeholder="Appartement, suite, etc.">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="collecte-ville" class="form-label">Ville</label>
                            <input type="text" class="form-control" id="collecte-ville" name="collecte-ville" value="Brazzaville">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="collecte-cp" class="form-label">Code postal</label>
                            <input type="text" class="form-control" id="collecte-cp" name="collecte-cp" value="7500">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="collecte-date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="collecte-date" name="collecte-date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="collecte-creneau" class="form-label">Créneau horaire</label>
                            <select class="form-select" id="collecte-creneau" name="collecte-creneau">
                                <option value="08h-10h">08h - 10h</option>
                            </select>
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
                        <input class="form-check-input" type="checkbox" id="same-address" name="same-address">
                        <label class="form-check-label" for="same-address">
                            Utiliser la même adresse que pour la collecte.
                        </label>
                    </div>
                    <div id="delivery-address-fields">
                        <div class="mb-3">
                            <label for="livraison-adresse" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="livraison-adresse" name="livraison-adresse" placeholder="Rue, numéro">
                        </div>
                        <div class="mb-3">
                            <label for="livraison-complement" class="form-label">Complément d'adresse</label>
                            <input type="text" class="form-control" id="livraison-complement" name="livraison-complement" placeholder="Appartement, suite, etc.">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="livraison-ville" class="form-label">Ville</label>
                                <input type="text" class="form-control" id="livraison-ville" name="livraison-ville" placeholder="Votre ville">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="livraison-cp" class="form-label">Code postal</label>
                                <input type="text" class="form-control" id="livraison-cp" name="livraison-cp" placeholder="Votre code postal">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="livraison-instructions" class="form-label">Instructions de livraison (optionnel)</label>
                            <textarea class="form-control" id="livraison-instructions" name="livraison-instructions" rows="3" placeholder="Ex: Laisser chez le gardien, code portail 1234..."></textarea>
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
                                                <input type="number" class="form-control text-center item-quantity" value="0" min="0" aria-label="Quantité Chemise">
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
                                                <input type="number" class="form-control text-center item-quantity" value="0" min="0" aria-label="Quantité Jeans">
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
                                    <div class="col-4"><img src="https://via.placeholder.com/150x120?text=Veste" class="img-fluid rounded-start h-100 object-fit-cover" alt="Veste"></div>
                                    <div class="col-8"><div class="card-body"><h5 class="card-title">Veste</h5><p class="card-text"><small class="text-muted">15€ | 1-3 kg</small></p><div class="input-group input-group-sm quantity-selector"><button class="btn btn-outline-secondary quantity-minus" type="button">-</button><input type="number" class="form-control text-center item-quantity" value="0" min="0" aria-label="Quantité Veste"><button class="btn btn-outline-secondary quantity-plus" type="button">+</button></div></div></div>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-6 article-item" data-id="robe" data-name="Robe" data-price="20">
                            <div class="card">
                                <div class="row g-0">
                                    <div class="col-4"><img src="https://via.placeholder.com/150x120?text=Robe" class="img-fluid rounded-start h-100 object-fit-cover" alt="Robe"></div>
                                    <div class="col-8"><div class="card-body"><h5 class="card-title">Robe</h5><p class="card-text"><small class="text-muted">20€ | 0.8-1.8 kg</small></p><div class="input-group input-group-sm quantity-selector"><button class="btn btn-outline-secondary quantity-minus" type="button">-</button><input type="number" class="form-control text-center item-quantity" value="0" min="0" aria-label="Quantité Robe"><button class="btn btn-outline-secondary quantity-plus" type="button">+</button></div></div></div>
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
                                <button class="btn btn-apply-custom" type="button">Appliquer</button>
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
                        <input type="radio" class="btn-check" name="paymentMethod" id="paymentVisa" autocomplete="off" checked>
                        <label class="btn btn-outline-primary-custom" for="paymentVisa"><i class="bi bi-credit-card"></i> Visa/MasterCard</label>

                        <input type="radio" class="btn-check" name="paymentMethod" id="paymentMobile" autocomplete="off">
                        <label class="btn btn-outline-primary-custom" for="paymentMobile"><i class="bi bi-phone"></i> Mobile money</label>

                        <input type="radio" class="btn-check" name="paymentMethod" id="paymentAirtel" autocomplete="off">
                        <label class="btn btn-outline-primary-custom" for="paymentAirtel"><i class="bi bi-wifi"></i> Airtel Money</label>
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
                        <button type="submit" class="btn btn-pay-custom" id="payButton">Payer 15€</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <script>
        // JavaScript Intégré avec simulateur de prix fonctionnel
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

            const SHIPPING_COST = 10; 
            const TAX_COST = 5;       

            let currentStep = 0;
            let articlesData = {}; 

            function updateFormSteps() {
                formSteps.forEach((step, index) => {
                    step.classList.toggle('active', index === currentStep);
                });
                if (currentStep === 3 || currentStep === 4) { 
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

            function calculateAndUpdatePrices() {
                let currentArticlesTotal = 0;
                articlesData = {}; 

                if (articlesContainer) { // S'assurer que articlesContainer existe avant de l'utiliser
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
                    if (isNaN(currentValue)) currentValue = 0; // Si non numérique, initialiser à 0

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
                         // S'assurer que la valeur n'est pas négative ou non numérique
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

            const sameAddressCheckbox = document.getElementById('same-address');
            const deliveryAddressFields = document.getElementById('delivery-address-fields');
            if (sameAddressCheckbox && deliveryAddressFields) {
                sameAddressCheckbox.addEventListener('change', () => {
                    deliveryAddressFields.style.display = sameAddressCheckbox.checked ? 'none' : 'block';
                });
                deliveryAddressFields.style.display = sameAddressCheckbox.checked ? 'none' : 'block';
            }

            multiStepForm.addEventListener('submit', (e) => {
                e.preventDefault();
                console.log("Données des articles à soumettre:", articlesData); 
                alert(`Formulaire soumis ! Total à payer : ${document.getElementById('payment-total').textContent}`);
            });

            updateFormSteps();
            updateProgressBar();
            calculateAndUpdatePrices(); 
        });
    </script>
</body>
</html>