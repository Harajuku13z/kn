<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponse à votre ticket de support - KLINKLIN</title>
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
        .message-details {
            background-color: #f9f9f9;
            border-left: 4px solid #461871;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
            color: #856404;
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
            
            <p>Une nouvelle réponse a été ajoutée à votre ticket de support.</p>
            
            <div class="message-details">
                <p><strong>Référence du ticket :</strong> {{ $ticket->reference_number }}</p>
                <p><strong>Sujet :</strong> {{ $ticket->subject }}</p>
                <p><strong>Message :</strong></p>
                <p>{{ $message_excerpt }}</p>
            </div>
            
            @if($ticket_closed)
            <div class="warning">
                <p>⚠️ Ce ticket a été marqué comme résolu et fermé par notre équipe de support.</p>
            </div>
            @endif
            
            <p>Vous pouvez consulter l'intégralité de la conversation depuis votre compte.</p>
            
            <div style="text-align: center;">
                <a href="{{ $action_url }}" class="button">Voir la conversation complète</a>
            </div>
            
            <p>Si vous avez des difficultés pour cliquer sur le bouton, copiez et collez l'URL ci-dessous dans votre navigateur web :</p>
            <p style="word-break: break-all;">{{ $action_url }}</p>
            
            <p>Merci d'utiliser les services de KLINKLIN !</p>
            
            <p>Cordialement,<br>L'équipe KLINKLIN</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} KLINKLIN. Tous droits réservés.</p>
            <p>Cet email a été envoyé à {{ $notifiable->email }}</p>
            <p>Si vous avez des questions, n'hésitez pas à nous contacter à klinklin@touna.cloud</p>
        </div>
    </div>
</body>
</html> 