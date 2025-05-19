@extends('layouts.admin')

@section('title', 'Bon de livraison #' . $voucher->code . ' - KLINKLIN Admin')

@section('page_title', 'Détails du bon de livraison #' . $voucher->code)

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <div class="bg-{{ $voucher->is_active && (!$voucher->valid_until || $voucher->valid_until->isFuture()) ? 'primary' : 'secondary' }}-soft rounded-circle p-3 me-3">
                                <i class="fas fa-ticket-alt fa-2x text-{{ $voucher->is_active && (!$voucher->valid_until || $voucher->valid_until->isFuture()) ? 'primary' : 'secondary' }}"></i>
                            </div>
                            <div>
                                <h4 class="mb-1">Bon: {{ $voucher->code }}</h4>
                                <div class="d-flex flex-wrap align-items-center">
                                    <span class="badge rounded-pill bg-{{ 
                                        $voucher->is_active && (!$voucher->valid_until || $voucher->valid_until->isFuture()) ? 'success' : 'danger'
                                    }} me-2">
                                        {{ $voucher->is_active && (!$voucher->valid_until || $voucher->valid_until->isFuture()) ? 'Actif' : 'Inactif/Expiré' }}
                                    </span>
                                    <span class="text-muted small ms-1">
                                        <i class="far fa-calendar-alt me-1"></i> Créé le {{ $voucher->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap">
                            @if($voucher->is_active)
                            <a href="{{ route('admin.delivery-vouchers.edit', $voucher) }}" class="btn btn-sm btn-primary me-2 mb-2">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger mb-2" data-bs-toggle="modal" data-bs-target="#deleteVoucherModal">
                                <i class="fas fa-trash me-1"></i> Supprimer
                            </button>
                            @else
                            <a href="{{ route('admin.delivery-vouchers.edit', $voucher) }}" class="btn btn-sm btn-outline-secondary me-2 mb-2">
                                <i class="fas fa-edit me-1"></i> Voir/Modifier
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-8">
            <!-- Détails du bon -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i> Détails du bon de livraison
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-muted mb-2">Code</h6>
                            <div class="bg-light p-3 rounded">
                                <h5 class="mb-0">{{ $voucher->code }}</h5>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-muted mb-2">Client</h6>
                            <div class="bg-light p-3 rounded">
                                @if($voucher->user)
                                <p class="mb-0 fw-bold">
                                    <a href="{{ route('admin.users.show', $voucher->user) }}" class="text-decoration-none">
                                        {{ $voucher->user->name }}
                                    </a>
                                </p>
                                <p class="mb-0 small text-muted">{{ $voucher->user->email }}</p>
                                @else
                                <p class="mb-0 text-muted">Client non défini</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-muted mb-2">Description</h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0">{{ $voucher->description ?: 'Aucune description fournie' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-muted mb-2">Raison</h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0">{{ $voucher->reason ?: 'Aucune raison spécifiée' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-muted mb-2">Livraisons</h6>
                            <div class="bg-light p-3 rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-0">{{ $voucher->used_deliveries }} / {{ $voucher->number_of_deliveries }}</h5>
                                    <span class="badge bg-{{ 
                                        $voucher->used_deliveries >= $voucher->number_of_deliveries ? 'danger' : 'success' 
                                    }}">
                                        {{ $voucher->used_deliveries >= $voucher->number_of_deliveries ? 'Épuisé' : 'Disponible' }}
                                    </span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" role="progressbar" 
                                        style="width: {{ ($voucher->used_deliveries / $voucher->number_of_deliveries) * 100 }}%" 
                                        aria-valuenow="{{ $voucher->used_deliveries }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="{{ $voucher->number_of_deliveries }}">
                                    </div>
                                </div>
                                <p class="mt-2 mb-0 small text-muted">
                                    {{ $voucher->number_of_deliveries - $voucher->used_deliveries }} livraisons restantes
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-muted mb-2">Période de validité</h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-1">Du: <strong>{{ $voucher->valid_from->format('d/m/Y') }}</strong></p>
                                @if($voucher->valid_until)
                                    <p class="mb-0">Au: <strong>{{ $voucher->valid_until->format('d/m/Y') }}</strong></p>
                                    @if($voucher->valid_until->isPast())
                                        <span class="badge bg-danger mt-2">Expiré</span>
                                    @else
                                        <span class="badge bg-success mt-2">Valide</span>
                                    @endif
                                @else
                                    <p class="mb-0">Au: <strong>Illimitée</strong></p>
                                    <span class="badge bg-success mt-2">Valide</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-muted mb-2">Statut</h6>
                            <div class="bg-light p-3 rounded">
                                @if($voucher->is_active)
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" checked disabled>
                                        </div>
                                        <span class="ms-2">Actif</span>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" disabled>
                                        </div>
                                        <span class="ms-2">Inactif</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Utilisation du bon -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-history me-2"></i> Historique d'utilisation
                    </h6>
                    <span class="badge bg-primary">{{ $orders->total() }} utilisations</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4">Commande</th>
                                    <th class="py-3">Client</th>
                                    <th class="py-3">Montant</th>
                                    <th class="py-3">Statut</th>
                                    <th class="py-3">Date</th>
                                    <th class="py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td class="py-3 px-4">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none">
                                            #{{ $order->id }}
                                        </a>
                                    </td>
                                    <td class="py-3">
                                        @if($order->user)
                                        <a href="{{ route('admin.users.show', $order->user) }}" class="text-decoration-none">
                                            {{ $order->user->name }}
                                        </a>
                                        @else
                                        Client inconnu
                                        @endif
                                    </td>
                                    <td class="py-3">{{ number_format($order->total, 0, ',', ' ') }} FCFA</td>
                                    <td class="py-3">
                                        <span class="badge rounded-pill bg-{{ 
                                            $order->status === 'pending' ? 'warning' : 
                                            ($order->status === 'completed' || $order->status === 'delivered' ? 'success' : 
                                            ($order->status === 'cancelled' ? 'danger' : 'info')) 
                                        }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="py-3">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-muted mb-0">Aucune utilisation de ce bon</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($orders->hasPages())
                    <div class="d-flex justify-content-center mt-4 mb-2">
                        {{ $orders->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Colonne latérale -->
        <div class="col-lg-4">
            <!-- Informations -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i> Statistiques
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h6 class="fw-bold text-muted mb-3">Utilisation</h6>
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary-soft rounded-circle p-2 me-3">
                                <i class="fas fa-truck text-primary"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 fw-bold">{{ $voucher->used_deliveries }}</h2>
                                <p class="text-muted mb-0 small">Livraisons utilisées</p>
                            </div>
                        </div>
                        
                        <div class="bg-light p-3 rounded">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small">Utilisation</span>
                                <span class="small">{{ $voucher->used_deliveries }}/{{ $voucher->number_of_deliveries }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ ($voucher->used_deliveries / $voucher->number_of_deliveries) * 100 }}%"></div>
                            </div>
                            @if($voucher->used_deliveries >= $voucher->number_of_deliveries)
                            <p class="text-danger small mt-2 mb-0">Le nombre maximum de livraisons a été atteint.</p>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <h6 class="fw-bold text-muted mb-3">Validité</h6>
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary-soft rounded-circle p-2 me-3">
                                    <i class="fas fa-calendar-alt text-primary"></i>
                                </div>
                                <div>
                                    <p class="mb-0 small">Date de début</p>
                                    <p class="mb-0 fw-bold">{{ $voucher->valid_from->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <div class="bg-primary-soft rounded-circle p-2 me-3">
                                    <i class="fas fa-calendar-times text-primary"></i>
                                </div>
                                <div>
                                    <p class="mb-0 small">Date de fin</p>
                                    <p class="mb-0 fw-bold">{{ $voucher->valid_until ? $voucher->valid_until->format('d/m/Y') : 'Illimitée' }}</p>
                                </div>
                            </div>
                            
                            @if($voucher->valid_until)
                                @if($voucher->valid_until->isPast())
                                <div class="alert alert-danger mt-3 mb-0">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Ce bon a expiré depuis {{ $voucher->valid_until->diffForHumans() }}.
                                </div>
                                @else
                                <div class="alert alert-info mt-3 mb-0">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Ce bon expire dans {{ $voucher->valid_until->diffForHumans() }}.
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-cog me-2"></i> Actions
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.delivery-vouchers.edit', $voucher) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteVoucherModal">
                            <i class="fas fa-trash me-1"></i> {{ $voucher->used_deliveries > 0 ? 'Désactiver' : 'Supprimer' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression -->
<div class="modal fade" id="deleteVoucherModal" tabindex="-1" aria-labelledby="deleteVoucherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteVoucherModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p>Êtes-vous sûr de vouloir supprimer le bon de livraison <strong>{{ $voucher->code }}</strong> ?</p>
                    
                    @if($voucher->used_deliveries > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i> Ce bon a déjà été utilisé {{ $voucher->used_deliveries }} fois. 
                        Il sera désactivé plutôt que supprimé définitivement.
                    </div>
                    @else
                    <p class="text-danger small">Cette action est irréversible.</p>
                    @endif
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.delivery-vouchers.destroy', $voucher) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> 
                        @if($voucher->used_deliveries > 0)
                            Désactiver
                        @else
                            Supprimer
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 