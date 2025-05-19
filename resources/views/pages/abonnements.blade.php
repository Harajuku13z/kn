@extends('layouts.app')

@section('title', 'Nos Abonnements')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1>Nos Formules d'Abonnement</h1>
            <p>Choisissez la formule qui correspond le mieux à vos besoins</p>
        </div>
    </div>

    <!-- Subscription Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title">Simplifiez votre quotidien</h2>
                    <p class="section-description">Avec nos abonnements mensuels, profitez d'un service de blanchisserie régulier à prix avantageux. Fini la corvée de linge !</p>
                </div>
            </div>

            <div class="row g-4 pricing-cards">
                @forelse($subscriptionTypes as $index => $type)
                    @php
                        $badgeClass = '';
                        $badgeText = '';
                        if ($index == 0) {
                            $badgeClass = 'bg-primary';
                            $badgeText = 'POPULAIRE';
                            $cardClass = 'price-card-starter';
                            $btnClass = 'btn-primary';
                        } elseif ($index == 1) {
                            $badgeClass = 'bg-success';
                            $badgeText = 'ÉCONOMIQUE';
                            $cardClass = 'price-card-family';
                            $btnClass = 'btn-success';
                        } else {
                            $badgeClass = 'bg-warning';
                            $badgeText = 'PREMIUM';
                            $cardClass = 'price-card-premium';
                            $btnClass = 'btn-warning';
                        }
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="price-card {{ $cardClass }} h-100 shadow-sm">
                            <div class="price-card-header text-center mb-4">
                                <span class="badge {{ $badgeClass }} mb-3">{{ $badgeText }}</span>
                                <h3 class="mb-3">{{ $type->name }}</h3>
                                <div class="price mb-2">{{ number_format($type->price, 0, ',', ' ') }} FCFA</div>
                                <p class="text-muted">{{ $type->quota }} kg de linge</p>
                            </div>
                            <ul class="price-features mb-4">
                                <li><i class="fas fa-check text-success me-2"></i>Lavage et repassage inclus</li>
                                @if($index == 0)
                                <li><i class="fas fa-check text-success me-2"></i>Collecte et livraison standard</li>
                                <li><i class="fas fa-check text-success me-2"></i>Service en 48h</li>
                                <li><i class="fas fa-check text-success me-2"></i>Traitement standard</li>
                                @elseif($index == 1)
                                <li><i class="fas fa-check text-success me-2"></i>Collecte et livraison prioritaires</li>
                                <li><i class="fas fa-check text-success me-2"></i>Service en 24h</li>
                                <li><i class="fas fa-check text-success me-2"></i>Traitement premium</li>
                                @else
                                <li><i class="fas fa-check text-success me-2"></i>Collecte et livraison express</li>
                                <li><i class="fas fa-check text-success me-2"></i>Service en 24h</li>
                                <li><i class="fas fa-check text-success me-2"></i>Traitement VIP</li>
                                @endif
                                <li><i class="fas fa-check text-success me-2"></i>Validité : {{ $type->duration }} jours</li>
                                <li><i class="fas fa-check text-success me-2"></i>Idéal pour {{ $index == 0 ? '1 personne' : ($index == 1 ? '2-3 personnes' : 'les familles nombreuses') }}</li>
                            </ul>
                            @auth
                                <a href="{{ route('subscriptions.create') }}" class="btn {{ $btnClass }} w-100">Souscrire</a>
                            @else
                                <a href="{{ route('register') }}" class="btn {{ $btnClass }} w-100">S'inscrire pour souscrire</a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            Aucune formule d'abonnement n'est disponible pour le moment. Veuillez revenir plus tard.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title">Les avantages de nos abonnements</h2>
                    <p class="section-description">Pourquoi choisir un abonnement plutôt qu'un service à la demande ?</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <i class="fas fa-piggy-bank fa-3x text-primary"></i>
                            </div>
                            <h3 class="card-title h4 text-center">Économies</h3>
                            <p class="card-text">Bénéficiez de tarifs préférentiels par rapport au service à la demande. Plus votre abonnement est important, plus le prix au kilogramme est avantageux.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <i class="fas fa-clock fa-3x text-primary"></i>
                            </div>
                            <h3 class="card-title h4 text-center">Praticité</h3>
                            <p class="card-text">Oubliez la corvée hebdomadaire de linge. Votre abonnement vous permet de planifier à l'avance vos collectes et livraisons selon votre rythme de vie.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <i class="fas fa-star fa-3x text-primary"></i>
                            </div>
                            <h3 class="card-title h4 text-center">Priorité</h3>
                            <p class="card-text">Les clients abonnés bénéficient d'un service prioritaire pour la collecte, le traitement et la livraison de leur linge, même en période de forte affluence.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title">Comment fonctionne l'abonnement ?</h2>
                    <p class="section-description">Un processus simple en 4 étapes</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-3">
                    <div class="process-step text-center">
                        <div class="process-icon mb-3">
                            <i class="fas fa-user-plus fa-3x text-primary"></i>
                        </div>
                        <h3 class="h5">1. Inscription</h3>
                        <p>Créez votre compte sur notre plateforme et choisissez la formule d'abonnement qui correspond à vos besoins.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="process-step text-center">
                        <div class="process-icon mb-3">
                            <i class="fas fa-credit-card fa-3x text-primary"></i>
                        </div>
                        <h3 class="h5">2. Paiement</h3>
                        <p>Réglez votre abonnement mensuel en toute sécurité via nos moyens de paiement (carte bancaire, mobile money).</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="process-step text-center">
                        <div class="process-icon mb-3">
                            <i class="fas fa-calendar-alt fa-3x text-primary"></i>
                        </div>
                        <h3 class="h5">3. Planification</h3>
                        <p>Planifiez vos collectes selon vos besoins pendant toute la durée de validité de votre abonnement.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="process-step text-center">
                        <div class="process-icon mb-3">
                            <i class="fas fa-sync fa-3x text-primary"></i>
                        </div>
                        <h3 class="h5">4. Renouvellement</h3>
                        <p>Votre abonnement se renouvelle automatiquement chaque mois, mais vous pouvez le modifier ou l'annuler à tout moment.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title">Questions fréquentes</h2>
                    <p class="section-description">Tout ce que vous devez savoir sur nos abonnements</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item mb-3 rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    <i class="fas fa-calendar-day me-3"></i>
                                    Quelle est la durée de validité d'un abonnement ?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Tous nos abonnements ont une validité d'un mois à compter de la date de souscription. Ils sont automatiquement renouvelés chaque mois, sauf si vous demandez leur annulation.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    <i class="fas fa-weight me-3"></i>
                                    Que se passe-t-il si je dépasse le poids inclus dans mon abonnement ?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Si vous dépassez le poids inclus dans votre abonnement, le surplus sera facturé au tarif standard de {{ number_format($pricePerKg, 0, ',', ' ') }} FCFA/kg. Vous serez informé avant le traitement si votre linge dépasse le poids de votre forfait.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    <i class="fas fa-exchange-alt me-3"></i>
                                    Puis-je changer de formule d'abonnement ?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Oui, vous pouvez changer de formule à tout moment depuis votre espace client. Le changement prendra effet au prochain renouvellement de votre abonnement.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="cta-title mb-4">Prêt à simplifier votre quotidien ?</h2>
                    <p class="cta-description mb-4">Souscrivez dès maintenant à l'un de nos abonnements et profitez d'un service de blanchisserie régulier et économique.</p>
                    <div class="d-flex justify-content-center flex-wrap gap-3">
                        @auth
                            <a href="{{ route('subscriptions.create') }}" class="btn-mint">Souscrire maintenant</a>
                        @else
                            <a href="{{ route('register') }}" class="btn-mint">Créer un compte</a>
                        @endauth
                        <a href="{{ route('simulateur') }}" class="btn-light-mint">Simuler un prix</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    /* Styles améliorés pour les cartes d'abonnement */
    .price-card {
        padding: 2rem;
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }
    
    .price-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15) !important;
    }
    
    .price-card-header {
        position: relative;
        z-index: 2;
    }
    
    .price-card-starter {
        background: linear-gradient(145deg, #ffffff, #f8f9ff);
        border-top: 5px solid #0d6efd;
    }
    
    .price-card-family {
        background: linear-gradient(145deg, #ffffff, #f0fff7);
        border-top: 5px solid #198754;
    }
    
    .price-card-premium {
        background: linear-gradient(145deg, #ffffff, #fffdf0);
        border-top: 5px solid #ffc107;
    }
    
    .price-card .badge {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 1px;
    }
    
    .price-card .price {
        font-size: 2.5rem;
        font-weight: 700;
        color: #14081c;
    }
    
    .price-features {
        list-style: none;
        padding-left: 0;
        position: relative;
        z-index: 2;
    }
    
    .price-features li {
        padding: 0.75rem 0;
        border-bottom: 1px dashed rgba(0,0,0,0.1);
    }
    
    .price-features li:last-child {
        border-bottom: none;
    }
    
    .price-card .btn {
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
        z-index: 2;
        transition: all 0.3s ease;
    }
    
    .price-card .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    /* Effet décoratif */
    .price-card::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
        z-index: 1;
    }
    
    .price-card::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -80px;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background: rgba(0,0,0,0.03);
        z-index: 1;
    }
</style>
@endpush 