@extends('layouts.app')

@section('title', 'KLIN KLIN | Service de Blanchisserie Éco-Responsable et Pratique')

@section('content')
    {{-- 1. Hero Section Améliorée --}}
    <div class="page-header py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">KLIN KLIN : Votre Linge Impeccable, <br class="d-none d-md-block"> l'Esprit Serein et la Planète Respectée.</h1>
            <p class="lead mb-4">Libérez-vous de la corvée du linge grâce à notre service de blanchisserie à la demande, pratique, professionnel et respectueux de l'environnement.</p>
            <a href="{{ route('register') }}" class="btn btn-custom-primary btn-lg shadow pulse-hover">Découvrez nos services et tarifs</a>
        </div>
    </div>

    {{-- 2. Comment ça marche ? --}}
    <section class="py-5 section-how-it-works">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="section-title mb-3">Simplifiez votre quotidien en 4 étapes</h2>
                    <p class="section-description">Découvrez la facilité KLIN KLIN.</p>
                </div>
            </div>
            <div class="row g-4 text-center">
                <div class="col-md-6 col-lg-3">
                    <div class="step-card p-4 h-100">
                        <div class="step-icon mx-auto mb-3"><i class="fas fa-calendar-alt fa-2x"></i></div>
                        <h3 class="h5 step-title">1. Planifiez</h3>
                        <p>Choisissez le créneau de collecte et livraison qui vous arrange, directement en ligne.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step-card p-4 h-100">
                        <div class="step-icon mx-auto mb-3"><i class="fas fa-shopping-bag fa-2x"></i></div>
                        <h3 class="h5 step-title">2. Collecte</h3>
                        <p>Nous venons chercher votre linge à votre domicile ou sur votre lieu de travail.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step-card p-4 h-100">
                        <div class="step-icon mx-auto mb-3"><i class="fas fa-hands-wash fa-2x"></i></div>
                        <h3 class="h5 step-title">3. Soin Expert</h3>
                        <p>Votre linge est traité avec le plus grand soin par nos professionnels, selon vos préférences.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="step-card p-4 h-100">
                        <div class="step-icon mx-auto mb-3"><i class="fas fa-truck fa-2x"></i></div>
                        <h3 class="h5 step-title">4. Livraison</h3>
                        <p>Recevez votre linge propre, frais et parfaitement plié ou sur cintre, prêt à être rangé.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Notre Engagement Écologique --}}
    <section class="py-5 bg-klin-violet-attenue section-eco">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="{{ asset('img/services.jpeg') }}" alt="Blanchisserie écologique KLIN KLIN" class="img-fluid rounded shadow-lg">
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <h2 class="section-title text-start mb-4">Un Soin Expert, Doux pour Vos Vêtements <br class="d-none d-md-block"> et pour la Planète.</h2>
                    <p class="lead mb-4">Chez KLIN KLIN, nous croyons qu'un service impeccable peut aussi être respectueux de l'environnement.</p>
                    <ul class="list-unstyled eco-list">
                        <li><i class="fas fa-leaf me-2"></i> Utilisation de détergents écologiques et biodégradables.</li>
                        <li><i class="fas fa-tint me-2"></i> Optimisation de la consommation d'eau et d'énergie.</li>
                        <li><i class="fas fa-recycle me-2"></i> Processus de nettoyage à sec moins polluants.</li>
                        <li><i class="fas fa-route me-2"></i> Optimisation des tournées de livraison pour réduire notre empreinte carbone.</li>
                        <li><i class="fas fa-box-open me-2"></i> Utilisation d'emballages réutilisables ou recyclés (sur demande).</li>
                    </ul>
                    <a href="#contact" class="btn btn-outline-custom-primary mt-3">En savoir plus sur nos pratiques</a>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. Nos Services Experts -- IMAGES CARRÉES --}}
    <section class="py-5 section-services">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="section-title mb-3">Des Solutions pour Chaque Type de Linge</h2>
                    <p class="section-description">
                        Du quotidien au plus délicat, KLIN KLIN prend soin de tout.
                    </p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card service-card h-100 border-0 shadow-sm">
                        <img src="{{asset('img/repassage.png')}}" class="card-img-top" alt="Service de lavage KLIN KLIN">
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title h4 mb-3"><i class="fas fa-water me-2 text-klin-cyan"></i>Lavage & Séchage</h3>
                            <p class="card-text">Votre linge quotidien (vêtements, draps, serviettes) lavé, séché et plié avec soin. Options écologiques disponibles.</p>
                            <a href="{{ route('services.lavage') }}" class="btn btn-outline-custom-primary mt-auto">Détails Lavage</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card service-card h-100 border-0 shadow-sm">
                        <img src="{{ asset('img/lavage.png') }}" class="card-img-top" alt="Service de repassage KLIN KLIN">
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title h4 mb-3"><i class="fas fa-tshirt me-2 text-klin-cyan"></i>Repassage Professionnel</h3>
                            <p class="card-text">Chemises, pantalons, robes... Recevez vos vêtements impeccablement repassés, sur cintre ou pliés.</p>
                            <a href="{{ route('services.repassage') }}" class="btn btn-outline-custom-primary mt-auto">Détails Repassage</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card service-card h-100 border-0 shadow-sm">
                        <img src="{{ asset('img/pressing.png') }}" class="card-img-top" alt="Service de pressing KLIN KLIN">
                        <div class="card-body d-flex flex-column">
                            <h3 class="card-title h4 mb-3"><i class="fas fa-diagnoses me-2 text-klin-cyan"></i>Nettoyage à Sec Éco</h3>
                            <p class="card-text">Pour vos costumes, manteaux, robes de soirée et textiles délicats. Solutions de nettoyage à sec respectueuses.</p>
                            <a href="{{ route('services.pressing') }}" class="btn btn-outline-custom-primary mt-auto">Détails Pressing</a>
                        </div>
                    </div>
                </div>
            </div>
             <div class="text-center mt-5">
                <a href="{{ route('services.index') }}" class="btn btn-custom-primary btn-lg">Voir tous nos services et abonnements</a>
            </div>
        </div>
    </section>

    {{-- 5. Pourquoi Nous Choisir ? --}}
    <section class="py-5 bg-klin-violet-attenue section-why-us">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="section-title mb-3">Les Avantages KLIN KLIN</h2>
                    <p class="section-description">Plus qu'une simple blanchisserie, une expérience pensée pour vous.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="advantage-card p-4 h-100 text-center">
                        <div class="advantage-icon mx-auto mb-3"><i class="fas fa-clock fa-2x"></i></div>
                        <h3 class="h5 advantage-title">Gain de Temps Précieux</h3>
                        <p>Ne perdez plus de temps avec la lessive. Consacrez-le à ce qui compte vraiment pour vous.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="advantage-card p-4 h-100 text-center">
                        <div class="advantage-icon mx-auto mb-3"><i class="fas fa-check-circle fa-2x"></i></div>
                        <h3 class="h5 advantage-title">Qualité Professionnelle</h3>
                        <p>Un savoir-faire expert pour un linge toujours impeccable, respectant les fibres et les couleurs.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="advantage-card p-4 h-100 text-center">
                        <div class="advantage-icon mx-auto mb-3"><i class="fas fa-leaf fa-2x"></i></div>
                        <h3 class="h5 advantage-title">Engagement Écologique</h3>
                        <p>Des pratiques durables pour minimiser notre impact sur l'environnement, sans compromis sur la propreté.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="advantage-card p-4 h-100 text-center">
                        <div class="advantage-icon mx-auto mb-3"><i class="fas fa-hand-holding-usd fa-2x"></i></div>
                        <h3 class="h5 advantage-title">Tarification Claire</h3>
                        <p>Des prix transparents au kilo ou à la pièce, et des abonnements flexibles pour plus d'économies.</p>
                    </div>
                </div>
                 <div class="col-md-6 col-lg-4">
                    <div class="advantage-card p-4 h-100 text-center">
                        <div class="advantage-icon mx-auto mb-3"><i class="fas fa-concierge-bell fa-2x"></i></div>
                        <h3 class="h5 advantage-title">Service Client Dédié</h3>
                        <p>Une équipe à votre écoute pour répondre à vos besoins spécifiques et garantir votre satisfaction.</p>
                    </div>
                </div>
                 <div class="col-md-6 col-lg-4">
                    <div class="advantage-card p-4 h-100 text-center">
                        <div class="advantage-icon mx-auto mb-3"><i class="fas fa-mobile-alt fa-2x"></i></div>
                        <h3 class="h5 advantage-title">Simplicité d'Utilisation</h3>
                        <p>Commandez en quelques clics via notre site web ou notre application mobile (bientôt disponible).</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 6. FAQ --}}
    <section class="py-5 section-faq">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="section-title mb-3">Questions Fréquentes</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Quels types de vêtements traitez-vous ?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Nous traitons une large gamme de vêtements et de linge de maison, y compris les articles délicats, les costumes, les robes, les couettes, etc. Chaque article est inspecté et traité selon ses besoins spécifiques. Pour des articles très particuliers, n'hésitez pas à nous contacter.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Comment fonctionne la tarification ?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Notre tarification est généralement au poids pour le lavage et séchage standard. Le repassage et le nettoyage à sec sont facturés à la pièce. Nous proposons également des abonnements avantageux. Vous pouvez consulter notre grille tarifaire détaillée sur la page "Tarifs" ou lors de votre commande.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Quelles sont vos mesures pour l'environnement ?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Nous nous engageons à minimiser notre impact environnemental en utilisant des détergents écologiques, en optimisant notre consommation d'eau et d'énergie, en proposant des solutions de nettoyage à sec plus vertes et en optimisant nos tournées de livraison. Plus de détails sont disponibles dans notre section "Notre Engagement Écologique".
                                </div>
                            </div>
                        </div>
                         <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Quels sont les délais de livraison ?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Le délai standard est de 48h à 72h. Un service express peut être disponible dans certaines zones moyennant des frais supplémentaires. Vous pouvez choisir votre créneau de livraison lors de la planification de votre commande.
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
    :root {
        --klin-violet-fonce: #461871;
        --klin-cyan-fluo: #8DFFC8;
        --klin-violet-structure: #684289;
        --klin-violet-intense: #14081c;
        --klin-blanc: #ffffff;
        --klin-violet-attenue: #c6b7d3;
        --klin-vert-dark: #315946;
        --klin-dark-cyan-charte: #190828;
        --klin-violet-moins-intense: #73356F;
        --klin-pms-2766-dark-blue: #141B4D;

        --klin-primary: var(--klin-violet-fonce);
        --klin-accent: var(--klin-cyan-fluo);
        --klin-light-background: var(--klin-violet-attenue);
        --klin-dark-text-color: var(--klin-pms-2766-dark-blue);
        --klin-light-text-color: var(--klin-blanc);

        --klin-primary-rgb: 70, 24, 113;
        --klin-accent-rgb: 141, 255, 200;
        --klin-violet-attenue-rgb: 198, 183, 211;
        --klin-light-text-color-rgb: 255, 255, 255; /* Ajout explicite pour blanc */
    }

    body {
        font-family: 'Montserrat', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--klin-dark-text-color);
        background-color: var(--klin-blanc);
    }

    .page-header {
        background: linear-gradient(rgba(var(--klin-primary-rgb), 0.88), rgba(var(--klin-primary-rgb), 0.92)), url('{{ asset("img/laundry_background_hero.jpg") }}') no-repeat center center;
        background-size: cover;
        color: var(--klin-light-text-color);
    }
    .page-header h1, .page-header p {
        color: var(--klin-light-text-color);
    }

    .btn-custom-primary {
        background-color: var(--klin-accent);
        border-color: var(--klin-accent);
        color: var(--klin-primary);
        font-weight: bold;
        padding: 0.75rem 1.75rem;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .btn-custom-primary:hover, .btn-custom-primary:focus {
        background-color: #7ADCC0;
        border-color: #7ADCC0;
        color: var(--klin-primary);
        box-shadow: 0 0.5rem 1.5rem rgba(var(--klin-accent-rgb), 0.4);
        transform: translateY(-3px);
    }
    .pulse-hover:hover {
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(var(--klin-accent-rgb), 0.7); }
        70% { box-shadow: 0 0 0 15px rgba(var(--klin-accent-rgb), 0); }
        100% { box-shadow: 0 0 0 0 rgba(var(--klin-accent-rgb), 0); }
    }

    .btn-outline-custom-primary {
        color: var(--klin-primary);
        border-color: var(--klin-primary);
        font-weight: 600;
        padding: 0.6rem 1.2rem;
        transition: all 0.3s ease;
    }
    .btn-outline-custom-primary:hover, .btn-outline-custom-primary:focus {
        background-color: var(--klin-primary);
        color: var(--klin-light-text-color);
        border-color: var(--klin-primary);
        transform: translateY(-2px);
        box-shadow: 0 0.3rem 0.8rem rgba(var(--klin-primary-rgb),0.2);
    }

    .section-title {
        font-size: 2.6rem;
        color: var(--klin-primary);
        position: relative;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
        font-weight: 700;
    }
    .section-title.text-start::before {
        left: 0;
        transform: translateX(0);
    }
    .section-title::before {
        content: "";
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 80px;
        height: 4px;
        background-color: var(--klin-accent);
        transform: translateX(-50%);
        border-radius: 2px;
    }
    .section-description {
        color: #495057;
        font-size: 1.15rem;
        max-width: 750px;
        margin-left: auto;
        margin-right: auto;
    }

    .bg-klin-violet-attenue {
        background-color: var(--klin-light-background);
    }
    .bg-klin-violet { /* Utilisé pour la section CTA finale si réintroduite */
        background-color: var(--klin-primary);
    }
    .text-klin-cyan {
        color: var(--klin-accent);
    }

    /* How it works / Step cards */
    .section-how-it-works { background-color: var(--klin-blanc); }
    .step-card {
        background-color: var(--klin-blanc);
        border: 1px solid var(--klin-light-background);
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(var(--klin-primary-rgb), 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .step-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 25px rgba(var(--klin-primary-rgb), 0.12);
    }
    .step-icon {
        width: 70px; height: 70px;
        border-radius: 50%;
        background-color: var(--klin-primary);
        color: var(--klin-accent);
        display: flex; align-items: center; justify-content: center;
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .step-card:hover .step-icon {
        background-color: var(--klin-accent);
        color: var(--klin-primary);
    }
    .step-title {
        color: var(--klin-primary);
        font-weight: 700;
        margin-top: 0.5rem;
    }

    /* Eco Section */
    .section-eco .eco-list li {
        font-size: 1.1rem;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
    }
    .section-eco .eco-list i {
        color: var(--klin-accent);
        font-size: 1.5rem;
        width: 30px;
    }
    .section-eco img {
        border: 5px solid var(--klin-accent);
    }

    /* Services Section -- MODIFIÉ pour images carrées */
    .service-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 25px rgba(var(--klin-primary-rgb), 0.12);
    }
    .service-card .card-img-top {
        width: 100%; /* Prend toute la largeur de la colonne de la carte */
        aspect-ratio: 1 / 1; /* Force un ratio carré */
        object-fit: cover; /* Assure que l'image couvre la zone, en la rognant si nécessaire */
    }
    .service-card .card-title { color: var(--klin-primary); font-weight: 600; }
    .service-card .card-title i { vertical-align: middle; }

    /* Why Us Section / Advantage Cards */
    .advantage-card {
        background-color: var(--klin-blanc);
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(var(--klin-primary-rgb), 0.07);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .advantage-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(var(--klin-primary-rgb), 0.1);
    }
    .advantage-icon {
        width: 60px; height: 60px;
        border-radius: 50%;
        background-color: var(--klin-primary);
        color: var(--klin-accent);
        display: flex; align-items: center; justify-content: center;
    }
    .advantage-title { color: var(--klin-primary); font-weight: 600; }

    /* FAQ Section */
    .section-faq .accordion-item {
        margin-bottom: 1rem;
        border: 1px solid var(--klin-light-background);
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .section-faq .accordion-button {
        font-weight: 600;
        color: var(--klin-primary);
        background-color: var(--klin-light-background);
    }
    .section-faq .accordion-button:not(.collapsed) {
        background-color: var(--klin-accent);
        color: var(--klin-primary);
        box-shadow: inset 0 -1px 0 rgba(var(--klin-primary-rgb),.125);
    }
    .section-faq .accordion-button:focus {
        box-shadow: 0 0 0 0.25rem rgba(var(--klin-accent-rgb), 0.5);
        border-color: var(--klin-accent);
    }
    .section-faq .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23461871'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }
     .section-faq .accordion-button:not(.collapsed)::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23461871'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }
    .section-faq .accordion-body {
        background-color: var(--klin-blanc);
        padding: 1.25rem;
    }

    /* Styles pour la section Final CTA (si elle est réintroduite)
    .section-final-cta h2 { color: var(--klin-light-text-color); }
    .section-final-cta p { color: rgba(var(--klin-light-text-color-rgb),0.85); }
    */

</style>
@endsection

@section('scripts')
<script>
    // Le script pour --klin-light-text-color-rgb n'est plus critique si la section CTA finale est supprimée,
    // mais il est conservé car c'est une bonne pratique pour d'autres usages potentiels de rgba().
    document.addEventListener("DOMContentLoaded", function() {
        const root = document.documentElement;
        function setRgbVar(cssVarName, cssRgbVarName) {
            let colorValue = getComputedStyle(root).getPropertyValue(cssVarName).trim();
            if (colorValue.startsWith('#')) {
                const r = parseInt(colorValue.slice(1, 3), 16);
                const g = parseInt(colorValue.slice(3, 5), 16);
                const b = parseInt(colorValue.slice(5, 7), 16);
                root.style.setProperty(cssRgbVarName, `${r}, ${g}, ${b}`);
            } else if (colorValue.toLowerCase() === 'white' || colorValue === '#ffffff' || colorValue === '#fff') {
                 root.style.setProperty(cssRgbVarName, '255, 255, 255');
            }
            // Ajoutez d'autres conversions de couleurs nommées vers RGB si nécessaire
        }
        setRgbVar('--klin-light-text-color', '--klin-light-text-color-rgb');
        // Vous pouvez appeler setRgbVar pour d'autres couleurs si besoin
    });
</script>
@endsection