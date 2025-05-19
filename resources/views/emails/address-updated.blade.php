<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adresse mise à jour - KLINKLIN</title>
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
        .address-details {
            background-color: #f9f9f9;
            border-left: 4px solid #41748D;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
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
            
            @php
                $addressType = $address->type === 'pickup' ? 'de collecte' : 
                              ($address->type === 'delivery' ? 'de livraison' : 
                              'de collecte et livraison');
            @endphp
            
            <p>Nous vous confirmons la mise à jour de votre adresse {{ $addressType }}.</p>
            
            <div class="address-details">
                <p><strong>Nom :</strong> {{ $address->name }}</p>
                <p><strong>Adresse :</strong> {{ $address->address }}</p>
                <p><strong>Quartier :</strong> {{ $address->neighborhood }}</p>
                <p><strong>Contact :</strong> {{ $address->contact_name }} ({{ $address->phone }})</p>
                @if($address->landmark)
                <p><strong>Point de repère :</strong> {{ $address->landmark }}</p>
                @endif
            </div>
            
            <p>Vous pouvez gérer vos adresses à tout moment depuis votre compte.</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/addresses') }}" class="button">Gérer mes adresses</a>
            </div>
            
            <p>Merci d'utiliser les services de KLINKLIN !</p>
            
            <p>Cordialement,<br>L'équipe KLINKLIN</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} KLINKLIN. Tous droits réservés.</p>
            <p>Cet email a été envoyé à {{ $notifiable->email }}</p>
        </div>
    </div>
</body>
</html> 