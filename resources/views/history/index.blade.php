@extends('layouts.dashboard')

@section('title', 'Historique de commandes - KLINKLIN')

@section('content')
<div class="dashboard-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-klin-primary">Mon historique</h1>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Retour au tableau de bord
            </a>
        </div>
    </div>
    
    <div class="alert alert-light shadow-sm border-0">
        <div class="d-flex align-items-center">
            <i class="bi bi-info-circle-fill fs-3 me-3 text-klin-primary"></i>
            <div>
                <h5 class="mb-1">Historique des collectes</h5>
                <p class="mb-0">Retrouvez ici toutes vos commandes terminées. Vous pouvez télécharger les factures correspondantes.</p>
            </div>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-klin-primary"><i class="bi bi-clock-history me-2"></i>Commandes passées</h5>
        </div>
        <div class="card-body p-0">
            @if($orders->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h5>Aucune commande terminée</h5>
                    <p class="text-muted">Vos commandes passées apparaîtront ici une fois terminées.</p>
                    <a href="{{ route('orders.create') }}" class="btn btn-primary mt-2">
                        <i class="bi bi-plus-circle me-1"></i> Créer une commande
                    </a>
                </div>
            @else
                <!-- Vue desktop : tableau -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>N° Commande</th>
                                <th>Date</th>
                                <th>Services</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <span class="fw-medium">#{{ $order->id }}</span>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($order->order_type === 'kilogram')
                                            <span class="badge bg-info">Lavage au kilo</span>
                                        @elseif($order->order_type === 'pressing')
                                            <span class="badge bg-primary">Pressing</span>
                                        @else
                                            <span class="badge bg-secondary">Standard</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($order->final_price ?? $order->estimated_price, 0, ',', ' ') }} FCFA</td>
                                    <td>
                                        @if($order->status === 'delivered' || $order->status === 'completed')
                                            <span class="badge bg-success">Terminé</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('orders.invoice.download', $order->id) }}" class="btn btn-sm btn-outline-success" title="Télécharger la facture" id="invoiceBtn-{{ $order->id }}">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <script>
                                    document.getElementById('invoiceBtn-{{ $order->id }}').addEventListener('click', function() {
                                        this.innerHTML = '<i class="bi bi-hourglass-split"></i>';
                                        this.classList.add('disabled');
                                        
                                        setTimeout(function() {
                                            document.getElementById('invoiceBtn-{{ $order->id }}').innerHTML = '<i class="bi bi-file-earmark-pdf"></i>';
                                            document.getElementById('invoiceBtn-{{ $order->id }}').classList.remove('disabled');
                                        }, 10000);
                                    });
                                </script>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Vue mobile : cartes -->
                <div class="d-md-none">
                    <div class="row g-3 p-3">
                        @foreach($orders as $order)
                            <div class="col-12">
                                <div class="card h-100 border-0 shadow-sm order-card">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                                        <span class="fw-medium text-klin-primary">Commande #{{ $order->id }}</span>
                                        <span class="badge bg-success">Terminé</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <div class="detail-label"><i class="bi bi-calendar me-1"></i> Date</div>
                                                <div class="detail-value">{{ $order->created_at->format('d/m/Y') }}</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="detail-label"><i class="bi bi-tag me-1"></i> Type</div>
                                                <div class="detail-value">
                                                    @if($order->order_type === 'kilogram')
                                                        <span class="badge bg-info">Lavage au kilo</span>
                                                    @elseif($order->order_type === 'pressing')
                                                        <span class="badge bg-primary">Pressing</span>
                                                    @else
                                                        <span class="badge bg-secondary">Standard</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <div class="detail-label"><i class="bi bi-currency-exchange me-1"></i> Montant</div>
                                                <div class="detail-value fw-bold">{{ number_format($order->final_price ?? $order->estimated_price, 0, ',', ' ') }} FCFA</div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                                <i class="bi bi-eye me-1"></i> Voir détails
                                            </a>
                                            <a href="{{ route('orders.invoice.download', $order->id) }}" class="btn btn-sm btn-outline-success rounded-pill">
                                                <i class="bi bi-file-earmark-pdf me-1"></i> Facture
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="px-4 py-3">
                    {{ $orders->links() }}
                </div>
            @endif
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
    .table > :not(caption) > * > * {
        padding: 1rem 1rem;
    }
    
    /* Styles pour les cartes mobiles */
    .order-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .order-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .detail-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.2rem;
        font-weight: 600;
        color: #6c757d;
    }
    
    .detail-value {
        font-size: 0.9rem;
        line-height: 1.3;
    }
    
    @media (max-width: 768px) {
        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.85rem;
        }
    }
</style>
@endpush 