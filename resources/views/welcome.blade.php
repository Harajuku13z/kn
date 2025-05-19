@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    <div class="container mt-5 hero-section">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="hero-title">Votre <span class="underline">linge propre</span>, sans quitter la <span class="underline">maison</span>.</h1>
                <p class="hero-description">Planifiez la collecte, on s'occupe du reste. Livraison en 48h, soignée et sécurisée.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('orders.create') }}" class="btn btn-primary-custom">Planifier une collecte</a>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary-custom">Accéder à mon espace</a>
                </div>
            </div>
            <div class="col-md-6">
                <img src="{{ asset('img/bloc2.png') }}" alt="Service illustration" class="img-fluid">
            </div>
        </div>
    </div>

    <div class="services-banner mt-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <span>Planifiez votre collecte</span>
                <span class="separator">✦</span>
                <span>Lavage écoresponsable</span>
                <span class="separator">✦</span>
                <span>Collecte & livraison en 48h chrono !</span>
                <span class="separator">✦</span>
                <span>Paiement en ligne</span>
            </div>
        </div>
    </div>

    <section class="how-it-works" id="howItWorks">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h2 class="how-it-works-title section-title">Comment ça marche ?</h2>
                    <p class="how-it-works-description section-description">Un service simple, rapide et sans effort en 3 étapes :</p>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="step-card step-primary">
                                <h3>1. Je planifie ma collecte en ligne</h3>
                                <p>Choisissez la date, l'heure et le lieu de collecte : domicile ou bureau.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="step-card step-secondary"> {{-- Styles affinés --}}
                                <h3>2. On récupère mon linge à l'adresse choisie</h3>
                                <p>Notre livreur passe à l'heure convenue. Plus besoin de vous déplacer.</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="step-card step-secondary"> {{-- Styles affinés --}}
                                <h3>3. Je reçois mon linge propre, repassé et plié</h3>
                                <p>En 48h, votre linge est prêt à l'emploi, livré avec soin.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <img src="{{ asset('img/frame.png') }}" alt="Frame illustration" class="how-it-works-image img-fluid">
                </div>
            </div>
        </div>
    </section>

    <section class="services-explanation py-5 bg-light" id="services">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Nos Services de Blanchisserie</h2>
                <p class="section-description">Découvrez nos différentes formules adaptées à vos besoins</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="service-card h-100">
                        <div class="service-header text-center p-4 bg-primary text-white">
                            <div class="service-icon mb-3">
                                <i class="fas fa-weight fa-3x"></i>
                            </div>
                            <h3 class="service-title text-white">Lavage au Kilo by KLINKLIN</h3>
                        </div>
                        <div class="service-body p-4">
                            <p class="service-description-card">
                                Notre formule la plus populaire et économique. Nous pesons votre linge et appliquons un tarif unique par kilogramme.
                                Idéal pour le linge quotidien : vêtements, draps, serviettes...
                            </p>
                            <div class="service-features">
                                <div class="feature-item">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>Tarif unique de {{ number_format($pricePerKg, 0, ',', ' ') }} FCFA/kg</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>Lavage, séchage et pliage inclus</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>Option repassage disponible</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>Économies avec les abonnements</span>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <a href="{{ route('orders.create') }}" class="btn btn-primary btn-service">Commander maintenant</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="service-card h-100">
                        <div class="service-header text-center p-4 bg-success text-white">
                            <div class="service-icon mb-3">
                                <i class="fas fa-tshirt fa-3x"></i>
                            </div>
                            <h3 class="service-title">Service Pressing avec nos Partenaires</h3>
                        </div>
                        <div class="service-body p-4">
                            <p class="service-description-card">
                                Pour vos vêtements délicats nécessitant un soin particulier, nous collaborons avec les meilleurs pressings de la ville.
                                Tarification à l'article selon le type de vêtement.
                            </p>
                            <div class="service-features">
                                <div class="feature-item">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>Nettoyage professionnel à sec</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>Traitement des taches spécifiques</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>Repassage haute qualité</span>
                                </div>
                                <div class="feature-item">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span>Collecte et livraison incluses</span>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <a href="{{ route('orders.create.pressing') }}" class="btn btn-success btn-service">Découvrir nos pressings</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="features-section" id="advantages">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="features-title section-title text-white">Pourquoi choisir KLIN KLIN ?</h2>
                <p class="features-description section-description">Offrez-vous une expérience de blanchisserie simple, rapide et professionnelle. Découvrez ce qui rend notre service unique.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-title">
                            <i class="fas fa-tshirt"></i>
                            <h3>Collecte de vêtement</h3>
                        </div>
                        <p>Ne perdez plus de temps à vous déplacer : nous venons chercher votre linge directement chez vous ou sur votre lieu de travail, sans frais supplémentaires.</p>
                        <a href="{{ route('services.lavage') }}" class="learn-more">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card highlight">
                        <div class="feature-title">
                            <i class="fas fa-washing-machine"></i>
                            <h3>Lavage professionnel</h3>
                        </div>
                        <p>Chaque vêtement est traité avec soin selon ses besoins : lavage à la main, nettoyage doux ou pressing. Nous utilisons des machines et produits de qualité professionnelle.</p>
                        <a href="{{ route('services.pressing') }}" class="learn-more">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-title">
                            <i class="fas fa-clock"></i>
                            <h3>Livraison rapide</h3>
                        </div>
                        <p>Votre linge propre et repassé vous est retourné en seulement deux jours. Fini les longues attentes !</p>
                        <a href="{{ route('services.repassage') }}" class="learn-more">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-title">
                            <i class="fas fa-lock"></i>
                            <h3>Paiement sécurisé</h3>
                        </div>
                        <p>Payez en toute tranquillité via carte bancaire, mobile money ou en espèces à la livraison. Toutes les transactions sont protégées.</p>
                        <a href="{{ route('aide.conditions-generales') }}" class="learn-more">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-title">
                            <i class="fas fa-sync"></i>
                            <h3>Option abonnement</h3>
                        </div>
                        <p>Choisissez la solution qui vous convient : optez pour un abonnement régulier ou payez à chaque commande, selon vos besoins.</p>
                        <a href="{{ route('abonnements') }}" class="learn-more">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-title">
                            <i class="fas fa-leaf"></i>
                            <h3>Produits respectueux</h3>
                        </div>
                        <p>Nous privilégions des lessives écologiques, biodégradables et sans produits agressifs, pour le respect de votre peau et de la planète.</p>
                        <a href="{{ route('aide.faq') }}" class="learn-more">En savoir plus <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pricing py-5 bg-light" id="pricing">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Nos Formules d'Abonnement</h2>
                <p class="section-description">Choisissez la formule qui correspond le mieux à vos besoins</p>
            </div>

            <div class="row g-4">
                @forelse($subscriptionTypes as $index => $type)
                    @php
                        $badgeClass = '';
                        $badgeText = '';
                        if ($index == 0) {
                            $badgeClass = 'bg-primary';
                            $badgeText = 'POPULAIRE';
                            $cardClass = 'price-card-standard';
                            $btnClass = 'btn-primary';
                        } elseif ($index == 1) {
                            $badgeClass = 'bg-success';
                            $badgeText = 'ÉCONOMIQUE';
                            $cardClass = 'price-card-family';
                            $btnClass = 'btn-success';
                        } else {
                            $badgeClass = 'bg-custom-yellow';
                            $badgeText = 'PREMIUM';
                            $cardClass = 'price-card-premium';
                            $btnClass = 'btn-warning';
                        }
                        // Use the current price from price_configurations instead of calculating it from subscription type
                        // $pricePerKg is already defined in WelcomeController
                    @endphp
                    <div class="col-md-4">
                        <div class="price-card {{ $cardClass }} h-100 d-flex flex-column">
                            <div class="price-card-header">
                                <span class="badge {{ $badgeClass }} mb-2">{{ $badgeText }}</span>
                                <h3>{{ $type->name }}</h3>
                                <div class="price">{{ number_format($type->price, 0, ',', ' ') }} FCFA</div>
                                <p class="text-muted">{{ $type->quota }} kg de linge</p>
                            </div>
                            <div class="price-card-body">
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
                                </ul>
                            </div>
                            <div class="price-card-footer mt-auto">
                                @auth
                                    <a href="{{ route('subscriptions.create') }}" class="btn {{ $btnClass }} w-100 btn-subscribe">Souscrire</a>
                                @else
                                    <a href="{{ route('register') }}" class="btn {{ $btnClass }} w-100 btn-subscribe">S'inscrire pour souscrire</a>
                                @endauth
                            </div>
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

  <section class="faq-section py-5">
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
                            <h2 class="accordion-header" id="faqHeading1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
                                    <i class="fas fa-calendar-day me-3"></i>
                                    Quelle est la durée de validité d'un abonnement ?
                                </button>
                            </h2>
                            <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Tous nos abonnements ont une validité d'un mois à compter de la date de souscription. Ils sont automatiquement renouvelés chaque mois, sauf si vous demandez leur annulation.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 rounded-3 overflow-hidden">
                            <h2 class="accordion-header" id="faqHeading2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                                    <i class="fas fa-weight me-3"></i>
                                    Que se passe-t-il si je dépasse le poids inclus dans mon abonnement ?
                                </button>
                            </h2>
                            <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Si vous dépassez le poids inclus dans votre abonnement, le surplus sera facturé au tarif standard de {{ number_format($pricePerKg, 0, ',', ' ') }} FCFA/kg. Vous serez informé avant le traitement si votre linge dépasse le poids de votre forfait.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 rounded-3 overflow-hidden">
                            <h2 class="accordion-header" id="faqHeading3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                                    <i class="fas fa-exchange-alt me-3"></i>
                                    Puis-je changer de formule d'abonnement ?
                                </button>
                            </h2>
                            <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#faqAccordion">
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
@endsection

@section('styles')
<style>
    /* Styles généraux pour le corps si nécessaire */
    body {
        font-family: 'Montserrat', sans-serif; 
    }

    /* Hero Section */
    .hero-title {
        font-size: clamp(38px, 6vw, 68px); 
        font-weight: bold;
        font-family: 'Montserrat', sans-serif;
        line-height: 1.25;
        color: #2a0e44;
        margin-bottom: 25px;
    }

    .underline {
        position: relative;
        display: inline-block;
        z-index: 1;
    }

    .underline::after {
        content: '';
        position: absolute;
        left: -5px;
        right: -5px;
        bottom: 0.1em; 
        height: 0.2em;  
        background-color: #8dffc8;
        border-radius: 20px;
        z-index: -1;
        opacity: 0.6; 
    }

    .text-mint {
        color: #8dffc8;
    }

    .hero-description {
        color: #5a6268; 
        font-weight: 500; 
        font-size: clamp(16px, 2.5vw, 19px); 
        margin: 25px 0 35px 0; 
        line-height: 1.65; 
    }

    .btn-primary-custom, .btn-secondary-custom {
        padding: 14px 30px; 
        font-weight: 600;
        border-radius: 8px; 
        transition: all 0.25s ease-in-out; /* Assurer la transition */
        text-transform: uppercase; 
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05); 
    }

    .btn-primary-custom {
        background-color: #461871; 
        border-color: #461871;
        color: white;
    }

    .btn-primary-custom:hover, .btn-primary-custom:focus {
        background-color: #3a145c; 
        border-color: #3a145c;
        color: white;
        transform: translateY(-3px); 
        box-shadow: 0 7px 15px rgba(70, 24, 113, 0.25); 
    }

    .btn-secondary-custom {
        background-color: transparent;
        border: 2px solid #461871; 
        color: #461871; 
    }

    .btn-secondary-custom:hover, .btn-secondary-custom:focus {
        background-color: #461871; 
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 7px 15px rgba(70, 24, 113, 0.2);
    }

    /* Services Banner */
    .services-banner {
        width: 100%;
        background-color: #6abf96; 
        padding: 25px 0; 
        margin: 60px 0; 
    }

    .services-banner span {
        color: white; 
        font-family: 'Montserrat', sans-serif;
        font-size: clamp(15px, 2vw, 17px); 
        font-weight: 500; 
    }

    .services-banner .separator {
        color: #fae100; 
        margin: 0 clamp(12px, 2.5vw, 22px); 
        font-weight: bold; 
    }

    /* How it works Section */
    .how-it-works {
        margin: 80px 0;
        padding-top: 40px; 
    }

    .how-it-works-image { 
        max-width: 100%;
        height: auto;
    }

    .step-card {
        border-radius: 15px;
        padding: 30px 25px; 
        margin-bottom: 20px;
        height: 100%;
        transition: all 0.3s ease-in-out; 
        /* Ombre par défaut gérée par .step-primary et .step-secondary spécifiques */
    }

    .step-card h3 {
        font-size: 1.2rem; 
        font-weight: 600;
        margin-bottom: 12px;
        text-align: left;
    }

    .step-card p {
        font-size: 0.9rem; 
        margin-bottom: 0;
        line-height: 1.65;
        text-align: left;
        color: #555;
    }

    .step-primary {
        background-color: #461871;
        color: white;
        box-shadow: 0 6px 18px rgba(70,24,113,0.15); /* Ombre par défaut pour la carte primaire */
    }
    .step-primary p { 
        color: rgba(255,255,255,0.85);
    }
    .step-primary:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(70, 24, 113, 0.25);
    }

    .step-secondary {
        background-color: white;
        border: 1px solid #e9ecef; /* Bordure par défaut légèrement plus claire */
        color: #14081c;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04); /* NOUVEAU: Ombre par défaut subtile */
    }
    .step-secondary:hover {
        border-color: #8dffc8; /* NOUVEAU: Bordure menthe au survol */
        transform: translateY(-6px); 
        box-shadow: 0 10px 20px rgba(70, 24, 113, 0.12); /* Ombre au survol légèrement ajustée */
    }
    
    /* Titres et descriptions de section globaux */
    .section-title {
        font-size: clamp(26px, 5vw, 34px); 
        font-weight: 700;
        color: #461871; 
        margin-bottom: 15px; 
        font-family: 'Montserrat', sans-serif;
    }

    .section-description {
        font-size: clamp(16px, 2.5vw, 18px); 
        color: #6c757d; 
        margin-bottom: 45px; 
        font-family: 'Montserrat', sans-serif;
        line-height: 1.65;
        max-width: 650px; 
        margin-left: auto;
        margin-right: auto;
    }
    .how-it-works-title, .features-title {
        text-align: center;
    }
    .how-it-works-description, .features-description {
        text-align: center;
    }


    /* Services Explanation Section */
    .services-explanation {
        padding-top: 60px; 
        padding-bottom: 70px; 
    }
    .services-explanation .section-title { 
        position: relative;
        padding-bottom: 20px; 
        margin-bottom: 25px; 
    }

    .services-explanation .section-title::after {
        content: '';
        position: absolute;
        left: 50%;
        bottom: 0;
        transform: translateX(-50%);
        width: 70px; 
        height: 3px;
        background-color: #8dffc8; 
        border-radius: 3px;
    }

    .service-card {
        border-radius: 15px;
        overflow: hidden; 
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #eaeaea; 
        background-color: #fff; 
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 18px 35px rgba(70, 24, 113, 0.12) !important; 
    }
    
    .service-header {
        padding: 30px 25px; 
        position: relative; 
        overflow: hidden;
    }
    
    .service-header::before { 
        content: '';
        position: absolute;
        top: -20px;
        right: -20px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
        z-index: 0;
    }
    
    .service-header::after { 
        content: '';
        position: absolute;
        bottom: -30px;
        left: -30px;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255,255,255,0.05);
        z-index: 0;
    }
    
    .service-icon {
        position: relative; 
        z-index: 1;
    }
    
    .service-icon i {
        transition: transform 0.3s ease;
    }
    
    .service-card:hover .service-icon i {
        transform: scale(1.15); 
    }
    
    .service-title {
        font-size: 1.5rem; 
        position: relative; 
        z-index: 1;
    }
    
    .service-description-card { 
        font-size: 0.95rem;
        color: #555;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }
    
    .service-features {
        margin-bottom: 20px;
    }
    
    .feature-item {
        padding: 0.4rem 0;
        font-size: 0.95rem;
    }
    
    .feature-item i {
        font-size: 1.1rem;
    }

    .btn-service {
        padding: 12px 28px; 
        font-weight: 600;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
    .btn-service:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }


    /* Features Section */
    .features-section {
        padding: 70px 0; 
    }
        
    .feature-card {
        background-color: #fff;
        border-radius: 12px;
        padding: 30px 25px;
        box-shadow: 0 7px 22px rgba(0,0,0,0.07); 
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid transparent; 
    }

    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 28px rgba(70, 24, 113, 0.1); 
        border-color: #8dffc8; 
    }

    .feature-card .feature-title {
        display: flex;
        align-items: center;
        margin-bottom: 18px;
        color: #461871;
    }

    .feature-card .feature-title i {
        font-size: 24px; 
        margin-right: 15px; 
        width: 30px;
        text-align: center;
        transition: color 0.3s ease, transform 0.3s ease;
    }
     .feature-card:hover .feature-title i {
        transform: scale(1.1);
    }

    .feature-card .feature-title h3 {
        font-size: 1.25rem; 
        font-weight: 600;
        margin-bottom: 0;
    }

    .feature-card p {
        font-size: 0.9rem; 
        color: #555; 
        line-height: 1.65; 
        flex-grow: 1;
        margin-bottom: 20px;
    }

    .feature-card .learn-more {
        color: #461871;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex; 
        align-items: center;
        font-size: 0.9rem;
        transition: color 0.3s ease, letter-spacing 0.3s ease; 
    }

    .feature-card .learn-more i {
        margin-left: 8px; 
        transition: transform 0.3s ease;
    }

    .feature-card .learn-more:hover {
        color: #3a145c;
        letter-spacing: 0.3px; 
    }

    .feature-card .learn-more:hover i {
        transform: translateX(3px); 
    }

    .feature-card.highlight {
        background-color: #f7f4fa; 
        border-left: 4px solid #461871; 
    }
    .feature-card.highlight .feature-title i {
        color: #6abf96; 
    }


    /* Section Abonnements (Pricing) */
    .pricing {
        padding: 70px 0; 
    }
    .price-card {
        padding: 0; 
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #f0f0f0; 
        background-color: #fff; 
        overflow: hidden;
    }
    
    .price-card:hover {
        transform: translateY(-12px); 
        box-shadow: 0 20px 40px rgba(70, 24, 113, 0.15); 
    }
    
    .price-card-header {
        padding: 30px 25px 20px;
        text-align: center;
        position: relative;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .price-card-body {
        padding: 20px 25px;
    }
    
    .price-card-footer {
        padding: 20px 25px 30px;
        text-align: center;
    }
    
    .price-card-standard {
        border-top: 5px solid #461871;
    }
    
    .price-card-family { 
        border-top: 5px solid #6abf96;
    }
    
    .price-card-premium { 
        border-top: 5px solid #fae100;
    }
    
    .price-card .badge {
        font-size: 0.75rem;
        padding: 7px 14px;
        font-weight: bold;
        border-radius: 20px; 
        position: absolute;
        top: 15px;
        right: 15px;
    }
    .price-card h3 {
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
        font-size: 1.4rem; 
        margin-top: 12px;
        color: #2a0e44;
    }
    .price-card .price { 
        font-size: 2.6rem; 
        font-weight: 700;
        color: #461871; 
        margin: 8px 0 12px 0;
    }
    .price-card .text-muted {
        font-size: 0.85rem;
    }
    .price-features {
        list-style: none;
        padding-left: 0;
        margin: 25px 0;
    }
    .price-features li {
        padding: 9px 0; 
        font-size: 0.9rem;
        color: #333;
    }
    .price-features li i {
        margin-right: 10px; 
    }
    .btn-subscribe {
        padding: 12px 20px;
        font-weight: 600;
        border-radius: 8px;
        text-transform: uppercase;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
    .btn-subscribe:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    /* FAQ Section */
    .faq-section { 
        padding: 70px 0;
    }
    .accordion-item {
        border: 1px solid #e9ecef; 
        background-color: #fff; 
        transition: box-shadow 0.3s ease, border-color 0.3s ease;
    }
    .accordion-item:not(:first-of-type) {
        border-top: 1px solid #e9ecef; 
    }
    .accordion-item:not(:last-of-type) {
         margin-bottom: 1rem !important; 
    }
    .accordion-item:hover {
        box-shadow: 0 6px 18px rgba(0,0,0,0.06); 
        border-color: #ced4da;
    }

    .accordion-button {
        font-weight: 600; 
        color: #2a0e44; 
        font-size: 1.05rem; 
        padding: 1.1rem 1.3rem; 
        border-radius: inherit; 
    }
    .accordion-button:focus {
        box-shadow: 0 0 0 0.2rem rgba(70, 24, 113, 0.25); 
    }

    .accordion-button:not(.collapsed) {
        background-color: #461871;
        color: white;
        box-shadow: none; 
    }
    .accordion-button:not(.collapsed)::after { 
         background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
         transform: rotate(-180deg); 
    }
    .accordion-button i { 
        color: #461871;
        transition: color 0.3s ease, transform 0.3s ease; 
        font-size: 1.1rem; 
        margin-right: 12px; 
    }
    .accordion-button:not(.collapsed) i {
        color: white;
    }
    .accordion-body {
        padding: 1.2rem 1.3rem; 
    }
    .accordion-body p {
        font-size: 0.95rem; 
        line-height: 1.7;
        color: #555;
        margin-bottom: 0; 
    }
    .accordion-body p:last-child {
        margin-bottom: 0;
    }
    
    /* Styles responsifs */
    @media (max-width: 991.98px) { 
        .hero-title {
            font-size: clamp(32px, 7vw, 50px); 
        }
        .hero-description {
            font-size: clamp(15px, 3vw, 17px);
        }
    }

    @media (max-width: 767.98px) {
        .hero-section .row { 
            flex-direction: column-reverse; 
        }
        .hero-section .col-md-6 { 
            width: 100%;
            text-align: center; 
        }
        .hero-section .col-md-6:last-child { 
            margin-top: 0; 
            margin-bottom: 30px; 
        }
        .hero-section .d-flex.gap-3 { 
            flex-direction: column;
            align-items: center; 
        }
        .hero-section .btn-primary-custom, .hero-section .btn-secondary-custom {
            width: 80%; 
            max-width: 300px; 
            margin-bottom: 12px;
        }
        .hero-section .btn-secondary-custom {
            margin-bottom: 0;
        }

        .services-banner .container > div { 
            flex-direction: column;
            gap: 8px;
            text-align: center;
        }
        .services-banner .separator {
            display: none; 
        }
        .services-banner span {
            padding: 4px 0;
        }
        
        .how-it-works .col-md-7, .how-it-works .col-md-5 { 
             width: 100%;
        }
        .how-it-works .col-md-5 { 
            margin-top: 30px;
            padding-top: 10px;
        }
        .step-card { 
             margin-left: 0;
             margin-right: 0;
        }
        .section-title, .section-description, 
        .how-it-works-title, .how-it-works-description,
        .features-title, .features-description {
            padding-left: 15px; 
            padding-right: 15px;
        }
    }
    
    /* Harmonisation des couleurs Bootstrap ET NOUVEAUX EFFETS DE SURVOL POUR BOUTONS */
    .btn-primary, .btn-success, .btn-warning,
    .btn-primary-custom, .btn-secondary-custom {
        transition: all 0.25s ease-in-out; /* Transition standard pour tous les boutons principaux */
    }

    .bg-primary, .btn-primary {
        background-color: #461871 !important;
        border-color: #461871 !important;
        color: white !important; 
    }
    .btn-primary:hover, .btn-primary:focus {
        background-color: #3a145c !important;
        border-color: #3a145c !important;
        color: white !important;
        transform: translateY(-2px); /* Soulèvement */
        box-shadow: 0 6px 12px rgba(0,0,0,0.1); /* Ombre */
    }
    
    .btn-outline-primary {
        color: #461871 !important;
        border-color: #461871 !important;
        transition: all 0.25s ease-in-out;
    }
    
    .btn-outline-primary:hover, .btn-outline-primary:focus {
        background-color: #461871 !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    }
    
    .service-header.bg-primary {
        background-color: #461871 !important;
    }
    
    .bg-success, .btn-success {
        background-color: #6abf96 !important; 
        border-color: #6abf96 !important;
        color: #fff !important;
    }
    .btn-success:hover, .btn-success:focus {
        background-color: #58a885 !important;
        border-color: #58a885 !important;
        color: #fff !important;
        transform: translateY(-2px); /* Soulèvement */
        box-shadow: 0 6px 12px rgba(0,0,0,0.1); /* Ombre */
    }
    
    .bg-warning, .btn-warning { 
        background-color: #fae100 !important; 
        border-color: #fae100 !important;
        color: #2a0e44 !important; 
    }
    .btn-warning:hover, .btn-warning:focus {
        background-color: #e6ce00 !important; 
        border-color: #e6ce00 !important;
        color: #2a0e44 !important;
        transform: translateY(-2px); /* Soulèvement */
        box-shadow: 0 6px 12px rgba(0,0,0,0.1); /* Ombre */
    }
    
    .text-success {
        color: #6abf96 !important; 
    }
    
    .accordion-button:not(.collapsed) {
        background-color: #461871; 
        color: white;
    }

    /* Styles pour les badges d'abonnement */
    .price-card .badge.bg-primary { 
        color: white !important;
        background-color: #461871 !important;
    }
    .price-card .badge.bg-success { 
        color: white !important;
        background-color: #6abf96 !important;
    }
    .price-card .badge.bg-custom-yellow { 
        color: #2a0e44 !important; 
        background-color: #fae100 !important;
    }

</style>
@endsection