@extends('layouts.dashboard')

@section('title', "Mon Abonnement - KLINKLIN")

@section('content')
<div class="dashboard-content-redesigned">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="mb-2 display-5 fw-bold text-klin-primary">
                <i class="bi bi-star-fill me-2 text-warning"></i> Mon Abonnement
            </h2>
            <p class="text-muted fs-5">Gérez votre quota, suivez vos paiements et consultez l'historique d'utilisation.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('subscriptions.index') }}" class="btn btn-outline-primary klin-btn-outline">
                <i class="bi bi-arrow-left me-1"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="row gy-4">
        <!-- Card Infos principales -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 notification-item border-klin-primary mb-4">
                <div class="card-header d-flex align-items-center" style="background-color: var(--klin-light-bg);">
                    <i class="bi bi-graph-up-arrow me-2 text-klin-primary fs-4"></i>
                    <h5 class="mb-0 fw-bold">Informations Générales</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-6">
                            <span class="text-muted">Quota Acheté</span>
                            <div class="fw-bold text-klin-primary fs-4">{{ number_format($subscription->quota_purchased, 1) }} kg</div>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">Quota Restant</span>
                            <div class="fw-bold text-success fs-4">{{ number_format($subscription->remaining_quota, 1) }} kg</div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <span class="text-muted">Montant Payé</span>
                            <div class="fw-bold">{{ number_format($subscription->amount_paid) }} FCFA</div>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">Méthode de Paiement</span>
                            <div>{{ $subscription->formatted_payment_method }}</div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <span class="text-muted">Statut du Paiement</span>
                            <span class="badge px-2 py-1 text-sm rounded-pill 
                                @if($subscription->payment_status === 'paid') bg-success-subtle text-success
                                @elseif($subscription->payment_status === 'pending') bg-warning-subtle text-warning
                                @else bg-danger-subtle text-danger
                                @endif">
                                <i class="bi bi-circle-fill me-1"></i> {{ $subscription->formatted_payment_status }}
                            </span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted">Date d'Achat</span>
                            <div>{{ $subscription->purchase_date->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    @if($subscription->expiration_date)
                    <div class="row mb-2">
                        <div class="col-6">
                            <span class="text-muted">Expiration</span>
                            <div>{{ $subscription->expiration_date->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-6">
                            @if($subscription->notes)
                                <span class="text-muted">Notes</span>
                                <div>{{ $subscription->notes }}</div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Card Progression -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 notification-item border-warning mb-4">
                <div class="card-header d-flex align-items-center" style="background-color: #fffce7;">
                    <i class="bi bi-bar-chart-steps me-2 text-warning fs-4"></i>
                    <h5 class="mb-0 fw-bold">Progression du Quota</h5>
                </div>
                <div class="card-body">
                    @php
                        $usedQuota = $subscription->quota_purchased - $subscription->remaining_quota;
                        $usagePercentage = ($subscription->quota_purchased > 0) ? min(100, ($usedQuota / $subscription->quota_purchased * 100)) : 0;
                    @endphp
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <span class="text-muted">Utilisation</span>
                        <span class="fw-bold">{{ number_format($usagePercentage, 0) }}%</span>
                    </div>
                    <div class="progress mb-2" style="height: 12px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $usagePercentage }}%;" aria-valuenow="{{ $usagePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between text-muted">
                        <span>{{ number_format($usedQuota, 1) }} kg utilisé</span>
                        <span>{{ number_format($subscription->remaining_quota, 1) }} kg restant</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card Historique -->
        <div class="col-12">
            <div class="card shadow-sm border-0 notification-item border-info mb-4">
                <div class="card-header d-flex align-items-center" style="background-color: var(--klin-light-bg);">
                    <i class="bi bi-clock-history me-2 text-info fs-4"></i>
                    <h5 class="mb-0 fw-bold">Historique d'Utilisation</h5>
                </div>
                <div class="card-body">
                    @if($quotaUsages->isEmpty())
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-archive fs-1 mb-2"></i>
                            <div>Aucune utilisation enregistrée pour cet abonnement.</div>
                        </div>
                    @else
                <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-0">
                                <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th>Commande</th>
                                        <th>Quota Utilisé</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quotaUsages as $usage)
                            <tr>
                                <td>{{ $usage->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                            <a href="{{ route('orders.show', $usage->order_id) }}" class="text-primary">
                                                <i class="bi bi-box-seam me-1"></i> #{{ $usage->order_id }}
                                        </a>
                                </td>
                                <td>{{ number_format($usage->amount_used, 1) }} kg</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                        @if($quotaUsages->hasPages())
                            <div class="mt-3">
                                {{ $quotaUsages->links() }}
                            </div>
                        @endif
                    @endif
            </div>
            </div>
        </div>
</div>

@if($subscription->payment_status === 'pending')
    <div class="row">
        <div class="col-12 text-end">
            <form action="{{ route('subscriptions.cancel', $subscription) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger klin-btn-outline" onclick="return confirm('Êtes-vous sûr de vouloir annuler cet abonnement ?')">
                    <i class="bi bi-x-circle me-1"></i> Annuler l'abonnement
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection 