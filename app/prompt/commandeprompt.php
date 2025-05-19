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
            --sidebar-bg: #2a0e42;
            --primary-purple: #4c2975; 
            --primary-purple-darker: #3b1f59;
            --secondary-orange: #f26d50;
            --secondary-orange-darker: #d95f42;
            --light-purple-bg: #f2eef5; 
            --light-grey-bg: #f8f9fa; 
            --text-color: #333;
            --heading-color: #2c3e50; 
            --border-color: #dee2e6;
            --inactive-step-color: #d1c5e0;
            --active-step-color: var(--primary-purple);

            --sidebar-link-color: #ffffff;
            --sidebar-link-active-bg: #dbffef;
            --sidebar-link-active-color: #3d735b;
            --sidebar-user-initial-bg: #dbffef;
            --sidebar-user-initial-color: #3d735b;
            
            --button-light-border-radius: 6px; 
            --button-full-border-radius: 25px; 
        }

        body {
            font-family: 'Montserrat', sans-serif; 
            background-color: var(--light-grey-bg);
            color: var(--text-color);
            margin: 0;
            display: flex; 
        }

        /* Sidebar Styles (inchangé) */
        .sidebar {
            width: 280px; background-color: var(--sidebar-bg); color: var(--sidebar-link-color);
            height: 100vh; position: fixed; top: 0; left: 0; z-index: 1030; 
            padding: 1rem; display: flex; flex-direction: column;
            justify-content: space-between; transition: transform 0.3s ease-in-out;
        }
        .sidebar-logo img { max-width: 120px; margin-bottom: 1.5rem; }
        .sidebar .nav-link {
            color: var(--sidebar-link-color); padding: 0.75rem 1rem; margin-bottom: 0.5rem;
            border-radius: var(--button-light-border-radius); font-size: 0.95rem; font-weight: 500; 
        }
        .sidebar .nav-link:hover { background-color: rgba(255, 255, 255, 0.1); }
        .sidebar .nav-link.active { 
            background-color: var(--sidebar-link-active-bg); color: var(--sidebar-link-active-color);
            font-weight: 600; border-radius: var(--button-full-border-radius); 
        }
        .sidebar .nav-link i { font-size: 1.1rem; }
        .sidebar hr { border-top: 1px solid rgba(255, 255, 255, 0.2); margin: 1rem 0; }
        .user-avatar-initial {
            width: 40px; height: 40px; border-radius: 50%; background-color: var(--sidebar-user-initial-bg);
            color: var(--sidebar-user-initial-color); display: flex; align-items: center;
            justify-content: center; font-weight: 600; font-size: 1.2rem; 
        }
        .user-section .user-name { color: var(--sidebar-link-color); font-size: 0.9rem; font-weight: 600;}
        .user-section .user-email { color: var(--sidebar-user-email-color, #b3aec5); font-size: 0.8rem; font-weight: 400;}
        .btn-logout {
            background-color: transparent; border: 1px solid rgba(255, 255, 255, 0.3);
            color: var(--sidebar-link-color); text-align: left; font-weight: 500;
            border-radius: var(--button-light-border-radius);
        }
        .btn-logout:hover { background-color: rgba(255, 255, 255, 0.1); color: var(--sidebar-link-color); }

        /* Mobile Navbar (inchangé) */
        .mobile-navbar {
            background-color: var(--sidebar-bg);
            padding: 0.5rem 1rem;
        }
        .mobile-navbar .navbar-brand img { max-height: 50px; }
        .mobile-navbar .navbar-toggler { border-color: rgba(255,255,255,0.5); }
        .mobile-navbar .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Main Content & Multi-Step Form (inchangé) */
        .main-content {
            margin-left: 280px; 
            flex-grow: 1; 
            padding: 2.5rem 3rem; 
            overflow-y: auto; 
            height: 100vh; 
        }

        /* Progress Bar (inchangé) */
        .progress-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start; 
            margin-bottom: 3rem; 
            position: relative;
        }
        .progress-step {
            text-align: center;
            flex: 1;
            position: relative;
            color: var(--inactive-step-color);
            font-weight: 500;
        }
        .progress-step .step-circle {
            width: 35px; 
            height: 35px; 
            border-radius: 50%;
            background-color: #fff;
            border: 4px solid var(--inactive-step-color); 
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem auto; 
            z-index: 2;
            position: relative; 
        }
        .progress-step .step-label {
            font-size: 0.9rem; 
            color: var(--inactive-step-color);
        }
        .progress-step.active .step-circle {
            border-color: var(--active-step-color);
            background-color: var(--active-step-color);
        }
        .progress-step.active .step-label {
            color: var(--active-step-color);
            font-weight: 600;
        }
        .progress-container::before { 
            content: ''; position: absolute;
            top: 17px; 
            left: 0; 
            right: 0; 
            height: 5px; 
            background-color: var(--inactive-step-color);
            z-index: 1; 
            width: 100%; 
        }
        .progress-line-active { 
            position: absolute; top: 17px; 
            left: 0; 
            height: 5px; background-color: var(--active-step-color);
            z-index: 1; width: 0%; 
            transition: width 0.3s ease;
        }

        .form-step { display: none; }
        .form-step.active-step { display: block; }
        .form-step h2 { font-weight: 700; color: var(--heading-color); margin-bottom: 0.75rem; }
        .form-step .step-subtitle { font-size: 1rem; color: #555; margin-bottom: 2.5rem; }
        .form-label { font-weight: 500; margin-bottom: 0.3rem; font-size: 0.9rem; }
        .form-control, .form-select {
            border-radius: var(--button-light-border-radius); padding: 0.75rem 1rem;
            font-size: 0.95rem; border-color: #ced4da;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 0.2rem rgba(76, 41, 117, 0.25);
        }
        .input-group-text { background-color: #e9ecef; border-color: #ced4da;}
        .btn-primary-purple {
            background-color: var(--primary-purple); border-color: var(--primary-purple); color: white;
            padding: 0.75rem 1.5rem; border-radius: var(--button-light-border-radius); font-weight: 500;
        }
        .btn-primary-purple:hover { background-color: var(--primary-purple-darker); border-color: var(--primary-purple-darker); }
        .btn-secondary-orange {
            background-color: var(--secondary-orange); border-color: var(--secondary-orange); color: white;
            padding: 0.75rem 1.5rem; border-radius: var(--button-light-border-radius); font-weight: 500;
        }
        .btn-secondary-orange:hover { background-color: var(--secondary-orange-darker); border-color: var(--secondary-orange-darker); }
        .btn-light-purple {
            background-color: var(--light-purple-bg); border-color: var(--light-purple-bg); color: var(--primary-purple);
            padding: 0.5rem 1rem; border-radius: var(--button-light-border-radius); font-weight: 500;
        }
        .btn-light-purple:hover { background-color: #dcd3e8; }

        /* Step Articles: Card Style Ajustements */
        .article-card {
            border: 2px solid var(--sidebar-bg); 
            background-color: #fff; 
            border-radius: var(--button-light-border-radius);
            padding: 0; 
            margin-bottom: 1.5rem;
            overflow: hidden; 
        }
        .article-card .d-flex { 
            align-items: stretch; 
        }
        .article-card .article-image-container { 
            flex: 0 0 50%; 
            position: relative; 
        }
        .article-card .article-image {
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
        }
        .article-card-body {
            flex: 0 0 50%; 
            padding: 0.75rem; 
            display: flex;
            flex-direction: column;
            justify-content: space-between; 
        }
        .article-card-body h6 { font-weight: 600; margin-bottom: 0.25rem; font-size: 0.9rem; }
        .article-card-body .article-price { font-weight: 500; color: var(--primary-purple); margin-bottom: 0.25rem; font-size: 0.85rem; }
        .article-card-body .article-weight-text { font-size: 0.75rem; color: #555; margin-bottom: 0.5rem; }
        
        .quantity-selector { display: flex; align-items: center; margin-top: auto; }
        .quantity-selector .btn { 
            padding: 0.2rem 0.5rem; 
            border-color: var(--sidebar-bg); 
            color: var(--sidebar-bg);
            background-color: transparent; 
            font-size: 0.8rem; 
        }
        .quantity-selector .btn:hover { background-color: var(--light-purple-bg); }
        .quantity-selector .quantity-display { padding: 0 0.5rem; font-weight: 500; min-width: 18px; text-align: center; font-size: 0.85rem; }
        
        .filters-sidebar { background-color: #fff; padding: 1.5rem; border-radius: var(--button-light-border-radius); box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .filters-sidebar h5 { font-weight: 600; margin-bottom: 1rem; }
        .form-check-label { font-size: 0.9rem; }
        /* Styles pour l'accordéon des filtres */
        .filters-sidebar .accordion-button {
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            font-weight: 600;
            background-color: var(--light-purple-bg);
            color: var(--primary-purple);
        }
        .filters-sidebar .accordion-button:not(.collapsed) {
            background-color: var(--primary-purple);
            color: white;
        }
        .filters-sidebar .accordion-button:focus { box-shadow: none; }
        .filters-sidebar .accordion-button::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%234c2975'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        }
        .filters-sidebar .accordion-button:not(.collapsed)::after {
             background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='white'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        }
        .filters-sidebar .accordion-body { padding: 1rem; }
        .filters-sidebar .accordion-item { border-color: var(--border-color); }

        /* Total estimé en bas sur mobile */
        .mobile-totals-summary {
            background-color: var(--light-purple-bg);
            padding: 0.75rem 1rem;
            border-radius: var(--button-light-border-radius);
            margin-top: 1.5rem;
            border: 1px solid var(--primary-purple);
        }
        .mobile-totals-summary p {
            margin-bottom: 0.25rem;
            font-weight: 500;
            font-size: 0.95rem;
        }
         .mobile-totals-summary p strong {
            color: var(--primary-purple);
        }


        /* Styles pour récapitulatif et paiement (inchangé) */
        .summary-box {
            background-color: var(--light-purple-bg); padding: 1.5rem 2rem;
            border-radius: var(--button-light-border-radius);
        }
        .summary-box h4 { font-weight: 600; color: var(--primary-purple); margin-bottom: 1.5rem; }
        .summary-box .row { margin-bottom: 0.5rem; }
        .summary-box .total-row strong { font-size: 1.2rem; color: var(--primary-purple); }
        .selected-articles-box { background-color: #fff; padding: 1.5rem; border-radius: var(--button-light-border-radius); border: 1px solid var(--border-color); }
        .payment-method-card {
            border: 1px solid var(--border-color); border-radius: var(--button-light-border-radius);
            padding: 1rem; text-align: center; cursor: pointer; margin-bottom: 0.5rem;
            transition: box-shadow 0.2s ease;
        }
        .payment-method-card:hover, .payment-method-card.active {
            box-shadow: 0 0 0 0.2rem rgba(76, 41, 117, 0.25);
            border-color: var(--primary-purple);
        }
        .payment-method-card img { max-height: 30px; margin-bottom: 0.5rem;}
        .recap-article-item { font-size: 0.9rem; }

        /* Responsive */
        @media (max-width: 991.98px) { 
            body { display: block; }
            .sidebar { transform: translateX(-100%); top:0; }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 1.5rem; }
            .mobile-navbar { display: flex !important; }
        }
         @media (min-width: 992px) { 
            .mobile-navbar { display: none !important; }
        }
    </style>
</head>
<body>
    <nav class="sidebar" id="sidebarMenu">
        <div class="h-100 d-flex flex-column">
            <div>
                <div class="sidebar-logo text-center">
                    <img src="img/logo.png" alt="Klin Klin Logo" class="d-none d-lg-block">
                </div>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="#" class="nav-link active" aria-current="page"><i class="bi bi-house-door-fill me-2"></i> Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="bi bi-person-fill me-2"></i> Profil</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="bi bi-cart-fill me-2"></i> Nouvelle Commande</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="bi bi-journal-text me-2"></i> Mon abonnement</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="bi bi-bell-fill me-2"></i> Notifications</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link"><i class="bi bi-question-circle-fill me-2"></i> Aide & Support</a>
                    </li>
                </ul>
            </div>
            <div class="user-section mt-auto"> 
                <hr>
                <div class="d-flex align-items-center mb-2">
                    <div class="user-avatar-initial me-2">F</div>
                    <div>
                        <small class="d-block fw-bold user-name">A. Fabrice</small>
                        <small class="user-email">fab.nomade@gmail.com</small>
                    </div>
                </div>
                <a href="#" class="btn btn-sm btn-logout w-100"><i class="bi bi-box-arrow-left me-2"></i> Déconnexion</a>
            </div>
        </div>
    </nav>
    
    <nav class="navbar mobile-navbar d-lg-none">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="img/logo.png" alt="Klin Klin Logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <main class="main-content">
        <div id="progress-bar-container" class="progress-container mb-5">
            <div class="progress-line-active"></div>
            <div class="progress-step active" data-step="1"><div class="step-circle"></div><div class="step-label">Collecte</div></div>
            <div class="progress-step" data-step="2"><div class="step-circle"></div><div class="step-label">Livraison</div></div>
            <div class="progress-step" data-step="3"><div class="step-circle"></div><div class="step-label">Articles</div></div>
            <div class="progress-step" data-step="4"><div class="step-circle"></div><div class="step-label">Récapitulatif</div></div>
            <div class="progress-step" data-step="5"><div class="step-circle"></div><div class="step-label">Paiement</div></div>
        </div>

        <div id="step1" class="form-step active-step">
            <h2>Planifier une collecte <span class="wave">👋</span></h2>
            <p class="step-subtitle">Choisissez la date, le créneau horaire et l'adresse pour votre collecte.</p>
             <form id="form-step1">
                <div class="row g-3 mb-3">
                    <div class="col-md-6"><label for="adresseCollecte" class="form-label">Adresse</label><select class="form-select" id="adresseCollecte"><option selected>95 Rue Pointe Noire, Congo</option></select></div>
                    <div class="col-md-6"><label for="complementAdresseCollecte" class="form-label">Complément d'adresse</label><input type="text" class="form-control" id="complementAdresseCollecte" placeholder="Appartement, suite, etc."></div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6"><label for="villeCollecte" class="form-label">Ville</label><input type="text" class="form-control" id="villeCollecte" value="Brazzaville"></div>
                    <div class="col-md-6"><label for="codePostalCollecte" class="form-label">Code postal</label><input type="text" class="form-control" id="codePostalCollecte" value="N/A"></div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6"><label for="dateCollecte" class="form-label">Date</label><div class="input-group"><span class="input-group-text"><i class="bi bi-calendar-event"></i></span><input type="date" class="form-control" id="dateCollecte"></div></div>
                    <div class="col-md-6"><label for="creneauHoraireCollecte" class="form-label">Créneau horaire</label><div class="input-group"><span class="input-group-text"><i class="bi bi-clock"></i></span><select class="form-select" id="creneauHoraireCollecte"><option selected>08h - 10h</option><option>10h - 12h</option></select></div></div>
                </div>
                <div class="d-flex justify-content-end"><button type="button" class="btn btn-primary-purple" id="btn-next-step1">Continuer vers Livraison</button></div>
            </form>
        </div>

        <div id="step2_livraison" class="form-step">
             <h2>Informations de livraison</h2>
            <p class="step-subtitle">Où souhaitez-vous être livré(e) ?</p>
            <form id="form-step2-livraison">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="" id="memeAdresseLivraison" checked>
                    <label class="form-check-label" for="memeAdresseLivraison">Livrer à la même adresse que la collecte</label>
                </div>
                <div id="formAdresseLivraisonSpecifique" style="display: none;">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6"><label for="adresseLivraison" class="form-label">Adresse de livraison</label><input type="text" class="form-control" id="adresseLivraison" placeholder="Ex: 123 Rue de la Paix"></div>
                        <div class="col-md-6"><label for="complementAdresseLivraison" class="form-label">Complément d'adresse</label><input type="text" class="form-control" id="complementAdresseLivraison" placeholder="Appartement, suite, etc."></div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6"><label for="villeLivraison" class="form-label">Ville</label><input type="text" class="form-control" id="villeLivraison" placeholder="Ex: Pointe-Noire"></div>
                        <div class="col-md-6"><label for="codePostalLivraison" class="form-label">Code postal</label><input type="text" class="form-control" id="codePostalLivraison" placeholder="Ex: N/A"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="instructionsLivraison" class="form-label">Instructions de livraison (optionnel)</label>
                    <textarea class="form-control" id="instructionsLivraison" rows="3" placeholder="Ex: Laisser chez le gardien, appeler avant d'arriver..."></textarea>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-secondary-orange me-2" id="btn-retour-step2liv">Retour</button>
                    <button type="button" class="btn btn-primary-purple" id="btn-next-step2liv">Continuer vers Articles</button>
                </div>
            </form>
        </div>

        <div id="step3_articles" class="form-step">
            <h2>Vous choisissez ce que nous lavons !</h2>
            <p class="step-subtitle">Ajoutez vos articles et découvrez le tarif estimé.</p>
            <div class="row">
                <div class="col-lg-4 order-1 order-lg-2 mb-3 mb-lg-0">
                    <div class="filters-sidebar">
                        <p class="fs-5 fw-bold text-primary-purple">Prix estimé : <span id="prix-estime-articles">0 FCFA</span></p>
                        <hr>
                        <h5><i class="bi bi-filter me-1"></i>Filtres</h5>
                        
                        <label for="prixRangeArticles" class="form-label mt-2">Prix max: <span id="prixRangeArticlesLabel">50000</span> FCFA</label>
                        <input type="range" class="form-range mb-3" min="0" max="50000" step="500" value="50000" id="prixRangeArticles">
                        
                        <div class="accordion" id="filtersAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingType">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseType" aria-expanded="false" aria-controls="collapseType">
                                        Par type
                                    </button>
                                </h2>
                                <div id="collapseType" class="accordion-collapse collapse" aria-labelledby="headingType" data-bs-parent="#filtersAccordion">
                                    <div class="accordion-body">
                                        <div class="form-check"><input class="form-check-input filter-checkbox" type="checkbox" id="typeVetementsArt" data-filter-group="type" data-filter-value="vetements"><label class="form-check-label" for="typeVetementsArt">Vêtements</label></div>
                                        <div class="form-check"><input class="form-check-input filter-checkbox" type="checkbox" id="typeLingeMaisonArt" data-filter-group="type" data-filter-value="lingedemaison"><label class="form-check-label" for="typeLingeMaisonArt">Linge de maison</label></div>
                                        <div class="form-check"><input class="form-check-input filter-checkbox" type="checkbox" id="typeDelicatArt" data-filter-group="type" data-filter-value="delicat"><label class="form-check-label" for="typeDelicatArt">Délicat</label></div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingUsage">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUsage" aria-expanded="false" aria-controls="collapseUsage">
                                        Par usage
                                    </button>
                                </h2>
                                <div id="collapseUsage" class="accordion-collapse collapse" aria-labelledby="headingUsage" data-bs-parent="#filtersAccordion">
                                    <div class="accordion-body">
                                        <div class="form-check"><input class="form-check-input filter-checkbox" type="checkbox" id="usageHommeArt" data-filter-group="usage" data-filter-value="homme"><label class="form-check-label" for="usageHommeArt">Homme</label></div>
                                        <div class="form-check"><input class="form-check-input filter-checkbox" type="checkbox" id="usageFemmeArt" data-filter-group="usage" data-filter-value="femme"><label class="form-check-label" for="usageFemmeArt">Femme</label></div>
                                        <div class="form-check"><input class="form-check-input filter-checkbox" type="checkbox" id="usageEnfantArt" data-filter-group="usage" data-filter-value="enfant"><label class="form-check-label" for="usageEnfantArt">Enfant</label></div>
                                        <div class="form-check"><input class="form-check-input filter-checkbox" type="checkbox" id="usageBebeArt" data-filter-group="usage" data-filter-value="bebe"><label class="form-check-label" for="usageBebeArt">Bébé</label></div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingPoids">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePoids" aria-expanded="false" aria-controls="collapsePoids">
                                        Par poids estimé
                                    </button>
                                </h2>
                                <div id="collapsePoids" class="accordion-collapse collapse" aria-labelledby="headingPoids" data-bs-parent="#filtersAccordion">
                                    <div class="accordion-body">
                                        <div class="form-check"><input class="form-check-input filter-checkbox" type="checkbox" id="poidsLegerArt" data-filter-group="weightClass" data-filter-value="leger"><label class="form-check-label" for="poidsLegerArt">Léger (~ &lt;0.4 kg)</label></div>
                                        <div class="form-check"><input class="form-check-input filter-checkbox" type="checkbox" id="poidsMoyenArt" data-filter-group="weightClass" data-filter-value="moyen"><label class="form-check-label" for="poidsMoyenArt">Moyen (0.4-0.8 kg)</label></div>
                                        <div class="form-check"><input class="form-check-input filter-checkbox" type="checkbox" id="poidsLourdArt" data-filter-group="weightClass" data-filter-value="lourd"><label class="form-check-label" for="poidsLourdArt">Lourd (&gt; 0.8 kg)</label></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 order-2 order-lg-1">
                    <div id="articles-list-container" class="row row-cols-1 row-cols-md-2 g-3"> 
                        </div>
                    <div class="mobile-totals-summary d-block d-lg-none">
                        <p>Prix estimé : <strong id="prix-estime-articles-mobile">0 FCFA</strong></p>
                        <p>Poids total estimé : <strong id="poids-estime-articles-mobile">0 kg</strong></p>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-secondary-orange me-2" id="btn-retour-step3art">Retour</button>
                <button type="button" class="btn btn-primary-purple" id="btn-next-step3art">Vers Récapitulatif</button>
            </div>
        </div>

        <div id="step4_recap" class="form-step">
            <h2>Récapitulatifs de la commande</h2>
            <p class="step-subtitle">Vérifiez les informations de votre commande avant de passer au paiement.</p>
            <div class="bg-white p-3 p-md-4 rounded shadow-sm mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="me-3 mb-2 mb-md-0">
                        <i class="bi bi-calendar-check me-2"></i>Date & créneau collecte: <strong id="recap-date-creneau-collecte">À définir</strong>
                    </div>
                     <div class="me-3 mb-2 mb-md-0">
                        <i class="bi bi-truck me-2"></i>Adresse de Livraison: <strong id="recap-adresse-livraison">À définir</strong>
                    </div>
                    <button type="button" class="btn btn-light-purple btn-sm" onclick="showStep(1)">Modifier Infos</button> 
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <div class="selected-articles-box">
                        <h5 class="mb-3">Articles sélectionnés <i class="bi bi-chevron-down"></i></h5>
                        <div id="recap-liste-articles-final">
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold mt-3">
                            <span>Total estimé articles:</span>
                            <span id="recap-total-estime-articles-final">0 FCFA</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="summary-box">
                        <h4>Récapitulatif</h4>
                        <div class="row"><div class="col">Sous-total articles</div><div class="col text-end" id="recap-sous-total-final">0 FCFA</div></div>
                        <div class="row"><div class="col">Frais de livraison</div><div class="col text-end" id="recap-livraison-final">0 FCFA</div></div>
                        <hr>
                        <div class="row total-row"><div class="col"><strong>Total Général</strong></div><div class="col text-end"><strong id="recap-total-general-final">0 FCFA</strong></div></div>
                        <button type="button" class="btn btn-primary-purple w-100 my-3" id="btn-next-step4recap">Passer au paiement</button>
                        <button type="button" class="btn btn-outline-danger w-100">Annuler</button>
                        <div class="input-group mt-3"><input type="text" class="form-control" id="codePromoStep4recap" placeholder="Code promo"><button class="btn btn-primary-purple" type="button">Appliquer</button></div>
                    </div>
                </div>
            </div>
             <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-secondary-orange me-2" id="btn-retour-step4recap">Retour</button>
            </div>
        </div>

        <div id="step5_paiement" class="form-step">
             <h2>Effectuer le paiement</h2>
             <p class="step-subtitle">Sécurisez votre commande en complétant le paiement.</p>
             <div class="row">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <h5 class="mb-3">Choisissez votre moyen de paiement :</h5>
                    <div class="row mb-3">
                        <div class="col-sm-4"><div class="payment-method-card" data-payment="cb"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/Old_Visa_Logo.svg/1280px-Old_Visa_Logo.svg.png" alt="Visa" style="max-height:20px"> <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/MasterCard_Logo.svg/1280px-MasterCard_Logo.svg.png" alt="Mastercard" style="max-height:20px"><div>Carte Bancaire</div></div></div>
                        <div class="col-sm-4"><div class="payment-method-card" data-payment="mobilemoney"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/93/MTN_Logo.svg/1200px-MTN_Logo.svg.png" alt="MTN" style="max-height:25px"> <img src="https://upload.wikimedia.org/wikipedia/fr/thumb/4/4b/Airtel_logo.svg/1200px-Airtel_logo.svg.png" alt="Airtel" style="max-height:25px"><div>Mobile Money</div></div></div>
                        <div class="col-sm-4"><div class="payment-method-card" data-payment="quotas"><i class="bi bi-gem fs-3 text-primary-purple"></i><div>Quotas Abonnement</div></div></div>
                    </div>
                    <div id="form-cb-paiement" style="display:none;"><div class="row g-3 mb-3"><div class="col-12"><label for="numeroCarteP" class="form-label">Numéro de carte*</label><input type="text" class="form-control" id="numeroCarteP" placeholder="XXXX XXXX XXXX XXXX"></div><div class="col-md-6"><label for="dateExpirationP" class="form-label">Date d'expiration*</label><input type="text" class="form-control" id="dateExpirationP" placeholder="MM/AA"></div><div class="col-md-6"><label for="cvvP" class="form-label">CVV*</label><input type="text" class="form-control" id="cvvP" placeholder="123"></div></div><div class="form-check mb-3"><input class="form-check-input" type="checkbox" id="adresseFacturationIdentiqueP" checked><label class="form-check-label" for="adresseFacturationIdentiqueP">Utiliser l'adresse de livraison comme adresse de facturation</label></div></div>
                    <div id="form-quotas-paiement" style="display:none;"><p>Solde quotas : <span id="solde-quotas-val">XX</span> unités.</p></div>
                    <button type="button" class="btn btn-primary-purple w-100 btn-lg" id="btn-payer-final">Payer <span id="total-a-payer-bouton-final">0 FCFA</span></button>
                </div>
                <div class="col-lg-5">
                     <div class="summary-box">
                        <h4>Récapitulatif</h4>
                        <div class="row"><div class="col">Sous-total articles</div><div class="col text-end" id="recap-sous-total-paiement-final">0 FCFA</div></div>
                        <div class="row"><div class="col">Frais de livraison</div><div class="col text-end" id="recap-livraison-paiement-final">0 FCFA</div></div>
                        <hr>
                        <div class="row total-row"><div class="col"><strong>Total Général</strong></div><div class="col text-end"><strong id="recap-total-general-paiement-final">0 FCFA</strong></div></div>
                        <div class="input-group mt-3"><input type="text" class="form-control" id="codePromoStep5" placeholder="Code promo"><button class="btn btn-primary-purple" type="button">Appliquer</button></div>
                    </div>
                </div>
            </div>
             <div class="d-flex justify-content-end mt-4">
                <button type="button" class="btn btn-secondary-orange me-2" id="btn-retour-step5pay">Retour</button>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const steps = document.querySelectorAll('.form-step');
            const progressSteps = document.querySelectorAll('.progress-step');
            const progressLine = document.querySelector('.progress-line-active');
            const totalSteps = 5; 
            let currentStep = 1;

            // --- Gestion de la navigation entre étapes ---
            window.showStep = function(stepNumber) { 
                steps.forEach(step => step.classList.remove('active-step'));
                
                const stepIdMap = {
                    1: 'step1',
                    2: 'step2_livraison',
                    3: 'step3_articles',
                    4: 'step4_recap',
                    5: 'step5_paiement'
                };
                document.getElementById(stepIdMap[stepNumber]).classList.add('active-step');
                
                currentStep = stepNumber;
                updateProgress();
                updateReturnButtons();

                if (stepNumber === 4) {
                    updateRecapStep4();
                } else if (stepNumber === 5) {
                    updatePaymentStep5();
                }
            }

            function updateProgress() {
                progressSteps.forEach((step) => {
                    const stepNumAttr = parseInt(step.getAttribute('data-step'));
                    if (stepNumAttr < currentStep) {
                        step.classList.add('active'); 
                        step.classList.remove('current');
                    } else if (stepNumAttr === currentStep) {
                        step.classList.add('active');
                        step.classList.add('current'); 
                    } else {
                        step.classList.remove('active');
                        step.classList.remove('current');
                    }
                });
                const progressPercentage = totalSteps > 1 ? ((currentStep - 1) / (totalSteps - 1)) * 100 : 0;
                if(progressLine) progressLine.style.width = progressPercentage + '%';
            }
            
            function updateReturnButtons() {
                const returnButtonMap = {
                    2: 'btn-retour-step2liv',
                    3: 'btn-retour-step3art',
                    4: 'btn-retour-step4recap',
                    5: 'btn-retour-step5pay'
                };
                document.querySelectorAll('[id^="btn-retour-"]').forEach(btn => btn.style.display = 'none');
                
                if (currentStep > 1 && returnButtonMap[currentStep]) {
                    const btnRetour = document.getElementById(returnButtonMap[currentStep]);
                    if(btnRetour) btnRetour.style.display = 'inline-block';
                }
            }

            document.getElementById('btn-next-step1').addEventListener('click', () => showStep(2));
            document.getElementById('btn-retour-step2liv').addEventListener('click', () => showStep(1));
            document.getElementById('btn-next-step2liv').addEventListener('click', () => showStep(3));
            document.getElementById('btn-retour-step3art').addEventListener('click', () => showStep(2));
            document.getElementById('btn-next-step3art').addEventListener('click', () => {
                updateRecapStep4(); 
                showStep(4);
            });
            document.getElementById('btn-retour-step4recap').addEventListener('click', () => showStep(3));
            document.getElementById('btn-next-step4recap').addEventListener('click', () => {
                updatePaymentStep5(); 
                showStep(5);
            });
            document.getElementById('btn-retour-step5pay').addEventListener('click', () => showStep(4));

            const checkboxMemeAdresse = document.getElementById('memeAdresseLivraison');
            const formAdresseLivraison = document.getElementById('formAdresseLivraisonSpecifique');
            if (checkboxMemeAdresse && formAdresseLivraison) {
                checkboxMemeAdresse.addEventListener('change', function() {
                    formAdresseLivraison.style.display = this.checked ? 'none' : 'block';
                });
            }
            
            const paymentMethods = document.querySelectorAll('.payment-method-card');
            const formCB = document.getElementById('form-cb-paiement');
            const formQuotas = document.getElementById('form-quotas-paiement');

            paymentMethods.forEach(method => {
                method.addEventListener('click', function() {
                    paymentMethods.forEach(m => m.classList.remove('active'));
                    this.classList.add('active');
                    if(formCB) formCB.style.display = 'none';
                    if(formQuotas) formQuotas.style.display = 'none';
                    if (this.dataset.payment === 'cb' && formCB) formCB.style.display = 'block';
                    else if (this.dataset.payment === 'quotas' && formQuotas) formQuotas.style.display = 'block';
                });
            });

            // --- Données et logique pour les articles ---
            const FRAIS_LIVRAISON = 1000;
            let allArticlesData = [];
            let selectedArticles = {}; 
            const articlesListContainer = document.getElementById('articles-list-container');
            const prixEstimeGlobalEl = document.getElementById('prix-estime-articles'); // Dans les filtres
            const prixEstimeMobileEl = document.getElementById('prix-estime-articles-mobile'); // Sous les articles (mobile)
            const poidsEstimeMobileEl = document.getElementById('poids-estime-articles-mobile'); // Sous les articles (mobile)

            function getAverageWeight(weightText) {
                if (!weightText || typeof weightText !== 'string' || weightText.toLowerCase() === 'n/a' || weightText.toLowerCase().includes('par kg')) {
                    return 0;
                }
                const numbers = weightText.match(/\d+(\.\d+)?/g); 
                if (numbers) {
                    const numericValues = numbers.map(parseFloat);
                    if (numericValues.length === 1) {
                        return numericValues[0]; 
                    } else if (numericValues.length > 1) {
                        return numericValues.reduce((sum, val) => sum + val, 0) / numericValues.length;
                    }
                }
                return 0; 
            }

            function initializeArticlesMasterData() {
                allArticlesData = [
                    { id: 'costume', name: 'Costume', price: 5000, priceText: '5000 FCFA', imageSrc: 'img/articles/costume.jpeg', weightText: 'Poids estimé: 1 - 2 kg', type: ['vetements'], usage: ['homme'], weightClass: 'lourd' },
                    { id: 'culotte', name: 'Culotte', price: 500, priceText: '500 FCFA', imageSrc: 'img/articles/cullote.jpeg', weightText: 'Poids estimé: ~0.1 kg', type: ['vetements', 'delicat'], usage: ['femme'], weightClass: 'leger' },
                    { id: 'drap', name: 'Drap', price: 2000, priceText: '2000 FCFA', imageSrc: 'img/articles/drap.jpeg', weightText: 'Poids estimé: 0.5 - 1 kg', type: ['lingedemaison'], usage: [], weightClass: 'moyen' },
                    { id: 'jean', name: 'Jean', price: 2500, priceText: '2500 FCFA', imageSrc: 'img/articles/jean.jpeg', weightText: 'Poids estimé: 0.5 - 0.8 kg', type: ['vetements'], usage: ['homme', 'femme'], weightClass: 'moyen' },
                    { id: 'lingemaison', name: 'Linge de maison', price: 0, priceText: 'Variable', imageSrc: 'img/articles/lingemaison.jpeg', weightText: 'Poids estimé: Par kg', type: ['lingedemaison'], usage: [], weightClass: 'variable' }, 
                    { id: 'pantalon', name: 'Pantalon', price: 2000, priceText: '2000 FCFA', imageSrc: 'img/articles/pantalon.jpeg', weightText: 'Poids estimé: 0.4 - 0.7 kg', type: ['vetements'], usage: ['homme', 'femme'], weightClass: 'moyen' },
                    { id: 'robepagne', name: 'Robe Pagne', price: 3500, priceText: '3500 FCFA', imageSrc: 'img/articles/robe pagne.jpeg', weightText: 'Poids estimé: 0.5 - 1 kg', type: ['vetements'], usage: ['femme'], weightClass: 'moyen' },
                    { id: 'robe', name: 'Robe', price: 3000, priceText: '3000 FCFA', imageSrc: 'img/articles/robe.jpeg', weightText: 'Poids estimé: 0.3 - 0.8 kg', type: ['vetements'], usage: ['femme'], weightClass: 'moyen' },
                    { id: 'servicespecifique', name: 'Service Spécifique', price: NaN, priceText: 'Sur devis', imageSrc: 'img/articles/services.jpeg', weightText: 'Poids estimé: N/A', type: ['service'], usage: [], weightClass: 'variable' }, 
                    { id: 'serviette', name: 'Serviette', price: 1000, priceText: '1000 FCFA', imageSrc: 'img/articles/serviette.jpeg', weightText: 'Poids estimé: 0.2 - 0.5 kg', type: ['lingedemaison'], usage: [], weightClass: 'leger' },
                    { id: 'soutiengorge', name: 'Soutien-gorge', price: 800, priceText: '800 FCFA', imageSrc: 'img/articles/soutiengoerge.jpeg', weightText: 'Poids estimé: ~0.1 kg', type: ['vetements', 'delicat'], usage: ['femme'], weightClass: 'leger' },
                    { id: 'teeshirt', name: 'T-shirt', price: 1500, priceText: '1500 FCFA', imageSrc: 'img/articles/teeshirt.jpeg', weightText: 'Poids estimé: 0.2 - 0.4 kg', type: ['vetements'], usage: ['homme', 'femme', 'enfant'], weightClass: 'leger' },
                    { id: 'veste', name: 'Veste', price: 4000, priceText: '4000 FCFA', imageSrc: 'img/articles/veste.jpeg', weightText: 'Poids estimé: 0.8 - 1.5 kg', type: ['vetements'], usage: ['homme', 'femme'], weightClass: 'lourd' },
                    { id: 'vestesimple', name: 'Veste simple', price: 3000, priceText: '3000 FCFA', imageSrc: 'img/articles/vestesimple.jpeg', weightText: 'Poids estimé: 0.6 - 1 kg', type: ['vetements'], usage: ['homme', 'femme'], weightClass: 'lourd' }
                ];

                // Calculer et ajouter averageWeight à chaque article
                allArticlesData.forEach(article => {
                    article.averageWeight = getAverageWeight(article.weightText);
                });
            }
            
            function createArticleCardHTML(article) {
                const quantity = selectedArticles[article.id] ? selectedArticles[article.id].quantity : 0;
                return `
                <div class="col">
                    <div class="article-card" 
                         data-id="${article.id}" 
                         data-name="${article.name}" 
                         data-price="${article.price}" 
                         data-price-text="${article.priceText}"
                         data-image="${article.imageSrc}"
                         data-weight-text="${article.weightText}"
                         data-average-weight="${article.averageWeight}">
                        <div class="d-flex">
                            <div class="article-image-container">
                                <img src="${article.imageSrc}" alt="${article.name}" class="article-image">
                            </div>
                            <div class="article-card-body">
                                <div>
                                    <h6>${article.name}</h6>
                                    <p class="article-price mb-1">${article.priceText}</p>
                                    <p class="article-weight-text mb-2">${article.weightText}</p>
                                </div>
                                <div class="quantity-selector">
                                    <button type="button" class="btn btn-sm btn-decrease" data-id="${article.id}">-</button>
                                    <span class="quantity-display px-2">${quantity}</span>
                                    <button type="button" class="btn btn-sm btn-increase" data-id="${article.id}">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `;
            }

            function renderFilteredArticles(articlesToDisplay) {
                if (!articlesListContainer) return;
                articlesListContainer.innerHTML = '';
                articlesToDisplay.forEach(articleData => {
                    articlesListContainer.insertAdjacentHTML('beforeend', createArticleCardHTML(articleData));
                });
                setupQuantitySelectors(); 
                updateTotalPriceStep3();
                updateTotalWeightStep3();
            }

            function setupQuantitySelectors() {
                document.querySelectorAll('.quantity-selector .btn-increase').forEach(button => {
                    const newButton = button.cloneNode(true); 
                    button.parentNode.replaceChild(newButton, button); 
                    newButton.addEventListener('click', function() {
                        handleQuantityChange(this.dataset.id, 1);
                    });
                });
                document.querySelectorAll('.quantity-selector .btn-decrease').forEach(button => {
                    const newButton = button.cloneNode(true);
                    button.parentNode.replaceChild(newButton, button);
                    newButton.addEventListener('click', function() {
                        handleQuantityChange(this.dataset.id, -1);
                    });
                });
            }


            function handleQuantityChange(articleId, change) {
                const articleMasterData = allArticlesData.find(art => art.id === articleId);
                if (!articleMasterData) return; 

                if (!selectedArticles[articleId]) {
                    selectedArticles[articleId] = { 
                        quantity: 0, 
                        price: articleMasterData.price, 
                        name: articleMasterData.name, 
                        priceText: articleMasterData.priceText, 
                        imageSrc: articleMasterData.imageSrc,
                        averageWeight: articleMasterData.averageWeight // S'assurer que le poids est là
                    };
                }
                
                let newQuantity = selectedArticles[articleId].quantity + change;
                if (newQuantity < 0) newQuantity = 0;
                
                selectedArticles[articleId].quantity = newQuantity;

                // Mettre à jour l'affichage de la quantité sur la carte si elle est visible
                const articleCard = document.querySelector(`.article-card[data-id="${articleId}"]`);
                if (articleCard) { 
                    const quantityDisplay = articleCard.querySelector('.quantity-display');
                    if (quantityDisplay) quantityDisplay.textContent = newQuantity;
                }
                
                updateTotalPriceStep3();
                updateTotalWeightStep3();
            }

            function updateTotalPriceStep3() {
                let total = 0;
                for (const id in selectedArticles) {
                    if (selectedArticles[id].quantity > 0 && !isNaN(selectedArticles[id].price)) {
                        total += selectedArticles[id].quantity * selectedArticles[id].price;
                    }
                }
                if (prixEstimeGlobalEl) prixEstimeGlobalEl.textContent = `${total} FCFA`;
                if (prixEstimeMobileEl) prixEstimeMobileEl.textContent = `${total} FCFA`;
            }

            function updateTotalWeightStep3() {
                let totalWeight = 0;
                for (const id in selectedArticles) {
                    const item = selectedArticles[id];
                    if (item.quantity > 0 && typeof item.averageWeight === 'number' && !isNaN(item.averageWeight)) {
                         totalWeight += item.quantity * item.averageWeight;
                    }
                }
                if (poidsEstimeMobileEl) {
                    // Arrondir à 2 décimales pour un affichage plus propre
                    poidsEstimeMobileEl.textContent = `${totalWeight.toFixed(2)} kg`;
                }
            }
            
            function updateRecapStep4() {
                const recapList = document.getElementById('recap-liste-articles-final');
                recapList.innerHTML = '';
                let sousTotalArticles = 0;

                document.getElementById('recap-date-creneau-collecte').textContent = `${document.getElementById('dateCollecte').value || 'Non définie'} / ${document.getElementById('creneauHoraireCollecte').value || 'Non défini'}`;
                const adresseCollecteEl = document.getElementById('adresseCollecte');
                const adresseLivraisonEl = document.getElementById('adresseLivraison');
                const memeAdresse = document.getElementById('memeAdresseLivraison').checked;
                
                let adresseLivraisonText = "Non définie";
                if (memeAdresse && adresseCollecteEl) {
                    adresseLivraisonText = adresseCollecteEl.value;
                } else if (adresseLivraisonEl && adresseLivraisonEl.value) {
                    adresseLivraisonText = adresseLivraisonEl.value;
                }
                document.getElementById('recap-adresse-livraison').textContent = adresseLivraisonText;

                let hasSelectedItems = false;
                for (const id in selectedArticles) {
                    const item = selectedArticles[id];
                    if (item.quantity > 0) {
                        hasSelectedItems = true;
                        const itemDiv = document.createElement('div');
                        itemDiv.classList.add('d-flex', 'justify-content-between', 'mb-1', 'recap-article-item');
                        
                        const itemNameQty = document.createElement('span');
                        itemNameQty.textContent = `${item.name} x ${item.quantity}`;
                        
                        const itemPriceSpan = document.createElement('span');
                        if (!isNaN(item.price)) {
                            itemPriceSpan.textContent = `${item.quantity * item.price} FCFA`;
                            sousTotalArticles += item.quantity * item.price;
                        } else {
                            itemPriceSpan.textContent = item.priceText; 
                        }
                        itemDiv.appendChild(itemNameQty);
                        itemDiv.appendChild(itemPriceSpan);
                        recapList.appendChild(itemDiv);
                    }
                }
                 if (!hasSelectedItems) {
                    recapList.innerHTML = '<p class="text-muted">Aucun article sélectionné.</p>';
                }

                document.getElementById('recap-total-estime-articles-final').textContent = `${sousTotalArticles} FCFA`;
                document.getElementById('recap-sous-total-final').textContent = `${sousTotalArticles} FCFA`;
                
                const currentFraisLivraison = hasSelectedItems ? FRAIS_LIVRAISON : 0;

                document.getElementById('recap-livraison-final').textContent = `${currentFraisLivraison} FCFA`;
                document.getElementById('recap-total-general-final').textContent = `${sousTotalArticles + currentFraisLivraison} FCFA`;
            }

            function updatePaymentStep5() {
                let sousTotalArticles = 0;
                let hasSelectedItems = false;
                for (const id in selectedArticles) {
                    const item = selectedArticles[id];
                     if (item.quantity > 0) {
                        hasSelectedItems = true;
                        if (!isNaN(item.price)) {
                            sousTotalArticles += item.quantity * item.price;
                        }
                    }
                }
                
                const currentFraisLivraison = hasSelectedItems ? FRAIS_LIVRAISON : 0;

                document.getElementById('recap-sous-total-paiement-final').textContent = `${sousTotalArticles} FCFA`;
                document.getElementById('recap-livraison-paiement-final').textContent = `${currentFraisLivraison} FCFA`;
                const totalGeneral = sousTotalArticles + currentFraisLivraison;
                document.getElementById('recap-total-general-paiement-final').textContent = `${totalGeneral} FCFA`;
                document.getElementById('total-a-payer-bouton-final').textContent = `${totalGeneral} FCFA`;
            }

            // --- Logique des filtres ---
            const prixRangeInput = document.getElementById('prixRangeArticles');
            const prixRangeLabel = document.getElementById('prixRangeArticlesLabel');
            const filterCheckboxes = document.querySelectorAll('.filter-checkbox');

            function applyFilters() {
                const maxPrice = parseInt(prixRangeInput.value);
                
                const activeFilters = {};
                filterCheckboxes.forEach(cb => {
                    if (cb.checked) {
                        const group = cb.dataset.filterGroup;
                        if (!activeFilters[group]) activeFilters[group] = [];
                        activeFilters[group].push(cb.dataset.filterValue);
                    }
                });

                const filteredArticles = allArticlesData.filter(article => {
                    let matchesPrice = isNaN(article.price) ? (maxPrice === 50000) : (article.price <= maxPrice); 
                    if (article.price === 0 && article.priceText === 'Variable') matchesPrice = true; 
                    
                    let matchesAllGroups = true;
                    for (const group in activeFilters) {
                        if (activeFilters[group].length > 0) { 
                            if (group === 'type') {
                                if (!activeFilters[group].some(val => article.type.includes(val))) {
                                    matchesAllGroups = false; break;
                                }
                            } else if (group === 'usage') {
                                 if (!activeFilters[group].some(val => article.usage.includes(val))) {
                                    matchesAllGroups = false; break;
                                }
                            } else if (group === 'weightClass') {
                                if (!activeFilters[group].includes(article.weightClass)) {
                                    matchesAllGroups = false; break;
                                }
                            }
                        }
                    }
                    return matchesPrice && matchesAllGroups;
                });
                renderFilteredArticles(filteredArticles);
            }

            if (prixRangeInput && prixRangeLabel) {
                prixRangeInput.addEventListener('input', function() {
                    prixRangeLabel.textContent = this.value;
                    applyFilters();
                });
            }
            filterCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', applyFilters);
            });

            // Initialisation
            initializeArticlesMasterData();
            renderFilteredArticles(allArticlesData); 
            showStep(1); 
        });
    </script>
</body>
</html>