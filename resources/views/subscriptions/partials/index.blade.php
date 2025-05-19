@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Mon abonnement</h5>
                    <a href="{{ route('subscriptions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Nouvel abonnement
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Quota total disponible</h6>
                                    <h2 class="mb-0">{{ $totalQuota }} kg</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Quota payé</h6>
                                    <h2 class="mb-0">{{ $paidQuota }} kg</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h6 class="card-title">Quota en attente</h6>
                                    <h2 class="mb-0">{{ $pendingQuota }} kg</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Abonnements actifs</h5>
                </div>
                <div class="card-body">
                    @if($activeSubscriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date d'achat</th>
                                        <th>Quota</th>
                                        <th>Montant</th>
                                        <th>Méthode de paiement</th>
                                        <th>Statut</th>
                                        <th>Expiration</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeSubscriptions as $subscription)
                                        <tr>
                                            <td>{{ $subscription->purchase_date->format('d/m/Y') }}</td>
                                            <td>{{ $subscription->quota_purchased }} kg</td>
                                            <td>{{ number_format($subscription->amount_paid, 0, ',', ' ') }} FCFA</td>
                                            <td>{{ ucfirst($subscription->payment_method) }}</td>
                                            <td>
                                                <span class="badge bg-success">Actif</span>
                                            </td>
                                            <td>{{ $subscription->expiration_date->format('d/m/Y') }}</td>
                                            <td>
                                                <a href="{{ route('subscriptions.show', $subscription) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Aucun abonnement actif.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Abonnements expirés</h5>
                </div>
                <div class="card-body">
                    @if($expiredSubscriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date d'achat</th>
                                        <th>Quota</th>
                                        <th>Montant</th>
                                        <th>Méthode de paiement</th>
                                        <th>Statut</th>
                                        <th>Expiration</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expiredSubscriptions as $subscription)
                                        <tr>
                                            <td>{{ $subscription->purchase_date->format('d/m/Y') }}</td>
                                            <td>{{ $subscription->quota_purchased }} kg</td>
                                            <td>{{ number_format($subscription->amount_paid, 0, ',', ' ') }} FCFA</td>
                                            <td>{{ ucfirst($subscription->payment_method) }}</td>
                                            <td>
                                                @if($subscription->payment_status === 'pending')
                                                    <span class="badge bg-warning">En attente</span>
                                                @else
                                                    <span class="badge bg-danger">Expiré</span>
                                                @endif
                                            </td>
                                            <td>{{ $subscription->expiration_date->format('d/m/Y') }}</td>
                                            <td>
                                                <a href="{{ route('subscriptions.show', $subscription) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Aucun abonnement expiré.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 