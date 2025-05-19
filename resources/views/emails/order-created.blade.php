<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande - KLINKLIN</title>
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
        .order-details {
            background-color: #f9f9f9;
            border-left: 4px solid #461871;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
        .order-summary {
            background-color: #f5f0fa;
            border: 1px solid #e6d9f2;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #ddd;
        }
        .order-total {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            margin-top: 10px;
            border-top: 2px solid #461871;
            font-weight: bold;
            font-size: 18px;
        }
        .address-block {
            background-color: #f9f9f9;
            padding: 15px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            background-color: #ffc107;
            color: #000;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }
        .text-right {
            text-align: right;
        }
        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 20px 0;
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
            
            <p>Nous vous confirmons la création de votre commande. Voici les détails :</p>
            
            <div class="order-details">
                <p style="text-align: center;"><span class="status-badge">{{ ucfirst($order->status) }}</span></p>
                <p><strong>Numéro de commande :</strong> #{{ $order->id }}</p>
                <p><strong>Date de création :</strong> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                <p><strong>Poids estimé :</strong> {{ $order->weight ? number_format($order->weight, 1, ',', ' ') : 'À déterminer' }} kg</p>
                <p><strong>Prix estimé :</strong> {{ $order->estimated_price ? number_format($order->estimated_price, 0, ',', ' ') : 'À déterminer' }} FCFA</p>
                <p><strong>Frais de collecte :</strong> {{ $order->pickup_fee ? number_format($order->pickup_fee, 0, ',', ' ') : '0' }} FCFA</p>
                <p><strong>Frais de livraison :</strong> {{ $order->drop_fee ? number_format($order->drop_fee, 0, ',', ' ') : '0' }} FCFA</p>
                <p><strong>Total :</strong> {{ number_format(($order->estimated_price ?? 0) + ($order->pickup_fee ?? 0) + ($order->drop_fee ?? 0), 0, ',', ' ') }} FCFA</p>
                <p><strong>Méthode de paiement :</strong> {{ ucfirst($order->payment_method ?? 'Non spécifiée') }}</p>
            </div>

            <h3>Dates importantes</h3>
            <div class="address-block">
                <p><strong>Collecte :</strong> {{ $order->pickup_date->format('d/m/Y') }} - <span style="color: #461871; font-weight: 600;">{{ $order->pickup_time_slot }}</span></p>
                <p><strong>Livraison :</strong> {{ $order->delivery_date->format('d/m/Y') }} - <span style="color: #461871; font-weight: 600;">{{ $order->delivery_time_slot }}</span></p>
            </div>
            
            <div class="divider"></div>
            
            <h3>Adresse de collecte</h3>
            <div class="address-block">
                @php
                    // Check if pickup_address is numeric (an ID) or a string (address text)
                    $pickupAddressModel = is_numeric($order->pickup_address) ? \App\Models\Address::find($order->pickup_address) : null;
                @endphp
                @if($pickupAddressModel)
                    <p><strong>{{ $pickupAddressModel->name }}</strong></p>
                    <p>{{ $pickupAddressModel->address }}</p>
                    @if(isset($pickupAddressModel->landmark) && !empty($pickupAddressModel->landmark))
                        <p>{{ $pickupAddressModel->landmark }}</p>
                    @endif
                    <p>{{ $pickupAddressModel->district ?? $pickupAddressModel->neighborhood ?? '' }}</p>
                    <p>Contact: {{ $pickupAddressModel->contact_name }} - {{ $pickupAddressModel->phone }}</p>
                @else
                    <p>{{ $order->pickup_address }}</p>
                @endif
            </div>
            
            <h3>Adresse de livraison</h3>
            <div class="address-block">
                @if($order->pickup_address == $order->delivery_address)
                    <p>Même adresse que pour la collecte</p>
                @elseif(isset($order->delivery_address))
                    @php
                        // Check if delivery_address is numeric (an ID) or a string (address text)
                        $deliveryAddressModel = is_numeric($order->delivery_address) ? \App\Models\Address::find($order->delivery_address) : null;
                    @endphp
                    @if($deliveryAddressModel)
                        <p><strong>{{ $deliveryAddressModel->name }}</strong></p>
                        <p>{{ $deliveryAddressModel->address }}</p>
                        @if(isset($deliveryAddressModel->landmark) && !empty($deliveryAddressModel->landmark))
                            <p>{{ $deliveryAddressModel->landmark }}</p>
                        @endif
                        <p>{{ $deliveryAddressModel->district ?? $deliveryAddressModel->neighborhood ?? '' }}</p>
                        <p>Contact: {{ $deliveryAddressModel->contact_name }} - {{ $deliveryAddressModel->phone }}</p>
                    @else
                        <p>{{ $order->delivery_address }}</p>
                    @endif
                @else
                    <p>Adresse non disponible</p>
                @endif
                
                @if($order->delivery_instructions)
                    <p><strong>Instructions :</strong> {{ $order->delivery_instructions }}</p>
                @endif
            </div>
            
            @if(isset($order->items))
            <h3>Articles</h3>
            <div class="order-summary">
                @php
                    $articles = [];
                    try {
                        // Try to decode JSON if it's a string
                        if (is_string($order->items)) {
                            $articles = json_decode($order->items, true) ?? [];
                        } 
                        // If it's already a collection, convert to array
                        elseif ($order->items instanceof \Illuminate\Database\Eloquent\Collection) {
                            $articles = $order->items->toArray();
                        }
                    } catch (\Exception $e) {
                        // If there's an error, set articles to empty array
                        $articles = [];
                    }
                @endphp
                
                @if(is_array($articles) && count($articles) > 0)
                    @foreach($articles as $article)
                        <div class="order-item">
                            <div>
                                @if(is_array($article))
                                    {{ $article['name'] ?? $article }}
                                    @if(isset($article['quantity']) && $article['quantity'] > 1)
                                        <span class="text-muted">(x{{ $article['quantity'] }})</span>
                                    @endif
                                    @if(isset($article['weight']))
                                        <small class="text-muted">{{ number_format($article['weight'], 1, ',', ' ') }} kg</small>
                                    @endif
                                @else
                                    {{ $article }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>Aucun article spécifique n'a été sélectionné.</p>
                @endif
                
                <div class="order-total">
                    <div>Total estimé</div>
                    <div>{{ number_format(($order->estimated_price ?? 0) + ($order->pickup_fee ?? 0) + ($order->drop_fee ?? 0), 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
            @endif
            
            <p>Vous pouvez suivre l'évolution de votre commande à tout moment en cliquant sur le bouton ci-dessous.</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/orders/' . $order->id) }}" class="button">Voir les détails de ma commande</a>
            </div>
            
            <p>Si vous avez des questions concernant votre commande, n'hésitez pas à nous contacter.</p>
            
            <p>Merci de votre confiance !<br>L'équipe KLINKLIN</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} KLINKLIN. Tous droits réservés.</p>
            <p>Cet email a été envoyé à {{ $notifiable->email }}</p>
        </div>
    </div>
</body>
</html> 