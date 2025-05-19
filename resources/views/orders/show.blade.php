@extends('layouts.dashboard')

@section('title', 'Détails de la commande #' . $order->id . ' - KLINKLIN')

@push('styles')
<style>
    :root {
        --klin-primary: #4A148C;
        --klin-primary-dark: #38006b;
        --klin-secondary: #f26d50;
        --klin-light-bg: #f8f5fc;
        --klin-border-color: #e0d8e7;
    }
    
    .order-detail-page .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1.5rem;
        border-radius: 0.5rem;
    }
    
    .order-detail-page .card-header {
        background-color: var(--klin-primary);
        color: white;
        border-radius: 0.5rem 0.5rem 0 0 !important;
        padding: 1rem 1.25rem;
    }
    
    .order-detail-page .card-header h5 {
        margin-bottom: 0;
        font-weight: 600;
    }
    
    .order-detail-page .card-header i {
        font-size: 1.1rem;
    }
    
    .order-detail-page .card-body {
        padding: 1.5rem;
    }
    
    .order-detail-page .badge {
        padding: 0.5em 1em;
        font-size: 0.85rem;
        border-radius: 2rem;
    }
    
    .order-detail-page .address-block {
        background-color: var(--klin-light-bg);
        border-left: 4px solid var(--klin-primary);
        padding: 1rem;
        border-radius: 0 0.25rem 0.25rem 0;
        margin: 0.5rem 0;
    }
    
    .order-detail-page .order-status-timeline {
        position: relative;
        padding-left: 2rem;
        margin-bottom: 1.5rem;
    }
    
    .order-detail-page .order-status-timeline:before {
        content: '';
        position: absolute;
        left: 0.65rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #e0e0e0;
    }
    
    .order-detail-page .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .order-detail-page .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .order-detail-page .timeline-dot {
        position: absolute;
        left: -2rem;
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 50%;
        background-color: white;
        border: 2px solid #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .order-detail-page .timeline-dot.active {
        background-color: var(--klin-primary);
        border-color: var(--klin-primary);
        color: white;
    }
    
    .order-detail-page .timeline-dot i {
        font-size: 0.7rem;
    }
    
    .order-detail-page .timeline-content {
        padding-left: 0.5rem;
    }
    
    .order-detail-page .timeline-date {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .order-detail-page .table th {
        font-weight: 600;
        color: var(--klin-primary);
    }
    
    .order-detail-page .notification-icon-circle {
        width: 4rem;
        height: 4rem;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(74,20,140,0.10);
        border: 2px solid var(--klin-primary);
    }
    
    .order-detail-page .notification-icon-circle i {
        color: var(--klin-primary);
        font-size: 2rem;
    }
    
    .order-detail-page hr {
        opacity: 0.15;
        margin: 1.5rem 0;
    }
    
    .order-detail-page .table td, 
    .order-detail-page .table th {
        vertical-align: middle;
    }
    
    .order-detail-page .status-badge {
        padding: 0.5em 1em;
        font-weight: 500;
        border-radius: 50px;
    }
    
    .order-detail-page .text-klin-primary {
        color: var(--klin-primary) !important;
    }
    
    .order-detail-page .btn-klin {
        background-color: var(--klin-primary);
        border-color: var(--klin-primary);
        color: white;
        transition: all 0.2s;
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
    }
    
    .order-detail-page .btn-klin:hover {
        background-color: var(--klin-primary-dark);
        border-color: var(--klin-primary-dark);
        transform: translateY(-2px);
    }
    
    .order-detail-page .btn-outline-klin {
        border-color: var(--klin-primary);
        color: var(--klin-primary);
        transition: all 0.2s;
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
    }
    
    .order-detail-page .btn-outline-klin:hover {
        background-color: var(--klin-primary);
        color: white;
    }
    
    .order-detail-page .instructions-block {
        background-color: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 1rem;
        border-radius: 0 0.25rem 0.25rem 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid order-detail-page">
    <div class="row mb-4 align-items-center">
        <div class="col-auto">
            <div class="notification-icon-circle">
                <i class="bi bi-box-seam-fill"></i>
            </div>
        </div>
        <div class="col">
            <h1 class="display-5 fw-bold text-klin-primary mb-1">Commande #{{ $order->id }}</h1>
            <p class="text-muted fs-5 mb-0">
                Créée le {{ $order->created_at ? $order->created_at->format('d/m/Y à H:i') : 'N/A' }}
                <span class="badge 
                    {{ $order->status == 'completed' ? 'bg-success' : 
                      ($order->status == 'pending' ? 'bg-warning' : 
                       ($order->status == 'processing' ? 'bg-info' : 'bg-secondary')) }} 
                    ms-2">
                    {{ ucfirst($order->status ?? 'non spécifié') }}
                </span>
            </p>
        </div>
        <div class="col-auto text-end">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-klin me-2">
                <i class="bi bi-arrow-left me-1"></i> Retour à la liste
            </a>
            @if(in_array($order->status, ['pending', 'processing']))
            <a href="{{ route('orders.edit', $order) }}" class="btn btn-klin">
                <i class="bi bi-pencil-fill me-1"></i> Modifier
            </a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Informations de collecte et livraison -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-emoji-smile me-2"></i> Informations de collecte</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <span class="badge bg-info text-white p-2 rounded-circle">
                                        <i class="bi bi-calendar-check"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    @if($order->pickup_date)
                                        <p class="mb-0 fw-bold">{{ $order->pickup_date->format('d/m/Y') }}</p>
                                        <p class="mb-0 text-muted">{{ $order->pickup_time_slot ?? 'Non spécifié' }}</p>
                                    @else
                                        <p class="mb-0 fw-bold">Date non spécifiée</p>
                                        <p class="mb-0 text-danger">
                                            Debug: pickup_date est null
                                        </p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="address-block mt-3">
                                @php
                                    $pickupAddressModel = \App\Models\Address::find($order->pickup_address);
                                @endphp
                                @if($pickupAddressModel)
                                    <p class="mb-0 fw-bold">{{ $pickupAddressModel->address ?? 'Non spécifiée' }}</p>
                                    @if(isset($pickupAddressModel->landmark) && !empty($pickupAddressModel->landmark))
                                        <p class="mb-0">{{ $pickupAddressModel->landmark }}</p>
                                    @endif
                                    <p class="mb-0">{{ $pickupAddressModel->neighborhood ?? '' }}</p>
                                    <p class="mb-0">Contact: {{ $pickupAddressModel->contact_name ?? 'Non spécifié' }} - {{ $pickupAddressModel->phone ?? '' }}</p>
                                @else
                                    <p class="mb-0 text-muted">Adresse non disponible (ID: {{ $order->pickup_address }})</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-truck me-2"></i> Informations de livraison</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0">
                                    <span class="badge bg-success text-white p-2 rounded-circle">
                                        <i class="bi bi-calendar-check"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-0 fw-bold">{{ $order->delivery_date ? $order->delivery_date->format('d/m/Y') : 'Non spécifiée' }}</p>
                                    <p class="mb-0 text-muted">{{ $order->delivery_time_slot ?? 'Non spécifié' }}</p>
                                </div>
                            </div>
                            
                            <div class="address-block mt-3">
                                @if($order->pickup_address == $order->delivery_address)
                                    <p class="mb-0 fst-italic">Même adresse que pour la collecte</p>
                                @elseif(isset($order->delivery_address))
                                    @php
                                        $deliveryAddressModel = \App\Models\Address::find($order->delivery_address);
                                    @endphp
                                    @if($deliveryAddressModel)
                                        <p class="mb-0 fw-bold">{{ $deliveryAddressModel->address ?? 'Non spécifiée' }}</p>
                                        @if(isset($deliveryAddressModel->landmark) && !empty($deliveryAddressModel->landmark))
                                            <p class="mb-0">{{ $deliveryAddressModel->landmark }}</p>
                                        @endif
                                        <p class="mb-0">{{ $deliveryAddressModel->district ?? '' }}</p>
                                        <p class="mb-0">Contact: {{ $deliveryAddressModel->contact_name ?? 'Non spécifié' }} - {{ $deliveryAddressModel->phone ?? '' }}</p>
                                    @else
                                        <p class="mb-0 text-muted">Adresse non disponible (ID: {{ $order->delivery_address }})</p>
                                    @endif
                                @else
                                    <p class="mb-0 text-muted">Adresse non disponible</p>
                                @endif
                            </div>

                            @if($order->delivery_instructions)
                                <div class="instructions-block mt-3">
                                    <p class="mb-0"><strong>Instructions :</strong> {{ $order->delivery_instructions }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Articles commandés -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-basket3-fill me-2"></i> Articles commandés</h5>
                </div>
                <div class="card-body">
                    @if($order->items && $order->items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Article</th>
                                        <th class="text-center">Prix unitaire</th>
                                        <th class="text-center">Quantité</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        @php
                                            $article = \App\Models\Article::find($item->item_type);
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-2">
                                                        <h6 class="mb-0">{{ $article ? $article->name : ($item->item_name ?? 'Article #'.$item->item_type) }}</h6>
                                                        <small class="text-muted">Réf: {{ $item->item_type ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end fw-bold">{{ number_format($item->unit_price * $item->quantity, 0, ',', ' ') }} FCFA</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total articles :</strong></td>
                                        <td class="text-end fw-bold">{{ $order->items->sum('quantity') }} article(s)</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Sous-total :</strong></td>
                                        <td class="text-end">
                                            @php
                                                $subtotal = 0;
                                                foreach($order->items as $item) {
                                                    $subtotal += $item->unit_price * $item->quantity;
                                                }
                                            @endphp
                                            {{ number_format($subtotal, 0, ',', ' ') }} FCFA
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Frais de collecte :</strong></td>
                                        <td class="text-end">{{ number_format($order->pickup_fee ?? 0, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Frais de livraison :</strong></td>
                                        <td class="text-end">{{ number_format($order->drop_fee ?? 0, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Taxes :</strong></td>
                                        <td class="text-end">{{ number_format($order->tax ?? 0, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total :</strong></td>
                                        <td class="text-end fw-bold text-klin-primary fs-5">
                                            @php
                                                $total = $subtotal + ($order->pickup_fee ?? 0) + ($order->drop_fee ?? 0) + ($order->tax ?? 0);
                                            @endphp
                                            {{ number_format($total, 0, ',', ' ') }} FCFA
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> Aucun article trouvé pour cette commande.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Statut de la commande -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle-fill me-2"></i> Suivi de la commande</h5>
                </div>
                <div class="card-body">
                    <div class="order-status-timeline">
                        <div class="timeline-item">
                            <div class="timeline-dot active">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Commande créée</h6>
                                <div class="timeline-date">{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}</div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-dot {{ in_array($order->status, ['collected', 'in_transit', 'washing', 'ironing', 'ready_for_delivery', 'delivering', 'delivered', 'completed']) ? 'active' : '' }}">
                                <i class="bi bi-{{ in_array($order->status, ['collected', 'in_transit', 'washing', 'ironing', 'ready_for_delivery', 'delivering', 'delivered', 'completed']) ? 'check-lg' : 'clock' }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Collecté</h6>
                                <div class="timeline-date">{{ $order->collected_at ? $order->collected_at->format('d/m/Y H:i') : 'À venir' }}</div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-dot {{ in_array($order->status, ['washing', 'ironing', 'ready_for_delivery', 'delivering', 'delivered', 'completed']) ? 'active' : '' }}">
                                <i class="bi bi-{{ in_array($order->status, ['washing', 'ironing', 'ready_for_delivery', 'delivering', 'delivered', 'completed']) ? 'check-lg' : 'clock' }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Lavage</h6>
                                <div class="timeline-date">{{ $order->washing_at ? $order->washing_at->format('d/m/Y H:i') : 'À venir' }}</div>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-dot {{ in_array($order->status, ['ironing', 'ready_for_delivery', 'delivering', 'delivered', 'completed']) ? 'active' : '' }}">
                                <i class="bi bi-{{ in_array($order->status, ['ironing', 'ready_for_delivery', 'delivering', 'delivered', 'completed']) ? 'check-lg' : 'clock' }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Repassage</h6>
                                <div class="timeline-date">{{ $order->ironing_at ? $order->ironing_at->format('d/m/Y H:i') : 'À venir' }}</div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-dot {{ in_array($order->status, ['ready_for_delivery', 'delivering', 'delivered', 'completed']) ? 'active' : '' }}">
                                <i class="bi bi-{{ in_array($order->status, ['ready_for_delivery', 'delivering', 'delivered', 'completed']) ? 'check-lg' : 'clock' }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Prêt à la livraison</h6>
                                <div class="timeline-date">{{ $order->ready_at ? $order->ready_at->format('d/m/Y H:i') : 'À venir' }}</div>
                            </div>
                        </div>
                        
                        <div class="timeline-item">
                            <div class="timeline-dot {{ in_array($order->status, ['delivered', 'completed']) ? 'active' : '' }}">
                                <i class="bi bi-{{ in_array($order->status, ['delivered', 'completed']) ? 'check-lg' : 'clock' }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Livré</h6>
                                <div class="timeline-date">{{ $order->completed_at ? $order->completed_at->format('d/m/Y H:i') : 'À venir' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <div>Statut de la commande:</div>
                        <div>
                            <span class="badge 
                                {{ $order->status == 'delivered' ? 'bg-success' : 
                                ($order->status == 'pending' ? 'bg-warning' : 
                                ($order->status == 'cancelled' ? 'bg-danger' : 
                                ($order->status == 'ready_for_delivery' ? 'bg-primary' : 
                                ($order->status == 'collected' ? 'bg-info' : 'bg-secondary')))) }}">
                                @php
                                    $statusLabels = [
                                        'pending' => 'En attente',
                                        'collected' => 'Collecté',
                                        'in_transit' => 'En transit',
                                        'washing' => 'Lavage',
                                        'ironing' => 'Repassage',
                                        'ready_for_delivery' => 'Prêt pour livraison',
                                        'delivering' => 'En cours de livraison',
                                        'delivered' => 'Livré',
                                        'completed' => 'Terminé',
                                        'cancelled' => 'Annulé'
                                    ];
                                    echo $statusLabels[$order->status] ?? ucfirst($order->status ?? 'non spécifié');
                                @endphp
                            </span>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <div>Statut du paiement:</div>
                        <div>
                            <span class="badge 
                                {{ $order->payment_status == 'paid' ? 'bg-success' : 
                                ($order->payment_status == 'pending' ? 'bg-warning' : 
                                ($order->payment_status == 'failed' ? 'bg-danger' : 'bg-secondary')) }}">
                                @php
                                    $paymentStatusLabels = [
                                        'pending' => 'En attente',
                                        'paid' => 'Payé',
                                        'failed' => 'Échoué',
                                        'refunded' => 'Remboursé'
                                    ];
                                    echo $paymentStatusLabels[$order->payment_status] ?? ucfirst($order->payment_status ?? 'non spécifié');
                                @endphp
                            </span>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <div>Méthode de paiement:</div>
                        <div class="fw-bold">{{ ucfirst($order->payment_method) ?: 'Non spécifiée' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Résumé de la commande -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i> Résumé</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Poids total</span>
                        <span class="fs-5 fw-bold">{{ number_format($order->weight ?? 0, 1, ',', ' ') }} kg</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Articles</span>
                        <span>{{ $order->items ? $order->items->count() : 0 }} type(s)</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Montant total</span>
                        <span class="fs-5 fw-bold text-klin-primary">
                            @php
                                $orderTotal = 0;
                                foreach($order->items as $item) {
                                    $orderTotal += $item->unit_price * $item->quantity;
                                }
                                $orderTotal += ($order->pickup_fee ?? 0) + ($order->drop_fee ?? 0) + ($order->tax ?? 0);
                            @endphp
                            {{ number_format($orderTotal, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                    
                    <hr>
                    
                    @if(in_array($order->status, ['pending']))
                    <div class="d-grid gap-2">
                        <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-x-circle me-1"></i> Annuler la commande
                            </button>
                        </form>
                    </div>
                    @endif
                    
                    @if(in_array($order->status, ['delivered', 'completed']))
                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('orders.invoice.download', $order->id) }}" class="btn btn-success w-100" id="downloadInvoiceBtn">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Télécharger la facture
                        </a>
                        <p class="text-muted small text-center mt-1 mb-0">
                            <i class="bi bi-info-circle me-1"></i> La génération du PDF peut prendre quelques secondes
                        </p>
                    </div>
                    <script>
                        document.getElementById('downloadInvoiceBtn').addEventListener('click', function(e) {
                            this.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Téléchargement...';
                            this.classList.add('disabled');
                            
                            // Réactiver le bouton après 10 secondes
                            setTimeout(function() {
                                document.getElementById('downloadInvoiceBtn').innerHTML = '<i class="bi bi-file-earmark-pdf me-1"></i> Télécharger la facture';
                                document.getElementById('downloadInvoiceBtn').classList.remove('disabled');
                            }, 10000);
                        });
                    </script>
                    @endif
                </div>
            </div>

            <!-- Section du détail des frais -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Détail des frais</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <!-- ... existing rows ... -->
                                
                                <!-- Frais de livraison -->
                                <tr>
                                    <th>Frais de livraison</th>
                                    <td class="text-end">
                                        @if($order->deliveryVoucher)
                                            <span class="text-success">
                                                <i class="bi bi-ticket-perforated me-1"></i>
                                                Livraison gratuite (Bon {{ $order->deliveryVoucher->code }})
                                            </span>
                                        @else
                                            {{ number_format($order->pickup_fee + $order->drop_fee, 0, ',', ' ') }} FCFA
                                        @endif
                                    </td>
                                </tr>
                                
                                <!-- ... existing rows ... -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 