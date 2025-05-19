<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe modifié - KLINKLIN</title>
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
        .success-info {
            background-color: #e4f8f0;
            padding: 15px;
            border-left: 4px solid #006a4e;
            margin: 15px 0;
            border-radius: 0 4px 4px 0;
        }
        .warning-info {
            background-color: #fff8e1;
            padding: 15px;
            border-left: 4px solid #ffa000;
            margin: 15px 0;
            border-radius: 0 4px 4px 0;
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
            <p>Nous vous informons que le mot de passe de votre compte KLINKLIN a été modifié avec succès.</p>
            
            <div class="success-info">
                <p><strong>Modification effectuée :</strong> Votre mot de passe a été changé le {{ date('d/m/Y à H:i') }}.</p>
            </div>
            
            <div class="warning-info">
                <p><strong>Important :</strong> Si vous n'avez pas effectué cette modification, veuillez sécuriser votre compte immédiatement en contactant notre service client ou en réinitialisant votre mot de passe.</p>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ url('/profile') }}" class="button">Accéder à mon profil</a>
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