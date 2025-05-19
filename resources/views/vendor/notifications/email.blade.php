<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>{{ $level === 'error' ? 'Erreur' : 'Notification' }} - KLINKLIN</title>
    <style>
        body {
            font-family: 'Montserrat', 'Helvetica', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
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
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #315946;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777777;
        }
        .signature {
            margin-top: 30px;
        }
        h1, h2, h3, h4, h5, h6 {
            color: #461871;
        }
        a {
            color: #461871;
            text-decoration: none;
        }
        p {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>KLINKLIN</h1>
    </div>

    <div class="content">
        <h2>{{ $greeting ?? 'Bonjour!' }}</h2>

        @foreach ($introLines as $line)
            <p>{{ $line }}</p>
        @endforeach

        @isset($actionText)
            <div style="text-align: center;">
                <a href="{{ $actionUrl }}" class="button">{{ $actionText }}</a>
            </div>
            
            <p style="margin-top: 25px;">Si vous avez des difficultés pour cliquer sur le bouton "{{ $actionText }}", copiez et collez l'URL ci-dessous dans votre navigateur web :</p>
            <p style="word-break: break-all;">{{ $actionUrl }}</p>
        @endisset

        @foreach ($outroLines as $line)
            <p>{{ $line }}</p>
        @endforeach

        <div class="signature">
            <p>Cordialement,<br>L'équipe KLINKLIN</p>
        </div>
        
        @isset($displayableActionUrl)
            <div style="margin-top: 25px;">
                <p style="font-size: 12px; color: #777777;">Si vous avez des questions, n'hésitez pas à nous contacter à <a href="mailto:klinklin@touna.cloud">klinklin@touna.cloud</a></p>
            </div>
        @endisset
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} KLINKLIN. Tous droits réservés.</p>
    </div>
</div>
</body>
</html>
