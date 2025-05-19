@extends('layouts.admin')

@section('page_title', 'Détails de l\'Abonnement')

@section('content')
<div class="container-fluid">
    <!-- En-tête de la page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Détails de l'Abonnement #{{ $subscription->id }}</h1>
        <div>
            <a href="{{ route('admin.subscriptions.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm me-2">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour à la liste
            </a>
            <a href="{{ route('admin.subscriptions.edit', $subscription) }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Modifier
            </a>
        </div>
    </div>

    <!-- Alert messages -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Informations générales -->
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Client</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $subscription->user->name }}</div>
                            <div class="text-muted">{{ $subscription->user->email }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Type d'abonnement</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $subscription->subscriptionType->name }}</div>
                            <div class="text-muted">{{ $subscription->subscriptionType->description }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card 
                @if($subscription->payment_status == 'paid')
                    border-left-success
                @elseif($subscription->payment_status == 'pending')
                    border-left-warning
                @else
                    border-left-danger
                @endif
                shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold 
                                @if($subscription->payment_status == 'paid')
                                    text-success
                                @elseif($subscription->payment_status == 'pending')
                                    text-warning
                                @else
                                    text-danger
                                @endif
                                text-uppercase mb-1">
                                Statut du paiement</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $subscription->formattedPaymentStatus }}</div>
                            <div class="text-muted">{{ $subscription->formattedPaymentMethod }} - {{ number_format($subscription->amount_paid, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Détails de l'abonnement -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Informations détaillées</h6>
            
            @if($subscription->payment_status == 'pending')
                <form action="{{ route('admin.subscriptions.confirm-payment', $subscription) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Confirmer le paiement de cet abonnement ?')">
                        <i class="fas fa-check mr-1"></i> Confirmer le paiement
                    </button>
                </form>
            @endif
            
            @if($subscription->payment_status != 'cancelled')
                <form action="{{ route('admin.subscriptions.cancel', $subscription) }}" method="POST" class="d-inline ml-2">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir annuler cet abonnement ?')">
                        <i class="fas fa-times mr-1"></i> Annuler l'abonnement
                    </button>
                </form>
            @endif
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 40%">ID</th>
                                <td>{{ $subscription->id }}</td>
                            </tr>
                            <tr>
                                <th>Créé le</th>
                                <td>{{ $subscription->created_at->format('d/m/Y à H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Mis à jour le</th>
                                <td>{{ $subscription->updated_at->format('d/m/Y à H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Date d'expiration</th>
                                <td>{{ $subscription->expiration_date ? $subscription->expiration_date->format('d/m/Y') : 'Non définie' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 40%">Quota total</th>
                                <td>{{ $subscription->quota_purchased }} kg</td>
                            </tr>
                            <tr>
                                <th>Quota utilisé</th>
                                <td>{{ $subscription->quotaUsages()->sum('amount_used') }} kg</td>
                            </tr>
                            <tr>
                                <th>Quota restant</th>
                                <td>{{ $subscription->remaining_quota }} kg</td>
                            </tr>
                            <tr>
                                <th>Statut</th>
                                <td>
                                    @if($subscription->is_active)
                                        <span class="badge bg-success text-white">Actif</span>
                                    @else
                                        <span class="badge bg-danger text-white">Inactif</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($subscription->notes)
                <div class="mt-4">
                    <h6 class="font-weight-bold">Notes :</h6>
                    <p class="p-3 bg-light rounded">{{ $subscription->notes }}</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Utilisation du quota -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Utilisation du quota</h6>
        </div>
        <div class="card-body">
            @if(count($quotaUsages) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Commande</th>
                                <th>Quantité utilisée</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quotaUsages as $usage)
                                <tr>
                                    <td>{{ $usage->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($usage->order)
                                            <a href="{{ route('admin.orders.show', $usage->order_id) }}" class="text-primary">
                                                Commande #{{ $usage->order_id }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $usage->amount_used }} kg</td>
                                    <td>
                                        @if($usage->order)
                                            <a href="{{ route('admin.orders.show', $usage->order_id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $quotaUsages->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    Aucune utilisation de quota n'a été enregistrée pour cet abonnement.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
