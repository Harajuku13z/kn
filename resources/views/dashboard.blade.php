@extends('layouts.dashboard')

@section('title', 'Tableau de Bord - KLINKLIN')

@section('content')
<h1 class="mb-4">Tableau de bord</h1>

@if (session('verification_email_sent'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <strong>Un email de vérification a été envoyé!</strong> Veuillez vérifier votre boîte de réception et cliquer sur le lien pour confirmer votre adresse email.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (!Auth::user()->hasVerifiedEmail())
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Votre adresse email n'est pas vérifiée!</strong> Pour accéder à toutes les fonctionnalités de KLINKLIN, veuillez vérifier votre adresse email.
        <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-primary ms-2">Renvoyer l'email de vérification</button>
        </form>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <h5 class="card-title">Quota disponible</h5>
                @if(count($activeQuotas) > 0)
                    @php
                        $totalQuota = $activeQuotas->sum('total_kg');
                        $usedQuota = $activeQuotas->sum('used_kg');
                        $remainingQuota = $totalQuota - $usedQuota;
                        $percentUsed = $totalQuota > 0 ? ($usedQuota / $totalQuota) * 100 : 0;
                    @endphp
                    <h2 class="mb-3">{{ number_format($remainingQuota, 1) }} kg</h2>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ 100 - $percentUsed }}%" aria-valuenow="{{ 100 - $percentUsed }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p class="card-text mt-2">{{ number_format($usedQuota, 1) }} kg utilisés sur {{ number_format($totalQuota, 1) }} kg</p>
                @else
                    <h2 class="mb-3">0 kg</h2>
                    <p class="card-text">Aucun quota actif</p>
                    <a href="#" class="btn btn-outline-primary btn-sm">Acheter un quota</a>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <h5 class="card-title">Commandes</h5>
                <h2 class="mb-3">{{ count($recentOrders) }}</h2>
                <p class="card-text">Commandes en cours</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-primary btn-sm">Voir toutes</a>
                    <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">Nouvelle commande</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <h5 class="card-title">Prochaine collecte</h5>
                @php
                    $nextPickup = $recentOrders->where('status', 'scheduled')->first();
                @endphp
                
                @if($nextPickup)
                    <h2 class="mb-3">{{ $nextPickup->pickup_date ? \Carbon\Carbon::parse($nextPickup->pickup_date)->format('d/m/Y') : 'N/A' }}</h2>
                    <p class="card-text">à {{ $nextPickup->pickup_date ? \Carbon\Carbon::parse($nextPickup->pickup_date)->format('H:i') : '' }}</p>
                    <a href="{{ route('orders.show', $nextPickup) }}" class="btn btn-outline-primary btn-sm">Détails</a>
                @else
                    <p class="card-text">Aucune collecte prévue</p>
                    <a href="{{ route('orders.create') }}" class="btn btn-outline-primary btn-sm">Planifier</a>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <h5 class="card-title">Prochaine livraison</h5>
                @php
                    $nextDelivery = $recentOrders->whereIn('status', ['processing', 'ready_for_delivery'])->first();
                @endphp
                
                @if($nextDelivery)
                    <h2 class="mb-3">{{ $nextDelivery->delivery_date ? \Carbon\Carbon::parse($nextDelivery->delivery_date)->format('d/m/Y') : 'N/A' }}</h2>
                    <p class="card-text">à {{ $nextDelivery->delivery_date ? \Carbon\Carbon::parse($nextDelivery->delivery_date)->format('H:i') : '' }}</p>
                    <a href="{{ route('orders.show', $nextDelivery) }}" class="btn btn-outline-primary btn-sm">Détails</a>
                @else
                    <p class="card-text">Aucune livraison prévue</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-8">
        <div class="card dashboard-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Commandes récentes</h5>
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">Voir toutes</a>
            </div>
            <div class="card-body">
                @if($recentOrders->isEmpty())
                    <p class="text-center py-3">Aucune commande récente</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Numéro</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Poids</th>
                                    <th>Montant</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') : 'N/A' }}</td>
                                        <td>
                                            @if($order->status == 'pending')
                                                <span class="badge bg-warning">En attente</span>
                                            @elseif($order->status == 'scheduled')
                                                <span class="badge bg-info">Planifiée</span>
                                            @elseif($order->status == 'collected')
                                                <span class="badge bg-primary">Collectée</span>
                                            @elseif($order->status == 'processing')
                                                <span class="badge bg-secondary">En traitement</span>
                                            @elseif($order->status == 'ready_for_delivery')
                                                <span class="badge bg-info">Prête</span>
                                            @elseif($order->status == 'delivered')
                                                <span class="badge bg-success">Livrée</span>
                                            @elseif($order->status == 'cancelled')
                                                <span class="badge bg-danger">Annulée</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->total_weight }} kg</td>
                                        <td>{{ number_format($order->total_price, 0, ',', ' ') }} FCFA</td>
                                        <td>
                                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">Notifications</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex align-items-center border-start-0 border-end-0">
                        <div class="me-3 text-primary">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                        <div>
                            <p class="mb-0">Votre commande #123 a été collectée</p>
                            <small class="text-muted">Il y a 2 heures</small>
                        </div>
                    </li>
                    <li class="list-group-item d-flex align-items-center border-start-0 border-end-0">
                        <div class="me-3 text-info">
                            <i class="fas fa-info-circle fa-lg"></i>
                        </div>
                        <div>
                            <p class="mb-0">Votre quota est presque épuisé</p>
                            <small class="text-muted">Il y a 1 jour</small>
                        </div>
                    </li>
                    <li class="list-group-item d-flex align-items-center border-start-0 border-end-0">
                        <div class="me-3 text-success">
                            <i class="fas fa-truck fa-lg"></i>
                        </div>
                        <div>
                            <p class="mb-0">Votre commande #120 a été livrée</p>
                            <small class="text-muted">Il y a 3 jours</small>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="card-footer text-center">
                <a href="#" class="text-decoration-none">Voir toutes les notifications</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .feature-icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .feature-icon-circle i {
        font-size: 1.5rem;
    }
    
    .bg-light-purple {
        background-color: #f2eef5;
    }
    
    .text-klin-primary {
        color: #4A148C;
    }
</style>
@endpush 