@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- En-tête de la page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Détails de l'Utilisateur</h1>
        <a href="{{ route('admin.users.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour à la liste
        </a>
    </div>

    <!-- Informations de l'utilisateur -->
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations Personnelles</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Nom:</strong> {{ $user->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> {{ $user->email }}
                    </div>
                    <div class="mb-3">
                        <strong>Téléphone:</strong> {{ $user->phone }}
                    </div>
                    <div class="mb-3">
                        <strong>Date d'inscription:</strong> {{ $user->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div class="mb-3">
                        <strong>Statut:</strong>
                        <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                            {{ $user->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $user->is_active ? 'warning' : 'success' }}">
                            {{ $user->is_active ? 'Désactiver' : 'Activer' }} l'utilisateur
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quota Disponible</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Quota Total:</strong> {{ number_format($user->getTotalAvailableQuota(), 2) }} kg
                    </div>
                    <div class="mb-3">
                        <strong>Quota Payé:</strong> {{ number_format($user->getPaidSubscriptionQuota(), 2) }} kg
                    </div>
                    <div class="mb-3">
                        <strong>Quota en Attente:</strong> {{ number_format($user->getPendingSubscriptionQuota(), 2) }} kg
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistiques</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Commandes Total:</strong> {{ $user->orders->count() }}
                    </div>
                    <div class="mb-3">
                        <strong>Abonnements Actifs:</strong> {{ $user->activeSubscriptions->count() }}
                    </div>
                    <div class="mb-3">
                        <strong>Dernière Commande:</strong>
                        @if($user->orders->isNotEmpty())
                            {{ $user->orders->sortByDesc('created_at')->first()->created_at->format('d/m/Y H:i') }}
                        @else
                            Aucune commande
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Abonnements -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Abonnements</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Date d'achat</th>
                            <th>Date d'expiration</th>
                            <th>Quota</th>
                            <th>Montant</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->subscriptions as $subscription)
                        <tr>
                            <td>{{ $subscription->subscriptionType->name }}</td>
                            <td>{{ $subscription->purchase_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $subscription->expiration_date ? $subscription->expiration_date->format('d/m/Y H:i') : 'Illimité' }}</td>
                            <td>{{ number_format($subscription->quota_purchased, 2) }} kg</td>
                            <td>{{ number_format($subscription->amount_paid) }} FCFA</td>
                            <td>
                                <span class="badge bg-{{ $subscription->payment_status === 'paid' ? 'success' : ($subscription->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ $subscription->formatted_payment_status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Aucun abonnement</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Commandes récentes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Commandes Récentes</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Poids</th>
                            <th>Prix</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->orders->take(5) as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($order->weight, 2) }} kg</td>
                            <td>{{ number_format($order->final_price) }} FCFA</td>
                            <td>
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Aucune commande</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Utilisations de quota -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Utilisations de Quota</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Quota utilisé</th>
                            <th>Commande</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->quotaUsages->take(5) as $usage)
                        <tr>
                            <td>{{ $usage->usage_date->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($usage->amount_used, 2) }} kg</td>
                            <td>
                                @if($usage->order)
                                    <a href="{{ route('admin.orders.show', $usage->order) }}">#{{ $usage->order->id }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $usage->description }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Aucune utilisation de quota</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 