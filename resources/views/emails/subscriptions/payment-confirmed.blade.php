<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement confirmé - KLINKLIN</title>
    <style>
        body {
            font-family: 'Montserrat', 'Helvetica', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            background-color: #461871;
            color: white;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 30px 20px;
            border: 1px solid #e0e0e0;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #461871;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
        .subscription-details {
            background-color: #f9f9f9;
            border-left: 4px solid #461871;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
        .success-badge {
            display: inline-block;
            padding: 6px 12px;
            background-color: #28a745;
            color: white;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('img/logo.png') }}" alt="KLINKLIN Logo" style="max-width: 150px; margin-bottom: 10px;">
            <h1>KLINKLIN</h1>
        </div>
        <div class="content">
            <h2>Bonjour {{ $notifiable->name }},</h2>
            
            <p>Nous vous confirmons que votre paiement a été reçu et validé.</p>
            
            <div style="text-align: center; margin: 25px 0;">
                <span class="success-badge">Paiement confirmé</span>
            </div>
            
            <div class="subscription-details">
                <p><strong>Détails de l'abonnement :</strong></p>
                <p><strong>Quota disponible :</strong> {{ $subscription->quota_purchased }} kg</p>
                <p><strong>Montant :</strong> {{ number_format($subscription->amount_paid, 0, ',', ' ') }} FCFA</p>
                <p><strong>Méthode de paiement :</strong> {{ $subscription->formattedPaymentMethod }}</p>
                @if($subscription->expiration_date)
                <p><strong>Date d'expiration :</strong> {{ $subscription->expiration_date->format('d/m/Y') }}</p>
                @endif
            </div>
            
            <p>Votre quota est maintenant disponible pour vos commandes de blanchisserie. Vous pouvez facilement planifier une collecte depuis votre compte.</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/subscriptions') }}" class="button">Voir mes abonnements</a>
            </div>
            
            <p>Si vous avez des questions concernant votre abonnement, n'hésitez pas à nous contacter.</p>
            
            <p>Merci de votre confiance !<br>L'équipe KLINKLIN</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} KLINKLIN. Tous droits réservés.</p>
            <p>Cet email a été envoyé à {{ $notifiable->email }}</p>
        </div>
    </div>
</body>
</html> 