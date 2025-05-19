@extends('layouts.admin')

@section('title', 'Créer un coupon - KLINKLIN Admin')

@section('page_title', 'Créer un nouveau coupon')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold">Nouveau coupon de réduction</h5>
                            <p class="text-muted mb-0">Créez un nouveau code promo pour vos clients</p>
                        </div>
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
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
                        <i class="fas fa-tag me-2 text-primary"></i> Informations du coupon
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.coupons.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label fw-semibold">Code du coupon</label>
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
                                        placeholder="ex: Promo lancement" value="{{ old('description') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label fw-semibold">Type de réduction</label>
                                    <select class="form-select border-0 bg-light py-2" id="type" name="type">
                                        <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Pourcentage (%)</option>
                                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Montant fixe (FCFA)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="value" class="form-label fw-semibold">Valeur</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control border-0 bg-light py-2" id="value" name="value" 
                                            placeholder="ex: 10" value="{{ old('value') }}" min="0" step="0.01" required>
                                        <span class="input-group-text bg-light border-0" id="valueType">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="min_order_amount" class="form-label fw-semibold">Montant minimum de commande (optionnel)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control border-0 bg-light py-2" id="min_order_amount" name="min_order_amount" 
                                            placeholder="ex: 5000" value="{{ old('min_order_amount') }}" min="0">
                                        <span class="input-group-text bg-light border-0">FCFA</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="maxDiscountContainer">
                                <div class="mb-3">
                                    <label for="max_discount_amount" class="form-label fw-semibold">Réduction maximum (optionnel)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control border-0 bg-light py-2" id="max_discount_amount" name="max_discount_amount" 
                                            placeholder="ex: 2000" value="{{ old('max_discount_amount') }}" min="0">
                                        <span class="input-group-text bg-light border-0">FCFA</span>
                                    </div>
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
                                    <label for="max_uses" class="form-label fw-semibold">Nombre maximum d'utilisations (optionnel)</label>
                                    <input type="number" class="form-control border-0 bg-light py-2" id="max_uses" name="max_uses" 
                                        placeholder="ex: 100" value="{{ old('max_uses') }}" min="1">
                                    <small class="text-muted">Laisser vide pour des utilisations illimitées</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Statut</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                            {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Coupon actif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary me-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Créer le coupon
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
                        <h6 class="fw-bold text-primary">Types de réduction</h6>
                        <ul class="ps-3">
                            <li class="mb-2"><strong>Pourcentage</strong> - Réduit le montant de la commande d'un certain pourcentage (ex: 10%).</li>
                            <li><strong>Montant fixe</strong> - Réduit le montant de la commande d'une valeur fixe (ex: 1000 FCFA).</li>
                        </ul>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary">Dates de validité</h6>
                        <p>Définissez une période pendant laquelle le coupon peut être utilisé. Si aucune date de fin n'est spécifiée, le coupon restera valide jusqu'à sa désactivation manuelle.</p>
                    </div>
                    
                    <div>
                        <h6 class="fw-bold text-primary">Limitations</h6>
                        <ul class="ps-3">
                            <li class="mb-2"><strong>Montant minimum</strong> - Montant que la commande doit atteindre pour que le coupon soit applicable.</li>
                            <li class="mb-2"><strong>Réduction maximum</strong> - Plafonne le montant de la réduction (utile pour les pourcentages).</li>
                            <li><strong>Nombre d'utilisations</strong> - Limite le nombre total de fois qu'un coupon peut être utilisé.</li>
                        </ul>
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
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = '';
            for (let i = 0; i < 8; i++) {
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
        
        // Afficher/masquer la réduction maximum en fonction du type
        const typeSelect = document.getElementById('type');
        const valueTypeSpan = document.getElementById('valueType');
        const maxDiscountContainer = document.getElementById('maxDiscountContainer');
        
        if (typeSelect && valueTypeSpan && maxDiscountContainer) {
            typeSelect.addEventListener('change', function() {
                if (this.value === 'percentage') {
                    valueTypeSpan.textContent = '%';
                    maxDiscountContainer.style.display = 'block';
                } else {
                    valueTypeSpan.textContent = 'FCFA';
                    maxDiscountContainer.style.display = 'none';
                }
            });
            
            // Initialiser l'affichage
            if (typeSelect.value === 'percentage') {
                valueTypeSpan.textContent = '%';
                maxDiscountContainer.style.display = 'block';
            } else {
                valueTypeSpan.textContent = 'FCFA';
                maxDiscountContainer.style.display = 'none';
            }
        }
    });
</script>
@endsection 