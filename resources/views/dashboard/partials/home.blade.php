@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Bienvenue, {{ $user->name }}!</h5>
                    <p class="card-text">Voici un aperçu de votre activité récente.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Commandes récentes</h5>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <div class="list-group">
                            @foreach($recentOrders as $order)
                                <a href="{{ route('orders.show', $order) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Commande #{{ $order->id }}</h6>
                                        <small>{{ $order->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">Statut: {{ $order->status }}</p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Aucune commande récente.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quotas actifs</h5>
                </div>
                <div class="card-body">
                    @if($activeQuotas->count() > 0)
                        <div class="list-group">
                            @foreach($activeQuotas as $quota)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">{{ $quota->name }}</h6>
                                        <small>Expire dans {{ $quota->expires_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">Quota restant: {{ $quota->remaining_quota }} kg</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Aucun quota actif.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 