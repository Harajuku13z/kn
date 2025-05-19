@extends('layouts.dashboard')

@section('title', 'Détail de commande - KLINKLIN')

@section('content')
<div class="dashboard-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-klin-primary">Détail de la commande #{{ $order->id }}</h1>
        <div>
            <a href="{{ route('history.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Retour à l'historique
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Résumé de la commande -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-klin-primary"><i class="bi bi-box-seam me-2"></i>Résumé de la commande</h5>
                    <a href="{{ route('orders.invoice.download', $order->id) }}" class="btn btn-success btn-sm" id="invoiceTopBtn">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Télécharger la facture
                    </a>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="border-bottom pb-2">Informations générales</h6>
                                <div class="mb-2">
                                    <span class="text-muted">Date de commande:</span>
                                    <div class="fw-medium">{{ $order->created_at->format('d/m/Y à H:i') }}</div>
                                </div>
                                <div class="mb-2">
                                    <span class="text-muted">Type de commande:</span>
                                    <div>
                                        @if($order->order_type === 'kilogram')
                                            <span class="badge bg-info">Lavage au kilo</span>
                                        @elseif($order->order_type === 'pressing')
                                            <span class="badge bg-primary">Pressing</span>
                                            @if($order->pressing)
                                                <div class="small text-muted mt-1">Pressing: {{ $order->pressing->name }}</div>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Standard</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <span class="text-muted">Statut:</span>
                                    <div>
                                        @if($order->status === 'pending')
                                            <span class="badge bg-warning text-dark">En attente</span>
                                        @elseif($order->status === 'scheduled')
                                            <span class="badge bg-info">Planifiée</span>
                                        @elseif($order->status === 'collected')
                                            <span class="badge bg-primary">Collectée</span>
                                        @elseif($order->status === 'processing')
                                            <span class="badge bg-info">En traitement</span>
                                        @elseif($order->status === 'ready_for_delivery')
                                            <span class="badge bg-warning text-dark">Prête pour livraison</span>
                                        @elseif($order->status === 'delivered' || $order->status === 'completed')
                                            <span class="badge bg-success">Terminée</span>
                                        @elseif($order->status === 'cancelled')
                                            <span class="badge bg-danger">Annulée</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <span class="text-muted">Paiement:</span>
                                    <div>
                                        @if($order->payment_status === 'paid')
                                            <span class="badge bg-success">Payé</span>
                                        @elseif($order->payment_status === 'pending')
                                            <span class="badge bg-warning text-dark">En attente</span>
                                        @elseif($order->payment_status === 'failed')
                                            <span class="badge bg-danger">Échec</span>
                                        @endif
                                        
                                        <span class="small text-muted ms-2">
                                            via 
                                            @if($order->payment_method === 'quota')
                                                Quota
                                            @elseif($order->payment_method === 'card')
                                                Carte bancaire
                                            @elseif($order->payment_method === 'mobile_money')
                                                Mobile Money
                                            @else
                                                {{ $order->payment_method }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="border-bottom pb-2">Dates et adresses</h6>
                                <div class="mb-2">
                                    <span class="text-muted">Collecte:</span>
                                    <div class="fw-medium">
                                        {{ $order->pickup_date ? $order->pickup_date->format('d/m/Y') : 'N/A' }}
                                        <span class="text-muted small">{{ $order->pickup_time_slot ?? '' }}</span>
                                    </div>
                                    @if($order->pickupAddress)
                                        <div class="small">{{ $order->pickupAddress->address }}</div>
                                    @endif
                                </div>
                                <div class="mb-0">
                                    <span class="text-muted">Livraison:</span>
                                    <div class="fw-medium">
                                        {{ $order->delivery_date ? $order->delivery_date->format('d/m/Y') : 'N/A' }}
                                        <span class="text-muted small">{{ $order->delivery_time_slot ?? '' }}</span>
                                    </div>
                                    @if($order->deliveryAddress)
                                        <div class="small">{{ $order->deliveryAddress->address }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Détails des articles -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-klin-primary"><i class="bi bi-list-ul me-2"></i>Détails des articles</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Article</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-end">Prix unitaire</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            @if($item->item_type === 'pressing_service' && $item->pressingService)
                                                {{ $item->pressingService->name }}
                                            @elseif($item->service)
                                                {{ $item->service->name }}
                                            @else
                                                Article #{{ $item->item_type }}
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</td>
                                        <td class="text-end">{{ number_format($item->unit_price * $item->quantity, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                @endforeach
                                
                                <!-- Frais de livraison/collecte -->
                                @if($order->pickup_fee > 0)
                                    <tr class="table-light">
                                        <td>Frais de collecte</td>
                                        <td class="text-center">1</td>
                                        <td class="text-end">{{ number_format($order->pickup_fee, 0, ',', ' ') }} FCFA</td>
                                        <td class="text-end">{{ number_format($order->pickup_fee, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                @endif
                                
                                @if($order->drop_fee > 0)
                                    <tr class="table-light">
                                        <td>Frais de livraison</td>
                                        <td class="text-center">1</td>
                                        <td class="text-end">{{ number_format($order->drop_fee, 0, ',', ' ') }} FCFA</td>
                                        <td class="text-end">{{ number_format($order->drop_fee, 0, ',', ' ') }} FCFA</td>
                                    </tr>
                                @endif
                                
                                <!-- Ligne de total -->
                                <tr class="fw-bold">
                                    <td colspan="3" class="text-end">Total</td>
                                    <td class="text-end text-klin-primary">{{ number_format($order->final_price ?? $order->estimated_price, 0, ',', ' ') }} FCFA</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Notes et instructions spéciales -->
            @if($order->special_instructions)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-klin-primary"><i class="bi bi-chat-left-text me-2"></i>Instructions spéciales</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->special_instructions }}</p>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-lg-4">
            <!-- Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-klin-primary"><i class="bi bi-box-arrow-right me-2"></i>Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('orders.invoice.download', $order->id) }}" class="btn btn-success" id="invoiceMainBtn">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Télécharger la facture
                        </a>
                        <a href="{{ route('orders.create') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-repeat me-1"></i> Commander à nouveau
                        </a>
                        @if($order->status === 'delivered' || $order->status === 'completed')
                            <a href="#" class="btn btn-outline-secondary">
                                <i class="bi bi-chat-dots me-1"></i> Laisser un avis
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Suivi de commande -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-klin-primary"><i class="bi bi-truck me-2"></i>Suivi de commande</h5>
                </div>
                <div class="card-body p-0">
                    <div class="timeline-steps">
                        <div class="timeline-step {{ in_array($order->status, ['pending', 'scheduled', 'collected', 'processing', 'ready_for_delivery', 'delivered', 'completed']) ? 'complete' : '' }}">
                            <div class="timeline-content">
                                <div class="timeline-point"></div>
                                <h6 class="mb-1">Commande créée</h6>
                                <p class="mb-0 text-muted small">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        <div class="timeline-step {{ in_array($order->status, ['scheduled', 'collected', 'processing', 'ready_for_delivery', 'delivered', 'completed']) ? 'complete' : '' }}">
                            <div class="timeline-content">
                                <div class="timeline-point"></div>
                                <h6 class="mb-1">Collecte planifiée</h6>
                                <p class="mb-0 text-muted small">{{ $order->pickup_date ? $order->pickup_date->format('d/m/Y') : 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="timeline-step {{ in_array($order->status, ['collected', 'processing', 'ready_for_delivery', 'delivered', 'completed']) ? 'complete' : '' }}">
                            <div class="timeline-content">
                                <div class="timeline-point"></div>
                                <h6 class="mb-1">Articles collectés</h6>
                                <p class="mb-0 text-muted small">{{ $order->status === 'collected' ? $order->updated_at->format('d/m/Y') : 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="timeline-step {{ in_array($order->status, ['processing', 'ready_for_delivery', 'delivered', 'completed']) ? 'complete' : '' }}">
                            <div class="timeline-content">
                                <div class="timeline-point"></div>
                                <h6 class="mb-1">En traitement</h6>
                                <p class="mb-0 text-muted small">{{ $order->status === 'processing' ? $order->updated_at->format('d/m/Y') : 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="timeline-step {{ in_array($order->status, ['ready_for_delivery', 'delivered', 'completed']) ? 'complete' : '' }}">
                            <div class="timeline-content">
                                <div class="timeline-point"></div>
                                <h6 class="mb-1">Prêt pour livraison</h6>
                                <p class="mb-0 text-muted small">{{ $order->status === 'ready_for_delivery' ? $order->updated_at->format('d/m/Y') : 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="timeline-step {{ in_array($order->status, ['delivered', 'completed']) ? 'complete' : '' }}">
                            <div class="timeline-content">
                                <div class="timeline-point"></div>
                                <h6 class="mb-1">Livré</h6>
                                <p class="mb-0 text-muted small">{{ in_array($order->status, ['delivered', 'completed']) ? $order->updated_at->format('d/m/Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Script pour les boutons de facture -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Fonction pour gérer le clic sur les boutons de facture
                    function handleInvoiceClick(button) {
                        var originalText = button.innerHTML;
                        button.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Génération en cours...';
                        button.classList.add('disabled');
                        
                        setTimeout(function() {
                            button.innerHTML = originalText;
                            button.classList.remove('disabled');
                        }, 10000);
                    }
                    
                    // Ajouter les écouteurs d'événements
                    document.getElementById('invoiceTopBtn').addEventListener('click', function() {
                        handleInvoiceClick(this);
                    });
                    
                    document.getElementById('invoiceMainBtn').addEventListener('click', function() {
                        handleInvoiceClick(this);
                    });
                });
            </script>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .text-klin-primary {
        color: #4A148C;
    }
    .badge {
        font-weight: 500;
        padding: 0.5em 0.75em;
    }
    
    /* Timeline style */
    .timeline-steps {
        position: relative;
        padding: 1.5rem;
    }
    
    .timeline-step {
        position: relative;
        padding-left: 2.5rem;
        padding-bottom: 1.5rem;
        border-left: 1px solid #dee2e6;
    }
    
    .timeline-step:last-child {
        padding-bottom: 0;
    }
    
    .timeline-content {
        position: relative;
    }
    
    .timeline-point {
        position: absolute;
        width: 15px;
        height: 15px;
        background-color: #e0e0e0;
        border-radius: 50%;
        left: -2.58rem;
        top: 0.3rem;
        border: 1px solid #dee2e6;
    }
    
    .timeline-step.complete .timeline-point {
        background-color: #4A148C;
        border-color: #4A148C;
    }
</style>
@endpush 