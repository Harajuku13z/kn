@extends('layouts.app')

@section('title', 'Conditions Générales d\'Utilisation et de Vente')

@section('content')
    <div class="page-header"> {{-- Utilise le style .page-header défini globalement --}}
        <div class="container text-center"> {{-- Ajout de text-center pour le contenu du header --}}
            <h1 class="display-5 fw-bold">Conditions Générales</h1> {{-- Taille ajustée pour page interne --}}
            <p class="lead mb-0">Les termes et conditions qui régissent l'utilisation de nos services.</p>
        </div>
    </div>

    <section class="py-5 terms-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="terms-content-card">
                        <div class="terms-article mb-5">
                            <h2 class="terms-article-title">Préambule</h2>
                            <p>Les présentes conditions générales de vente et d'utilisation (ci-après les "CGV/CGU") s'appliquent à toutes les prestations de services conclues entre la société KLIN KLIN, société anonyme au capital de 1 000 000 FCFA, dont le siège social est situé à Brazzaville, Congo (ci-après "KLIN KLIN") et les clients particuliers et professionnels (ci-après le "Client").</p>
                            <p>Les CGV/CGU détaillent les droits et obligations de KLIN KLIN et du Client dans le cadre de la fourniture des prestations de services suivantes : lavage, repassage, pressing et autres services de blanchisserie (ci-après les "Services").</p>
                            <p>Toute prestation accomplie par KLIN KLIN implique l'adhésion sans réserve du Client aux présentes CGV/CGU.</p>
                        </div>

                        <div class="terms-article mb-5">
                            <h2 class="terms-article-title">1. Application des conditions générales</h2>
                            <p>Les présentes CGV/CGU sont accessibles à tout moment sur le site internet de KLIN KLIN (www.klinklin.com) et prévaudront sur tout autre document contradictoire.</p>
                            <p>KLIN KLIN se réserve le droit de modifier ses CGV/CGU à tout moment. Dans ce cas, les conditions applicables seront celles en vigueur à la date de la commande par le Client.</p>
                        </div>

                        <div class="terms-article mb-5">
                            <h2 class="terms-article-title">2. Commande de services</h2>
                            <p>Les commandes de Services peuvent être passées :</p>
                            <ul>
                                <li>Sur le site internet de KLIN KLIN : www.klinklin.com</li>
                                <li>Via l'application mobile KLIN KLIN</li>
                                <li>Par téléphone au +242069349160</li>
                            </ul>
                            <p>La commande n'est définitive qu'après confirmation par KLIN KLIN et réception du paiement ou validation de la commande en cas de paiement différé ou abonnement.</p>
                            <p>KLIN KLIN se réserve le droit de refuser toute commande pour des motifs légitimes, notamment en cas d'indisponibilité du service, d'informations erronées fournies par le Client ou d'historique de commandes problématique.</p>
                        </div>

                        <div class="terms-article mb-5">
                            <h2 class="terms-article-title">3. Tarifs et modalités de paiement</h2>
                            <p>Les tarifs des Services sont indiqués en Francs CFA (FCFA) et sont disponibles sur le site internet de KLIN KLIN. Ils sont susceptibles d'être modifiés à tout moment, mais les Services seront facturés sur la base des tarifs en vigueur au moment de la confirmation de la commande.</p>
                            <p>Les moyens de paiement acceptés sont :</p>
                            <ul>
                                <li>Paiement par carte bancaire</li>
                                <li>Paiement par Mobile Money (Orange Money, MTN Mobile Money)</li>
                                <li>Paiement en espèces à la livraison</li>
                            </ul>
                            <p>Pour les abonnements, les paiements sont prélevés mensuellement à la date de souscription.</p>
                        </div>

                        <div class="terms-article mb-5">
                            <h2 class="terms-article-title">4. Collecte et livraison</h2>
                            <p>KLIN KLIN propose un service de collecte et livraison aux adresses indiquées par le Client, sous réserve que ces adresses se trouvent dans les zones de livraison couvertes. </p>
                            <p>Le Client s'engage à être présent ou à désigner une personne habilitée pour la collecte et la livraison aux horaires convenus. En cas d'absence, des frais supplémentaires pourront être facturés pour une nouvelle livraison.</p>
                            <p>KLIN KLIN s'engage à respecter les délais de livraison indiqués lors de la commande. Toutefois, en cas de circonstances exceptionnelles (conditions météorologiques, problèmes techniques, etc.), KLIN KLIN ne pourra être tenu responsable des retards de livraison.</p>
                        </div>

                        <div class="terms-article mb-5">
                            <h2 class="terms-article-title">5. Traitement des articles</h2>
                            <p>KLIN KLIN s'engage à traiter les articles confiés avec le plus grand soin, en respectant les indications d'entretien présentes sur les étiquettes.</p>
                            <p>Le Client est tenu de vérifier les poches des vêtements avant la collecte. KLIN KLIN ne pourra être tenu responsable de la perte, dégradation ou vol d'objets laissés dans les poches des vêtements.</p>
                            <p>En cas de taches particulièrement tenaces ou d'articles nécessitant un traitement spécial, KLIN KLIN se réserve le droit de facturer un supplément, après en avoir informé le Client et obtenu son accord.</p>
                        </div>

                        <div class="terms-article mb-5">
                            <h2 class="terms-article-title">6. Responsabilité et assurance</h2>
                            <p>KLIN KLIN est assuré pour les dommages qui pourraient être causés aux articles confiés par le Client dans le cadre de l'exécution des Services.</p>
                            <p>En cas de perte ou détérioration d'un article, KLIN KLIN s'engage à indemniser le Client selon les barèmes suivants :</p>
                            <ul>
                                <li>Remboursement à hauteur de 80% de la valeur d'achat pour les articles datant de moins d'un an (sur présentation d'un justificatif d'achat)</li>
                                <li>Remboursement à hauteur de 50% de la valeur d'achat pour les articles datant de plus d'un an et de moins de deux ans</li>
                                <li>Remboursement à hauteur de 30% de la valeur d'achat pour les articles datant de plus de deux ans</li>
                            </ul>
                            <p>Toutefois, KLIN KLIN ne pourra être tenu responsable des dommages résultant :</p>
                            <ul>
                                <li>Du non-respect des indications d'entretien présentes sur les étiquettes</li>
                                <li>De l'usure normale des articles</li>
                                <li>De défauts préexistants non signalés par le Client lors de la collecte</li>
                            </ul>
                        </div>

                        <div class="terms-article mb-5">
                            <h2 class="terms-article-title">7. Droit de rétractation</h2>
                            <p>Conformément à la réglementation en vigueur, le Client dispose d'un délai de 14 jours à compter de la conclusion du contrat pour exercer son droit de rétractation, sans avoir à justifier de motifs ni à payer de pénalités.</p>
                            <p>Toutefois, le droit de rétractation ne peut être exercé pour les Services pleinement exécutés avant la fin du délai de rétractation et dont l'exécution a commencé après accord préalable exprès du Client et renoncement exprès à son droit de rétractation.</p>
                        </div>

                        <div class="terms-article mb-5">
                            <h2 class="terms-article-title">8. Protection des données personnelles</h2>
                            <p>KLIN KLIN s'engage à protéger les données personnelles du Client conformément à la réglementation en vigueur. Les informations collectées sont nécessaires au traitement des commandes, à la fourniture des Services et, si le Client y a consenti, à l'envoi d'informations commerciales.</p>
                            <p>Le Client dispose d'un droit d'accès, de rectification, d'opposition et de suppression des données le concernant, qu'il peut exercer en contactant KLIN KLIN à l'adresse suivante : contact@ezaklinklin.com.</p>
                            <p>Pour plus d'informations, le Client peut consulter la Politique de Confidentialité de KLIN KLIN disponible sur le site internet.</p>
                        </div>

                        <div class="terms-article mb-5">
                            <h2 class="terms-article-title">9. Propriété intellectuelle</h2>
                            <p>Tous les éléments du site internet et de l'application mobile de KLIN KLIN (textes, images, logos, etc.) sont la propriété exclusive de KLIN KLIN ou de ses partenaires. Toute reproduction, représentation, modification, publication ou adaptation de tout ou partie de ces éléments, quel que soit le moyen ou le procédé utilisé, est interdite, sauf autorisation écrite préalable de KLIN KLIN.</p>
                        </div>

                        <div class="terms-article">
                            <h2 class="terms-article-title">10. Droit applicable et litiges</h2>
                            <p>Les présentes CGV/CGU sont soumises au droit congolais.</p>
                            <p>En cas de litige, le Client s'engage à contacter en priorité KLIN KLIN afin de tenter de résoudre le différend à l'amiable.</p>
                            <p>À défaut de résolution amiable, les tribunaux de Brazzaville seront seuls compétents pour connaître de tout litige relatif à l'existence, l'interprétation, l'exécution ou la rupture du contrat conclu entre KLIN KLIN et le Client, même en cas de pluralité de défendeurs.</p>
                        </div>

                        <div class="mt-5 pt-4 border-top terms-update-date">
                            <p>Les présentes CGV/CGU ont été mises à jour le 10 mai 2025.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
@endsection

@section('styles')
<style>
    /* Variables KLINKLIN (assurez-vous qu'elles sont définies globalement, par exemple dans :root de votre layout principal)
    :root {
        --klin-primary: #461871;
        --klin-accent: #8DFFC8;
        --klin-dark-text-color: #141B4D;
        --klin-light-background: #c6b7d3;
        --klin-cta-bg: #3F1666;
        --klin-light-text-color: #ffffff;
         --klin-light-text-color-rgb: 255, 255, 255;
    }
    */

    /* Style pour le .page-header de cette page (si différent du style global) */
    /* Le style global du .page-header (fond violet, texte blanc) devrait convenir ici */
    /* Si vous souhaitez un style spécifique pour cette page:
    .page-header {
        background-color: var(--klin-light-background);
        padding: 60px 0;
        color: var(--klin-primary);
    }
    .page-header h1 {
        color: var(--klin-primary);
    }
    .page-header p {
        color: var(--klin-dark-text-color);
    }
    */

    .terms-section {
        background-color: #f9f9f9; /* Fond légèrement gris pour la section */
    }

    .terms-content-card {
        background-color: var(--klin-blanc, #ffffff);
        padding: 2.5rem 3rem; /* Plus de padding interne */
        border-radius: 0.75rem; /* Coins arrondis */
        box-shadow: 0 0.75rem 1.5rem rgba(0,0,0,0.06); /* Ombre plus douce */
        border: 1px solid #e9ecef;
    }

    .terms-article-title {
        color: var(--klin-primary, #461871);
        font-size: 1.75rem; /* 28px */
        font-weight: 600; /* Montserrat SemiBold */
        margin-top: 2.5rem; /* Espace avant chaque titre d'article */
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--klin-accent, #8DFFC8); /* Ligne d'accent sous le titre */
        display: inline-block; /* Pour que la bordure ne prenne que la largeur du texte */
    }
    .terms-article:first-child .terms-article-title {
        margin-top: 0; /* Pas de marge en haut pour le tout premier titre */
    }

    .terms-content-card p {
        color: #555555; /* Gris foncé pour le texte principal */
        line-height: 1.8; /* Interligne amélioré */
        margin-bottom: 1rem;
    }

    .terms-content-card ul {
        color: #555555;
        line-height: 1.8;
        padding-left: 1.5rem; /* Indentation de la liste */
        margin-bottom: 1rem;
        list-style-type: disc; /* Puces standard */
    }

    .terms-content-card ul li {
        margin-bottom: 0.6rem; /* Espace entre les items de liste */
    }
    .terms-content-card ul li::marker {
        color: var(--klin-primary, #461871); /* Couleur des puces */
    }

    .terms-update-date p {
        font-style: italic;
        color: #777777; /* Texte plus clair pour la date */
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    /* Styles pour la section CTA de cette page (normalement hérités des styles globaux si définis) */
    /* Les styles .cta-section, .cta-title, .cta-description, .btn-mint, .btn-light-mint
       doivent être définis dans votre CSS global pour s'appliquer ici.
       Si ce n'est pas le cas, voici un rappel (ajustez les couleurs selon vos besoins) :
    */
    .cta-section { /* Assurez-vous que ce style est global ou défini ici */
        padding: 60px 0; /* Ajustement du padding */
        background-color: var(--klin-cta-bg, #3F1666); /* Fond violet foncé demandé */
    }
    .cta-section .cta-title {
        color: var(--klin-light-text-color, #ffffff); /* Texte blanc */
        font-weight: 700;
    }
    .cta-section .cta-description {
        color: rgba(var(--klin-light-text-color-rgb, 255, 255, 255), 0.9); /* Texte blanc légèrement transparent */
        font-size: 1.1rem; /* Taille ajustée */
    }
    /* Les styles .btn-mint et .btn-light-mint doivent aussi être disponibles globalement */
    /* Exemple:
    .btn-mint {
        background-color: var(--klin-accent, #8DFFC8);
        color: var(--klin-primary, #461871);
        border-radius: 30px; padding: 12px 28px; font-weight: 600; text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-mint:hover { background-color: #7ADCC0; transform: translateY(-2px); }

    .btn-light-mint {
        background-color: var(--klin-blanc, #ffffff);
        color: var(--klin-cta-bg, #3F1666);
        border: 1px solid rgba(var(--klin-light-text-color-rgb, 255,255,255), 0.3);
        border-radius: 30px; padding: 12px 28px; font-weight: 600; text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-light-mint:hover { background-color: rgba(var(--klin-light-text-color-rgb, 255,255,255), 0.9); transform: translateY(-2px); }
    */

    @media (max-width: 767.98px) {
        .terms-content-card {
            padding: 1.5rem; /* Moins de padding sur mobile */
        }
        .terms-article-title {
            font-size: 1.5rem; /* Titres d'article plus petits sur mobile */
        }
    }

</style>
@endsection