@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- En-tête de la page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des Prix</h1>
        <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addPriceModal">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nouveau Prix
        </button>
    </div>

    <!-- Prix Actuel -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Prix Actuel</h6>
                </div>
                <div class="card-body">
                    @if($currentPrice)
                    <div class="row">
                        <div class="col-md-4">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($currentPrice->price_per_kg) }} FCFA/kg</div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Prix par kg</div>
                        </div>
                        <div class="col-md-4">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $currentPrice->effective_date->format('d/m/Y') }}</div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Date d'application</div>
                        </div>
                        <div class="col-md-4">
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $currentPrice->last_update_reason }}</div>
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Raison du changement</div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        Aucun prix n'est actuellement configuré.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des Prix -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Historique des Prix</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="pricesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Prix (FCFA/kg)</th>
                            <th>Date d'application</th>
                            <th>Raison du changement</th>
                            <th>Modifié par</th>
                            <th>Date de modification</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prices as $price)
                        <tr>
                            <td>{{ number_format($price->price_per_kg) }}</td>
                            <td>{{ $price->effective_date->format('d/m/Y') }}</td>
                            <td>{{ $price->last_update_reason }}</td>
                            <td>{{ $price->createdBy ? $price->createdBy->name : 'Système' }}</td>
                            <td>{{ $price->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nouveau Prix -->
<div class="modal fade" id="addPriceModal" tabindex="-1" aria-labelledby="addPriceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPriceModalLabel">Nouveau Prix</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.prices.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="price_per_kg">Prix par kg (FCFA)</label>
                        <input type="number" class="form-control" id="price_per_kg" name="price_per_kg" required min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="effective_date">Date d'application</label>
                        <input type="date" class="form-control" id="effective_date" name="effective_date" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label for="last_update_reason">Raison du changement</label>
                        <textarea class="form-control" id="last_update_reason" name="last_update_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#pricesTable').DataTable({
        order: [[1, 'desc']], // Trier par date d'application décroissante
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
        }
    });
});
</script>
@endpush
@endsection 