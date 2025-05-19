<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de changement d'email - KLINKLIN</title>
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
        .alert {
            padding: 15px;
            background-color: #f4eef9;
            border-left: 4px solid #461871;
            margin-bottom: 20px;
            color: #333;
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
            <h2>Bonjour {{ $user->name }},</h2>
            <p>Nous avons reçu une demande de changement d'adresse email pour votre compte KLINKLIN.</p>
            
            <div class="alert">
                <p><strong>Ancienne adresse email :</strong> {{ $oldEmail }}</p>
                <p><strong>Nouvelle adresse email :</strong> {{ $user->email }}</p>
            </div>
            
            <p>Pour confirmer ce changement d'email, veuillez cliquer sur le bouton ci-dessous :</p>
            
            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="button">Confirmer le changement d'email</a>
            </div>
            
            <p>Ou copiez-collez le lien suivant dans votre navigateur :</p>
            <p style="word-break: break-all;">{{ $verificationUrl }}</p>
            
            <p>Ce lien expirera dans 60 minutes.</p>
            
            <p>Si vous n'avez pas initié ce changement d'email, veuillez sécuriser votre compte immédiatement en contactant notre service client.</p>
            
            <p>Cordialement,<br>L'équipe KLINKLIN</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} KLINKLIN. Tous droits réservés.</p>
            <p>Cet email a été envoyé à {{ $oldEmail }}</p>
        </div>
    </div>
</body>
</html>
