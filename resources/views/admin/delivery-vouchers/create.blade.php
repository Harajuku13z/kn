@extends('layouts.admin')

@section('title', 'Créer un bon de livraison - KLINKLIN Admin')

@section('page_title', 'Créer un nouveau bon de livraison')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold">Nouveau bon de livraison</h5>
                            <p class="text-muted mb-0">Créez un nouveau bon de livraison pour un client</p>
                        </div>
                        <a href="{{ route('admin.delivery-vouchers.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> Il y a des erreurs dans le formulaire
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-ticket-alt me-2 text-primary"></i> Informations du bon
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.delivery-vouchers.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label fw-semibold">Code du bon</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control border-0 bg-light py-2" id="code" name="code" 
                                            placeholder="Laisser vide pour génération automatique" value="{{ old('code') }}">
                                        <button class="btn btn-outline-secondary" type="button" id="generateCode">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Si laissé vide, un code aléatoire sera généré</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-semibold">Description (optionnel)</label>
                                    <input type="text" class="form-control border-0 bg-light py-2" id="description" name="description" 
                                        placeholder="ex: Offert pour fidélité" value="{{ old('description') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label fw-semibold">Client bénéficiaire</label>
                                    <select class="form-select border-0 bg-light py-2" id="user_id" name="user_id" required>
                                        <option value="">Sélectionner un client</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="number_of_deliveries" class="form-label fw-semibold">Nombre de livraisons</label>
                                    <input type="number" class="form-control border-0 bg-light py-2" id="number_of_deliveries" name="number_of_deliveries" 
                                        placeholder="ex: 5" value="{{ old('number_of_deliveries', 1) }}" min="1" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="valid_from" class="form-label fw-semibold">Date de début</label>
                                    <input type="date" class="form-control border-0 bg-light py-2" id="valid_from" name="valid_from" 
                                        value="{{ old('valid_from', date('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="valid_until" class="form-label fw-semibold">Date de fin (optionnelle)</label>
                                    <input type="date" class="form-control border-0 bg-light py-2" id="valid_until" name="valid_until" 
                                        value="{{ old('valid_until') }}">
                                    <small class="text-muted">Laisser vide pour une validité illimitée</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reason" class="form-label fw-semibold">Raison (optionnel)</label>
                                    <input type="text" class="form-control border-0 bg-light py-2" id="reason" name="reason" 
                                        placeholder="ex: Compensation pour retard" value="{{ old('reason') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Statut</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                            {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Bon actif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.delivery-vouchers.index') }}" class="btn btn-outline-secondary me-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Créer le bon
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2 text-primary"></i> Aide
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary">Bons de livraison</h6>
                        <p>Les bons de livraison permettent d'offrir des livraisons gratuites à vos clients. Ces bons peuvent être utilisés lors de la création d'une commande.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary">Dates de validité</h6>
                        <p>Définissez une période pendant laquelle le bon peut être utilisé. Si aucune date de fin n'est spécifiée, le bon restera valide jusqu'à sa désactivation manuelle ou jusqu'à l'épuisement des livraisons.</p>
                    </div>
                    
                    <div>
                        <h6 class="fw-bold text-primary">Nombre de livraisons</h6>
                        <p>Ce nombre détermine combien de fois le client pourra utiliser ce bon pour bénéficier d'une livraison gratuite.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Générer un code aléatoire
        const generateRandomCode = function() {
            const prefix = 'DV-';
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = prefix;
            for (let i = 0; i < 6; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return result;
        };
        
        // Bouton de génération de code
        const generateBtn = document.getElementById('generateCode');
        if (generateBtn) {
            generateBtn.addEventListener('click', function() {
                document.getElementById('code').value = generateRandomCode();
            });
        }
    });
</script>
@endsection 