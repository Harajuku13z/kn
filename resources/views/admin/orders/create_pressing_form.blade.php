@extends('layouts.admin')

@section('title', 'Créer une commande pressing - KLINKLIN Admin')

@section('page_title')
    <div class="d-sm-flex align-items-center justify-content-between">
        <h1 class="h3 mb-0 text-gray-800">Nouvelle Commande Pressing</h1>
        <a href="{{ route('admin.orders.create.pressing.select-user') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
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
                        <i class="fas fa-tshirt me-2"></i> Commande pressing pour {{ $user->name }}
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

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_type" value="pressing">
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="fas fa-user me-2"></i> Informations client</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nom</label>
                                            <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Téléphone</label>
                                            <input type="text" class="form-control" value="{{ $user->phone ?? 'Non renseigné' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0"><i class="fas fa-store me-2"></i> Pressing</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="pressing_id" class="form-label">Pressing <span class="text-danger">*</span></label>
                                            <select name="pressing_id" id="pressing_id" class="form-control" required>
                                                <option value="">Sélectionner un pressing</option>
                                                @foreach($pressings as $pressing)
                                                    <option value="{{ $pressing->id }}">{{ $pressing->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
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
                                                <option value="mobile_money">Mobile Money</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i> Adresses</h5>
                                        <a href="{{ route('admin.orders.address.create', ['userId' => $user->id, 'return_type' => 'pressing']) }}" class="btn btn-sm btn-light">
                                            <i class="fas fa-plus me-1"></i> Ajouter une adresse
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        @if($addresses->count() > 0)
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="pickup_address" class="form-label">Adresse de collecte <span class="text-danger">*</span></label>
                                                        <select name="pickup_address" id="pickup_address" class="form-control" required>
                                                            <option value="">Sélectionner une adresse</option>
                                                            @foreach($addresses as $address)
                                                                <option value="{{ $address->id }}" {{ $address->is_default ? 'selected' : '' }}>
                                                                    {{ $address->name }} - {{ $address->address }}, {{ $address->district }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="delivery_address" class="form-label">Adresse de livraison <span class="text-danger">*</span></label>
                                                        <select name="delivery_address" id="delivery_address" class="form-control" required>
                                                            <option value="">Sélectionner une adresse</option>
                                                            @foreach($addresses as $address)
                                                                <option value="{{ $address->id }}" {{ $address->is_default ? 'selected' : '' }}>
                                                                    {{ $address->name }} - {{ $address->address }}, {{ $address->district }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i> Aucune adresse n'est enregistrée pour ce client. 
                                                <a href="{{ route('admin.orders.address.create', ['userId' => $user->id, 'return_type' => 'pressing']) }}" class="alert-link">
                                                    Ajouter une adresse
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Collecte</h5>
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
                                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Livraison</h5>
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
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Les articles spécifiques du pressing pourront être ajoutés après la création de la commande.
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.orders.create.pressing.select-user') }}" class="btn btn-outline-secondary">
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

    // Copy pickup address to delivery address if they are the same
    const pickupAddressSelect = document.getElementById('pickup_address');
    const deliveryAddressSelect = document.getElementById('delivery_address');
    
    pickupAddressSelect.addEventListener('change', function() {
        if (confirm('Utiliser la même adresse pour la livraison?')) {
            deliveryAddressSelect.value = pickupAddressSelect.value;
        }
    });
});
</script>
@endpush 