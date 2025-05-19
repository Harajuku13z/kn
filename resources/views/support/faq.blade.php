@extends('layouts.dashboard')

@section('title', 'FAQ - KLINKLIN')

@push('styles')
<style>
    .faq-header {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .accordion-button:not(.collapsed) {
        background-color: #f8f5fc;
        color: #4A148C;
        box-shadow: none;
    }
    
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(74, 20, 140, 0.25);
    }
    
    .accordion-button:not(.collapsed)::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%234A148C'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }
    
    .faq-category {
        margin-bottom: 2rem;
    }
    
    .faq-category-title {
        color: #4A148C;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .search-faq {
        max-width: 600px;
        margin: 0 auto 2rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('support.index') }}">Aide & Support</a></li>
                    <li class="breadcrumb-item active" aria-current="page">FAQ</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="faq-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-2">Foire Aux Questions</h1>
                <p class="text-muted mb-0">Trouvez rapidement des réponses à vos questions les plus fréquentes.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('support.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i> Poser une question
                </a>
            </div>
        </div>
    </div>
    
    <div class="search-faq">
        <div class="input-group">
            <span class="input-group-text bg-white">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" class="form-control" id="searchFaq" placeholder="Rechercher dans la FAQ...">
        </div>
    </div>
    
    <div class="faq-category" id="general">
        <h2 class="h4 faq-category-title">Questions générales</h2>
        <div class="accordion" id="accordionGeneral">
            <div class="accordion-item">
                <h3 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Qu'est-ce que KLINKLIN ?
                    </button>
                </h3>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionGeneral">
                    <div class="accordion-body">
                        KLINKLIN est un service de blanchisserie et de pressing à la demande. Nous collectons vos vêtements, les lavons et les livrons à votre porte, vous offrant ainsi un service pratique et de qualité pour tous vos besoins de nettoyage de vêtements.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Comment fonctionne le service ?
                    </button>
                </h3>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionGeneral">
                    <div class="accordion-body">
                        <p>Notre service fonctionne en 4 étapes simples :</p>
                        <ol>
                            <li>Vous créez une commande en ligne ou via notre application</li>
                            <li>Nous collectons vos vêtements à l'adresse et à l'heure que vous avez choisies</li>
                            <li>Nos experts nettoient vos vêtements selon vos préférences</li>
                            <li>Nous livrons vos vêtements propres et bien pliés à votre porte</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Quelles sont les zones desservies ?
                    </button>
                </h3>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionGeneral">
                    <div class="accordion-body">
                        Actuellement, nous desservons les principales zones urbaines d'Abidjan. Vous pouvez vérifier si votre adresse est dans notre zone de service en entrant votre code postal sur notre page d'accueil ou en contactant notre service client.
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="faq-category" id="orders">
        <h2 class="h4 faq-category-title">Commandes</h2>
        <div class="accordion" id="accordionOrders">
            <div class="accordion-item">
                <h3 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Comment passer une commande ?
                    </button>
                </h3>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionOrders">
                    <div class="accordion-body">
                        <p>Pour passer une commande, suivez ces étapes simples :</p>
                        <ol>
                            <li>Connectez-vous à votre compte KLINKLIN</li>
                            <li>Cliquez sur "Nouvelle commande" dans votre tableau de bord</li>
                            <li>Choisissez entre le service de blanchisserie au kilo ou le service de pressing</li>
                            <li>Sélectionnez la date et l'heure de collecte et de livraison</li>
                            <li>Ajoutez vos préférences spécifiques si nécessaire</li>
                            <li>Confirmez votre commande</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        Comment suivre ma commande ?
                    </button>
                </h3>
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionOrders">
                    <div class="accordion-body">
                        Vous pouvez suivre votre commande en temps réel dans la section "Mes commandes" de votre tableau de bord. Vous recevrez également des notifications par email et SMS à chaque étape du processus (collecte, lavage, livraison).
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="headingSix">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                        Puis-je annuler ma commande ?
                    </button>
                </h3>
                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionOrders">
                    <div class="accordion-body">
                        Oui, vous pouvez annuler votre commande sans frais jusqu'à 2 heures avant l'heure de collecte prévue. Pour annuler, accédez à la section "Mes commandes" de votre tableau de bord et cliquez sur le bouton "Annuler" pour la commande concernée.
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="faq-category" id="payment">
        <h2 class="h4 faq-category-title">Paiement et tarifs</h2>
        <div class="accordion" id="accordionPayment">
            <div class="accordion-item">
                <h3 class="accordion-header" id="headingSeven">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                        Quels sont les modes de paiement acceptés ?
                    </button>
                </h3>
                <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionPayment">
                    <div class="accordion-body">
                        Nous acceptons les paiements en espèces à la livraison, ainsi que les paiements par Mobile Money. Les abonnements peuvent être payés par carte bancaire ou Mobile Money.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="headingEight">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                        Comment fonctionnent les abonnements ?
                    </button>
                </h3>
                <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#accordionPayment">
                    <div class="accordion-body">
                        <p>Nos abonnements vous permettent de prépayer un certain nombre de kilos de linge à un tarif préférentiel. Le fonctionnement est simple :</p>
                        <ul>
                            <li>Choisissez l'abonnement qui correspond à vos besoins</li>
                            <li>Effectuez le paiement</li>
                            <li>Utilisez votre quota de kilos quand vous le souhaitez pendant la période de validité</li>
                            <li>Bénéficiez de tarifs réduits et d'avantages exclusifs</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h3 class="accordion-header" id="headingNine">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                        Y a-t-il des frais de livraison ?
                    </button>
                </h3>
                <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine" data-bs-parent="#accordionPayment">
                    <div class="accordion-body">
                        Oui, des frais de livraison s'appliquent en fonction de votre zone géographique. Ces frais sont clairement indiqués lors du processus de commande. Les abonnés bénéficient de livraisons gratuites ou à tarif réduit selon leur formule.
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-5">
        <p>Vous n'avez pas trouvé la réponse à votre question ?</p>
        <a href="{{ route('support.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Créer un ticket de support
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Recherche dans la FAQ
        const searchInput = document.getElementById('searchFaq');
        const accordionItems = document.querySelectorAll('.accordion-item');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            accordionItems.forEach(item => {
                const question = item.querySelector('.accordion-button').textContent.toLowerCase();
                const answer = item.querySelector('.accordion-body').textContent.toLowerCase();
                
                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Afficher/masquer les catégories vides
            document.querySelectorAll('.faq-category').forEach(category => {
                const visibleItems = category.querySelectorAll('.accordion-item[style=""]').length;
                if (visibleItems === 0) {
                    category.style.display = 'none';
                } else {
                    category.style.display = '';
                }
            });
        });
    });
</script>
@endpush 