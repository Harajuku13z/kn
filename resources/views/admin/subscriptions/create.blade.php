@extends('layouts.admin')

@section('page_title', 'Nouvel Abonnement')

@section('content')
<div class="container-fluid">
    <!-- En-tête de la page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Créer un Nouvel Abonnement</h1>
        <a href="{{ route('admin.subscriptions.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour à la liste
        </a>
    </div>

    <!-- Formulaire de création -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informations de l'abonnement</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.subscriptions.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Client -->
                    <div class="col-md-6 mb-3">
                        <label for="user_id" class="form-label">Client <span class="text-danger">*</span></label>
                        <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                            <option value="">Sélectionnez un client</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Type d'abonnement -->
                    <div class="col-md-6 mb-3">
                        <label for="subscription_type_id" class="form-label">Type d'abonnement <span class="text-danger">*</span></label>
                        <select class="form-control @error('subscription_type_id') is-invalid @enderror" id="subscription_type_id" name="subscription_type_id" required>
                            <option value="">Sélectionnez un type</option>
                            @foreach($subscriptionTypes as $type)
                                <option value="{{ $type->id }}" data-price="{{ $type->price }}" data-quota="{{ $type->quota }}" {{ old('subscription_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }} ({{ $type->quota }} kg - {{ number_format($type->price, 0, ',', ' ') }} FCFA)
                                </option>
                            @endforeach
                        </select>
                        @error('subscription_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Quota -->
                    <div class="col-md-6 mb-3">
                        <label for="quota_purchased" class="form-label">Quota (en kg) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('quota_purchased') is-invalid @enderror" id="quota_purchased" name="quota_purchased" value="{{ old('quota_purchased') }}" min="1" step="0.1" required>
                        @error('quota_purchased')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Montant payé -->
                    <div class="col-md-6 mb-3">
                        <label for="amount_paid" class="form-label">Montant payé (FCFA) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('amount_paid') is-invalid @enderror" id="amount_paid" name="amount_paid" value="{{ old('amount_paid') }}" min="0" required>
                        @error('amount_paid')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Méthode de paiement -->
                    <div class="col-md-6 mb-3">
                        <label for="payment_method" class="form-label">Méthode de paiement <span class="text-danger">*</span></label>
                        <select class="form-control @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                            <option value="">Sélectionnez une méthode</option>
                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Carte bancaire</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Espèces</option>
                            <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Virement bancaire</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Statut du paiement -->
                    <div class="col-md-6 mb-3">
                        <label for="payment_status" class="form-label">Statut du paiement <span class="text-danger">*</span></label>
                        <select class="form-control @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status" required>
                            <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Payé</option>
                            <option value="cancelled" {{ old('payment_status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                        </select>
                        @error('payment_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date d'expiration -->
                    <div class="col-md-6 mb-3">
                        <label for="expiration_date" class="form-label">Date d'expiration</label>
                        <input type="date" class="form-control @error('expiration_date') is-invalid @enderror" id="expiration_date" name="expiration_date" value="{{ old('expiration_date') }}">
                        @error('expiration_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Laisser vide pour utiliser la durée par défaut du type d'abonnement</small>
                    </div>

                    <!-- Notes -->
                    <div class="col-md-12 mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Créer l'abonnement</button>
                    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-remplir le quota et le prix lorsque le type d'abonnement est sélectionné
    $('#subscription_type_id').change(function() {
        var selectedOption = $(this).find('option:selected');
        var quota = selectedOption.data('quota');
        var price = selectedOption.data('price');
        
        if (quota) {
            $('#quota_purchased').val(quota);
        }
        
        if (price) {
            $('#amount_paid').val(price);
        }
    });
});
</script>
@endpush
@endsection
