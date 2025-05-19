@extends('layouts.admin')

@section('title', 'Détails du frais de livraison - KLINKLIN Admin')

@section('page_title')
    <div class="d-sm-flex align-items-center justify-content-between">
        <h1 class="h3 mb-0 text-klin-primary">Détails du frais de livraison</h1>
        <a href="{{ route('admin.delivery-fees.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Retour à la liste
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-klin-primary">
                <i class="bi bi-truck me-1"></i> Frais de livraison pour {{ $deliveryFee->neighborhood }}
            </h6>
            <div>
                <a href="{{ route('admin.delivery-fees.edit', $deliveryFee) }}" class="btn btn-sm btn-primary rounded-pill">
                    <i class="bi bi-pencil-square me-1"></i> Modifier
                </a>
                <form action="{{ route('admin.delivery-fees.destroy', $deliveryFee) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger rounded-pill" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce frais de livraison ?');">
                        <i class="bi bi-trash me-1"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th style="width: 200px;">ID</th>
                            <td>{{ $deliveryFee->id }}</td>
                        </tr>
                        <tr>
                            <th>Quartier</th>
                            <td>{{ $deliveryFee->neighborhood }}</td>
                        </tr>
                        <tr>
                            <th>Frais (FCFA)</th>
                            <td>{{ number_format($deliveryFee->fee, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        <tr>
                            <th>Statut</th>
                            <td>
                                @if($deliveryFee->is_active)
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-danger">Inactif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $deliveryFee->description ?: 'Aucune description' }}</td>
                        </tr>
                        <tr>
                            <th>Date de création</th>
                            <td>{{ $deliveryFee->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Dernière mise à jour</th>
                            <td>{{ $deliveryFee->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.delivery-fees.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection 