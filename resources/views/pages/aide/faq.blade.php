@extends('layouts.app')

@section('title', 'Foire Aux Questions')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container text-center">
            <h1>Foire Aux Questions</h1>
            <p class="text-center text-white">Trouvez toutes les réponses à vos questions concernant nos services</p>
        </div>
    </div>

    <!-- FAQ Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="mb-5">
                        <h2 class="section-title text-center mb-4">Questions fréquentes</h2>
                        <p class="text-center mb-5">Nous avons regroupé les questions les plus fréquentes concernant nos services. Si vous ne trouvez pas la réponse à votre question, n'hésitez pas à nous contacter.</p>
                    </div>

                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item mb-3 rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    <i class="fas fa-tshirt me-3"></i>
                                    Quels vêtements sont acceptés ?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Nous acceptons tous types de vêtements :</p>
                                    <ul>
                                        <li>Vêtements quotidiens (chemises, pantalons, t-shirts)</li>
                                        <li>Tenues de soirée et costumes</li>
                                        <li>Linge de maison (draps, serviettes)</li>
                                        <li>Vêtements délicats nécessitant un soin particulier</li>
                                    </ul>
                                    <p>Chaque article est traité selon ses spécificités et étiquettes d'entretien.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    <i class="fas fa-sort me-3"></i>
                                    Dois-je trier mon linge ?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Non, vous n'avez pas besoin de trier votre linge. Nos experts s'en chargent pour vous en respectant :
                                    <ul>
                                        <li>La séparation des couleurs</li>
                                        <li>Les températures de lavage appropriées</li>
                                        <li>Les tissus délicats</li>
                                        <li>Les instructions spécifiques de chaque vêtement</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    <i class="fas fa-clock me-3"></i>
                                    Quels sont les délais ?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Nous proposons plusieurs options de délais :</p>
                                    <ul>
                                        <li><strong>Standard (48h) :</strong> Collecte et livraison en 48h</li>
                                        <li><strong>Express (24h) :</strong> Service accéléré en 24h</li>
                                        <li><strong>Urgent (12h) :</strong> Pour vos besoins pressants</li>
                                    </ul>
                                    <p>Les délais sont garantis à partir de l'heure de collecte.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    <i class="fas fa-wallet me-3"></i>
                                    Quels moyens de paiement acceptez-vous ?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Nous acceptons plusieurs moyens de paiement :</p>
                                    <ul>
                                        <li>Cartes bancaires</li>
                                        <li>Mobile Money (Orange Money, MTN Mobile Money)</li>
                                        <li>Espèces à la livraison</li>
                                        <li>Paiement mensuel pour les abonnements</li>
                                    </ul>
                                    <p>Toutes les transactions sont sécurisées.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                    <i class="fas fa-ban me-3"></i>
                                    Puis-je annuler une collecte ?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Oui, vous pouvez annuler ou modifier votre collecte :</p>
                                    <ul>
                                        <li>Annulation gratuite jusqu'à 2h avant la collecte</li>
                                        <li>Modification de l'heure ou de la date possible</li>
                                        <li>Contact simple via notre service client</li>
                                        <li>Aucun frais pour les modifications</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                                    <i class="fas fa-truck me-3"></i>
                                    Comment fonctionne la collecte et la livraison ?
                                </button>
                            </h2>
                            <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Notre service de collecte et livraison est simple :</p>
                                    <ol>
                                        <li>Vous planifiez une collecte via notre site ou application</li>
                                        <li>Notre livreur passe à l'adresse et à l'heure convenues</li>
                                        <li>Votre linge est traité dans nos installations</li>
                                        <li>Nous vous livrons votre linge propre à l'adresse de votre choix</li>
                                    </ol>
                                    <p>Le service de collecte et livraison est inclus dans nos tarifs, sans frais supplémentaires dans les zones couvertes.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">
                                    <i class="fas fa-hands-wash me-3"></i>
                                    Quels produits utilisez-vous ?
                                </button>
                            </h2>
                            <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Nous utilisons des produits de qualité professionnelle qui sont :</p>
                                    <ul>
                                        <li>Respectueux de l'environnement</li>
                                        <li>Doux pour les tissus et les couleurs</li>
                                        <li>Hypoallergéniques et sans parfums agressifs</li>
                                        <li>Efficaces contre les taches et les odeurs</li>
                                    </ul>
                                    <p>Nous pouvons également utiliser des produits spécifiques sur demande pour les personnes ayant des sensibilités cutanées.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-3 rounded-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8">
                                    <i class="fas fa-calendar-alt me-3"></i>
                                    Comment fonctionnent les abonnements ?
                                </button>
                            </h2>
                            <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p>Nos abonnements sont conçus pour vous simplifier la vie :</p>
                                    <ul>
                                        <li>Plusieurs formules disponibles selon vos besoins</li>
                                        <li>Facturation mensuelle avec tarifs préférentiels</li>
                                        <li>Collectes régulières ou sur demande selon votre formule</li>
                                        <li>Modification ou annulation possible à tout moment</li>
                                    </ul>
                                    <p>Consultez notre page d'abonnement pour découvrir les différentes formules disponibles.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
    /* Variables de la charte KLIN KLIN nécessaires pour cette section */
    :root {
        --klin-violet-fonce: #461871;
        --klin-cyan-fluo: #8DFFC8;
        --klin-violet-presque-noir: #14081c; /* Pour texte sur fond clair/cyan */
        --klin-violet-attenue: #c6b7d3;    /* Pour texte sur fond violet foncé */
        --klin-blanc: #FFFFFF;
        --font-family-klinklin: 'Montserrat', sans-serif; /* Assurez-vous que Montserrat est chargée */
    }

    .cta-section-2-klinklin {
        background-color: var(--klin-violet-fonce);
        color: var(--klin-blanc);
        padding: 60px 15px; /* Ajout de padding horizontal pour mobile */
        font-family: var(--font-family-klinklin);
        text-align: center;
    }

    .cta-section-2-klinklin .container { /* Pour assurer le centrage sur toutes tailles */
        max-width: 960px; /* Ou une autre valeur selon votre grille */
    }

    .cta-title-2-klinklin {
        font-weight: 700;
        font-size: 2.3rem;
        color: var(--klin-blanc);
        margin-bottom: 1rem;
    }

    .cta-description-2-klinklin {
        font-weight: 400;
        font-size: 1.15rem;
        color: var(--klin-violet-attenue);
        line-height: 1.7;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 2rem;
    }

    .btn-cta-2-primary-klinklin {
        background-color: var(--klin-cyan-fluo);
        color: var(--klin-violet-fonce);
        border: 2px solid var(--klin-cyan-fluo);
        padding: 12px 30px;
        font-weight: 600;
        font-size: 1rem;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0,0,0, 0.1);
        display: inline-flex; /* Pour aligner icône et texte */
        align-items: center;
        justify-content: center;
    }

    .btn-cta-2-primary-klinklin:hover,
    .btn-cta-2-primary-klinklin:focus {
        background-color: #79eabd;
        border-color: #79eabd;
        color: var(--klin-violet-fonce);
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(0,0,0, 0.15);
    }

    .btn-cta-2-secondary-klinklin {
        background-color: var(--klin-blanc);
        color: var(--klin-violet-fonce);
        border: 2px solid var(--klin-blanc);
        padding: 12px 30px;
        font-weight: 600;
        font-size: 1rem;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0,0,0, 0.1);
        display: inline-flex; /* Pour aligner icône et texte */
        align-items: center;
        justify-content: center;
    }

    .btn-cta-2-secondary-klinklin:hover,
    .btn-cta-2-secondary-klinklin:focus {
        background-color: var(--klin-violet-attenue);
        border-color: var(--klin-violet-attenue);
        color: var(--klin-violet-fonce);
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(0,0,0, 0.15);
    }

    .btn-cta-2-primary-klinklin .fas,
    .btn-cta-2-secondary-klinklin .fas {
        margin-right: 0.5rem; /* Espace entre icône et texte */
        line-height: 1; 
    }
</style>

<section class="cta-section-2-klinklin">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="cta-title-2-klinklin mb-3">Une autre question ?</h2>
                <p class="cta-description-2-klinklin mb-4">
                    L'équipe KLIN KLIN est toujours prête à vous éclairer. N'hésitez pas à nous solliciter !
                </p>
                <div class="d-flex justify-content-center flex-wrap gap-3">
                    <a href="{{ route('contact') }}" class="btn btn-cta-2-primary-klinklin">
                        <i class="fas fa-paper-plane"></i>Nous Écrire
                    </a>
                    <a href="{{ route('simulateur') }}" class="btn btn-cta-2-secondary-klinklin">
                        <i class="fas fa-magic"></i>Simuler un Prix
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .accordion-button {
        background-color: #f8f9fa;
        color: #14081c;
        font-weight: 600;
        padding: 20px;
        border: none;
        box-shadow: none !important;
    }

    .accordion-button:not(.collapsed) {
        background-color: #461871;
        color: white;
    }

    .accordion-button::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23461871'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    .accordion-button:not(.collapsed)::after {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23ffffff'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    }

    .accordion-body {
        background-color: white;
        padding: 25px;
        color: #666;
        line-height: 1.6;
        font-size: 15px;
    }
</style>
@endsection 