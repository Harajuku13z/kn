<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $order->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #461871;
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            margin-top: 0;
        }
        
        .company-details {
            margin-bottom: 30px;
        }
        
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .invoice-info .left {
            width: 60%;
        }
        
        .invoice-info .right {
            width: 35%;
            text-align: right;
        }
        
        .invoice-info h3 {
            color: #461871;
            margin-bottom: 10px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .table th {
            background-color: #461871;
            color: white;
            text-align: left;
            padding: 10px;
        }
        
        .table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .subtotal {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
        
        .subtotal .label {
            width: 150px;
            text-align: right;
            font-weight: bold;
            padding-right: 20px;
        }
        
        .total {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
            font-size: 16px;
            font-weight: bold;
        }
        
        .total .label {
            width: 150px;
            text-align: right;
            padding-right: 20px;
            color: #461871;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 11px;
        }
        
        .payment-info {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>KLINKLIN</h1>
            <p>Service de blanchisserie éco-responsable</p>
        </div>
        
        <div class="company-details">
            <p>
                <strong>KLINKLIN</strong><br>
                1184 rue Louassi, Business Center Plateau<br>
                Brazzaville - Congo<br>
                Tel: +242 069349160<br>
                Email: contact@ezaklinklin.com
            </p>
        </div>
        
        <div class="invoice-info">
            <div class="left">
                <h3>Facturé à</h3>
                <p>
                    <strong>{{ $order->user->name ?? 'Client' }}</strong><br>
                    {{ $order->user->email ?? '' }}<br>
                    @if($order->deliveryAddress)
                        {{ $order->deliveryAddress->address_line1 }}<br>
                        @if($order->deliveryAddress->address_line2)
                            {{ $order->deliveryAddress->address_line2 }}<br>
                        @endif
                        {{ $order->deliveryAddress->city }}, {{ $order->deliveryAddress->postal_code }}<br>
                        @if($order->deliveryAddress->phone)
                            Tel: {{ $order->deliveryAddress->phone }}
                        @endif
                    @endif
                </p>
            </div>
            <div class="right">
                <h3>Facture</h3>
                <p>
                    <strong>Facture #:</strong> {{ $order->invoice_number ?? $order->id }}<br>
                    <strong>Date de facture:</strong> {{ $date }}<br>
                    <strong>Date de commande:</strong> {{ $order->created_at->format('d/m/Y') }}<br>
                    <strong>Statut de paiement:</strong> {{ ucfirst($order->payment_status) }}<br>
                </p>
            </div>
        </div>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            @if($item->service)
                                {{ $item->service->name }}
                            @elseif($item->pressingService)
                                {{ $item->pressingService->name }}
                            @else
                                {{ $item->description ?? 'Article #'.$item->item_type }}
                            @endif
                            @if($item->pressingService && $item->pressingService->pressing)
                                ({{ $item->pressingService->pressing->name }})
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price, 0, '.', ' ') }} FCFA</td>
                        <td>{{ number_format($item->quantity * $item->unit_price, 0, '.', ' ') }} FCFA</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="subtotal">
            <div class="label">Sous-total:</div>
            <div class="value">{{ number_format($order->items->sum(function($item) { return $item->quantity * $item->unit_price; }), 0, '.', ' ') }} FCFA</div>
        </div>
        
        @if($order->drop_fee > 0)
        <div class="subtotal">
            <div class="label">Frais de livraison:</div>
            <div class="value">{{ number_format($order->drop_fee, 0, '.', ' ') }} FCFA</div>
        </div>
        @endif
        
        @if($order->pickup_fee > 0)
        <div class="subtotal">
            <div class="label">Frais de collecte:</div>
            <div class="value">{{ number_format($order->pickup_fee, 0, '.', ' ') }} FCFA</div>
        </div>
        @endif
        
        <div class="total">
            <div class="label">TOTAL:</div>
            <div class="value">{{ number_format($order->total, 0, '.', ' ') }} FCFA</div>
        </div>
        
        <div class="payment-info">
            <h3>Informations de paiement</h3>
            <p>
                <strong>Méthode de paiement:</strong> 
                @if($order->payment_method == 'cash_on_delivery')
                    Paiement à la livraison
                @elseif($order->payment_method == 'card')
                    Carte bancaire
                @elseif($order->payment_method == 'mobile_money')
                    Mobile Money
                @elseif($order->payment_method == 'quota')
                    Quota d'abonnement
                @else
                    {{ ucfirst($order->payment_method ?? 'N/A') }}
                @endif
            </p>
            <p>
                <strong>Statut du paiement:</strong> 
                @if($order->payment_status == 'paid')
                    Payé
                @elseif($order->payment_status == 'pending')
                    En attente de paiement
                @else
                    {{ ucfirst($order->payment_status ?? 'N/A') }}
                @endif
            </p>
        </div>
        
        <div class="footer">
            <p>Merci de faire confiance à KLINKLIN pour vos besoins de blanchisserie.</p>
            <p>&copy; {{ date('Y') }} KLINKLIN. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html> 