@extends('layouts.dashboard')

@section('title', 'Liste des commandes - KLINKLIN')

@section('content')
<div class="container-fluid dashboard-content order-list-page">
    <div class="row mb-4 align-items-center">
        <div class="col-auto">
            <div class="notification-icon-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-basket-fill text-klin-primary"></i>
            </div>
        </div>
        <div class="col">
            <h1 class="display-5 fw-bold text-klin-primary mb-1">Mes Commandes</h1>
            <span class="text-muted fs-5 mb-0">Suivez et gérez toutes vos demandes de service.</span>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('orders.create') }}" class="btn btn-lg btn-primary klin-btn rounded-pill shadow-sm">
                <i class="bi bi-plus-circle-fill me-2"></i> Nouvelle Commande
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-center justify-content-between">
                <div class="col-md-8">
                    <div class="d-flex align-items-center flex-wrap">
                        <label class="form-label me-3 mb-0 fw-bold">Filtrer par statut:</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('orders.index') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">
                                <i class="bi bi-tag-fill me-1"></i> Tous
                            </a>
                            <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="btn {{ request('status') == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                                <i class="bi bi-hourglass-split me-1"></i> En attente
                            </a>
                            <a href="{{ route('orders.index', ['status' => 'collected']) }}" class="btn {{ request('status') == 'collected' ? 'btn-info' : 'btn-outline-info' }}">
                                <i class="bi bi-box-seam me-1"></i> Collecté
                            </a>
                            <a href="{{ route('orders.index', ['status' => 'delivered']) }}" class="btn {{ request('status') == 'delivered' ? 'btn-success' : 'btn-outline-success' }}">
                                <i class="bi bi-check-circle me-1"></i> Livré
                            </a>
                            <a href="{{ route('orders.index', ['status' => 'cancelled']) }}" class="btn {{ request('status') == 'cancelled' ? 'btn-danger' : 'btn-outline-danger' }}">
                                <i class="bi bi-x-circle me-1"></i> Annulé
                            </a>
                    </div>
                    </div>
                    </div>
                <div class="col-md-auto">
                    <div class="d-flex gap-2">
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Tout afficher
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Affichage des commandes en cartes -->
    <div class="order-status-indicator mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-klin-primary fw-bold"><i class="bi bi-list-check me-2"></i> Liste des Commandes</h5>
            @if(request('status'))
                <span class="badge bg-info text-white">
                    <i class="bi bi-funnel-fill me-1"></i> Filtré par statut: {{ ucfirst(str_replace('_', ' ', request('status'))) }}
                </span>
            @endif
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3">
                        @forelse($orders as $order)
                                @php
                                    $statusClass = '';
                $statusColor = '';
                $statusBg = '';
                                    $statusIcon = 'bi-question-circle';
                $statusText = ucfirst(str_replace('_', ' ', $order->status));
                
                                    switch (strtolower($order->status)) {
                                        case 'pending':
                        $statusClass = 'status-pending'; 
                        $statusColor = '#856404';
                        $statusBg = '#fff3cd';
                        $statusIcon = 'bi-hourglass-split'; 
                        $statusText = 'En attente';
                        break;
                    case 'collected':
                        $statusClass = 'status-collected';
                        $statusColor = '#0c5460';
                        $statusBg = '#d1ecf1';  
                        $statusIcon = 'bi-box-seam'; 
                        $statusText = 'Collecté';
                        break;
                    case 'in_transit':
                        $statusClass = 'status-in-transit'; 
                        $statusColor = '#856404';
                        $statusBg = '#fff3cd';
                        $statusIcon = 'bi-truck-front-fill'; 
                        $statusText = 'En transit';
                        break;
                    case 'washing':
                        $statusClass = 'status-washing'; 
                        $statusColor = '#005cb9';
                        $statusBg = '#e6f2ff';
                        $statusIcon = 'bi-water'; 
                        $statusText = 'Lavage';
                        break;
                    case 'ironing':
                        $statusClass = 'status-ironing'; 
                        $statusColor = '#005cb9';
                        $statusBg = '#e6f2ff';
                        $statusIcon = 'bi-steam'; 
                        $statusText = 'Repassage';
                        break;
                    case 'ready_for_delivery':
                        $statusClass = 'status-ready'; 
                        $statusColor = '#084298';
                        $statusBg = '#cfe2ff';
                        $statusIcon = 'bi-check-square'; 
                        $statusText = 'Prêt pour livraison';
                        break;
                    case 'delivering':
                        $statusClass = 'status-delivering'; 
                        $statusColor = '#856404';
                        $statusBg = '#fff3cd';
                        $statusIcon = 'bi-truck'; 
                        $statusText = 'En cours de livraison';
                        break;
                                        case 'delivered':
                        $statusClass = 'status-completed'; 
                        $statusColor = '#107a42';
                        $statusBg = '#d4edda';
                        $statusIcon = 'bi-check-circle-fill'; 
                        $statusText = 'Livré';
                        break;
                                        case 'cancelled':
                        $statusClass = 'status-cancelled'; 
                        $statusColor = '#721c24';
                        $statusBg = '#f8d7da';
                        $statusIcon = 'bi-x-circle-fill'; 
                        $statusText = 'Annulé';
                        break;
                                        default:
                                            $statusClass = 'status-default';
                        $statusColor = '#495057';
                        $statusBg = '#e9ecef';
                                    }
                                @endphp
            
            <div class="col">
                <div class="card order-card shadow-sm h-100" style="border-top: 3px solid {{ $statusColor }}">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fs-5 fw-bold">
                                <a href="{{ route('orders.show', $order) }}" class="text-decoration-none text-klin-primary">
                                    #{{ $order->order_number ?? $order->id }}
                                </a>
                                </span>
                        </div>
                        <div class="order-status" style="background-color: {{ $statusBg }}; color: {{ $statusColor }};">
                            <i class="bi {{ $statusIcon }}"></i> {{ $statusText }}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="order-detail">
                                    <div class="detail-label"><i class="bi bi-calendar-check me-1"></i> Collecte</div>
                                    <div class="detail-value">
                                        {{ $order->pickup_date ? $order->pickup_date->translatedFormat('d F Y') : 'N/A' }}
                                        <div class="text-muted small">{{ $order->pickup_time_slot ?: 'Horaire non défini' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="order-detail">
                                    <div class="detail-label"><i class="bi bi-calendar-event me-1"></i> Livraison</div>
                                    <div class="detail-value">
                                        {{ $order->delivery_date ? $order->delivery_date->translatedFormat('d F Y') : 'N/A' }}
                                        <div class="text-muted small">{{ $order->delivery_time_slot ?: 'Horaire non défini' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="order-detail">
                                    <div class="detail-label"><i class="bi bi-geo-alt me-1"></i> Adresse de collecte</div>
                                    <div class="detail-value">
                                        @if($order->pickupAddress)
                                            {{ Str::limit($order->pickupAddress->address, 40) }}
                                            <div class="text-muted small">{{ $order->pickupAddress->district }}</div>
                                        @else
                                            {{ Str::limit($order->pickup_address ?? 'Adresse non spécifiée', 40) }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="order-detail">
                                    <div class="detail-label"><i class="bi bi-geo-alt me-1"></i> Adresse de livraison</div>
                                    <div class="detail-value">
                                        @if($order->deliveryAddress)
                                            {{ Str::limit($order->deliveryAddress->address, 40) }}
                                            <div class="text-muted small">{{ $order->deliveryAddress->district }}</div>
                                        @else
                                            {{ Str::limit($order->delivery_address ?? 'Adresse non spécifiée', 40) }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <div class="order-detail">
                                    <div class="detail-label"><i class="bi bi-currency-exchange me-1"></i> Montant</div>
                                    <div class="detail-value fw-bold">
                                        {{ number_format($order->total_amount, 0, ',', ' ') }} FCFA
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="order-detail">
                                    <div class="detail-label"><i class="bi bi-clock-history me-1"></i> Créée le</div>
                                    <div class="detail-value">
                                        {{ $order->created_at ? $order->created_at->translatedFormat('d F Y') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                @if($order->order_type === 'kilogram')
                                    <span class="badge bg-info text-white">Lavage au kilo</span>
                                @elseif($order->order_type === 'pressing')
                                    <span class="badge bg-primary text-white">Pressing</span>
                                @else
                                    <span class="badge bg-secondary text-white">Standard</span>
                                @endif
                            </div>
                            <div class="order-actions">
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary klin-btn-outline-icon" title="Voir Détails">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                @if(in_array($order->status, ['pending', 'processing']))
                                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-outline-secondary klin-btn-outline-icon" title="Modifier">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    @endif
                                @if(in_array($order->status, ['pending']))
                                    <button type="button" class="btn btn-sm btn-outline-danger klin-btn-outline-icon" title="Annuler/Supprimer" 
                                            data-bs-toggle="modal" data-bs-target="#deleteOrderModal" 
                                            data-order-id="{{ $order->id }}" 
                                            data-order-number="{{ $order->order_number }}">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                    @endif
                                </div>
                        </div>
                    </div>
                </div>
            </div>
                        @empty
            <div class="col-12">
                <div class="alert alert-light text-center py-5">
                                <i class="bi bi-folder-x fs-1 text-klin-primary opacity-50"></i>
                                <h5 class="mt-2">Aucune commande trouvée.</h5>
                                <p class="text-muted">Essayez d'ajuster vos filtres ou <a href="{{ route('orders.create') }}">créez une nouvelle commande</a>.</p>
                </div>
            </div>
        @endforelse
        </div>

        @if ($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
                {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
</div>

<!-- Modale de suppression de commande -->
<div class="modal fade" id="deleteOrderModal" tabindex="-1" aria-labelledby="deleteOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title" id="deleteOrderModalLabel"><i class="bi bi-exclamation-triangle me-2"></i>Confirmation de suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-4">
                    <div class="d-inline-block p-3 rounded-circle bg-danger bg-opacity-10 mb-3">
                        <i class="bi bi-trash3-fill text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="fw-bold">Annuler la commande</h4>
                    <p class="text-muted">Êtes-vous sûr de vouloir annuler la commande <span id="order-number-to-delete" class="fw-bold text-danger"></span> ?</p>
                </div>
                
                <div class="alert alert-warning">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="bi bi-info-circle-fill text-warning fs-4"></i>
                        </div>
                        <div class="text-start">
                            <p class="mb-0">Cette action est irréversible. La commande sera définitivement annulée.</p>
                        </div>
                    </div>
                </div>
                
                <form id="delete-order-form" action="" method="POST">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4 justify-content-center">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 py-2" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Annuler
                </button>
                <button type="button" class="btn btn-danger rounded-pill px-4 py-2 ms-3" id="confirmDeleteBtn">
                    <i class="bi bi-trash3 me-2"></i>Supprimer la commande
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --klin-primary: #4A148C;
        --klin-primary-dark: #38006b;
        --klin-secondary: #f26d50;
        --klin-light-bg: #f8f5fc; 
        --klin-border-color: #e0d8e7;
        --klin-text-muted: #6c757d;
        --klin-success: #28a745;
        --klin-warning: #ffc107;
        --klin-info: #0dcaf0;
        --klin-danger: #dc3545;
        --klin-processing-bg: #e6f2ff;
        --klin-processing-text: #005cb9;
        --klin-transit-bg: #fff3cd;
        --klin-transit-text: #856404;
        --klin-cancelled-bg: #f8d7da;
        --klin-cancelled-text: #721c24;
    }

    .order-list-page .display-5 { font-size: 2.25rem; }
    .text-klin-primary { color: var(--klin-primary) !important; }
    .bg-klin-light { background-color: var(--klin-light-bg) !important; }

    .klin-btn {
        background-color: var(--klin-primary) !important;
        border-color: var(--klin-primary) !important;
        color: white !important;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }
    .klin-btn:hover {
        background-color: var(--klin-primary-dark) !important;
        border-color: var(--klin-primary-dark) !important;
        transform: translateY(-2px);
    }
    .klin-btn-outline-icon {
        border-width: 1px;
        padding: 0.375rem 0.75rem;
    }
    .klin-btn-outline-icon.btn-outline-primary { color: var(--klin-primary); border-color: var(--klin-primary); }
    .klin-btn-outline-icon.btn-outline-primary:hover { background-color: var(--klin-primary); color: white; }
    .klin-btn-outline-icon.btn-outline-secondary { color: #6c757d; border-color: #6c757d; }
    .klin-btn-outline-icon.btn-outline-secondary:hover { background-color: #6c757d; color: white; }
    .klin-btn-outline-icon.btn-outline-danger { color: var(--klin-danger); border-color: var(--klin-danger); }
    .klin-btn-outline-icon.btn-outline-danger:hover { background-color: var(--klin-danger); color: white; }

    /* Styles pour les cartes de commande */
    .order-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid rgba(0,0,0,0.08);
        border-radius: 10px;
        overflow: hidden;
    }
    
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .order-detail {
        margin-bottom: 1rem;
    }
    
    .detail-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.2rem;
        font-weight: 600;
        color: var(--klin-text-muted);
    }
    
    .detail-value {
        font-size: 0.9rem;
        line-height: 1.3;
    }
    
    .order-status {
        font-size: 0.75rem;
        padding: 0.35em 0.75em;
        border-radius: 50px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
    }
    
    .order-status i {
        margin-right: 0.25rem;
        font-size: 0.8rem;
    }
    
    .order-actions .btn {
        margin-left: 0.25rem;
    }
    
    .order-actions .btn i {
        font-size: 0.8rem;
    }

    .status-select option {
        padding: 8px;
    }

    .notification-icon-circle {
        width: 5rem;
        height: 5rem;
        background: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(74,20,140,0.10);
        border: 2px solid var(--klin-primary);
        margin-right: 1rem;
    }
    
    .notification-icon-circle i {
        color: var(--klin-primary);
        font-size: 2.8rem;
    }
    
    .pagination .page-item.active .page-link {
        background-color: var(--klin-primary);
        border-color: var(--klin-primary);
        color: white;
    }
    
    .pagination .page-link { 
        color: var(--klin-primary); 
    }
    
    .pagination .page-link:hover { 
        color: var(--klin-primary-dark); 
    }
    
    @media (max-width: 768px) {
        .notification-icon-circle {
            width: 4rem;
            height: 4rem;
            margin-right: 0.75rem;
        }
        .notification-icon-circle i {
            font-size: 2.2rem;
        }
        .display-5 {
            font-size: 1.5rem !important;
        }
        .fs-5 {
            font-size: 1rem !important;
        }
        .order-card {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prépare la modale de suppression
        const deleteOrderModal = document.getElementById('deleteOrderModal');
        if (deleteOrderModal) {
            deleteOrderModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const orderId = button.getAttribute('data-order-id');
                const orderNumber = button.getAttribute('data-order-number') || orderId;
                
                document.getElementById('order-number-to-delete').textContent = '#' + orderNumber;
                document.getElementById('delete-order-form').action = `/orders/${orderId}`;
                
                document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                    document.getElementById('delete-order-form').submit();
                });
            });
        }
    });
</script>
@endpush