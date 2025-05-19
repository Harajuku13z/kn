@extends('layouts.app')

@section('title', 'Contactez-nous - KLIN KLIN')

@section('styles')
{{-- Intégration de Montserrat depuis Google Fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* Palette KLIN KLIN & Variables Générales */
    :root {
        --klin-violet-fonce: #461871;
        --klin-cyan-fluo: #8DFFC8;
        --klin-violet-presque-noir: #14081c;
        --klin-violet-intense: #684289;
        --klin-violet-attenue: #c6b7d3;
        --klin-blanc: #FFFFFF;
        --klin-gris-clair-fond: #f7f8fc;
        --klin-bordure-claire: #e3e6f0;
        --klin-ombre-tres-subtile: rgba(70, 24, 113, 0.05);
        --klin-ombre-subtile: rgba(70, 24, 113, 0.08);
        --klin-ombre-moyenne: rgba(70, 24, 113, 0.12);

        --font-family-klinklin: 'Montserrat', sans-serif;
        --transition-smooth: all 0.3s ease-in-out;

        /* Variables pour l'accordéon */
        --contact-faq-accordion-bg: var(--klin-blanc);
        --contact-faq-accordion-active-bg: var(--klin-violet-fonce);
        --contact-faq-accordion-active-color: var(--klin-blanc);
        --contact-faq-accordion-btn-color: var(--klin-violet-presque-noir);
        --contact-faq-accordion-btn-focus-box-shadow: 0 0 0 0.25rem rgba(141, 255, 200, 0.4);
        --contact-faq-accordion-border-color: var(--klin-bordure-claire);
        --contact-faq-accordion-border-radius: 0.5rem;
    }

    /* Conteneur principal pour la page Contact afin d'isoler les styles */
    .klinklin-contact-page {
        font-family: var(--font-family-klinklin);
        /* IMPORTANT : Aucune propriété 'color' ou 'background-color' n'est définie ici 
           pour éviter d'affecter le footer ou d'autres éléments globaux.
           Chaque section interne gère son propre fond et ses couleurs de texte.
        */
    }

    /* Définition des couleurs de texte par défaut pour les éléments DANS .klinklin-contact-page */
    .klinklin-contact-page p,
    .klinklin-contact-page label,
    .klinklin-contact-page .accordion-body, /* Pour le corps de l'accordéon FAQ */
    .klinklin-contact-page .section-description-klinklin { /* Pour les descriptions de section */
        color: var(--klin-violet-presque-noir);
    }

    .klinklin-contact-page h1, 
    .klinklin-contact-page h2, 
    .klinklin-contact-page h3, 
    .klinklin-contact-page h4, 
    .klinklin-contact-page h5, 
    .klinklin-contact-page h6,
    .klinklin-contact-page .accordion-button { /* Pour les boutons d'accordéon non actifs */
        color: var(--klin-violet-fonce); /* Couleur par défaut des titres et boutons d'accordéon */
    }
    /* Les exceptions (texte blanc sur fond violet, etc.) seront gérées par des règles plus spécifiques ci-dessous. */


    /* Page Header KLIN KLIN */
    .klinklin-contact-page .page-header-contact-klinklin {
        background: linear-gradient(135deg, var(--klin-violet-fonce) 0%, var(--klin-violet-intense) 100%);
        color: var(--klin-blanc); /* Couleur de texte spécifique pour ce header */
        padding: 70px 20px;
        text-align: center;
        border-bottom: 5px solid var(--klin-cyan-fluo);
        position: relative;
        overflow: hidden;
    }
    .klinklin-contact-page .page-header-contact-klinklin::before,
    .klinklin-contact-page .page-header-contact-klinklin::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        opacity: 0.1;
        background-color: var(--klin-cyan-fluo);
        z-index: 0;
    }
    .klinklin-contact-page .page-header-contact-klinklin::before {
        width: 200px;
        height: 200px;
        top: -50px;
        left: -50px;
    }
    .klinklin-contact-page .page-header-contact-klinklin::after {
        width: 300px;
        height: 300px;
        bottom: -100px;
        right: -100px;
        background-color: var(--klin-violet-intense);
    }
    .klinklin-contact-page .page-header-contact-klinklin .container {
        position: relative;
        z-index: 1;
    }
    .klinklin-contact-page .page-header-contact-klinklin h1 {
        font-weight: 800;
        font-size: 2.8rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        color: var(--klin-blanc); /* Couleur texte H1 spécifique pour ce header */
    }
    .klinklin-contact-page .page-header-contact-klinklin h1 i {
        color: var(--klin-cyan-fluo);
        font-size: 2.6rem;
        text-shadow: 0 0 10px rgba(141, 255, 200, 0.3);
    }
    .klinklin-contact-page .page-header-contact-klinklin p {
        font-weight: 400;
        font-size: 1.15rem;
        color: var(--klin-violet-attenue); /* Couleur texte P spécifique pour ce header */
        margin-bottom: 0;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Titres de section généraux */
    .klinklin-contact-page .section-title-klinklin {
        /* color est déjà défini via .klinklin-contact-page h2 */
        font-weight: 700;
        font-size: 2.1rem;
        margin-bottom: 0.75rem;
        position: relative;
        padding-bottom: 12px;
    }
    .klinklin-contact-page .section-title-klinklin::after {
        content: '';
        display: block;
        width: 50px;
        height: 3px;
        background-color: var(--klin-cyan-fluo);
        margin: 12px auto 0;
        border-radius: 2px;
    }
    .klinklin-contact-page .section-title-klinklin.text-start::after {
        margin-left: 0;
    }
    .klinklin-contact-page .section-description-klinklin {
        /* color est déjà défini via .klinklin-contact-page p */
        font-size: 1rem;
        line-height: 1.65;
        margin-bottom: 2.5rem;
    }

    /* Section Contact principale (formulaire et infos) */
    .klinklin-contact-page .contact-main-section {
        background-color: var(--klin-blanc); /* Fond explicite pour cette section */
        padding-top: 60px;
        padding-bottom: 60px;
    }

    /* Formulaire de contact */
    .klinklin-contact-page .contact-form-klinklin .form-label {
        font-weight: 600;
        color: var(--klin-violet-intense); /* Couleur label spécifique */
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    .klinklin-contact-page .contact-form-klinklin .form-control {
        border: 1px solid var(--klin-bordure-claire);
        border-radius: 6px;
        padding: 0.9rem 1.2rem;
        font-family: var(--font-family-klinklin);
        font-size: 0.95rem;
        transition: var(--transition-smooth);
        background-color: var(--klin-blanc);
        box-shadow: var(--klin-ombre-tres-subtile);
        color: var(--klin-violet-presque-noir); /* Couleur du texte dans les inputs */
    }
    .klinklin-contact-page .contact-form-klinklin .form-control::placeholder {
        color: var(--klin-violet-attenue);
        opacity: 1;
    }
    .klinklin-contact-page .contact-form-klinklin .form-control:focus {
        border-color: var(--klin-cyan-fluo);
        box-shadow: 0 0 0 0.2rem rgba(141, 255, 200, 0.3), var(--klin-ombre-subtile);
        background-color: var(--klin-blanc);
    }
    .klinklin-contact-page .contact-form-klinklin .btn-submit-klinklin {
        background: var(--klin-cyan-fluo);
        color: var(--klin-violet-fonce);
        border: none;
        padding: 0.9rem 2.2rem;
        font-weight: 700;
        font-size: 0.95rem;
        border-radius: 50px;
        transition: var(--transition-smooth);
        text-transform: uppercase;
        letter-spacing: 0.75px;
        box-shadow: 0 4px 15px rgba(141, 255, 200, 0.4);
    }
    .klinklin-contact-page .contact-form-klinklin .btn-submit-klinklin:hover,
    .klinklin-contact-page .contact-form-klinklin .btn-submit-klinklin:focus {
        background: #63e0b3;
        color: var(--klin-violet-fonce);
        transform: translateY(-2px) scale(1.01);
        box-shadow: 0 7px 20px rgba(141, 255, 200, 0.5);
    }

    /* Informations de contact */
    .klinklin-contact-page .contact-info-card-klinklin {
        background-color: var(--klin-blanc);
        border-radius: 10px;
        padding: 35px;
        box-shadow: 0 10px 30px var(--klin-ombre-subtile);
        height: 100%;
    }
    .klinklin-contact-page .contact-info-item-klinklin {
        display: flex;
        align-items: center;
        margin-bottom: 28px;
    }
    .klinklin-contact-page .contact-info-item-klinklin:last-of-type {
        margin-bottom: 0;
    }
    .klinklin-contact-page .contact-info-item-klinklin i.fas {
        color: var(--klin-blanc);
        background: linear-gradient(135deg, var(--klin-violet-fonce) 0%, var(--klin-violet-intense) 100%);
        font-size: 1.1rem;
        margin-right: 18px;
        width: 42px;
        height: 42px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 3px 8px var(--klin-ombre-subtile);
        transition: var(--transition-smooth);
    }
    .klinklin-contact-page .contact-info-item-klinklin:hover i.fas {
        transform: scale(1.1) rotate(-5deg);
        background: linear-gradient(135deg, var(--klin-cyan-fluo) 0%, #63e0b3 100%);
        color: var(--klin-violet-fonce);
    }
    .klinklin-contact-page .contact-info-item-klinklin div h4 {
        font-size: 1.1rem;
        margin-bottom: 0.3rem;
        color: var(--klin-violet-fonce); /* Couleur titre H4 spécifique */
        font-weight: 600;
    }
    .klinklin-contact-page .contact-info-item-klinklin div p,
    .klinklin-contact-page .contact-info-item-klinklin div p a {
        color: var(--klin-violet-presque-noir); /* Couleur texte P spécifique */
        margin-bottom: 0;
        font-size: 0.9rem;
        line-height: 1.55;
        text-decoration: none;
        transition: var(--transition-smooth);
    }
     .klinklin-contact-page .contact-info-item-klinklin div p a:hover {
        color: var(--klin-cyan-fluo);
     }

    /* Liens réseaux sociaux */
    .klinklin-contact-page .social-links-klinklin {
         border-top: 1px solid var(--klin-bordure-claire);
         margin-top: 30px;
         padding-top: 30px;
    }
    .klinklin-contact-page .social-links-klinklin h4 {
        font-size: 1.1rem;
        color: var(--klin-violet-fonce); /* Couleur titre H4 spécifique */
        font-weight: 600;
        margin-bottom: 1rem;
    }
    .klinklin-contact-page .social-links-klinklin .social-link-klinklin {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background-color: transparent;
        color: var(--klin-violet-attenue);
        border: 2px solid var(--klin-violet-attenue);
        border-radius: 50%;
        transition: var(--transition-smooth);
        font-size: 1.1rem;
        text-decoration: none !important;
    }
    .klinklin-contact-page .social-links-klinklin .social-link-klinklin:hover {
        background-color: var(--klin-cyan-fluo);
        color: var(--klin-violet-fonce);
        border-color: var(--klin-cyan-fluo);
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 4px 10px var(--klin-ombre-subtile);
    }
    .klinklin-contact-page .social-links-klinklin .social-link-klinklin i.fab {
        text-decoration: none;
    }

    /* Section Carte */
    .klinklin-contact-page .map-section-klinklin {
        background-color: var(--klin-gris-clair-fond); /* Fond explicite pour cette section */
        padding: 60px 0;
    }
    .klinklin-contact-page .map-container-klinklin {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 12px 35px var(--klin-ombre-moyenne);
    }
    .klinklin-contact-page .map-container-klinklin iframe {
        display: block;
    }

    /* Section FAQ de Contact */
    .klinklin-contact-page .contact-faq-section-klinklin {
         background-color: var(--klin-blanc); /* Fond explicite pour cette section */
         padding: 60px 0;
    }
    .klinklin-contact-page .contact-faq-section-klinklin .accordion-item {
        background-color: var(--contact-faq-accordion-bg);
        border: 1px solid var(--contact-faq-accordion-border-color);
        border-radius: var(--contact-faq-accordion-border-radius) !important;
        margin-bottom: 1rem !important;
        box-shadow: 0 4px 10px var(--klin-ombre-tres-subtile);
        overflow: hidden;
        transition: var(--transition-smooth);
    }
    .klinklin-contact-page .contact-faq-section-klinklin .accordion-item:hover {
        box-shadow: 0 6px 15px var(--klin-ombre-subtile);
        transform: translateY(-2px);
    }
    .klinklin-contact-page .contact-faq-section-klinklin .accordion-button {
        font-family: var(--font-family-klinklin);
        font-weight: 600;
        font-size: 1.05rem;
        color: var(--contact-faq-accordion-btn-color); /* Couleur spécifique pour bouton accordéon */
        background-color: var(--klin-blanc);
        padding: 1.2rem 1.4rem;
        border: none;
        box-shadow: none !important;
        text-align: left;
        width: 100%;
        display: flex;
        align-items: center;
        transition: background-color 0.2s ease-out;
    }
     .klinklin-contact-page .contact-faq-section-klinklin .accordion-button:hover {
        background-color: var(--klin-gris-clair-fond);
     }
    .klinklin-contact-page .contact-faq-section-klinklin .accordion-button:focus {
        z-index: 3;
        border-color: transparent;
        outline: 0;
        box-shadow: var(--contact-faq-accordion-btn-focus-box-shadow) !important;
    }
    .klinklin-contact-page .contact-faq-section-klinklin .accordion-button:not(.collapsed) {
        color: var(--contact-faq-accordion-active-color); /* Couleur texte spécifique actif */
        background-color: var(--contact-faq-accordion-active-bg);
    }
    .klinklin-contact-page .contact-faq-section-klinklin .accordion-button i.fas {
        color: var(--klin-violet-fonce); /* Couleur icône spécifique */
        margin-right: 0.9rem;
        font-size: 1.15em;
        transition: color 0.15s ease-in-out;
        width: 22px;
        text-align: center;
    }
    .klinklin-contact-page .contact-faq-section-klinklin .accordion-button:not(.collapsed) i.fas {
        color: var(--klin-cyan-fluo); /* Couleur icône spécifique actif */
    }
    .klinklin-contact-page .contact-faq-section-klinklin .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23461871'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        transition: background-image 0.15s ease-in-out, transform 0.3s ease-in-out;
    }
    .klinklin-contact-page .contact-faq-section-klinklin .accordion-button:not(.collapsed)::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23FFFFFF'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        transform: rotate(-180deg);
    }
    .klinklin-contact-page .contact-faq-section-klinklin .accordion-body {
        font-family: var(--font-family-klinklin);
        padding: 1.4rem 1.6rem;
        background-color: var(--klin-blanc);
        color: var(--klin-violet-presque-noir); /* Couleur texte body accordéon spécifique */
        line-height: 1.65;
        font-size: 0.9rem;
        font-weight: 400;
        border-top: 1px solid var(--contact-faq-accordion-border-color);
    }
</style>
@endsection

@section('content')
<div class="klinklin-contact-page">

    <div class="page-header-contact-klinklin">
        <div class="container">
            <h1><i class="fas fa-headset"></i> Contactez KLIN KLIN</h1>
            <p>Nous sommes à votre entière disposition pour toute question, suggestion ou demande d'assistance.</p>
        </div>
    </div>

    <section class="contact-main-section">
        <div class="container">
            <div class="row gx-lg-5">
                <div class="col-lg-7 mb-5 mb-lg-0">
                    <h2 class="section-title-klinklin text-start">Envoyez-nous un message</h2>
                    <p class="section-description-klinklin text-start">Remplissez le formulaire ci-dessous, notre équipe vous répondra dans les plus brefs délais.</p>
                    
                    <form class="contact-form-klinklin" id="contactForm" method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="contactName" class="form-label">Nom complet <span style="color: var(--klin-cyan-fluo);">*</span></label>
                                <input type="text" class="form-control" id="contactName" name="name" placeholder="Votre nom et prénom" required>
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="contactEmail" class="form-label">Email <span style="color: var(--klin-cyan-fluo);">*</span></label>
                                <input type="email" class="form-control" id="contactEmail" name="email" placeholder="Votre adresse e-mail" required>
                                <div class="invalid-feedback" id="emailError"></div>
                            </div>
                            <div class="col-12">
                                <label for="contactSubject" class="form-label">Sujet <span style="color: var(--klin-cyan-fluo);">*</span></label>
                                <input type="text" class="form-control" id="contactSubject" name="subject" placeholder="Objet de votre message" required>
                                <div class="invalid-feedback" id="subjectError"></div>
                            </div>
                            <div class="col-12">
                                <label for="contactMessage" class="form-label">Message <span style="color: var(--klin-cyan-fluo);">*</span></label>
                                <textarea class="form-control" id="contactMessage" name="message" rows="6" placeholder="Laissez-nous votre message ici..." required></textarea>
                                <div class="invalid-feedback" id="messageError"></div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-submit-klinklin" id="submitContactBtn">
                                    <i class="fas fa-paper-plane me-2"></i>Envoyer
                                </button>
                                <div class="spinner-border text-primary d-none" id="contactFormSpinner" role="status">
                                    <span class="visually-hidden">Chargement...</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="col-lg-5">
                    <div class="contact-info-card-klinklin">
                        <h2 class="section-title-klinklin text-start mb-4">Nos coordonnées</h2>
                        
                        <div class="contact-info-item-klinklin">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <h4>Notre Adresse</h4>
                                <p>1184 rue Louassi, Business Center Plateau<br>Brazzaville - Congo</p>
                            </div>
                        </div>
                        
                        <div class="contact-info-item-klinklin">
                            <i class="fas fa-phone-alt"></i>
                            <div>
                                <h4>Appelez-nous</h4>
                                <p><a href="tel:+242069349160">+242 06 934 91 60</a></p>
                            </div>
                        </div>
                        
                        <div class="contact-info-item-klinklin">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <h4>Écrivez-nous</h4>
                                <p><a href="mailto:contact@ezaklinklin.com">contact@ezaklinklin.com</a></p>
                            </div>
                        </div>
                        
                        <div class="contact-info-item-klinklin">
                            <i class="fas fa-clock"></i>
                            <div>
                                <h4>Horaires d'ouverture</h4>
                                <p>
                                    Lundi - Samedi: <strong>8h00 - 18h00</strong><br>
                                    Dimanche: Fermé
                                </p>
                            </div>
                        </div>
                        
                        <div class="social-links-klinklin">
                            <h4 class="mb-3">Restons connectés</h4>
                            <div class="d-flex gap-2">
                                <a href="#" class="social-link-klinklin" aria-label="Facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-link-klinklin" aria-label="Instagram" target="_blank"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="social-link-klinklin" aria-label="Twitter" target="_blank"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="social-link-klinklin" aria-label="WhatsApp" target="_blank"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="map-section-klinklin">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-4">
                    <h2 class="section-title-klinklin">Notre emplacement</h2>
                    <p class="section-description-klinklin">Venez nous rendre visite ou visualisez notre zone d'activité principale.</p>
                </div>
                <div class="col-12">
                    <div class="map-container-klinklin">
                        {{-- 
                            **IMPORTANT : INSTRUCTIONS POUR LA CARTE GOOGLE MAPS**
                            1. Allez sur Google Maps.
                            2. Recherchez votre adresse exacte : "1184 rue Louassi, Business Center Plateau, Brazzaville - Congo".
                            3. Cliquez sur "Partager" une fois l'emplacement trouvé.
                            4. Allez dans l'onglet "Intégrer une carte".
                            5. Copiez le code HTML <iframe ...></iframe> fourni.
                            6. Remplacez l'iframe ci-dessous par le code que vous avez copié.
                        --}}
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3978.827496362561!2d15.263300873918045!3d-4.2537889957200905!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1a6a32d81436ebf9%3A0x70183e1b50b6d24e!2s1184%20Rue%20Louassi%2C%20Brazzaville%2C%20R%C3%A9publique%20du%20Congo!5e0!3m2!1sfr!2sfr!4v1746872619448!5m2!1sfr!2sfr" 
                            width="100%" height="480" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    
                             </div>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-faq-section-klinklin">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 mx-auto text-center mb-5">
                    <h2 class="section-title-klinklin">Réponses rapides</h2>
                    <p class="section-description-klinklin">Quelques interrogations courantes que vous pourriez avoir avant de nous contacter.</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-9 mx-auto">
                    <div class="accordion" id="contactFaqAccordionKlinKlin">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="contactFaqHeadingKlinKlin1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#contactFaqKlinKlin1">
                                    <i class="fas fa-shipping-fast"></i>
                                    Comment puis-je suivre ma commande de pressing ?
                                </button>
                            </h2>
                            <div id="contactFaqKlinKlin1" class="accordion-collapse collapse show" aria-labelledby="contactFaqHeadingKlinKlin1" data-bs-parent="#contactFaqAccordionKlinKlin">
                                <div class="accordion-body">
                                    <p>Le suivi de votre commande se fait en toute simplicité ! Connectez-vous à votre compte KLIN KLIN via notre application mobile ou notre site web. Accédez à la section "Mes Commandes" pour visualiser en temps réel le statut de chaque prestation, de la collecte à la livraison.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="contactFaqHeadingKlinKlin2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#contactFaqKlinKlin2">
                                    <i class="fas fa-calendar-check"></i>
                                    Puis-je modifier l'heure de collecte après avoir commandé ?
                                </button>
                            </h2>
                            <div id="contactFaqKlinKlin2" class="accordion-collapse collapse" aria-labelledby="contactFaqHeadingKlinKlin2" data-bs-parent="#contactFaqAccordionKlinKlin">
                                <div class="accordion-body">
                                    <p>Oui, la flexibilité est clé chez KLIN KLIN. Vous pouvez modifier l'heure ou la date de votre collecte sans frais jusqu'à 2 heures avant le créneau initialement prévu. Ces modifications peuvent être effectuées directement depuis votre espace client ou en contactant notre support.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="contactFaqHeadingKlinKlin3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#contactFaqKlinKlin3">
                                    <i class="fas fa-globe-africa"></i>
                                    Quelles sont précisément les zones desservies à Brazzaville ?
                                </button>
                            </h2>
                            <div id="contactFaqKlinKlin3" class="accordion-collapse collapse" aria-labelledby="contactFaqHeadingKlinKlin3" data-bs-parent="#contactFaqAccordionKlinKlin">
                                <div class="accordion-body">
                                    <p>KLIN KLIN s'efforce de couvrir l'ensemble de Brazzaville. Nos zones de service principales incluent le Centre-ville (Plateau), Bacongo, Makélékélé, Poto-Poto, Moungali, Ouenzé, Talangaï, Mfilou et Djiri. Pour une confirmation précise concernant votre adresse ou pour des besoins spécifiques, le plus simple est de simuler une commande sur notre application ou de contacter directement notre service client.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

<!-- Success Modal -->
<div class="modal fade" id="contactSuccessModal" tabindex="-1" aria-labelledby="contactSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: var(--klin-violet-fonce); color: white;">
                <h5 class="modal-title" id="contactSuccessModalLabel">Message envoyé avec succès</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-check-circle mb-3" style="font-size: 3rem; color: var(--klin-cyan-fluo);"></i>
                <h4>Merci pour votre message !</h4>
                <p>Nous avons bien reçu votre demande et nous vous répondrons dans les meilleurs délais.</p>
                <p>Un e-mail de confirmation a été envoyé à l'adresse que vous nous avez communiquée.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn" style="background-color: var(--klin-cyan-fluo); color: var(--klin-violet-fonce);" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contactForm');
        const submitBtn = document.getElementById('submitContactBtn');
        const spinner = document.getElementById('contactFormSpinner');
        const successModal = new bootstrap.Modal(document.getElementById('contactSuccessModal'));

        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Reset any previous error messages
                document.querySelectorAll('.invalid-feedback').forEach(el => {
                    el.textContent = '';
                });
                document.querySelectorAll('.form-control').forEach(el => {
                    el.classList.remove('is-invalid');
                });
                
                // Show spinner, hide button
                submitBtn.classList.add('d-none');
                spinner.classList.remove('d-none');
                
                // Get form data
                const formData = new FormData(contactForm);
                
                // Submit via AJAX
                fetch('{{ route('contact.submit') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Hide spinner, show button
                    submitBtn.classList.remove('d-none');
                    spinner.classList.add('d-none');
                    
                    if (data.success) {
                        // Reset form
                        contactForm.reset();
                        
                        // Show success modal
                        successModal.show();
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(key => {
                                const errorElement = document.getElementById(key + 'Error');
                                const inputElement = document.querySelector(`[name="${key}"]`);
                                
                                if (errorElement && inputElement) {
                                    errorElement.textContent = data.errors[key][0];
                                    inputElement.classList.add('is-invalid');
                                }
                            });
                        } else if (data.message) {
                            alert(data.message);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.classList.remove('d-none');
                    spinner.classList.add('d-none');
                    alert('Une erreur est survenue. Veuillez réessayer plus tard.');
                });
            });
        }
    });
</script>
@endsection