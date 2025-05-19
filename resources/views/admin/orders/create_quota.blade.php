@extends('layouts.admin')

@section('title', 'Créer une commande au kilo - KLINKLIN Admin')

@section('page_title')
    <div class="d-sm-flex align-items-center justify-content-between">
        <h1 class="h3 mb-0 text-gray-800">Nouvelle Commande au Kilo</h1>
        <a href="{{ route('admin.orders.create') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-box-open me-2"></i> Formulaire de commande au kilo
                    </h6>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Erreurs de validation</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_type" value="kilogram">
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="fas fa-user me-2"></i> Informations client</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="user_id" class="form-label">Client <span class="text-danger">*</span></label>
                                            <select name="user_id" id="user_id" class="form-control" required>
                                                <option value="">Sélectionner un client</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i> Informations de paiement</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="payment_status" class="form-label">Statut de paiement <span class="text-danger">*</span></label>
                                            <select name="payment_status" id="payment_status" class="form-control" required>
                                                <option value="pending">En attente</option>
                                                <option value="paid">Payé</option>
                                                <option value="failed">Échoué</option>
                                                <option value="refunded">Remboursé</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="payment_method" class="form-label">Méthode de paiement</label>
                                            <select name="payment_method" id="payment_method" class="form-control">
                                                <option value="cash">Espèces</option>
                                                <option value="quota">Quota</option>
                                                <option value="mobile_money">Mobile Money</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0"><i class="fas fa-truck me-2"></i> Collecte</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="pickup_date" class="form-label">Date de collecte <span class="text-danger">*</span></label>
                                            <input type="date" name="pickup_date" id="pickup_date" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pickup_time_slot" class="form-label">Créneau horaire <span class="text-danger">*</span></label>
                                            <select name="pickup_time_slot" id="pickup_time_slot" class="form-control" required>
                                                <option value="08:00-12:00">Matin (08:00 - 12:00)</option>
                                                <option value="12:00-18:00">Après-midi (12:00 - 18:00)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="mb-0"><i class="fas fa-truck-loading me-2"></i> Livraison</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="delivery_date" class="form-label">Date de livraison <span class="text-danger">*</span></label>
                                            <input type="date" name="delivery_date" id="delivery_date" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="delivery_time_slot" class="form-label">Créneau horaire <span class="text-danger">*</span></label>
                                            <select name="delivery_time_slot" id="delivery_time_slot" class="form-control" required>
                                                <option value="08:00-12:00">Matin (08:00 - 12:00)</option>
                                                <option value="12:00-18:00">Après-midi (12:00 - 18:00)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card bg-light mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informations supplémentaires</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Statut de la commande <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="pending">En attente</option>
                                        <option value="collected">Collecté</option>
                                        <option value="in_transit">En transit</option>
                                        <option value="washing">Lavage</option>
                                        <option value="ironing">Repassage</option>
                                        <option value="ready_for_delivery">Prêt pour livraison</option>
                                        <option value="delivering">En cours de livraison</option>
                                        <option value="delivered">Livré</option>
                                        <option value="cancelled">Annulé</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.orders.create') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Enregistrer la commande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default dates
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    // Format dates as YYYY-MM-DD
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };
    
    document.getElementById('pickup_date').value = formatDate(today);
    document.getElementById('delivery_date').value = formatDate(tomorrow);
});
</script>
@endpush 