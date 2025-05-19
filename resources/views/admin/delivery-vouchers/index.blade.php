@extends('layouts.admin')

@section('title', 'Gestion des bons de livraison - KLINKLIN Admin')

@section('page_title', 'Gestion des bons de livraison')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold">Bons de livraison</h5>
                            <p class="text-muted mb-0">Gérez les bons de livraison offerts aux clients</p>
                        </div>
                        <a href="{{ route('admin.delivery-vouchers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Nouveau bon
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

    <!-- Bons actifs -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold text-primary">
                <i class="fas fa-ticket-alt me-2"></i> Bons de livraison actifs
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4">Code</th>
                            <th class="py-3">Client</th>
                            <th class="py-3">Description</th>
                            <th class="py-3">Livraisons</th>
                            <th class="py-3">Validité</th>
                            <th class="py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeVouchers as $voucher)
                        <tr>
                            <td class="py-3 px-4">
                                <span class="badge bg-primary fw-normal">{{ $voucher->code }}</span>
                            </td>
                            <td class="py-3">
                                @if($voucher->user)
                                <a href="{{ route('admin.users.show', $voucher->user) }}" class="text-decoration-none">
                                    {{ $voucher->user->name }}
                                </a>
                                @else
                                Client inconnu
                                @endif
                            </td>
                            <td class="py-3">{{ $voucher->description ?? 'Non définie' }}</td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <span class="me-3">{{ $voucher->used_deliveries ?? 0 }}/{{ $voucher->number_of_deliveries }}</span>
                                    <div class="flex-grow-1" style="max-width: 100px">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ ($voucher->used_deliveries / $voucher->number_of_deliveries) * 100 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="small">
                                    <div>Début: <strong>{{ $voucher->valid_from->format('d/m/Y') }}</strong></div>
                                    @if($voucher->valid_until)
                                        <div>Fin: <strong>{{ $voucher->valid_until->format('d/m/Y') }}</strong></div>
                                        @if($voucher->valid_until->isPast())
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
                                    <a href="{{ route('admin.delivery-vouchers.show', $voucher) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.delivery-vouchers.edit', $voucher) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteVoucherModal{{ $voucher->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="text-muted mb-0">Aucun bon de livraison actif</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bons expirés ou inactifs -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold text-secondary">
                <i class="fas fa-calendar-times me-2"></i> Bons de livraison expirés ou inactifs
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4">Code</th>
                            <th class="py-3">Client</th>
                            <th class="py-3">Description</th>
                            <th class="py-3">Livraisons</th>
                            <th class="py-3">Validité</th>
                            <th class="py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expiredVouchers as $voucher)
                        <tr class="text-muted">
                            <td class="py-3 px-4">
                                <span class="badge bg-secondary fw-normal">{{ $voucher->code }}</span>
                            </td>
                            <td class="py-3">
                                @if($voucher->user)
                                <a href="{{ route('admin.users.show', $voucher->user) }}" class="text-decoration-none text-secondary">
                                    {{ $voucher->user->name }}
                                </a>
                                @else
                                Client inconnu
                                @endif
                            </td>
                            <td class="py-3">{{ $voucher->description ?? 'Non définie' }}</td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <span class="me-3">{{ $voucher->used_deliveries ?? 0 }}/{{ $voucher->number_of_deliveries }}</span>
                                    <div class="flex-grow-1" style="max-width: 100px">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ ($voucher->used_deliveries / $voucher->number_of_deliveries) * 100 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="small">
                                    <div>Début: {{ $voucher->valid_from->format('d/m/Y') }}</div>
                                    <div>Fin: {{ $voucher->valid_until ? $voucher->valid_until->format('d/m/Y') : 'Illimitée' }}</div>
                                    @if(!$voucher->is_active)
                                        <span class="badge bg-secondary mt-1">Désactivé</span>
                                    @else
                                        <span class="badge bg-danger mt-1">Expiré</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.delivery-vouchers.show', $voucher) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="text-muted mb-0">Aucun bon de livraison expiré ou inactif</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modals de suppression pour chaque bon -->
@foreach($activeVouchers as $voucher)
<div class="modal fade" id="deleteVoucherModal{{ $voucher->id }}" tabindex="-1" aria-labelledby="deleteVoucherModalLabel{{ $voucher->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteVoucherModalLabel{{ $voucher->id }}">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p>Êtes-vous sûr de vouloir supprimer le bon de livraison <strong>{{ $voucher->code }}</strong> ?</p>
                    
                    @if($voucher->used_deliveries > 0)
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i> Ce bon a déjà été utilisé {{ $voucher->used_deliveries }} fois. 
                        Il sera désactivé plutôt que supprimé définitivement.
                    </div>
                    @else
                    <p class="text-danger small">Cette action est irréversible.</p>
                    @endif
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.delivery-vouchers.destroy', $voucher) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> 
                        @if($voucher->used_deliveries > 0)
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