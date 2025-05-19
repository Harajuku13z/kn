<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture {{ $invoice_number }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 7pt;
            line-height: 1.2;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .invoice-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            width: 100%;
            display: table;
            margin-bottom: 10px;
        }
        .header-left {
            display: table-cell;
            width: 40%;
            vertical-align: middle;
        }
        .header-right {
            display: table-cell;
            width: 60%;
            text-align: right;
            vertical-align: top;
            padding-left: 10px;
        }
        .logo {
            max-width: 150px;
            max-height: 60px;
        }
        .logo-text {
            font-size: 20pt;
            font-weight: bold;
            color: #4A148C;
        }
        .invoice-title {
            font-size: 20pt;
            color: #4A148C;
            margin-top: 5px;
            margin-bottom: 5px;
        }
        .info-block {
            width: 100%;
            display: table;
            margin-bottom: 15px;
            background-color: #f8f8f8;
            border-radius: 5px;
            padding: 10px;
        }
        .info-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: right;
        }
        .customer-info {
            padding: 10px;
            margin-bottom: 15px;
            background-color: #f8f8f8;
            border-radius: 5px;
        }
        .section-title {
            font-size: 10pt;
            font-weight: bold;
            color: #4A148C;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
            margin-bottom: 5px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        table.items th {
            background-color: #4A148C;
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 4px;
            font-size: 8pt;
        }
        table.items td {
            padding: 4px;
            border-bottom: 1px solid #eee;
            font-size: 8pt;
        }
        .subtotal-row {
            background-color: #f8f8f8;
        }
        .total-row {
            background-color: #f1f1f1;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .amount {
            white-space: nowrap;
        }
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        .notes-section {
            margin-top: 5px;
            padding: 5px;
            background-color: #f8f8f8;
            border-radius: 5px;
            font-size: 7pt;
        }
        .payment-info {
            margin-top: 5px;
            padding: 5px;
            border: 1px dashed #ccc;
            border-radius: 5px;
            background-color: #f8f8f8;
            font-size: 7pt;
        }
        .footer {
            margin-top: 10px;
            padding-top: 5px;
            border-top: 1px solid #ddd;
            font-size: 7pt;
            text-align: center;
            color: #777;
        }
        .thanks {
            text-align: center;
            font-weight: bold;
            color: #4A148C;
            margin: 15px 0;
            font-size: 10pt;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- En-tête -->
        <div class="header">
            <div class="header-left">
                <div class="logo-text">{{ $company['name'] }}</div>
                <div style="font-size: 8pt; color: #777;">Service de blanchisserie professionnelle</div>
            </div>
            <div class="header-right">
                <div class="invoice-title">FACTURE</div>
                <div><strong>Facture N°:</strong> {{ $invoice_number }}</div>
                <div><strong>Date:</strong> {{ $invoice_date }}</div>
                <div><strong>Commande N°:</strong> {{ $order->id }}</div>
                <div style="margin-top: 5px;">
                    @if($order->payment_status === 'paid')
                        <span class="badge badge-success">PAYÉE</span>
                    @elseif($order->payment_status === 'pending')
                        <span class="badge badge-warning">EN ATTENTE</span>
                    @else
                        <span class="badge badge-danger">NON PAYÉE</span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Informations de contact -->
        <div class="info-block">
            <div class="info-left">
                <div class="section-title">INFORMATIONS FOURNISSEUR</div>
                <div><strong>{{ $company['name'] }}</strong></div>
                <div>{{ $company['address'] }}</div>
                <div>Tél: {{ $company['phone'] }}</div>
                <div>Email: {{ $company['email'] }}</div>
                <div>Site: {{ $company['website'] }}</div>
                @if($company['registration_number'])
                    <div>RCCM: {{ $company['registration_number'] }}</div>
                @endif
                @if($company['tax_id'])
                    <div>NIU: {{ $company['tax_id'] }}</div>
                @endif
            </div>
            <div class="info-right">
                <div class="section-title">FACTURER À</div>
                <div><strong>{{ $order->user->name }}</strong></div>
                <div>{{ $order->user->email }}</div>
                @if($order->user->phone)
                    <div>Tél: {{ $order->user->phone }}</div>
                @endif
                @if($order->deliveryAddress)
                    <div>{{ $order->deliveryAddress->address ?? '' }}{{ $order->deliveryAddress->city ? ', '.$order->deliveryAddress->city : '' }}</div>
                @endif
                <div style="margin-top: 5px;"><strong>Méthode de paiement:</strong> 
                    @if($order->payment_method === 'quota')
                        Quota
                    @elseif($order->payment_method === 'card')
                        Carte bancaire
                    @elseif($order->payment_method === 'mobile_money')
                        Mobile Money
                    @else
                        {{ ucfirst($order->payment_method) }}
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Tableau des articles -->
        <table class="items">
            <thead>
                <tr>
                    <th width="50%">Description</th>
                    <th width="15%" class="text-center">Quantité</th>
                    <th width="15%" class="text-right">Prix unitaire</th>
                    <th width="20%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            @if(strpos($item->item_type, 'service_') === 0 && isset($item->pressingService))
                                {{ $item->pressingService->name }}
                            @elseif($item->service)
                                {{ $item->service->name }}
                            @else
                                @if(is_numeric($item->item_type))
                                    Article #{{ $item->item_type }}
                                @else
                                    {{ $item->item_type }}
                                @endif
                            @endif
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right amount">{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</td>
                        <td class="text-right amount">{{ number_format($item->unit_price * $item->quantity, 0, ',', ' ') }} FCFA</td>
                    </tr>
                @endforeach
                
                <!-- Frais supplémentaires -->
                @if($order->pickup_fee > 0)
                    <tr>
                        <td>Frais de collecte</td>
                        <td class="text-center">1</td>
                        <td class="text-right amount">{{ number_format($order->pickup_fee, 0, ',', ' ') }} FCFA</td>
                        <td class="text-right amount">{{ number_format($order->pickup_fee, 0, ',', ' ') }} FCFA</td>
                    </tr>
                @endif
                
                @if($order->drop_fee > 0)
                    <tr>
                        <td>Frais de livraison</td>
                        <td class="text-center">1</td>
                        <td class="text-right amount">{{ number_format($order->drop_fee, 0, ',', ' ') }} FCFA</td>
                        <td class="text-right amount">{{ number_format($order->drop_fee, 0, ',', ' ') }} FCFA</td>
                    </tr>
                @endif
                
                <!-- Récapitulatif -->
                <tr class="subtotal-row">
                    <td colspan="3" class="text-right"><strong>Sous-total</strong></td>
                    <td class="text-right amount">
                        @php
                            $subtotal = 0;
                            foreach($order->items as $item) {
                                $subtotal += $item->unit_price * $item->quantity;
                            }
                            $subtotal += ($order->pickup_fee ?? 0) + ($order->drop_fee ?? 0);
                        @endphp
                        {{ number_format($subtotal, 0, ',', ' ') }} FCFA
                    </td>
                </tr>
                <tr class="subtotal-row">
                    <td colspan="3" class="text-right"><strong>TVA (0%)</strong></td>
                    <td class="text-right amount">0 FCFA</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" class="text-right"><strong>TOTAL</strong></td>
                    <td class="text-right amount" style="color: #4A148C; font-size: 10pt;">
                        {{ number_format($order->final_price ?? $order->estimated_price, 0, ',', ' ') }} FCFA
                    </td>
                </tr>
            </tbody>
        </table>
        
        <!-- Informations complémentaires -->
        <div style="display: table; width: 100%; margin-top: 5px;">
            <div style="display: table-cell; width: 60%; vertical-align: top; padding-right: 5px;">
                <div class="payment-info">
                    <div class="section-title">INFORMATIONS DE PAIEMENT</div>
                    <div style="display: table; width: 100%;">
                        <div style="display: table-cell; width: 50%;">
                            @if($company['payment_info']['bank_name'])
                                <div><strong>Banque:</strong> {{ $company['payment_info']['bank_name'] }}</div>
                            @endif
                            
                            @if($company['payment_info']['account'])
                                <div><strong>Compte:</strong> {{ $company['payment_info']['account'] }}</div>
                            @endif
                            
                            @if($company['payment_info']['iban'])
                                <div><strong>IBAN:</strong> {{ $company['payment_info']['iban'] }}</div>
                            @endif
                        </div>
                        <div style="display: table-cell; width: 50%;">
                            @if($company['payment_info']['bic'])
                                <div><strong>BIC:</strong> {{ $company['payment_info']['bic'] }}</div>
                            @endif
                            <div><strong>Référence:</strong> {{ $invoice_number }}</div>
                            @if($company['payment_info']['instructions'])
                                <div>{{ Str::limit($company['payment_info']['instructions'], 70) }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: table-cell; width: 40%; vertical-align: top;">
                <div class="notes-section">
                    <strong>Notes et mentions légales:</strong><br>
                    @if($company['notes'])
                        {{ Str::limit($company['notes'], 100) }}
                    @else
                        TVA non applicable en vertu des dispositions fiscales en vigueur.
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Message de remerciement et pied de page -->
        <div class="footer">
            <div class="thanks">Merci de votre confiance !</div>
            {{ $company['name'] }} - {{ $company['address'] }} - Tél: {{ $company['phone'] }}
            @if($company['registration_number'] || $company['tax_id'])
                <br>
                @if($company['registration_number'])
                    RCCM: {{ $company['registration_number'] }}
                @endif
                @if($company['registration_number'] && $company['tax_id'])
                    -
                @endif
                @if($company['tax_id'])
                    NIU: {{ $company['tax_id'] }}
                @endif
                @if($company['website'])
                    - {{ $company['website'] }}
                @endif
            @endif
        </div>
    </div>
</body>
</html> 