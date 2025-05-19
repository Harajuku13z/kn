<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abonnement mis à jour - KLINKLIN</title>
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
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            color: white;
        }
        .status-paid {
            background-color: #28a745;
        }
        .status-pending {
            background-color: #ffc107;
            color: #333;
        }
        .status-cancelled {
            background-color: #dc3545;
        }
        .notification-icon {
            text-align: center;
            margin: 20px 0;
        }
        .notification-icon i {
            font-size: 48px;
            color: #461871;
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
            
            <p>Les informations de votre abonnement ont été mises à jour.</p>
            
            <div style="text-align: center; margin: 25px 0;">
                <span class="status-badge {{ $subscription->payment_status == 'paid' ? 'status-paid' : ($subscription->payment_status == 'pending' ? 'status-pending' : 'status-cancelled') }}">
                    {{ $subscription->formattedPaymentStatus }}
                </span>
            </div>
            
            <div class="subscription-details">
                <p><strong>Détails actualisés :</strong></p>
                <p><strong>Type :</strong> {{ $subscription->subscriptionType->name }}</p>
                <p><strong>Quota :</strong> {{ $subscription->quota_purchased }} kg</p>
                <p><strong>Montant :</strong> {{ number_format($subscription->amount_paid, 0, ',', ' ') }} FCFA</p>
                <p><strong>Méthode de paiement :</strong> {{ $subscription->formattedPaymentMethod }}</p>
                @if($subscription->expiration_date)
                <p><strong>Date d'expiration :</strong> {{ $subscription->expiration_date->format('d/m/Y') }}</p>
                @endif
            </div>
            
            @if($subscription->payment_status == 'paid')
            <p>Votre abonnement est actif et vous pouvez utiliser votre quota pour vos commandes.</p>
            @elseif($subscription->payment_status == 'pending')
            <p>Votre abonnement est en attente de paiement. Une fois le paiement confirmé, vous pourrez utiliser votre quota.</p>
            @else
            <p>Votre abonnement a été annulé. Si vous n'avez pas demandé cette annulation, veuillez nous contacter rapidement.</p>
            @endif
            
            <div style="text-align: center;">
                <a href="{{ url('/subscriptions') }}" class="button">Voir mes abonnements</a>
            </div>
            
            <p>Si vous avez des questions concernant cette mise à jour, n'hésitez pas à nous contacter.</p>
            
            <p>Merci de votre confiance !<br>L'équipe KLINKLIN</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} KLINKLIN. Tous droits réservés.</p>
            <p>Cet email a été envoyé à {{ $notifiable->email }}</p>
        </div>
    </div>
</body>
</html> 