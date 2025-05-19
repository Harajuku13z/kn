@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">Créer un article</h1>
    <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary-orange">
        <i class="bi bi-arrow-left me-2"></i> Retour à la liste
    </a>
</div>

<div class="content-card">
    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name" class="form-label">Nom de l'article <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="weight_text" class="form-label">Texte du poids <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('weight_text') is-invalid @enderror" id="weight_text" name="weight_text" value="{{ old('weight_text') }}" required placeholder="Ex: Poids estimé: 0.5 - 1 kg">
                    @error('weight_text')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="average_weight" class="form-label">Poids moyen (kg) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('average_weight') is-invalid @enderror" id="average_weight" name="average_weight" value="{{ old('average_weight', 0) }}" step="0.1" min="0.1" required>
                    @error('average_weight')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Cette valeur est utilisée pour le calcul du prix basé sur le poids</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="weight_class" class="form-label">Classe de poids <span class="text-danger">*</span></label>
                    <select class="form-select @error('weight_class') is-invalid @enderror" id="weight_class" name="weight_class" required>
                        <option value="" disabled {{ old('weight_class') ? '' : 'selected' }}>Sélectionnez une classe</option>
                        <option value="leger" {{ old('weight_class') == 'leger' ? 'selected' : '' }}>Léger (< 0.4 kg)</option>
                        <option value="moyen" {{ old('weight_class') == 'moyen' ? 'selected' : '' }}>Moyen (0.4 - 0.8 kg)</option>
                        <option value="lourd" {{ old('weight_class') == 'lourd' ? 'selected' : '' }}>Lourd (> 0.8 kg)</option>
                        <option value="variable" {{ old('weight_class') == 'variable' ? 'selected' : '' }}>Variable</option>
                    </select>
                    @error('weight_class')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Type d'article <span class="text-danger">*</span></label>
                    <div class="border rounded p-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="type[]" value="vetements" id="type_vetements" {{ is_array(old('type')) && in_array('vetements', old('type')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="type_vetements">Vêtements</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="type[]" value="lingedemaison" id="type_lingedemaison" {{ is_array(old('type')) && in_array('lingedemaison', old('type')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="type_lingedemaison">Linge de maison</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="type[]" value="delicat" id="type_delicat" {{ is_array(old('type')) && in_array('delicat', old('type')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="type_delicat">Délicat</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="type[]" value="service" id="type_service" {{ is_array(old('type')) && in_array('service', old('type')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="type_service">Service</label>
                        </div>
                    </div>
                    @error('type')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Le premier type sélectionné sera utilisé pour organiser les images</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Usage</label>
                    <div class="border rounded p-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="usage[]" value="homme" id="usage_homme" {{ is_array(old('usage')) && in_array('homme', old('usage')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="usage_homme">Homme</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="usage[]" value="femme" id="usage_femme" {{ is_array(old('usage')) && in_array('femme', old('usage')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="usage_femme">Femme</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="usage[]" value="enfant" id="usage_enfant" {{ is_array(old('usage')) && in_array('enfant', old('usage')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="usage_enfant">Enfant</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="usage[]" value="bebe" id="usage_bebe" {{ is_array(old('usage')) && in_array('bebe', old('usage')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="usage_bebe">Bébé</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mb-4">
            <label for="image" class="form-label">Image de l'article <span class="text-danger">*</span></label>
            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" required>
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Format recommandé : JPG, JPEG ou PNG, taille max : 2 Mo</small>
        </div>
        
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary-purple">
                <i class="bi bi-check-circle me-2"></i> Enregistrer l'article
            </button>
        </div>
    </form>
</div>
@endsection 