@extends('layouts.admin')

@section('title', 'Modifier la commande - KLINKLIN Admin')

@section('page_title', 'Modifier la commande #' . $order->id)

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            Modifier la commande #{{ $order->id }}
        </h1>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-edit me-1"></i> Formulaire de modification
            </h6>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="collected" {{ $order->status === 'collected' ? 'selected' : '' }}>Collecté</option>
                                <option value="in_transit" {{ $order->status === 'in_transit' ? 'selected' : '' }}>En transit</option>
                                <option value="washing" {{ $order->status === 'washing' ? 'selected' : '' }}>Lavage</option>
                                <option value="ironing" {{ $order->status === 'ironing' ? 'selected' : '' }}>Repassage</option>
                                <option value="ready_for_delivery" {{ $order->status === 'ready_for_delivery' ? 'selected' : '' }}>Prêt pour livraison</option>
                                <option value="delivering" {{ $order->status === 'delivering' ? 'selected' : '' }}>En cours de livraison</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Livré</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Statut de paiement</label>
                            <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status">
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Payé</option>
                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Échoué</option>
                                <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Remboursé</option>
                            </select>
                            @error('payment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4">{{ old('notes', $order->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="pickup_date" class="form-label">Date de collecte</label>
                            <input type="date" class="form-control @error('pickup_date') is-invalid @enderror" id="pickup_date" name="pickup_date" value="{{ old('pickup_date', $order->pickup_date ? $order->pickup_date->format('Y-m-d') : '') }}">
                            @error('pickup_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="pickup_time_slot" class="form-label">Créneau de collecte</label>
                            <select class="form-select @error('pickup_time_slot') is-invalid @enderror" id="pickup_time_slot" name="pickup_time_slot">
                                <option value="">Sélectionner un créneau</option>
                                <option value="08h - 10h" {{ $order->pickup_time_slot === '08h - 10h' ? 'selected' : '' }}>08h - 10h</option>
                                <option value="10h - 12h" {{ $order->pickup_time_slot === '10h - 12h' ? 'selected' : '' }}>10h - 12h</option>
                                <option value="12h - 14h" {{ $order->pickup_time_slot === '12h - 14h' ? 'selected' : '' }}>12h - 14h</option>
                                <option value="14h - 16h" {{ $order->pickup_time_slot === '14h - 16h' ? 'selected' : '' }}>14h - 16h</option>
                                <option value="16h - 18h" {{ $order->pickup_time_slot === '16h - 18h' ? 'selected' : '' }}>16h - 18h</option>
                            </select>
                            @error('pickup_time_slot')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="delivery_date" class="form-label">Date de livraison</label>
                            <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" id="delivery_date" name="delivery_date" value="{{ old('delivery_date', $order->delivery_date ? $order->delivery_date->format('Y-m-d') : '') }}">
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="delivery_time_slot" class="form-label">Créneau de livraison</label>
                            <select class="form-select @error('delivery_time_slot') is-invalid @enderror" id="delivery_time_slot" name="delivery_time_slot">
                                <option value="">Sélectionner un créneau</option>
                                <option value="08h - 10h" {{ $order->delivery_time_slot === '08h - 10h' ? 'selected' : '' }}>08h - 10h</option>
                                <option value="10h - 12h" {{ $order->delivery_time_slot === '10h - 12h' ? 'selected' : '' }}>10h - 12h</option>
                                <option value="12h - 14h" {{ $order->delivery_time_slot === '12h - 14h' ? 'selected' : '' }}>12h - 14h</option>
                                <option value="14h - 16h" {{ $order->delivery_time_slot === '14h - 16h' ? 'selected' : '' }}>14h - 16h</option>
                                <option value="16h - 18h" {{ $order->delivery_time_slot === '16h - 18h' ? 'selected' : '' }}>16h - 18h</option>
                            </select>
                            @error('delivery_time_slot')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <div>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Retour aux détails
                        </a>
                    </div>
                    
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 