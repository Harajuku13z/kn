@extends('layouts.admin')

@section('title', 'Modifier le coupon ' . $coupon->code . ' - KLINKLIN Admin')

@section('page_title', 'Modifier le coupon ' . $coupon->code)

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold">Modification du coupon</h5>
                            <p class="text-muted mb-0">Modifier les informations du coupon {{ $coupon->code }}</p>
                        </div>
                        <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Retour au détails
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
                    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label fw-semibold">Code du coupon</label>
                                    <input type="text" class="form-control border-0 bg-light py-2" id="code" name="code" 
                                        value="{{ old('code', $coupon->code) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-semibold">Description (optionnel)</label>
                                    <input type="text" class="form-control border-0 bg-light py-2" id="description" name="description" 
                                        placeholder="ex: Promo lancement" value="{{ old('description', $coupon->description) }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label fw-semibold">Type de réduction</label>
                                    <select class="form-select border-0 bg-light py-2" id="type" name="type">
                                        <option value="percentage" {{ (old('type', $coupon->type) == 'percentage') ? 'selected' : '' }}>Pourcentage (%)</option>
                                        <option value="fixed" {{ (old('type', $coupon->type) == 'fixed') ? 'selected' : '' }}>Montant fixe (FCFA)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="value" class="form-label fw-semibold">Valeur</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control border-0 bg-light py-2" id="value" name="value" 
                                            placeholder="ex: 10" value="{{ old('value', $coupon->value) }}" min="0" step="0.01" required>
                                        <span class="input-group-text bg-light border-0" id="valueType">
                                            {{ $coupon->type === 'percentage' ? '%' : 'FCFA' }}
                                        </span>
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
                                            placeholder="ex: 5000" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" min="0">
                                        <span class="input-group-text bg-light border-0">FCFA</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="maxDiscountContainer" style="{{ $coupon->type !== 'percentage' ? 'display: none;' : '' }}">
                                <div class="mb-3">
                                    <label for="max_discount_amount" class="form-label fw-semibold">Réduction maximum (optionnel)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control border-0 bg-light py-2" id="max_discount_amount" name="max_discount_amount" 
                                            placeholder="ex: 2000" value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}" min="0">
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
                                        value="{{ old('valid_from', $coupon->valid_from->format('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="valid_until" class="form-label fw-semibold">Date de fin (optionnelle)</label>
                                    <input type="date" class="form-control border-0 bg-light py-2" id="valid_until" name="valid_until" 
                                        value="{{ old('valid_until', $coupon->valid_until ? $coupon->valid_until->format('Y-m-d') : '') }}">
                                    <small class="text-muted">Laisser vide pour une validité illimitée</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_uses" class="form-label fw-semibold">Nombre maximum d'utilisations (optionnel)</label>
                                    <input type="number" class="form-control border-0 bg-light py-2" id="max_uses" name="max_uses" 
                                        placeholder="ex: 100" value="{{ old('max_uses', $coupon->max_uses) }}" min="1">
                                    <small class="text-muted">Laisser vide pour des utilisations illimitées</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Statut</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                            {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Coupon actif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-outline-secondary me-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Statistiques -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i> Utilisation
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary-soft rounded-circle p-2 me-3">
                            <i class="fas fa-shopping-cart text-primary"></i>
                        </div>
                        <div>
                            <h2 class="mb-0 fw-bold">{{ $coupon->used_count ?? 0 }}</h2>
                            <p class="text-muted mb-0 small">Utilisations actuelles</p>
                        </div>
                    </div>
                    
                    @if($coupon->max_uses)
                    <div class="bg-light p-3 rounded mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small">Utilisation</span>
                            <span class="small">{{ $coupon->used_count ?? 0 }}/{{ $coupon->max_uses }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ ($coupon->used_count / $coupon->max_uses) * 100 }}%"></div>
                        </div>
                    </div>
                    @endif
                    
                    @if($coupon->valid_until && $coupon->valid_until->isPast())
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Ce coupon est expiré depuis {{ $coupon->valid_until->diffForHumans() }}.
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Aide -->
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
                            <li class="mb-2"><strong>Pourcentage</strong> - Réduction basée sur un pourcentage du montant total.</li>
                            <li><strong>Montant fixe</strong> - Réduction d'un montant spécifique en FCFA.</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h6 class="fw-bold text-primary">Considérations</h6>
                        <ul class="ps-3">
                            <li class="mb-2">Si le coupon a déjà été utilisé, changer son type ou sa valeur n'affectera pas les commandes passées.</li>
                            <li>Désactiver un coupon empêchera son utilisation même s'il n'a pas atteint sa date d'expiration ou son nombre maximum d'utilisations.</li>
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
        }
    });
</script>
@endsection 