<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre profil a été mis à jour - KLINKLIN</title>
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
        .changes-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .changes-table th, .changes-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        .changes-table th {
            background-color: #f4eef9;
            color: #461871;
            font-weight: 600;
        }
        .changes-table tr:last-child td {
            border-bottom: none;
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
            <p>Votre profil KLINKLIN a été mis à jour avec succès.</p>
            
            @if(count($changedFields) > 0)
                <p><strong>Les informations suivantes ont été modifiées :</strong></p>
                
                <table class="changes-table">
                    <thead>
                        <tr>
                            <th>Information</th>
                            <th>Nouvelle valeur</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($changedFields as $field => $value)
                            <tr>
                                <td>{{ $field }}</td>
                                <td>{{ $value }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            
            <p>Si vous n'avez pas effectué ces modifications, veuillez sécuriser votre compte immédiatement en contactant notre service client.</p>
            
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