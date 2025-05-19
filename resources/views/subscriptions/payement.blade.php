@extends('layouts.dashboard')

@section('title', 'Paiement Abonnement - KLINKLIN')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="mb-4">
                <h2 class="fw-bold" style="color: #4CB690;">Paiement de l'abonnement</h2>
                <p class="text-muted">Vérifiez les informations et complétez votre paiement.</p>
            </div>
            <form action="{{ route('subscriptions.payement.store') }}" method="POST" class="bg-white p-4 rounded-4 border">
                @csrf
                <input type="hidden" name="subscription_type_id" value="{{ $subscriptionType->id }}">
                <div class="mb-3">
                    <label class="form-label fw-bold">Forfait choisi</label>
                    <input type="text" class="form-control" value="{{ $subscriptionType->name }} ({{ number_format($subscriptionType->price) }} FCFA / {{ $subscriptionType->duration }} jours, {{ $subscriptionType->quota }} kg)" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Moyen de paiement</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="cash" selected>Paiement à la collecte/livraison</option>
                        <option value="card" disabled>Carte bancaire (indisponible)</option>
                        <option value="mobile_money" disabled>Mobile Money (indisponible)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Nom du payeur</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Téléphone</label>
                    <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Montant à payer</label>
                    <div class="fs-3 fw-bold" style="color: #004AAD;">{{ number_format($subscriptionType->price) }} FCFA</div>
                </div>
                <button type="submit" class="btn btn-success w-100 py-2 fw-bold" style="border-radius: 50px; font-size: 1.1rem;">Payer</button>
            </form>
        </div>
    </div>
</div>
@endsection 