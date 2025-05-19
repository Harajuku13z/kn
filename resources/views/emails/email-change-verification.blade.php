<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de modification d'adresse email - KLINKLIN</title>
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
            background-color: #ffeaea;
            border-left: 4px solid #e94057;
            margin-bottom: 20px;
            color: #333;
        }
        .email-info {
            background-color: #f4eef9;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
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
            <p>Vous avez demandé à modifier votre adresse email sur KLINKLIN.</p>
            
            <div class="email-info">
                <p>Votre adresse email actuelle <strong>{{ $oldEmail }}</strong> sera remplacée par <strong>{{ $user->email }}</strong>.</p>
            </div>
            
            <p>Pour confirmer cette modification, veuillez cliquer sur le bouton ci-dessous :</p>
            
            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="button">Confirmer mon adresse email</a>
            </div>
            
            <p>Ou copiez-collez le lien suivant dans votre navigateur :</p>
            <p style="word-break: break-all; background-color: #f8f8f8; padding: 10px; border-radius: 4px; font-size: 14px;">{{ $verificationUrl }}</p>
            
            <p>Ce lien expirera dans 60 minutes.</p>
            
            <div class="alert">
                <p><strong>Note importante :</strong> Si vous n'avez pas demandé à modifier votre adresse email, veuillez ignorer cet email et sécuriser votre compte immédiatement.</p>
            </div>
            
            <p>Cordialement,<br>L'équipe KLINKLIN</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} KLINKLIN. Tous droits réservés.</p>
            <p>Cet email a été envoyé à {{ $user->email }}</p>
        </div>
    </div>
</body>
</html> 