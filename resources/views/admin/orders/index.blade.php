@extends('layouts.admin')

@section('title', 'Gestion des Commandes - KLINKLIN Admin')

@section('page_title', 'Gestion des Commandes')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            @if(request('status'))
                Commandes - {{ ucfirst(request('status')) }}
            @else
                Toutes les Commandes
            @endif
        </h1>
        <div>
            <a href="{{ route('admin.orders.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Nouvelle commande
            </a>
        </div>
    </div>
    
    <!-- Filtres -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-1"></i> Filtres
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Statut</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="collected" {{ request('status') == 'collected' ? 'selected' : '' }}>Collecté</option>
                        <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>En transit</option>
                        <option value="washing" {{ request('status') == 'washing' ? 'selected' : '' }}>Lavage</option>
                        <option value="ironing" {{ request('status') == 'ironing' ? 'selected' : '' }}>Repassage</option>
                        <option value="ready_for_delivery" {{ request('status') == 'ready_for_delivery' ? 'selected' : '' }}>Prêt pour livraison</option>
                        <option value="delivering" {{ request('status') == 'delivering' ? 'selected' : '' }}>En cours de livraison</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Livré</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label">Date de début</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">Date de fin</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="ID, nom client..." value="{{ request('search') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="fas fa-search me-1"></i> Filtrer
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mt-3">
                        <i class="fas fa-redo me-1"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des commandes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-shopping-cart me-1"></i> 
                @if(request('status'))
                    Commandes {{ ucfirst(request('status')) }}
                @else
                    Liste des Commandes
                @endif
            </h6>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-cog me-1"></i> Actions
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}">Toutes les commandes</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'pending']) }}">Commandes en attente</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'collected']) }}">Commandes collectées</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'in_transit']) }}">Commandes en transit</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'washing']) }}">Commandes en lavage</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'ironing']) }}">Commandes en repassage</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'ready_for_delivery']) }}">Commandes prêtes</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'delivering']) }}">Commandes en livraison</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'delivered']) }}">Commandes livrées</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}">Commandes annulées</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="ordersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Date de collecte</th>
                            <th>Date de livraison</th>
                            <th>Total</th>
                            <th>Statut</th>
                            <th>Créée le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>
                                    @if($order->user)
                                        <a href="{{ route('admin.users.show', $order->user) }}">{{ $order->user->name }}</a>
                                    @else
                                        Client inconnu
                                    @endif
                                </td>
                                <td>
                                    @if($order->pickup_date)
                                        {{ $order->pickup_date->format('d/m/Y') }}
                                        <small class="d-block text-muted">{{ $order->pickup_time_slot ?? 'Horaire non défini' }}</small>
                                    @else
                                        Non définie
                                    @endif
                                </td>
                                <td>
                                    @if($order->delivery_date)
                                        {{ $order->delivery_date->format('d/m/Y') }}
                                        <small class="d-block text-muted">{{ $order->delivery_time_slot ?? 'Horaire non défini' }}</small>
                                    @else
                                        Non définie
                                    @endif
                                </td>
                                <td>{{ number_format($order->total, 0, '.', ' ') }} FCFA</td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : 
                                        ($order->status === 'completed' ? 'success' : 
                                        ($order->status === 'processing' ? 'info' : 
                                        ($order->status === 'cancelled' ? 'danger' : 'secondary'))) }} text-white">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger" title="Supprimer" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $order->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Modal de suppression -->
                                    <div class="modal fade" id="deleteModal{{ $order->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $order->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $order->id }}">Confirmer la suppression</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Êtes-vous sûr de vouloir supprimer la commande #{{ $order->id }} ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Aucune commande trouvée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Vérifier si la table a déjà été initialisée
        if (!$.fn.DataTable.isDataTable('#ordersTable')) {
            $('#ordersTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Tous"]],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
                },
                "order": [[0, "desc"]], // Trier par ID décroissant par défaut
                "ordering": true,
                "searching": false, // Désactivé car nous avons notre propre formulaire de recherche
                "paging": false // Désactivé car nous utilisons la pagination de Laravel
            });
        }
    });
</script>
@endpush 