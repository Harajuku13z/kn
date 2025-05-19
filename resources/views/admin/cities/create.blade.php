@extends('layouts.admin')

@section('title', 'Ajouter une Ville')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ajouter une Ville</h1>
        <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informations de la Ville</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.cities.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Nom de la ville <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
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
</div>
@endsection 