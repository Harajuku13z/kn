@extends('layouts.dashboard')

@section('title', 'Modifier la commande #' . $order->id)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/orders.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Modifier la commande #{{ $order->id }}</h1>
        <div>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
            <a href="{{ route('orders.show', $order) }}" class="btn btn-info text-white">
                <i class="bi bi-eye"></i> Voir les détails
            </a>
        </div>
    </div>

    <form action="{{ route('orders.update', $order) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-emoji-wave me-2"></i> Informations de collecte</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="collection_address" class="form-label">Adresse</label>
                            <input type="text" class="form-control @error('collection_address') is-invalid @enderror" id="collection_address" name="collection_address" value="{{ old('collection_address', $order->collection_address) }}" required>
                            @error('collection_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="collection_address_complement" class="form-label">Complément d'adresse</label>
                            <input type="text" class="form-control @error('collection_address_complement') is-invalid @enderror" id="collection_address_complement" name="collection_address_complement" value="{{ old('collection_address_complement', $order->collection_address_complement) }}" placeholder="Appartement, suite, etc.">
                            @error('collection_address_complement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="collection_city" class="form-label">Ville</label>
                                <input type="text" class="form-control @error('collection_city') is-invalid @enderror" id="collection_city" name="collection_city" value="{{ old('collection_city', $order->collection_city) }}" required>
                                @error('collection_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="collection_postal_code" class="form-label">Code postal</label>
                                <input type="text" class="form-control @error('collection_postal_code') is-invalid @enderror" id="collection_postal_code" name="collection_postal_code" value="{{ old('collection_postal_code', $order->collection_postal_code) }}" required>
                                @error('collection_postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="collection_date" class="form-label">Date</label>
                                <input type="date" class="form-control @error('collection_date') is-invalid @enderror" id="collection_date" name="collection_date" value="{{ old('collection_date', $order->collection_date ? $order->collection_date->format('Y-m-d') : '') }}" required>
                                @error('collection_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="collection_time_slot" class="form-label">Créneau horaire</label>
                                <select class="form-select @error('collection_time_slot') is-invalid @enderror" id="collection_time_slot" name="collection_time_slot" required>
                                    <option value="">Sélectionnez un créneau</option>
                                    <option value="08h-10h" {{ old('collection_time_slot', $order->collection_time_slot) == '08h-10h' ? 'selected' : '' }}>08h - 10h</option>
                                    <option value="10h-12h" {{ old('collection_time_slot', $order->collection_time_slot) == '10h-12h' ? 'selected' : '' }}>10h - 12h</option>
                                    <option value="12h-14h" {{ old('collection_time_slot', $order->collection_time_slot) == '12h-14h' ? 'selected' : '' }}>12h - 14h</option>
                                    <option value="14h-16h" {{ old('collection_time_slot', $order->collection_time_slot) == '14h-16h' ? 'selected' : '' }}>14h - 16h</option>
                                    <option value="16h-18h" {{ old('collection_time_slot', $order->collection_time_slot) == '16h-18h' ? 'selected' : '' }}>16h - 18h</option>
                                </select>
                                @error('collection_time_slot')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-truck me-2"></i> Informations de livraison</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="same_address_for_delivery" name="same_address_for_delivery" {{ old('same_address_for_delivery', $order->same_address_for_delivery) ? 'checked' : '' }}>
                            <label class="form-check-label" for="same_address_for_delivery">
                                Utiliser la même adresse que pour la collecte.
                            </label>
                        </div>
                        <div id="delivery-address-fields" style="{{ old('same_address_for_delivery', $order->same_address_for_delivery) ? 'display: none;' : '' }}">
                            <div class="mb-3">
                                <label for="delivery_address" class="form-label">Adresse</label>
                                <input type="text" class="form-control @error('delivery_address') is-invalid @enderror" id="delivery_address" name="delivery_address" value="{{ old('delivery_address', $order->delivery_address) }}" placeholder="Rue, numéro">
                                @error('delivery_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="delivery_address_complement" class="form-label">Complément d'adresse</label>
                                <input type="text" class="form-control @error('delivery_address_complement') is-invalid @enderror" id="delivery_address_complement" name="delivery_address_complement" value="{{ old('delivery_address_complement', $order->delivery_address_complement) }}" placeholder="Appartement, suite, etc.">
                                @error('delivery_address_complement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="delivery_city" class="form-label">Ville</label>
                                    <input type="text" class="form-control @error('delivery_city') is-invalid @enderror" id="delivery_city" name="delivery_city" value="{{ old('delivery_city', $order->delivery_city) }}" placeholder="Votre ville">
                                    @error('delivery_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="delivery_postal_code" class="form-label">Code postal</label>
                                    <input type="text" class="form-control @error('delivery_postal_code') is-invalid @enderror" id="delivery_postal_code" name="delivery_postal_code" value="{{ old('delivery_postal_code', $order->delivery_postal_code) }}" placeholder="Votre code postal">
                                    @error('delivery_postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="delivery_instructions" class="form-label">Instructions de livraison (optionnel)</label>
                                <textarea class="form-control @error('delivery_instructions') is-invalid @enderror" id="delivery_instructions" name="delivery_instructions" rows="3" placeholder="Ex: Laisser chez le gardien, code portail 1234...">{{ old('delivery_instructions', $order->delivery_instructions) }}</textarea>
                                @error('delivery_instructions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-basket3-fill me-2"></i> Articles</h5>
            </div>
            <div class="card-body">
                <div class="row gy-4" id="articlesContainer">
                    <div class="col-md-6 article-item" data-id="chemise" data-name="Chemise" data-price="10">
                        <div class="card">
                            <div class="row g-0">
                                <div class="col-4">
                                    <img src="https://via.placeholder.com/150x120?text=Chemise" class="img-fluid rounded-start h-100 object-fit-cover" alt="Chemise">
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <h5 class="card-title">Chemise</h5>
                                        <p class="card-text"><small class="text-muted">10€ | 0,8 - 1,5 kg</small></p>
                                        <div class="input-group input-group-sm quantity-selector">
                                            <button class="btn btn-outline-secondary quantity-minus" type="button">-</button>
                                            <input type="number" class="form-control text-center item-quantity" value="{{ $order->items->where('item_id', 'chemise')->first()->quantity ?? 0 }}" min="0" aria-label="Quantité Chemise" name="items[chemise][quantity]">
                                            <input type="hidden" name="items[chemise][name]" value="Chemise">
                                            <input type="hidden" name="items[chemise][price]" value="10">
                                            <button class="btn btn-outline-secondary quantity-plus" type="button">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 article-item" data-id="jeans" data-name="Jeans" data-price="25">
                        <div class="card">
                            <div class="row g-0">
                                <div class="col-4">
                                    <img src="https://via.placeholder.com/150x120?text=Jeans" class="img-fluid rounded-start h-100 object-fit-cover" alt="Jeans">
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <h5 class="card-title">Jeans</h5>
                                        <p class="card-text"><small class="text-muted">25€ | 0,8 - 1,5 kg</small></p>
                                        <div class="input-group input-group-sm quantity-selector">
                                            <button class="btn btn-outline-secondary quantity-minus" type="button">-</button>
                                            <input type="number" class="form-control text-center item-quantity" value="{{ $order->items->where('item_id', 'jeans')->first()->quantity ?? 0 }}" min="0" aria-label="Quantité Jeans" name="items[jeans][quantity]">
                                            <input type="hidden" name="items[jeans][name]" value="Jeans">
                                            <input type="hidden" name="items[jeans][price]" value="25">
                                            <button class="btn btn-outline-secondary quantity-plus" type="button">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 article-item" data-id="veste" data-name="Veste" data-price="15">
                        <div class="card">
                            <div class="row g-0">
                                <div class="col-4"><img src="https://via.placeholder.com/150x120?text=Veste" class="img-fluid rounded-start h-100 object-fit-cover" alt="Veste"></div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <h5 class="card-title">Veste</h5>
                                        <p class="card-text"><small class="text-muted">15€ | 1-3 kg</small></p>
                                        <div class="input-group input-group-sm quantity-selector">
                                            <button class="btn btn-outline-secondary quantity-minus" type="button">-</button>
                                            <input type="number" class="form-control text-center item-quantity" value="{{ $order->items->where('item_id', 'veste')->first()->quantity ?? 0 }}" min="0" aria-label="Quantité Veste" name="items[veste][quantity]">
                                            <input type="hidden" name="items[veste][name]" value="Veste">
                                            <input type="hidden" name="items[veste][price]" value="15">
                                            <button class="btn btn-outline-secondary quantity-plus" type="button">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-credit-card-fill me-2"></i> Informations de paiement</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Méthode de paiement</label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method">
                                <option value="">Sélectionnez une méthode de paiement</option>
                                <option value="credit_card" {{ old('payment_method', $order->payment_method) == 'credit_card' ? 'selected' : '' }}>Carte de crédit</option>
                                <option value="paypal" {{ old('payment_method', $order->payment_method) == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                <option value="cash" {{ old('payment_method', $order->payment_method) == 'cash' ? 'selected' : '' }}>Espèces à la livraison</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="order_status" class="form-label">Statut de la commande</label>
                            <select class="form-select @error('order_status') is-invalid @enderror" id="order_status" name="order_status">
                                <option value="pending" {{ old('order_status', $order->order_status) == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="processing" {{ old('order_status', $order->order_status) == 'processing' ? 'selected' : '' }}>En traitement</option>
                                <option value="completed" {{ old('order_status', $order->order_status) == 'completed' ? 'selected' : '' }}>Terminée</option>
                                <option value="cancelled" {{ old('order_status', $order->order_status) == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                            </select>
                            @error('order_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de l'adresse de livraison
        const sameAddressCheckbox = document.getElementById('same_address_for_delivery');
        const deliveryFields = document.getElementById('delivery-address-fields');
        
        sameAddressCheckbox.addEventListener('change', function() {
            deliveryFields.style.display = this.checked ? 'none' : 'block';
        });
        
        // Gestion des quantités d'articles
        document.querySelectorAll('.quantity-plus').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('.item-quantity');
                input.value = parseInt(input.value) + 1;
                input.dispatchEvent(new Event('change'));
            });
        });
        
        document.querySelectorAll('.quantity-minus').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('.item-quantity');
                if (parseInt(input.value) > 0) {
                    input.value = parseInt(input.value) - 1;
                    input.dispatchEvent(new Event('change'));
                }
            });
        });
    });
</script>
@endsection 