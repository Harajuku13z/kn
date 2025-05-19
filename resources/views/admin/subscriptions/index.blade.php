@extends('layouts.admin')

@section('page_title', 'Gestion des Abonnements')

@section('content')
<div class="container-fluid">
    <!-- En-tête de la page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des Abonnements</h1>
        <a href="{{ route('admin.subscriptions.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nouvel Abonnement
        </a>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Abonnements en attente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Abonnements payés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $paidCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Abonnements annulés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cancelledCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtres</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.subscriptions.index') }}" class="row">
                <div class="col-md-3 mb-3">
                    <label for="status">Statut</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">Tous</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Payé</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="date_from">Date début</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="date_to">Date fin</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="search">Recherche</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="ID ou nom client" value="{{ request('search') }}">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des abonnements -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Abonnements</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="subscriptionsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Type</th>
                            <th>Quota</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscriptions as $subscription)
                        <tr>
                            <td>{{ $subscription->id }}</td>
                            <td>{{ $subscription->user->name ?? 'N/A' }}</td>
                            <td>{{ $subscription->subscriptionType->name ?? 'N/A' }}</td>
                            <td>{{ $subscription->quota_purchased }} kg</td>
                            <td>{{ number_format($subscription->amount_paid, 0, ',', ' ') }} FCFA</td>
                            <td>
                                @if($subscription->payment_status == 'pending')
                                    <span class="badge bg-warning text-white">En attente</span>
                                @elseif($subscription->payment_status == 'paid')
                                    <span class="badge bg-success text-white">Payé</span>
                                @elseif($subscription->payment_status == 'cancelled')
                                    <span class="badge bg-danger text-white">Annulé</span>
                                @else
                                    <span class="badge bg-secondary text-white">{{ $subscription->payment_status }}</span>
                                @endif
                            </td>
                            <td>{{ $subscription->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.subscriptions.show', $subscription->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($subscription->payment_status == 'pending')
                                    <form action="{{ route('admin.subscriptions.confirm-payment', $subscription->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Confirmer le paiement de cet abonnement ?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    
                                    @if($subscription->payment_status != 'cancelled')
                                    <form action="{{ route('admin.subscriptions.cancel', $subscription->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir annuler cet abonnement ?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $subscriptions->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialisation de DataTables avec pagination désactivée (nous utilisons celle de Laravel)
    $('#subscriptionsTable').DataTable({
        "paging": false,
        "searching": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
        }
    });
});
</script>
@endpush
@endsection 