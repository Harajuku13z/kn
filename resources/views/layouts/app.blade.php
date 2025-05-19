<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'KLIN KLIN') }} - @yield('title', 'Service de Blanchisserie Éco-Responsable et Pratique')</title>
    
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- NOTE: Les styles ci-dessous peuvent remplacer ou compléter ceux de style.css --}}
    
    <style>
        :root { /* Définition globale des variables de la charte */
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
            --klin-cta-bg: #3F1666; /* Nouvelle variable pour le fond CTA demandé */

            --klin-primary: var(--klin-violet-fonce);
            --klin-accent: var(--klin-cyan-fluo);
            --klin-light-background: var(--klin-violet-attenue);
            --klin-dark-text-color: var(--klin-pms-2766-dark-blue);
            --klin-light-text-color: var(--klin-blanc);

            --klin-primary-rgb: 70, 24, 113;
            --klin-accent-rgb: 141, 255, 200;
            --klin-violet-attenue-rgb: 198, 183, 211;
            --klin-light-text-color-rgb: 255, 255, 255;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--klin-blanc);
            color: var(--klin-dark-text-color);
        }

        /* Styles Navbar (box-shadow retirée) */
        .navbar {
            background-color: var(--klin-blanc);
            padding: 20px 0;
        }
        .nav-link {
            color: var(--klin-pms-2766-dark-blue, #1F0B33) !important;
            font-size: 15px;
            font-weight: 500;
            margin-left: 10px;
            margin-right: 10px;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--klin-primary) !important;
        }
        .btn-signup {
            background-color: var(--klin-primary);
            color: var(--klin-light-text-color);
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-signup:hover {
            background-color: var(--klin-violet-structure);
            color: var(--klin-light-text-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(70, 24, 113, 0.2);
        }
        .btn-signup .fas.fa-bars {
            font-size: 16px;
            color: white;
            margin-left: 0;
        }
        .dropdown-menu {
            border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 10px; padding: 10px;
        }
        .dropdown-item {
            padding: 8px 15px; border-radius: 5px; transition: all 0.2s ease;
            color: var(--klin-dark-text-color);
        }
        .dropdown-item:hover {
            background-color: var(--klin-light-background); color: var(--klin-primary);
        }

        /* Styles Page Header */
        .page-header {
            background: linear-gradient(rgba(var(--klin-primary-rgb), 0.88), rgba(var(--klin-primary-rgb), 0.92)), url('{{ asset("img/laundry_background_hero.jpg") }}') no-repeat center center;
            background-size: cover; color: var(--klin-light-text-color); padding: 80px 0;
        }
        .page-header h1, .page-header p.lead { color: var(--klin-light-text-color); }

        /* Boutons principaux et secondaires */
        .btn-custom-primary {
            background-color: var(--klin-accent); border-color: var(--klin-accent);
            color: var(--klin-primary); font-weight: bold; padding: 0.85rem 1.85rem;
            transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px;
            border-radius: 30px;
        }
        .btn-custom-primary:hover, .btn-custom-primary:focus {
            background-color: #7ADCC0; border-color: #7ADCC0; color: var(--klin-primary);
            box-shadow: 0 0.5rem 1.5rem rgba(var(--klin-accent-rgb), 0.4); transform: translateY(-3px);
        }
        .pulse-hover:hover { animation: pulse 1.5s infinite; }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(var(--klin-accent-rgb), 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(var(--klin-accent-rgb), 0); }
            100% { box-shadow: 0 0 0 0 rgba(var(--klin-accent-rgb), 0); }
        }
        .btn-outline-custom-primary {
            color: var(--klin-primary); border-color: var(--klin-primary);
            font-weight: 600; padding: 0.7rem 1.4rem; transition: all 0.3s ease;
            border-radius: 30px;
        }
        .btn-outline-custom-primary:hover, .btn-outline-custom-primary:focus {
            background-color: var(--klin-primary); color: var(--klin-light-text-color);
            border-color: var(--klin-primary); transform: translateY(-2px);
            box-shadow: 0 0.3rem 0.8rem rgba(var(--klin-primary-rgb),0.2);
        }

        /* Styles de Section */
        .section-title {
            font-size: 2.6rem; color: var(--klin-primary); position: relative;
            padding-bottom: 1rem; margin-bottom: 1.5rem; font-weight: 700;
        }
        .section-title.text-start::before { left: 0; transform: translateX(0); }
        .section-title::before {
            content: ""; position: absolute; bottom: 0; left: 50%;
            width: 80px; height: 4px; background-color: var(--klin-accent);
            transform: translateX(-50%); border-radius: 2px;
        }
        .section-description {
            color: #495057; font-size: 1.15rem; max-width: 750px;
            margin-left: auto; margin-right: auto; margin-bottom: 40px;
        }
        .bg-klin-violet-attenue { background-color: var(--klin-light-background); }
        .text-klin-cyan { color: var(--klin-accent); }

        /* How it works / Step cards */
        .section-how-it-works { background-color: var(--klin-blanc); }
        .step-card {
            background-color: var(--klin-blanc); 
            border: 1px solid var(--klin-light-background);
            border-radius: 12px; 
            box-shadow: none;
            transition: transform 0.3s ease;
        }
        .step-card:hover { 
            transform: translateY(-8px); 
            box-shadow: none; 
        }
        .step-icon {
            width: 70px; height: 70px; border-radius: 50%;
            background-color: var(--klin-primary); color: var(--klin-accent);
            display: flex; align-items: center; justify-content: center;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .step-card:hover .step-icon { background-color: var(--klin-accent); color: var(--klin-primary); }
        .step-title { color: var(--klin-primary); font-weight: 700; margin-top: 0.5rem; }

        /* Eco Section */
        .section-eco .eco-list li { font-size: 1.1rem; margin-bottom: 0.8rem; display: flex; align-items: center; }
        .section-eco .eco-list i { color: var(--klin-accent); font-size: 1.5rem; width: 30px; }
        .section-eco img { border: 5px solid var(--klin-accent); }

        /* Services Section */
        .service-card { transition: transform 0.3s ease; }
        .service-card:hover { transform: translateY(-8px); box-shadow: none; }
        .service-card .card-img-top { width: 100%; aspect-ratio: 1 / 1; object-fit: cover; }
        .service-card .card-title { color: var(--klin-primary); font-weight: 600; }
        .service-card .card-title i { vertical-align: middle; }

        /* Why Us Section / Advantage Cards */
        .advantage-card {
            background-color: var(--klin-blanc); 
            border-radius: 8px;
            box-shadow: none;
            transition: transform 0.3s ease;
        }
        .advantage-card:hover { transform: translateY(-5px); box-shadow: none; }
        .advantage-icon {
            width: 60px; height: 60px; border-radius: 50%;
            background-color: var(--klin-primary); color: var(--klin-accent);
            display: flex; align-items: center; justify-content: center;
        }
        .advantage-title { color: var(--klin-primary); font-weight: 600; }

        /* FAQ Section */
        .section-faq .accordion-item {
            margin-bottom: 1rem; border: 1px solid var(--klin-light-background);
            border-radius: 0.5rem; overflow: hidden;
        }
        .section-faq .accordion-button {
            font-weight: 600; color: var(--klin-primary);
            background-color: var(--klin-light-background);
        }
        .section-faq .accordion-button:not(.collapsed) {
            background-color: var(--klin-accent); color: var(--klin-primary);
            box-shadow: inset 0 -1px 0 rgba(var(--klin-primary-rgb),.125);
        }
        .section-faq .accordion-button:focus { box-shadow: 0 0 0 0.25rem rgba(var(--klin-accent-rgb), 0.5); border-color: var(--klin-accent); }
        .section-faq .accordion-button::after { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23461871'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e"); }
        .section-faq .accordion-button:not(.collapsed)::after { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23461871'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e"); }
        .section-faq .accordion-body { background-color: var(--klin-blanc); padding: 1.25rem; }

        /* Styles globaux de l'utilisateur pour .social-links */
        .social-links {
            display: flex;
            gap: 15px;
        }

        /* Section CTA (Styles MODIFIÉS comme demandé) */
        .cta-section {
            padding: 80px 0;
            background-color: var(--klin-cta-bg); /* Fond violet foncé #3F1666 */
        }
        .cta-section2 {
            padding: 80px 0;
            background-color: var(--klin-cta-bg); /* Fond violet foncé #3F1666 */
        }
        .cta-title {
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--klin-light-text-color); /* Texte en blanc */
            margin-bottom: 20px;
            line-height: 1.3;
        }
        .cta-description {
            font-size: 1.15rem;
            color: rgba(var(--klin-light-text-color-rgb), 0.9); /* Texte en blanc légèrement transparent pour douceur */
            margin-bottom: 30px;
        }
        .cta-buttons .btn-mint, .cta-buttons .btn-light-mint {
            padding: 12px 28px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 30px;
            margin-right: 15px;
            margin-bottom: 10px;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .cta-buttons .btn-mint { /* Bouton principal CTA */
            background-color: var(--klin-accent); /* Fond cyan fluo */
            color: var(--klin-primary); /* Texte violet foncé */
        }
        .cta-buttons .btn-mint:hover {
            background-color: #7ADCC0; /* Cyan un peu plus foncé/désaturé */
            color: var(--klin-primary);
            transform: translateY(-2px);
        }
        .cta-buttons .btn-light-mint { /* Bouton secondaire CTA */
            background-color: var(--klin-blanc); /* Fond blanc */
            color: var(--klin-cta-bg); /* Texte couleur du fond CTA (violet foncé) */
            border: 1px solid rgba(var(--klin-light-text-color-rgb), 0.3); /* Bordure blanche subtile */
        }
        .cta-buttons .btn-light-mint:hover {
            background-color: rgba(var(--klin-light-text-color-rgb), 0.9); /* Fond blanc légèrement transparent */
            color: var(--klin-cta-bg);
            border-color: var(--klin-blanc);
            transform: translateY(-2px);
        }

        /* === FOOTER STYLES KLINKLIN === */
        footer.klinklin-footer {
            background-color: var(--klin-violet-intense);
            color: var(--klin-violet-attenue);
            padding: 60px 0 0;
            font-size: 0.9rem;
        }
        .klinklin-footer .footer-logo { max-width: 130px; margin-bottom: 1rem; }
        .klinklin-footer .footer-description { font-size: 0.85rem; line-height: 1.6; }
        .klinklin-footer h4 {
            color: var(--klin-accent); font-size: 1.1rem; margin-bottom: 25px;
            font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;
            position: relative; padding-bottom: 10px;
        }
        .klinklin-footer h4::after {
            content: ''; position: absolute; left: 0; bottom: 0;
            width: 30px; height: 2px; background-color: var(--klin-primary);
        }
        .klinklin-footer .footer-menu { list-style: none; padding-left: 0; }
        .klinklin-footer .footer-menu li { margin-bottom: 12px; }
        .klinklin-footer .footer-menu li a {
            color: var(--klin-violet-attenue); text-decoration: none;
            transition: color 0.3s ease, padding-left 0.3s ease;
        }
        .klinklin-footer .footer-menu li a:hover { color: var(--klin-accent); padding-left: 8px; }
        .klinklin-footer .social-links { margin-top: 1.5rem; }
        .klinklin-footer .social-links a {
            display: inline-flex; align-items: center; justify-content: center;
            width: 38px; height: 38px; border-radius: 50%;
            background-color: var(--klin-primary); color: var(--klin-accent);
            margin-bottom: 8px; font-size: 0.9rem; text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease, transform 0.3s ease;
        }
        .klinklin-footer .social-links a:hover {
            background-color: var(--klin-accent); color: var(--klin-primary);
            transform: translateY(-2px);
        }
        .klinklin-footer .contact-info p {
            margin-bottom: 12px; display: flex; align-items: flex-start;
            line-height: 1.6; color: var(--klin-violet-attenue);
        }
        .klinklin-footer .contact-info p i {
            color: var(--klin-accent); margin-right: 12px; width: 18px;
            flex-shrink: 0; margin-top: 4px;
        }
        .klinklin-footer .contact-info p span { flex-grow: 1; }
        .klinklin-footer .footer-bottom {
            border-top: 1px solid var(--klin-violet-structure);
            color: var(--klin-violet-attenue); padding: 25px 0;
            margin-top: 40px; font-size: 0.8rem;
        }
        .klinklin-footer .footer-bottom p { margin-bottom: 0; }
        .klinklin-footer .footer-bottom .fa-heart { color: var(--klin-accent); animation: pulseHeart 1.5s infinite; }
        @keyframes pulseHeart { 0% { transform: scale(1); } 50% { transform: scale(1.15); } 100% { transform: scale(1); } }

        /* Media Queries */
        @media (max-width: 767.98px) {
            .klinklin-footer .footer-logo {
                display: block; margin-left: auto; margin-right: auto;
            }
            .klinklin-footer .social-links {
                justify-content: center !important;
            }
            .klinklin-footer .col-lg-4.col-md-6,
            .klinklin-footer .col-lg-2.col-md-6.col-sm-6,
            .klinklin-footer .col-lg-3.col-md-6.col-sm-6,
            .klinklin-footer .col-lg-3.col-md-6 {
                text-align: center;
            }
            .klinklin-footer h4::after {
                left: 50%; transform: translateX(-50%);
            }
            .klinklin-footer .contact-info p {
                justify-content: center;
            }
            .klinklin-footer .contact-info p i { margin-right: 8px; }

            .cta-section { padding: 60px 0; } /* Moins de padding sur mobile pour CTA */
            .cta-title { font-size: 2.2rem; }
            .cta-buttons a { display: block; margin-right: 0; margin-left: auto; margin-right: auto; max-width: 300px; } /* Boutons CTA en pleine largeur et centrés sur mobile */
            .cta-buttons a:last-child { margin-top: 15px; }
        }

        /* Style pour la flèche du dropdown */
        .dropdown-toggle::after {
            color: white;
            border-top: 0.3em solid white;
            margin-left: 0.5em;
        }
        
        /* Style spécifique pour le dropdown de profil */
        .nav-item.dropdown .dropdown-toggle {
            padding-right: 30px; /* Plus d'espace pour la flèche */
        }
        
        .nav-item.dropdown .dropdown-toggle::after {
            display: none; /* Masquer la flèche */
        }
        
        /* Styles pour la photo de profil */
        .profile-pic-link {
            display: block;
            text-decoration: none;
        }
        
        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--klin-primary);
            background-color: var(--klin-primary);
        }
        
        .profile-pic-initial {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--klin-primary);
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
            border: 2px solid var(--klin-primary);
        }
        
        /* Style pour le bouton du menu burger */
        .navbar-toggler {
            border: none;
            padding: 10px;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
            outline: none;
        }
        
        .navbar-toggler-icon {
            width: 24px;
            height: 24px;
        }
        
        .custom-toggler {
            background-color: var(--klin-primary);
            border-radius: 8px;
            padding: 12px;
            margin-right: 10px;
        }
        
        .custom-toggler .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* Style pour le bouton de largeur fixe */
        .fixed-width-btn {
            width: 150px !important;
            justify-content: center;
            padding: 10px 15px;
            white-space: nowrap;
            overflow: hidden;
            text-align: center;
            display: flex;
            align-items: center;
        }
        
        .button-content {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
        
        .button-content .fa-bars {
            margin-left: 10px;
        }
        
        .button-content span {
            text-align: center;
        }
        
        /* Style pour l'icône de notification */
        .notification-icon {
            position: relative;
            display: inline-block;
            color: var(--klin-primary);
            font-size: 22px;
            margin-top: 5px;
            text-decoration: none;
            padding-right: 10px;
            outline: none !important;
        }
        
        .notification-icon:hover {
            color: var(--klin-violet-structure);
        }
        
        .notification-icon:focus {
            outline: none !important;
            box-shadow: none !important;
        }
        
        /* Mobile header controls container */
        .mobile-header-controls {
            display: flex;
            align-items: center;
        }
        
        @media (max-width: 991px) {
            .mobile-header-controls {
                margin-right: 10px;
            }
            
            .notification-icon {
                margin-right: 15px;
            }
        }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #ff3860;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 11px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }
        
        /* Style pour le dropdown de notifications */
        .notification-dropdown {
            width: 320px;
            padding: 10px;
            max-height: 400px;
            overflow-y: auto;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        
        .notification-dropdown .dropdown-header {
            color: var(--klin-primary);
            padding: 8px 15px;
            font-size: 14px;
            font-weight: bold;
            background-color: transparent;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 5px;
        }
        
        .notification-item {
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.2s ease;
            border-bottom: none;
            white-space: normal;
        }
        
        .notification-item.unread {
            background-color: rgba(var(--klin-primary-rgb), 0.05);
        }
        
        .notification-item:hover {
            background-color: var(--klin-light-background);
            color: var(--klin-primary);
        }
        
        .notification-content {
            margin-left: 5px;
        }
        
        .notification-text {
            margin-bottom: 3px;
            font-size: 13px;
            color: var(--klin-dark-text-color);
        }
        
        .notification-time {
            color: #999;
            font-size: 11px;
        }
        
        .view-all {
            color: var(--klin-primary);
            font-weight: 600;
            font-size: 13px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        
        .view-all:hover {
            background-color: var(--klin-light-background);
        }
        
        .notification-dropdown .dropdown-divider {
            margin: 5px 0;
        }

        /* Styles responsifs pour mobile */
        @media (max-width: 767.98px) {
            /* Affichage des icônes de notification et menu en mobile */
            .navbar-nav {
                flex-direction: row;
                justify-content: flex-end;
                align-items: center;
            }
            
            .navbar-nav .nav-item.dropdown {
                display: inline-block;
                margin-left: 5px;
            }
            
            /* Ajustement des espaces pour les sections en mobile */
            .how-it-works .col-md-5 {
                margin-top: 30px;
            }
            
            .hero-section .col-md-6:last-child {
                margin-top: 30px;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('img/logo.png') }}" alt="KLIN KLIN" style="width: 85px; height: 113px;">
            </a>
            <div class="d-flex d-lg-none mobile-header-controls">
                @auth
                <a href="#" class="notification-icon dropdown-toggle" id="notificationDropdownMobile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    @php
                        $unreadCount = Auth::user()->unreadNotifications->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="notification-badge">{{ $unreadCount }}</span>
                    @endif
                </a>
                <ul class="dropdown-menu notification-dropdown dropdown-menu-end" aria-labelledby="notificationDropdownMobile">
                    <li>
                        <h6 class="dropdown-header">Notifications</h6>
                    </li>
                    @if(Auth::user()->notifications->count() > 0)
                        @foreach(Auth::user()->notifications->take(5) as $notification)
                            <li>
                                <a class="dropdown-item notification-item {{ $notification->read_at ? 'read' : 'unread' }}" href="{{ route('notifications.read', $notification->id) }}">
                                    <div class="notification-content">
                                        <p class="notification-text fw-semibold">{{ $notification->data['title'] ?? 'Mise à jour de commande' }}</p>
                                        <p class="notification-text">{{ $notification->data['message'] ?? 'Nouvelle notification' }}</p>
                                        <small class="notification-time">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-center view-all" href="{{ route('notifications.index') }}">
                                Voir toutes les notifications
                            </a>
                        </li>
                    @else
                        <li>
                            <span class="dropdown-item">Aucune notification</span>
                        </li>
                    @endif
                </ul>
                @endauth
                <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('simulateur') }}">Simuler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('')}}#howItWorks">Comment ça marche ?</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link " href="{{ url('/services')}}" role="button" >
                            Nos Services
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('abonnements') }}">Abonnements</a>
                    </li>
                    
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                        </li>
                        <li class="nav-item ms-3">
                            <a class="btn btn-signup" href="{{ route('register') }}">Créez un compte</a>
                        </li>
                    @else
                        <li class="nav-item dropdown d-none d-lg-block">
                            <a href="#" class="notification-icon dropdown-toggle" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding-right: 10px; margin-right: -5px;">
                                <i class="fas fa-bell"></i>
                                @php
                                    $unreadCount = Auth::user()->unreadNotifications->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="notification-badge">{{ $unreadCount }}</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu notification-dropdown dropdown-menu-end" aria-labelledby="notificationDropdown">
                                <li>
                                    <h6 class="dropdown-header">Notifications</h6>
                                </li>
                                @if(Auth::user()->notifications->count() > 0)
                                    @foreach(Auth::user()->notifications->take(5) as $notification)
                                        <li>
                                            <a class="dropdown-item notification-item {{ $notification->read_at ? 'read' : 'unread' }}" href="{{ route('notifications.read', $notification->id) }}">
                                                <div class="notification-content">
                                                    <p class="notification-text fw-semibold">{{ $notification->data['title'] ?? 'Mise à jour de commande' }}</p>
                                                    <p class="notification-text">{{ $notification->data['message'] ?? 'Nouvelle notification' }}</p>
                                                    <small class="notification-time">{{ $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-center view-all" href="{{ route('notifications.index') }}">
                                            Voir toutes les notifications
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <span class="dropdown-item">Aucune notification</span>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link btn-signup dropdown-toggle fixed-width-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="button-content">
                                    <span class="text-truncate" style="color: white; max-width: 95px;">Dashboard</span>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Tableau de bord</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Mon profil</a></li>
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}">Mes commandes</a></li>
                                <li><a class="dropdown-item" href="{{ route('subscriptions.index') }}">Mes abonnements</a></li>
                                @if(Auth::user()->is_admin)
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-lock-fill me-2"></i>Administration</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')
    {{-- Si ce fichier est un layout (ex: app.blade.php), le contenu de la page actuelle sera injecté ici.
         Si vous voulez voir le contenu spécifique que nous avons conçu (Hero, Comment ça marche, etc.),
         vous devez créer une vue Blade qui @extends ce layout et définit une @section('content')
         avec ce HTML. Par exemple, dans resources/views/welcome.blade.php ou une autre vue.
    --}}

 <section class="cta-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="cta-title">Prêt à dire adieu à la<br>corvée de linge ?</h2>
                    <p class="cta-description">Confiez-nous votre linge et retrouvez du temps pour ce qui compte vraiment.</p>
                    <div class="cta-buttons">
                        <a href="{{ route('orders.create') }}" class="btn-mint">Planifier ma collecte</a>
                        <a href="{{ route('dashboard') }}" class="btn-light-mint">Accéder à mon espace</a>
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-end"> <img src="{{ asset('img/ln.png') }}" alt="Service illustration" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <footer class="klinklin-footer">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6">
                    <img src="{{ asset('img/logo.png') }}" alt="KLIN KLIN" class="footer-logo">
                    <p class="footer-description">KLIN KLIN est une application de blanchisserie à la demande offrant un service rapide et pratique avec des tarifs compétitifs pour simplifier votre quotidien.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <h4>Services</h4>
                    <ul class="footer-menu">
                        <li><a href="{{ route('services.lavage') }}">Lavage</a></li>
                        <li><a href="{{ route('services.repassage') }}">Repassage</a></li>
                        <li><a href="{{ route('services.pressing') }}">Pressing</a></li>
                        <li><a href="{{ route('abonnements') }}">Abonnements</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <h4>Aide & Infos</h4>
                    <ul class="footer-menu">
                        <li><a href="{{ route('aide.faq') }}">FAQ</a></li>
                        <li><a href="{{ route('aide.conditions-generales') }}">Conditions générales</a></li>
                        <li><a href="{{ route('aide.politique-confidentialite') }}">Politique de confidentialité</a></li>
                        <li><a href="{{ route('contact') }}">Contactez-nous</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4>Nous Contacter</h4>
                    <div class="contact-info">
                        <p><i class="fas fa-map-marker-alt"></i><span>1184 rue Louassi, Business Center Plateau, Brazzaville - Congo</span></p>
                        <p><i class="fas fa-phone"></i><span>+242 069349160</span></p>
                        <p><i class="fas fa-envelope"></i><span>contact@ezaklinklin.com</span></p>
                        <p><i class="fas fa-clock"></i><span>Lun-Sam: 8h - 18h</span></p>
                    </div>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <p>&copy; {{ date('Y') }} KLIN KLIN. Tous droits réservés. Fait avec <i class="fas fa-heart"></i> au Congo.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="{{ asset('js/main.js') }}"></script>
    
    @yield('scripts')
</body>
</html> 