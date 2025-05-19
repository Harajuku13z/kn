@extends('layouts.admin')

@section('title', 'Tickets de support - KLINKLIN Admin')

@section('content')
<div class="container-fluid">
    <!-- En-tête avec statistiques -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tickets de support</h1>
        <div class="d-flex">
            <a href="{{ route('admin.support.stats') }}" class="btn btn-info mr-2">
                <i class="fas fa-chart-bar"></i> Statistiques
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.support.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Statut</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">Tous les statuts</option>
                        <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Ouvert</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>En traitement</option>
                        <option value="waiting_user" {{ request('status') === 'waiting_user' ? 'selected' : '' }}>En attente</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Fermé</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="priority" class="form-label">Priorité</label>
                    <select class="form-control" id="priority" name="priority">
                        <option value="">Toutes les priorités</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Basse</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Moyenne</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Haute</option>
                        <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgente</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="category" class="form-label">Catégorie</label>
                    <select class="form-control" id="category" name="category">
                        <option value="">Toutes les catégories</option>
                        <option value="general" {{ request('category') === 'general' ? 'selected' : '' }}>Question générale</option>
                        <option value="account" {{ request('category') === 'account' ? 'selected' : '' }}>Compte</option>
                        <option value="orders" {{ request('category') === 'orders' ? 'selected' : '' }}>Commandes</option>
                        <option value="payment" {{ request('category') === 'payment' ? 'selected' : '' }}>Paiement</option>
                        <option value="subscription" {{ request('category') === 'subscription' ? 'selected' : '' }}>Abonnement</option>
                        <option value="technical" {{ request('category') === 'technical' ? 'selected' : '' }}>Problème technique</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="search" class="form-label">Recherche</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Référence ou sujet...">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                    <a href="{{ route('admin.support.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tickets ouverts -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-exclamation-circle text-success"></i> Tickets ouverts
            </h6>
            <span class="badge bg-success">{{ $tickets->whereIn('status', ['open', 'in_progress', 'waiting_user'])->count() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Référence</th>
                            <th>Sujet</th>
                            <th>Client</th>
                            <th>Statut</th>
                            <th>Priorité</th>
                            <th>Catégorie</th>
                            <th>Dernière mise à jour</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets->whereIn('status', ['open', 'in_progress', 'waiting_user']) as $ticket)
                            <tr>
                                <td>#{{ $ticket->reference_number }}</td>
                                <td>
                                    <a href="{{ route('admin.support.show', $ticket->id) }}" class="text-decoration-none">
                                        {{ Str::limit($ticket->subject, 50) }}
                                    </a>
                                </td>
                                <td>{{ $ticket->user->name }}</td>
                                <td>
                                    @if($ticket->status === 'open')
                                        <span class="badge bg-success">Ouvert</span>
                                    @elseif($ticket->status === 'in_progress')
                                        <span class="badge bg-primary">En traitement</span>
                                    @else
                                        <span class="badge bg-warning">En attente</span>
                                    @endif
                                </td>
                                <td>
                                    @if($ticket->priority === 'urgent')
                                        <span class="badge bg-danger">Urgente</span>
                                    @elseif($ticket->priority === 'high')
                                        <span class="badge bg-warning">Haute</span>
                                    @elseif($ticket->priority === 'medium')
                                        <span class="badge bg-info">Moyenne</span>
                                    @else
                                        <span class="badge bg-secondary">Basse</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $ticket->getCategoryLabel() }}</span>
                                </td>
                                <td>{{ $ticket->updated_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.support.show', $ticket->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                                    <p class="mb-0">Aucun ticket ouvert</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tickets fermés -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-check-circle text-danger"></i> Tickets fermés
            </h6>
            <span class="badge bg-danger">{{ $tickets->where('status', 'closed')->count() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Référence</th>
                            <th>Sujet</th>
                            <th>Client</th>
                            <th>Catégorie</th>
                            <th>Date de fermeture</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets->where('status', 'closed') as $ticket)
                            <tr>
                                <td>#{{ $ticket->reference_number }}</td>
                                <td>
                                    <a href="{{ route('admin.support.show', $ticket->id) }}" class="text-decoration-none">
                                        {{ Str::limit($ticket->subject, 50) }}
                                    </a>
                                </td>
                                <td>{{ $ticket->user->name }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $ticket->getCategoryLabel() }}</span>
                                </td>
                                <td>{{ $ticket->closed_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.support.show', $ticket->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                                    <p class="mb-0">Aucun ticket fermé</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($tickets->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $tickets->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
.table th {
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-weight: 500;
    padding: 0.5em 0.75em;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e3e6f0;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.form-label {
    font-weight: 500;
    color: #5a5c69;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
}

.btn-secondary {
    background-color: #858796;
    border-color: #858796;
}

.btn-secondary:hover {
    background-color: #717384;
    border-color: #6b6d7d;
}

.btn-info {
    background-color: #36b9cc;
    border-color: #36b9cc;
}

.btn-info:hover {
    background-color: #2c9faf;
    border-color: #2a96a5;
}
</style>
@endpush
@endsection 