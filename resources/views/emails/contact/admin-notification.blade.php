<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact - KLINKLIN</title>
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
        .field {
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: bold;
            color: #461871;
            display: block;
            margin-bottom: 5px;
        }
        .field-value {
            background-color: #f9f9f9;
            padding: 12px;
            border-radius: 4px;
            border-left: 3px solid #461871;
        }
        .message-content {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            border-left: 3px solid #461871;
            white-space: pre-line;
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
            <h2>Nouveau message de contact</h2>
            <p>Vous avez reçu un nouveau message depuis le formulaire de contact de votre site web.</p>
            
            <div class="field">
                <span class="field-label">Nom :</span>
                <div class="field-value">{{ $data['name'] }}</div>
            </div>
            
            <div class="field">
                <span class="field-label">Email :</span>
                <div class="field-value">{{ $data['email'] }}</div>
            </div>
            
            <div class="field">
                <span class="field-label">Sujet :</span>
                <div class="field-value">{{ $data['subject'] }}</div>
            </div>
            
            <div class="field">
                <span class="field-label">Message :</span>
                <div class="message-content">{{ $data['message'] }}</div>
            </div>
            
            <p>Pour y répondre, vous pouvez simplement répondre à cet email ou contacter directement l'expéditeur à l'adresse indiquée.</p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} KLIN KLIN. Tous droits réservés.</p>
            <p>1184 rue Louassi, Business Center Plateau, Brazzaville - Congo</p>
        </div>
    </div>
</body>
</html> 