@extends('layouts.admin')

@section('title', 'Règles d\'attribution automatique - KLINKLIN Admin')

@section('page_title', 'Règles d\'attribution automatique de bons de livraison')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold">Règles d'attribution automatique</h5>
                            <p class="text-muted mb-0">Configurez quand les clients recevront automatiquement des bons de livraison gratuite</p>
                        </div>
                        <a href="{{ route('admin.delivery-vouchers.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-ticket-alt me-1"></i> Gérer les bons
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form action="{{ route('admin.automatic-vouchers.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Première commande -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-primary">
                                <i class="fas fa-star me-2"></i> Première commande
                            </h6>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="first_order_enabled" name="first_order_enabled" value="1" 
                                    {{ $settings['first_order_enabled'] ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted">Offrez automatiquement un bon de livraison gratuite lors de la première commande d'un client.</p>
                        
                        <div class="mb-3">
                            <label for="first_order_deliveries" class="form-label">Nombre de livraisons offertes</label>
                            <input type="number" class="form-control border-0 bg-light" id="first_order_deliveries" name="first_order_deliveries" 
                                placeholder="1" value="{{ $settings['first_order_deliveries'] }}" min="1" required>
                            <small class="text-muted">Combien de livraisons gratuites seront offertes</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Nième commande -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-primary">
                                <i class="fas fa-award me-2"></i> Fidélité client
                            </h6>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="nth_order_enabled" name="nth_order_enabled" value="1" 
                                    {{ $settings['nth_order_enabled'] ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted">Récompensez la fidélité de vos clients en offrant un bon après un certain nombre de commandes.</p>
                        
                        <div class="mb-3">
                            <label for="nth_order_count" class="form-label">À chaque X commandes</label>
                            <input type="number" class="form-control border-0 bg-light" id="nth_order_count" name="nth_order_count" 
                                placeholder="10" value="{{ $settings['nth_order_count'] }}" min="2" required>
                            <small class="text-muted">Offrir une livraison gratuite après X commandes</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nth_order_deliveries" class="form-label">Nombre de livraisons offertes</label>
                            <input type="number" class="form-control border-0 bg-light" id="nth_order_deliveries" name="nth_order_deliveries" 
                                placeholder="1" value="{{ $settings['nth_order_deliveries'] }}" min="1" required>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Période spéciale -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-primary">
                                <i class="fas fa-calendar-alt me-2"></i> Période spéciale
                            </h6>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="period_enabled" name="period_enabled" value="1" 
                                    {{ $settings['period_enabled'] ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted">Offrez des livraisons gratuites pendant une période spécifique (fêtes, promotions, etc).</p>
                        
                        <div class="mb-3">
                            <label for="period_start" class="form-label">Date de début</label>
                            <input type="date" class="form-control border-0 bg-light" id="period_start" name="period_start" 
                                value="{{ $settings['period_start'] ?? now()->format('Y-m-d') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="period_end" class="form-label">Date de fin</label>
                            <input type="date" class="form-control border-0 bg-light" id="period_end" name="period_end" 
                                value="{{ $settings['period_end'] ?? now()->addDays(7)->format('Y-m-d') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="period_deliveries" class="form-label">Nombre de livraisons offertes</label>
                            <input type="number" class="form-control border-0 bg-light" id="period_deliveries" name="period_deliveries" 
                                placeholder="1" value="{{ $settings['period_deliveries'] }}" min="1" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-2 mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold">Informations importantes</h6>
                                <p class="text-muted mb-0 small">
                                    <i class="fas fa-info-circle me-1"></i> Les règles s'appliquent à tous les nouveaux clients et commandes à partir du moment où elles sont activées.
                                </p>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Enregistrer les règles
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i> Statistiques des bons automatiques
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4">Type de bon</th>
                                    <th class="py-3">Bons générés</th>
                                    <th class="py-3">Livraisons utilisées</th>
                                    <th class="py-3">Économies pour les clients</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="py-3 px-4">Première commande</td>
                                    <td class="py-3">{{ App\Models\DeliveryVoucher::where('description', 'Livraison gratuite - Première commande')->count() }}</td>
                                    <td class="py-3">{{ App\Models\DeliveryVoucher::where('description', 'Livraison gratuite - Première commande')->sum('used_deliveries') }}</td>
                                    <td class="py-3">{{ number_format(App\Models\DeliveryVoucher::where('description', 'Livraison gratuite - Première commande')->sum('used_deliveries') * 1000, 0, ',', ' ') }} FCFA</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4">Fidélité</td>
                                    <td class="py-3">{{ App\Models\DeliveryVoucher::where('description', 'like', 'Livraison gratuite - Fidélité%')->count() }}</td>
                                    <td class="py-3">{{ App\Models\DeliveryVoucher::where('description', 'like', 'Livraison gratuite - Fidélité%')->sum('used_deliveries') }}</td>
                                    <td class="py-3">{{ number_format(App\Models\DeliveryVoucher::where('description', 'like', 'Livraison gratuite - Fidélité%')->sum('used_deliveries') * 1000, 0, ',', ' ') }} FCFA</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4">Période promotionnelle</td>
                                    <td class="py-3">{{ App\Models\DeliveryVoucher::where('description', 'like', 'Livraison gratuite - Période promotionnelle%')->count() }}</td>
                                    <td class="py-3">{{ App\Models\DeliveryVoucher::where('description', 'like', 'Livraison gratuite - Période promotionnelle%')->sum('used_deliveries') }}</td>
                                    <td class="py-3">{{ number_format(App\Models\DeliveryVoucher::where('description', 'like', 'Livraison gratuite - Période promotionnelle%')->sum('used_deliveries') * 1000, 0, ',', ' ') }} FCFA</td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-light fw-bold">
                                <tr>
                                    <td class="py-3 px-4">Total</td>
                                    <td class="py-3">{{ App\Models\DeliveryVoucher::whereIn('description', ['Livraison gratuite - Première commande', 'Livraison gratuite - Fidélité', 'Livraison gratuite - Période promotionnelle'])->count() }}</td>
                                    <td class="py-3">{{ App\Models\DeliveryVoucher::whereIn('description', ['Livraison gratuite - Première commande', 'Livraison gratuite - Fidélité', 'Livraison gratuite - Période promotionnelle'])->sum('used_deliveries') }}</td>
                                    <td class="py-3">{{ number_format(App\Models\DeliveryVoucher::whereIn('description', ['Livraison gratuite - Première commande', 'Livraison gratuite - Fidélité', 'Livraison gratuite - Période promotionnelle'])->sum('used_deliveries') * 1000, 0, ',', ' ') }} FCFA</td>
                                </tr>
                            </tfoot>
                        </table>
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
        // Activer/désactiver les champs en fonction des checkboxes
        function toggleFieldsState(checkboxId, fieldsContainer) {
            const checkbox = document.getElementById(checkboxId);
            const container = document.querySelector(fieldsContainer);
            const inputs = container.querySelectorAll('input:not([type="checkbox"])');
            
            function updateState() {
                const isEnabled = checkbox.checked;
                inputs.forEach(input => {
                    input.disabled = !isEnabled;
                    if (!isEnabled) {
                        input.classList.add('bg-light');
                    } else {
                        input.classList.remove('bg-light');
                    }
                });
            }
            
            checkbox.addEventListener('change', updateState);
            updateState(); // État initial
        }
        
        // Appliquer la logique à chaque section
        toggleFieldsState('first_order_enabled', '#first_order_enabled').closest('.card').querySelector('.card-body');
        toggleFieldsState('nth_order_enabled', '#nth_order_enabled').closest('.card').querySelector('.card-body');
        toggleFieldsState('period_enabled', '#period_enabled').closest('.card').querySelector('.card-body');
    });
</script>
@endsection 