@extends('layouts.admin')

@section('title', 'Nouvelle Configuration de Prix')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Nouveau Prix</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.prices.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.prices.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price_per_kg">Prix par kg (FCFA)</label>
                                    <input type="number" step="0.01" class="form-control @error('price_per_kg') is-invalid @enderror" 
                                           id="price_per_kg" name="price_per_kg" 
                                           value="{{ old('price_per_kg', $currentPrice->price_per_kg) }}" required>
                                    @error('price_per_kg')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="effective_date">Date d'effet</label>
                                    <input type="date" class="form-control @error('effective_date') is-invalid @enderror" 
                                           id="effective_date" name="effective_date" 
                                           value="{{ old('effective_date', $currentPrice->effective_date->format('Y-m-d')) }}" required>
                                    @error('effective_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="last_update_reason">Raison de la mise à jour</label>
                            <textarea class="form-control @error('last_update_reason') is-invalid @enderror" 
                                      id="last_update_reason" name="last_update_reason" rows="3" required>{{ old('last_update_reason', $currentPrice->last_update_reason) }}</textarea>
                            @error('last_update_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="notify_users" name="notify_users" value="1">
                                <label class="custom-control-label" for="notify_users">Notifier les utilisateurs de ce changement de prix</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 