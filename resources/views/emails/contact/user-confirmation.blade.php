<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de votre message - KLINKLIN</title>
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
        .summary {
            background-color: #f9f9f9;
            border-left: 4px solid #461871;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
        .summary p {
            margin: 10px 0;
        }
        .summary strong {
            color: #461871;
        }
        .social-links {
            text-align: center;
            margin-top: 20px;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #461871;
            font-size: 14px;
            text-decoration: none;
        }
        .highlight-box {
            background-color: #f4eef9;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border-radius: 4px;
        }
        .highlight-box h2 {
            color: #461871;
            margin-top: 0;
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
            <div class="highlight-box">
                <h2>Merci pour votre message !</h2>
                <p>Nous avons bien reçu votre demande et nous vous répondrons dans les meilleurs délais.</p>
            </div>
            
            <h2>Bonjour {{ $data['name'] }},</h2>
            
            <p>Nous vous confirmons la bonne réception de votre message. Un membre de notre équipe l'examinera et vous répondra dans un délai de 24 à 48 heures ouvrées.</p>
            
            <h3>Récapitulatif de votre message :</h3>
            
            <div class="summary">
                <p><strong>Sujet :</strong> {{ $data['subject'] }}</p>
                <p><strong>Message :</strong><br>{{ $data['message'] }}</p>
            </div>
            
            <p>Si vous avez besoin d'une assistance immédiate, n'hésitez pas à nous contacter par téléphone au <strong>+242 06 934 91 60</strong> durant nos heures d'ouverture (Lundi - Samedi : 8h00 - 18h00).</p>
            
            <div style="text-align: center;">
                <a href="{{ route('home') }}" class="button">Visiter notre site</a>
            </div>
            
            <p>Cordialement,<br>L'équipe KLINKLIN</p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} KLIN KLIN. Tous droits réservés.</p>
            <p>1184 rue Louassi, Business Center Plateau, Brazzaville - Congo</p>
            
            <div class="social-links">
                <a href="#" title="Facebook">Facebook</a>
                <a href="#" title="Instagram">Instagram</a>
                <a href="#" title="Twitter">Twitter</a>
            </div>
        </div>
    </div>
</body>
</html> 