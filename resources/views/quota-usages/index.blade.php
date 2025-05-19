@extends('layouts.app')

@section('title', 'Historique d\'utilisation - KLINKLIN')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-3">Historique d'utilisation</h2>
            <p class="text-muted">Consultez votre historique d'utilisation de quota de blanchisserie.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('subscriptions.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Retour aux abonnements
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-3">Quota disponible</h4>
                    <div class="progress mb-3" style="height: 25px;">
                        @php
                            $totalQuota = Auth::user()->getTotalAvailableQuota();
                            $paidQuota = Auth::user()->getPaidSubscriptionQuota();
                            $pendingQuota = Auth::user()->getPendingSubscriptionQuota();
                            $totalBase = 10; // Base pour calculer le pourcentage (10kg)
                            $paidPercentage = min(100, max(0, $paidQuota / $totalBase * 100));
                            $pendingPercentage = min(100 - $paidPercentage, max(0, $pendingQuota / $totalBase * 100));
                        @endphp
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $paidPercentage }}%;" 
                             aria-valuenow="{{ $paidPercentage }}" 
                             aria-valuemin="0" aria-valuemax="100">
                            @if($paidQuota > 0)
                                {{ number_format($paidQuota, 1) }} kg
                            @endif
                        </div>
                        <div class="progress-bar bg-warning" role="progressbar" 
                             style="width: {{ $pendingPercentage }}%;" 
                             aria-valuenow="{{ $pendingPercentage }}" 
                             aria-valuemin="0" aria-valuemax="100">
                            @if($pendingQuota > 0)
                                {{ number_format($pendingQuota, 1) }} kg
                            @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="badge bg-success">Payé</span> {{ number_format($paidQuota, 1) }} kg
                            <span class="badge bg-warning ms-2">En attente</span> {{ number_format($pendingQuota, 1) }} kg
                            <span class="badge bg-secondary ms-2">Total</span> {{ number_format($totalQuota, 1) }} kg
                        </div>
                        <div>
                            <a href="{{ route('subscriptions.create') }}" class="btn btn-sm btn-primary">Acheter du quota</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($quotaUsages->isNotEmpty())
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">Détail des utilisations</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Abonnement</th>
                                <th>Commande</th>
                                <th>Quantité utilisée</th>
                                <th>Détails</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quotaUsages as $usage)
                            <tr>
                                <td>{{ $usage->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($usage->subscription)
                                        <a href="{{ route('subscriptions.show', $usage->subscription) }}">
                                            {{ $usage->subscription->notes }}
                                        </a>
                                    @else
                                        <span class="text-muted">Abonnement supprimé</span>
                                    @endif
                                </td>
                                <td>
                                    @if($usage->order)
                                        <a href="{{ route('orders.show', $usage->order) }}">
                                            #{{ $usage->order->id }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ number_format($usage->amount_used, 1) }} kg</td>
                                <td>{{ $usage->description }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $quotaUsages->links() }}
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <i class="fas fa-history fa-4x text-muted mb-3"></i>
                    <h4>Aucun historique d'utilisation</h4>
                    <p class="text-muted mb-4">Vous n'avez pas encore utilisé votre quota. Commencez à utiliser nos services pour voir l'historique ici.</p>
                    <a href="{{ route('orders.create') }}" class="btn btn-primary">
                        Créer une commande
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 