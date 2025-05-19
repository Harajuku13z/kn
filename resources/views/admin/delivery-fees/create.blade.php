@extends('layouts.admin')

@section('title', 'Ajouter un Frais de Livraison')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ajouter un Frais de Livraison</h1>
        <a href="{{ route('admin.delivery-fees.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informations du Frais de Livraison</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.delivery-fees.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="city_id">Ville <span class="text-danger">*</span></label>
                    <select class="form-control @error('city_id') is-invalid @enderror" id="city_id" name="city_id" required>
                        <option value="">Sélectionner une ville</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ old('city_id', request('city_id')) == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('city_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="district">Quartier <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('district') is-invalid @enderror" id="district" name="district" value="{{ old('district') }}" required>
                    @error('district')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="fee">Frais (FCFA) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('fee') is-invalid @enderror" id="fee" name="fee" value="{{ old('fee', 750) }}" min="0" required>
                    @error('fee')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ old('is_active') ? 'checked' : 'checked' }}>
                        <label class="custom-control-label" for="is_active">Quartier actif</label>
                    </div>
                    <small class="form-text text-muted">Les quartiers inactifs ne seront pas disponibles pour les utilisateurs.</small>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Enregistrer
                    </button>
                    <a href="{{ route('admin.delivery-fees.index') }}" class="btn btn-secondary">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 