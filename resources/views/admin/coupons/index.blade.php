@extends('layouts.admin')

@section('title', 'Gestion des coupons - KLINKLIN Admin')

@section('page_title', 'Gestion des coupons')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold">Coupons de réduction</h5>
                            <p class="text-muted mb-0">Gérez les codes promo et les coupons de réduction</p>
                        </div>
                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Nouveau coupon
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

    <!-- Coupons actifs -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold text-primary">
                <i class="fas fa-tag me-2"></i> Coupons actifs
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4">Code</th>
                            <th class="py-3">Description</th>
                            <th class="py-3">Type</th>
                            <th class="py-3">Valeur</th>
                            <th class="py-3">Utilisation</th>
                            <th class="py-3">Période de validité</th>
                            <th class="py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeCoupons as $coupon)
                        <tr>
                            <td class="py-3 px-4">
                                <span class="badge bg-primary fw-normal">{{ $coupon->code }}</span>
                            </td>
                            <td class="py-3">{{ $coupon->description ?? 'Non définie' }}</td>
                            <td class="py-3">
                                @if($coupon->type == 'percentage')
                                    <span class="badge bg-info fw-normal">Pourcentage</span>
                                @else
                                    <span class="badge bg-success fw-normal">Montant fixe</span>
                                @endif
                            </td>
                            <td class="py-3">
                                @if($coupon->type == 'percentage')
                                    {{ $coupon->value }}%
                                    @if($coupon->max_discount_amount)
                                        <small class="d-block text-muted">(max: {{ number_format($coupon->max_discount_amount, 0) }} FCFA)</small>
                                    @endif
                                @else
                                    {{ number_format($coupon->value, 0) }} FCFA
                                @endif
                            </td>
                            <td class="py-3">
                                @if($coupon->max_uses)
                                    {{ $coupon->used_count ?? 0 }}/{{ $coupon->max_uses }}
                                    <div class="progress mt-1" style="height: 5px;">
                                        <div class="progress-bar" role="progressbar" style="width: {{ ($coupon->used_count / $coupon->max_uses) * 100 }}%"></div>
                                    </div>
                                @else
                                    {{ $coupon->used_count ?? 0 }} / Illimité
                                @endif
                            </td>
                            <td class="py-3">
                                <div class="small">
                                    <div>Début: <strong>{{ $coupon->valid_from->format('d/m/Y') }}</strong></div>
                                    @if($coupon->valid_until)
                                        <div>Fin: <strong>{{ $coupon->valid_until->format('d/m/Y') }}</strong></div>
                                        @if($coupon->valid_until->isPast())
                                            <span class="badge bg-danger mt-1">Expiré</span>
                                        @else
                                            <span class="badge bg-success mt-1">Valide</span>
                                        @endif
                                    @else
                                        <div>Fin: <strong>Illimitée</strong></div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteCouponModal{{ $coupon->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p class="text-muted mb-0">Aucun coupon actif</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Coupons expirés -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold text-secondary">
                <i class="fas fa-calendar-times me-2"></i> Coupons expirés ou inactifs
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4">Code</th>
                            <th class="py-3">Description</th>
                            <th class="py-3">Type</th>
                            <th class="py-3">Valeur</th>
                            <th class="py-3">Utilisation</th>
                            <th class="py-3">Période de validité</th>
                            <th class="py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expiredCoupons as $coupon)
                        <tr class="text-muted">
                            <td class="py-3 px-4">
                                <span class="badge bg-secondary fw-normal">{{ $coupon->code }}</span>
                            </td>
                            <td class="py-3">{{ $coupon->description ?? 'Non définie' }}</td>
                            <td class="py-3">
                                @if($coupon->type == 'percentage')
                                    <span class="badge bg-secondary fw-normal">Pourcentage</span>
                                @else
                                    <span class="badge bg-secondary fw-normal">Montant fixe</span>
                                @endif
                            </td>
                            <td class="py-3">
                                @if($coupon->type == 'percentage')
                                    {{ $coupon->value }}%
                                @else
                                    {{ number_format($coupon->value, 0) }} FCFA
                                @endif
                            </td>
                            <td class="py-3">
                                {{ $coupon->used_count ?? 0 }}
                                @if($coupon->max_uses)
                                    / {{ $coupon->max_uses }}
                                @endif
                            </td>
                            <td class="py-3">
                                <div class="small">
                                    <div>Début: {{ $coupon->valid_from->format('d/m/Y') }}</div>
                                    <div>Fin: {{ $coupon->valid_until ? $coupon->valid_until->format('d/m/Y') : 'Illimitée' }}</div>
                                    @if(!$coupon->is_active)
                                        <span class="badge bg-secondary mt-1">Désactivé</span>
                                    @else
                                        <span class="badge bg-danger mt-1">Expiré</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p class="text-muted mb-0">Aucun coupon expiré ou inactif</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modals de suppression pour chaque coupon -->
@foreach($activeCoupons as $coupon)
<div class="modal fade" id="deleteCouponModal{{ $coupon->id }}" tabindex="-1" aria-labelledby="deleteCouponModalLabel{{ $coupon->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteCouponModalLabel{{ $coupon->id }}">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p>Êtes-vous sûr de vouloir supprimer le coupon <strong>{{ $coupon->code }}</strong> ?</p>
                    
                    @if($coupon->used_count > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i> Ce coupon a déjà été utilisé {{ $coupon->used_count }} fois. 
                        Il sera désactivé plutôt que supprimé définitivement.
                    </div>
                    @else
                    <p class="text-danger small">Cette action est irréversible.</p>
                    @endif
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> 
                        @if($coupon->used_count > 0)
                            Désactiver
                        @else
                            Supprimer
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection 