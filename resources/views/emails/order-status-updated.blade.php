<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à jour de commande - KLINKLIN</title>
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
        .status-details {
            background-color: #f9f9f9;
            border-left: 4px solid #461871;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            color: white;
        }
        .status-collected { background-color: #36b9cc; }
        .status-washing { background-color: #1cc88a; }
        .status-ironing { background-color: #4e73df; }
        .status-ready { background-color: #f6c23e; color: #333; }
        .status-delivering { background-color: #e74a3b; }
        .status-delivered { background-color: #1cc88a; }
        .status-cancelled { background-color: #858796; }
        .status-paid { background-color: #1cc88a; }
        .status-pending { background-color: #f6c23e; color: #333; }
        
        /* Timeline styles */
        .timeline {
            margin: 30px 0;
            position: relative;
        }
        .timeline-track {
            height: 4px;
            background-color: #e0e0e0;
            position: relative;
            margin: 0 auto;
            width: 90%;
        }
        .timeline-progress {
            height: 4px;
            background-color: #461871;
            position: absolute;
            top: 0;
            left: 0;
            width: 0%; /* Set dynamically based on current status */
        }
        .timeline-progress.cancelled {
            background-color: #dc3545;
        }
        .timeline-steps {
            display: flex;
            justify-content: space-between;
            margin-top: -12px;
            position: relative;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
        }
        .timeline-step {
            text-align: center;
            width: 16.66%;
        }
        .timeline-dot {
            width: 20px;
            height: 20px;
            background-color: #e0e0e0;
            border-radius: 50%;
            display: inline-block;
            margin-bottom: 8px;
            border: 3px solid white;
            box-shadow: 0 0 0 1px #e0e0e0;
            position: relative;
            z-index: 2;
        }
        .timeline-label {
            font-size: 12px;
            color: #777;
            display: block;
            margin-top: 5px;
        }
        .timeline-step.active .timeline-dot {
            background-color: #461871;
            box-shadow: 0 0 0 1px #461871;
        }
        .timeline-step.active .timeline-label {
            color: #461871;
            font-weight: 600;
        }
        .timeline-step.completed .timeline-dot {
            background-color: #1cc88a;
            box-shadow: 0 0 0 1px #1cc88a;
        }
        .timeline-step.completed .timeline-label {
            color: #1cc88a;
        }
        .timeline-step.cancelled .timeline-dot {
            background-color: #dc3545;
            box-shadow: 0 0 0 1px #dc3545;
        }
        .timeline-step.cancelled .timeline-label {
            color: #dc3545;
            font-weight: 600;
        }
        .cancelled-notice {
            background-color: #ffeaea;
            border-left: 4px solid #dc3545;
            padding: 10px 15px;
            margin: 20px 0;
            color: #721c24;
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
            
            <p>Nous vous informons que le statut de votre commande <strong>#{{ $order->id }}</strong> a été mis à jour.</p>
            
            <div class="status-details">
                <p><strong>Statut actuel :</strong> 
                    <span class="status-badge {{ $order->status == 'collected' ? 'status-collected' : ($order->status == 'washing' ? 'status-washing' : ($order->status == 'ironing' ? 'status-ironing' : ($order->status == 'ready_for_delivery' ? 'status-ready' : ($order->status == 'delivering' ? 'status-delivering' : ($order->status == 'delivered' ? 'status-delivered' : ($order->status == 'cancelled' ? 'status-cancelled' : '')))))) }}">
                        {{ $statusText }}
                    </span>
                </p>
                
                <p><strong>Statut de paiement :</strong> 
                    <span class="status-badge {{ $order->payment_status == 'paid' ? 'status-paid' : 'status-pending' }}">
                        {{ $paymentStatusText }}
                    </span>
                </p>
                
                <p><strong>Date de collecte :</strong> {{ $order->pickup_date->format('d/m/Y') }} - {{ $order->pickup_time_slot }}</p>
                <p><strong>Date de livraison prévue :</strong> {{ $order->delivery_date->format('d/m/Y') }} - {{ $order->delivery_time_slot }}</p>
            </div>
            
            @php
                $steps = [
                    'pending' => 'En attente',
                    'collected' => 'Collecté',
                    'washing' => 'Lavage',
                    'ironing' => 'Repassage',
                    'ready_for_delivery' => 'Prêt',
                    'delivering' => 'Livraison',
                    'delivered' => 'Livré'
                ];
                
                $isCancelled = $order->status == 'cancelled';
                $currentStatusIndex = array_search($order->status, array_keys($steps));
                if ($currentStatusIndex === false) {
                    $currentStatusIndex = 0; // Default to pending if not found
                }
                
                $progressWidth = 0;
                if ($isCancelled) {
                    $progressWidth = 100; // Full width for cancelled but with different color
                } else {
                    $progressWidth = min(100, ($currentStatusIndex / (count($steps) - 1)) * 100);
                }
            @endphp
            
            @if($isCancelled)
                <div class="cancelled-notice">
                    <strong>Commande annulée :</strong> Votre commande a été annulée. Si vous avez des questions, n'hésitez pas à nous contacter.
                </div>
            @endif
            
            <!-- Timeline -->
            <div class="timeline">
                <div class="timeline-track">
                    <div class="timeline-progress {{ $isCancelled ? 'cancelled' : '' }}" style="width: {{ $progressWidth }}%;"></div>
                </div>
                
                <div class="timeline-steps">
                    @foreach($steps as $key => $label)
                        @php
                            $stepIndex = array_search($key, array_keys($steps));
                            $isCompleted = $stepIndex < $currentStatusIndex && !$isCancelled;
                            $isActive = $key == $order->status && !$isCancelled;
                            $classNames = [];
                            
                            if ($isCompleted) $classNames[] = 'completed';
                            if ($isActive) $classNames[] = 'active';
                            if ($isCancelled && $key == 'pending') $classNames[] = 'cancelled';
                        @endphp
                        
                        <div class="timeline-step {{ implode(' ', $classNames) }}">
                            <div class="timeline-dot"></div>
                            <span class="timeline-label">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <p>
                @if($order->status == 'pending')
                    Votre commande est en attente de collecte. Nous vous contacterons bientôt pour confirmer la collecte.
                @elseif($order->status == 'collected')
                    Nous avons bien collecté vos articles et les traitons actuellement.
                @elseif($order->status == 'washing')
                    Vos articles sont en cours de lavage dans nos installations.
                @elseif($order->status == 'ironing')
                    Vos articles sont en cours de repassage.
                @elseif($order->status == 'ready_for_delivery')
                    Vos articles sont prêts à être livrés, nous vous contacterons bientôt pour organiser la livraison.
                @elseif($order->status == 'delivering')
                    Vos articles sont en cours de livraison à votre adresse.
                @elseif($order->status == 'delivered')
                    Vos articles ont été livrés. Nous espérons que vous êtes satisfait de notre service!
                @elseif($order->status == 'cancelled')
                    Votre commande a été annulée. Si vous avez des questions, n'hésitez pas à nous contacter.
                @endif
            </p>
            
            <p>Pour plus de détails, vous pouvez consulter votre commande en cliquant sur le bouton ci-dessous :</p>
            
            <div style="text-align: center;">
                <a href="{{ route('orders.show', $order->id) }}" class="button">Voir ma commande</a>
            </div>
            
            <p>Merci de faire confiance à KLINKLIN pour vos besoins de blanchisserie !</p>
            
            <p>Cordialement,<br>L'équipe KLINKLIN</p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} KLINKLIN. Tous droits réservés.</p>
            <p>Cet email a été envoyé à {{ $notifiable->email }}</p>
        </div>
    </div>
</body>
</html> 