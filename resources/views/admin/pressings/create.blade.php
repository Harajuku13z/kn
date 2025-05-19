@extends('layouts.admin')

@section('title', 'Ajouter un Pressing - KLINKLIN Admin')

@push('styles')
<style>
    .ck-editor__editable {
        min-height: 200px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Ajouter un Pressing</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.pressings.index') }}">Pressings</a></li>
        <li class="breadcrumb-item active">Ajouter</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-building-add me-1"></i>
            Nouveau Pressing Partenaire
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('admin.pressings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-9">
                        <div class="card shadow-sm mb-3">
                            <div class="card-header">Informations générales</div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom du pressing <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug (URL)</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" placeholder="Généré automatiquement si laissé vide">
                                    <small class="text-muted">Le slug est utilisé dans l'URL. Laissez vide pour générer automatiquement.</small>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="address" class="form-label">Adresse complète <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control editor @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="opening_hours" class="form-label">Horaires d'ouverture</label>
                                    <textarea class="form-control @error('opening_hours') is-invalid @enderror" id="opening_hours" name="opening_hours" rows="4" placeholder="Lundi: 8h-18h&#10;Mardi: 8h-18h&#10;etc.">{{ old('opening_hours') }}</textarea>
                                    @error('opening_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="card shadow-sm">
                            <div class="card-header">Zones de couverture</div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Zones desservies</label>
                                    <div class="border p-3 rounded bg-light">
                                        <p class="text-muted mb-2"><small>Sélectionnez les quartiers desservis par ce pressing. Si aucun n'est sélectionné, la couverture générale de KLINKLIN sera utilisée.</small></p>
                                        <div class="row">
                                            @php
                                                // Exemple de quartiers - à remplacer par les quartiers réels de votre application
                                                $neighborhoods = [
                                                    'Bacongo', 'Makélékélé', 'Poto-Poto', 'Moungali', 'Ouenzé', 
                                                    'Talangaï', 'Mfilou', 'Madibou', 'Djiri', 'Centre-ville',
                                                    'Plateau des 15 ans', 'Diata', 'Mpila', 'Moukoundzi-Ngouaka'
                                                ];
                                                sort($neighborhoods);
                                                $oldCoverage = old('coverage_areas', []);
                                            @endphp
                                            
                                            @foreach($neighborhoods as $neighborhood)
                                                <div class="col-md-4 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="{{ $neighborhood }}" id="coverage_{{ Str::slug($neighborhood) }}" name="coverage_areas[]" {{ in_array($neighborhood, $oldCoverage) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="coverage_{{ Str::slug($neighborhood) }}">
                                                            {{ $neighborhood }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @error('coverage_areas')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card shadow-sm mb-3">
                            <div class="card-header">Paramètres</div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Pressing actif</label>
                                    </div>
                                    <small class="text-muted">Un pressing inactif ne sera pas visible pour les clients.</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="commission_rate" class="form-label">Taux de commission (%)</label>
                                    <input type="number" class="form-control @error('commission_rate') is-invalid @enderror" id="commission_rate" name="commission_rate" value="{{ old('commission_rate', 10) }}" min="0" max="100" step="0.01">
                                    <small class="text-muted">Pourcentage prélevé sur chaque commande.</small>
                                    @error('commission_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="card shadow-sm">
                            <div class="card-header">Logo</div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Logo du pressing</label>
                                    <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                                    <small class="text-muted">Format recommandé: carré, 400x400px minimum.</small>
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    <div class="mt-3 text-center">
                                        <img id="logo-preview" src="{{ asset('images/placeholder-image.png') }}" alt="Aperçu du logo" class="img-thumbnail" style="max-width: 100%; max-height: 150px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.pressings.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prévisualisation du logo
        const logoInput = document.getElementById('logo');
        const logoPreview = document.getElementById('logo-preview');
        
        logoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoPreview.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Auto-génération du slug
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        
        nameInput.addEventListener('keyup', function() {
            if (!slugInput.value) {
                slugInput.value = nameInput.value
                    .toLowerCase()
                    .replace(/[^\w ]+/g, '')
                    .replace(/ +/g, '-');
            }
        });
        
        // Éditeur WYSIWYG pour la description
        if (document.querySelector('.editor')) {
            ClassicEditor
                .create(document.querySelector('.editor'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo', 'redo']
                })
                .catch(error => {
                    console.error(error);
                });
        }
    });
</script>
@endpush 