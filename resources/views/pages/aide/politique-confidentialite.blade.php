@extends('layouts.app')

@section('title', 'Politique de Confidentialité')

@section('content')
    <div class="page-header"> {{-- Utilise le style .page-header défini globalement --}}
        <div class="container text-center"> {{-- Ajout de text-center pour le contenu du header --}}
            <h1 class="display-5 fw-bold">Politique de Confidentialité</h1>
            <p class="lead mb-0">Comment nous protégeons vos données personnelles.</p>
        </div>
    </div>

    <section class="py-5 privacy-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="privacy-content-card">
                        <div class="privacy-article mb-5">
                            <h2 class="privacy-article-title">Introduction</h2>
                            <p>La présente Politique de Confidentialité décrit comment KLIN KLIN collecte, utilise et protège les informations personnelles que vous nous fournissez lorsque vous utilisez nos services, notre site internet ou notre application mobile.</p>
                            <p>KLIN KLIN s'engage à assurer la protection de vos données personnelles dans le respect des lois et réglementations en vigueur relatives à la protection des données personnelles.</p>
                            <p>Cette politique s'applique à tous les clients, utilisateurs et visiteurs de nos services, et concerne les données collectées par tout moyen, y compris en ligne, hors ligne, par téléphone ou par correspondance.</p>
                        </div>

                        <div class="privacy-article mb-5">
                            <h2 class="privacy-article-title">1. Informations que nous collectons</h2>
                            <p>Nous pouvons collecter les types d'informations suivants :</p>
                            
                            <h4 class="privacy-sub-article-title">1.1 Informations que vous nous fournissez</h4>
                            <ul>
                                <li>Informations d'identification (nom, prénom, adresse email, numéro de téléphone)</li>
                                <li>Informations de livraison et facturation (adresse postale)</li>
                                <li>Informations de paiement (numéro de carte bancaire, informations Mobile Money)</li>
                                <li>Préférences concernant nos services</li>
                                <li>Communications que vous nous envoyez (emails, formulaires de contact)</li>
                            </ul>
                            
                            <h4 class="privacy-sub-article-title">1.2 Informations collectées automatiquement</h4>
                            <ul>
                                <li>Informations sur votre appareil (type d'appareil, système d'exploitation, navigateur)</li>
                                <li>Données de connexion (adresse IP, date et heure de connexion)</li>
                                <li>Données de navigation (pages visitées, durée de visite)</li>
                                <li>Données de localisation (si vous avez activé cette fonctionnalité)</li>
                            </ul>
                            
                            <h4 class="privacy-sub-article-title">1.3 Informations provenant de tiers</h4>
                            <ul>
                                <li>Informations provenant de réseaux sociaux (si vous vous connectez via ces plateformes)</li>
                                <li>Informations provenant de partenaires commerciaux (avec votre consentement)</li>
                            </ul>
                        </div>

                        <div class="privacy-article mb-5">
                            <h2 class="privacy-article-title">2. Utilisation de vos informations</h2>
                            <p>Nous utilisons vos informations personnelles pour les finalités suivantes :</p>
                            <ul>
                                <li>Fournir, exploiter et maintenir nos services (collecte, traitement et livraison de votre linge)</li>
                                <li>Traiter et gérer vos commandes et paiements</li>
                                <li>Vous envoyer des informations techniques, des mises à jour et des messages relatifs à la sécurité</li>
                                <li>Répondre à vos demandes, questions et préoccupations</li>
                                <li>Améliorer et personnaliser nos services en fonction de vos préférences</li>
                                <li>Vous envoyer des communications marketing (avec votre consentement)</li>
                                <li>Prévenir et détecter les fraudes et abus</li>
                                <li>Se conformer aux obligations légales et réglementaires</li>
                            </ul>
                        </div>

                        <div class="privacy-article mb-5">
                            <h2 class="privacy-article-title">3. Base légale du traitement</h2>
                            <p>Nous traitons vos données personnelles sur la base des fondements juridiques suivants :</p>
                            <ul>
                                <li><strong>Exécution d'un contrat :</strong> lorsque le traitement est nécessaire à l'exécution du contrat conclu avec vous (fourniture de nos services)</li>
                                <li><strong>Consentement :</strong> lorsque vous avez donné votre consentement explicite pour des finalités spécifiques (ex: communications marketing)</li>
                                <li><strong>Intérêts légitimes :</strong> lorsque le traitement est nécessaire aux fins de nos intérêts légitimes ou ceux d'un tiers, sans porter atteinte à vos droits fondamentaux</li>
                                <li><strong>Obligation légale :</strong> lorsque le traitement est nécessaire pour respecter une obligation légale à laquelle nous sommes soumis</li>
                            </ul>
                        </div>

                        <div class="privacy-article mb-5">
                            <h2 class="privacy-article-title">4. Partage et divulgation des informations</h2>
                            <p>Nous pouvons partager vos informations personnelles avec les catégories suivantes de destinataires :</p>
                            <ul>
                                <li><strong>Prestataires de services :</strong> sociétés qui nous aident à fournir nos services (transport, paiement, hébergement)</li>
                                <li><strong>Partenaires commerciaux :</strong> entreprises avec lesquelles nous collaborons pour offrir certains services ou promotions</li>
                                <li><strong>Autorités publiques :</strong> lorsque la loi l'exige ou pour protéger nos droits légaux</li>
                            </ul>
                            <p>Nous ne vendons pas vos données personnelles à des tiers. Les tiers avec lesquels nous partageons vos informations sont tenus de les utiliser uniquement pour les finalités prévues et conformément à cette politique de confidentialité.</p>
                        </div>

                        <div class="privacy-article mb-5">
                            <h2 class="privacy-article-title">5. Conservation des données</h2>
                            <p>Nous conservons vos données personnelles aussi longtemps que nécessaire pour fournir nos services et respecter nos obligations légales. Les périodes de conservation spécifiques dépendent du type de données et de la finalité du traitement :</p>
                            <ul>
                                <li>Données de compte : conservées tant que votre compte est actif</li>
                                <li>Données de commande : conservées pendant la durée légale requise pour les obligations comptables et fiscales (généralement 10 ans)</li>
                                <li>Données de communication : conservées pendant 3 ans à compter du dernier contact</li>
                            </ul>
                            <p>À l'expiration de ces périodes, vos données sont supprimées ou anonymisées de manière sécurisée.</p>
                        </div>

                        <div class="privacy-article mb-5">
                            <h2 class="privacy-article-title">6. Sécurité des données</h2>
                            <p>KLIN KLIN met en œuvre des mesures techniques et organisationnelles appropriées pour protéger vos données personnelles contre la perte, l'accès non autorisé, la divulgation, l'altération ou la destruction. Ces mesures comprennent :</p>
                            <ul>
                                <li>Le cryptage des données sensibles</li>
                                <li>L'accès restreint aux données personnelles</li>
                                <li>Des procédures de sauvegarde régulières</li>
                                <li>Des audits de sécurité périodiques</li>
                            </ul>
                            <p>Bien que nous nous efforcions de protéger vos informations personnelles, aucune méthode de transmission sur Internet ou de stockage électronique n'est totalement sécurisée. Nous ne pouvons donc pas garantir une sécurité absolue.</p>
                        </div>

                        <div class="privacy-article mb-5">
                            <h2 class="privacy-article-title">7. Vos droits</h2>
                            <p>En tant que personne concernée, vous disposez des droits suivants concernant vos données personnelles :</p>
                            <ul>
                                <li><strong>Droit d'accès :</strong> vous pouvez demander une copie des données personnelles que nous détenons à votre sujet</li>
                                <li><strong>Droit de rectification :</strong> vous pouvez demander la correction de données inexactes ou incomplètes</li>
                                <li><strong>Droit à l'effacement :</strong> vous pouvez demander la suppression de vos données dans certaines circonstances</li>
                                <li><strong>Droit à la limitation du traitement :</strong> vous pouvez demander la restriction du traitement de vos données</li>
                                <li><strong>Droit à la portabilité :</strong> vous pouvez demander le transfert de vos données à un autre prestataire</li>
                                <li><strong>Droit d'opposition :</strong> vous pouvez vous opposer au traitement de vos données, notamment à des fins de marketing</li>
                            </ul>
                            <p>Pour exercer ces droits, vous pouvez nous contacter à l'adresse email suivante : contact@ezaklinklin.com. Nous répondrons à votre demande dans un délai d'un mois, sauf circonstances exceptionnelles.</p>
                        </div>

                        <div class="privacy-article mb-5">
                            <h2 class="privacy-article-title">8. Cookies et technologies similaires</h2>
                            <p>Notre site web et notre application mobile utilisent des cookies et des technologies similaires pour améliorer votre expérience utilisateur, analyser l'utilisation de nos services et personnaliser nos offres.</p>
                            <p>Les types de cookies que nous utilisons sont :</p>
                            <ul>
                                <li><strong>Cookies essentiels :</strong> nécessaires au fonctionnement de notre site</li>
                                <li><strong>Cookies analytiques :</strong> nous aident à comprendre comment vous interagissez avec notre site</li>
                                <li><strong>Cookies de fonctionnalité :</strong> permettent de mémoriser vos préférences</li>
                                <li><strong>Cookies de ciblage :</strong> utilisés pour vous proposer des publicités personnalisées</li>
                            </ul>
                            <p>Vous pouvez configurer votre navigateur pour refuser tous ou certains cookies. Cependant, si vous désactivez les cookies, certaines fonctionnalités de notre site pourraient ne pas fonctionner correctement.</p>
                        </div>

                        <div class="privacy-article mb-5">
                            <h2 class="privacy-article-title">9. Modifications de la politique de confidentialité</h2>
                            <p>Nous nous réservons le droit de modifier cette politique de confidentialité à tout moment. Toute modification sera publiée sur cette page avec une date de mise à jour. Nous vous encourageons à consulter régulièrement cette politique pour rester informé de la façon dont nous protégeons vos informations.</p>
                            <p>Pour les modifications importantes, nous vous informerons par email ou par notification sur notre site ou application.</p>
                        </div>

                        <div class="privacy-article">
                            <h2 class="privacy-article-title">10. Nous contacter</h2>
                            <p>Si vous avez des questions concernant cette politique de confidentialité ou la façon dont nous traitons vos données personnelles, veuillez nous contacter :</p>
                            <p>
                                <strong>KLIN KLIN</strong><br>
                                Adresse : Brazzaville, Congo<br>
                                Email : contact@ezaklinklin.com<br>
                                Téléphone : +242 069349160
                            </p>
                            <p>Si vous n'êtes pas satisfait de notre réponse, vous avez le droit d'introduire une réclamation auprès de l'autorité de protection des données compétente.</p>
                        </div>

                        <div class="mt-5 pt-4 border-top privacy-update-date">
                            <p>Dernière mise à jour : 10 Mai 2025</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
@endsection

@section('styles')
<style>
    /* Variables KLINKLIN (assurez-vous qu'elles sont définies globalement)
    :root {
        --klin-primary: #461871;
        --klin-accent: #8DFFC8;
        --klin-violet-structure: #684289;
        --klin-dark-text-color: #141B4D; // ou #555
        --klin-light-background: #c6b7d3;
        --klin-cta-bg: #3F1666;
        --klin-light-text-color: #ffffff;
        --klin-light-text-color-rgb: 255, 255, 255;
    }
    */

    /* Style pour le .page-header de cette page (si différent du style global) */
    /* Si le style global du .page-header (fond violet, texte blanc) est utilisé, il devrait convenir.
       Sinon, pour un header plus sobre pour les pages de contenu : */
    /*
    .page-header {
        background-color: var(--klin-light-background, #f0f2f5); // Un gris clair ou violet atténué
        padding: 50px 0;
        text-align: center;
        border-bottom: 1px solid #e0e0e0;
    }
    .page-header h1 {
        color: var(--klin-primary, #461871);
        font-size: 2.5rem; // Ajusté pour page interne
        font-weight: 700;
    }
    .page-header p {
        color: var(--klin-dark-text-color, #555);
        font-size: 1.1rem;
    }
    */

    .privacy-section {
        background-color: #f9f9f9; /* Fond légèrement gris pour la section */
    }

    .privacy-content-card {
        background-color: var(--klin-blanc, #ffffff);
        padding: 2.5rem 3rem; /* Plus de padding interne */
        border-radius: 0.75rem; /* Coins arrondis */
        box-shadow: 0 0.75rem 1.5rem rgba(0,0,0,0.06); /* Ombre plus douce */
        border: 1px solid #e9ecef;
    }

    .privacy-article-title { /* Pour les <h2> */
        color: var(--klin-primary, #461871);
        font-size: 1.75rem; /* 28px, ou 24px comme dans votre exemple précédent */
        font-weight: 600; /* Montserrat SemiBold */
        margin-top: 2.5rem;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--klin-accent, #8DFFC8);
        display: inline-block; /* Pour que la bordure ne prenne que la largeur du texte */
    }
    .privacy-article:first-child .privacy-article-title {
        margin-top: 0; /* Pas de marge en haut pour le tout premier titre */
    }

    .privacy-sub-article-title { /* Pour les <h4> */
        color: var(--klin-violet-structure, #684289); /* Violet un peu plus clair */
        font-size: 1.25rem; /* Taille h5 de Bootstrap */
        font-weight: 600; /* Montserrat SemiBold */
        margin-top: 2rem; /* Espacement avant un sous-titre */
        margin-bottom: 0.75rem;
    }

    .privacy-content-card p,
    .privacy-content-card ul strong { /* Pour rendre les "Exécution d'un contrat :" etc. plus visibles */
        color: #555555; /* Gris foncé pour le texte principal */
        line-height: 1.8; /* Interligne amélioré */
        margin-bottom: 1rem;
    }
    .privacy-content-card ul strong {
        font-weight: 600; /* Montserrat SemiBold */
    }


    .privacy-content-card ul {
        color: #555555;
        line-height: 1.8;
        padding-left: 1.5rem; /* Indentation de la liste */
        margin-bottom: 1rem;
        list-style-type: disc; /* Puces standard */
    }

    .privacy-content-card ul li {
        margin-bottom: 0.6rem; /* Espace entre les items de liste */
    }
    .privacy-content-card ul li::marker {
        color: var(--klin-primary, #461871); /* Couleur des puces */
    }

    .privacy-update-date p {
        font-style: italic;
        color: #777777; /* Texte plus clair pour la date */
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    /* Styles pour la section CTA de cette page (normalement hérités des styles globaux si définis) */
    /* Assurez-vous que .cta-section, .cta-title, .cta-description, .btn-mint, .btn-light-mint
       sont définis dans votre CSS global avec le fond #3F1666 et textes blancs pour le CTA.
       Sinon, décommentez et ajustez ces styles :
    */
    /*
    .cta-section {
        padding: 60px 0;
        background-color: var(--klin-cta-bg, #3F1666);
    }
    .cta-section .cta-title {
        color: var(--klin-light-text-color, #ffffff);
        font-weight: 700;
    }
    .cta-section .cta-description {
        color: rgba(var(--klin-light-text-color-rgb, 255, 255, 255), 0.9);
        font-size: 1.1rem;
    }
    .btn-mint {
        background-color: var(--klin-accent, #8DFFC8);
        color: var(--klin-primary, #461871);
        // ... autres styles de bouton ...
    }
    .btn-light-mint {
        background-color: var(--klin-blanc, #ffffff);
        color: var(--klin-cta-bg, #3F1666);
        border: 1px solid rgba(var(--klin-light-text-color-rgb, 255,255,255), 0.3);
        // ... autres styles de bouton ...
    }
    */

    @media (max-width: 767.98px) {
        .privacy-content-card {
            padding: 1.5rem; /* Moins de padding sur mobile */
        }
        .privacy-article-title {
            font-size: 1.5rem; /* Titres d'article plus petits sur mobile */
        }
        .privacy-sub-article-title {
            font-size: 1.15rem; /* Sous-titres plus petits sur mobile */
        }
    }
</style>
@endsection