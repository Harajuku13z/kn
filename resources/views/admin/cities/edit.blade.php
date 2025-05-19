@extends('layouts.admin')

@section('title', 'Modifier une Ville')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Modifier la Ville : {{ $city->name }}</h1>
        <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informations de la Ville</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.cities.update', $city) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nom de la ville <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $city->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $city->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ old('is_active', $city->is_active) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">Ville active</label>
                    </div>
                    <small class="form-text text-muted">Les villes inactives ne seront pas affichées aux utilisateurs.</small>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Enregistrer
                    </button>
                    <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Display districts for this city -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Quartiers de cette ville</h6>
            <a href="{{ route('admin.delivery-fees.create') }}?city_id={{ $city->id }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus-circle"></i> Ajouter un quartier
            </a>
        </div>
        <div class="card-body">
            @if($city->deliveryFees->isEmpty())
                <div class="alert alert-info">
                    Cette ville n'a pas encore de quartiers définis.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Quartier</th>
                                <th>Frais de livraison</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($city->deliveryFees as $fee)
                                <tr>
                                    <td>{{ $fee->district }}</td>
                                    <td>{{ $fee->formatted_fee }}</td>
                                    <td>
                                        @if($fee->is_active)
                                            <span class="badge badge-success">Actif</span>
                                        @else
                                            <span class="badge badge-danger">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.delivery-fees.edit', $fee) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 