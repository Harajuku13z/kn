@extends('layouts.admin')

@section('title', 'Ajouter un Service - KLINKLIN Admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Ajouter un Service</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
        
        @if($pressing)
            <li class="breadcrumb-item"><a href="{{ route('admin.pressings.index') }}">Pressings</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.pressings.show', $pressing) }}">{{ $pressing->name }}</a></li>
        @else
            <li class="breadcrumb-item"><a href="{{ route('admin.pressing-services.index') }}">Services</a></li>
        @endif
        
        <li class="breadcrumb-item active">Ajouter un service</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-tags me-1"></i>
            Nouveau Service de Pressing
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
            
            <form action="{{ route('admin.pressing-services.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="card shadow-sm mb-3">
                            <div class="card-header">Informations du service</div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="pressing_id" class="form-label">Pressing <span class="text-danger">*</span></label>
                                    <select class="form-select @error('pressing_id') is-invalid @enderror" id="pressing_id" name="pressing_id" required>
                                        <option value="">Sélectionner un pressing</option>
                                        @foreach($pressings as $p)
                                            <option value="{{ $p->id }}" {{ (old('pressing_id') == $p->id || ($pressing && $pressing->id == $p->id)) ? 'selected' : '' }}>
                                                {{ $p->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pressing_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom du service <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">Catégorie</label>
                                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category">
                                            <option value="">Aucune catégorie</option>
                                            @php
                                                $categories = ['Chemises', 'Pantalons', 'Costumes', 'Robes', 'Vestes', 'Manteaux', 'Linge de maison', 'Autre'];
                                            @endphp
                                            @foreach($categories as $category)
                                                <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="price" class="form-label">Prix (FCFA) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="0" required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                    <small class="text-muted">Description détaillée du service (facultative).</small>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="estimated_time" class="form-label">Temps estimé (heures)</label>
                                    <input type="number" class="form-control @error('estimated_time') is-invalid @enderror" id="estimated_time" name="estimated_time" value="{{ old('estimated_time') }}" min="0">
                                    <small class="text-muted">Durée approximative du service en heures.</small>
                                    @error('estimated_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card shadow-sm mb-3">
                            <div class="card-header">Paramètres</div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="is_available" name="is_available" value="1" {{ old('is_available', '1') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_available">Service disponible</label>
                                    </div>
                                    <small class="text-muted">Un service non disponible ne sera pas proposé aux clients.</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card shadow-sm">
                            <div class="card-header">Image</div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image du service</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                    <small class="text-muted">Image illustrant le service (facultative).</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    <div class="mt-3 text-center">
                                        <img id="image-preview" src="{{ asset('images/placeholder-service.png') }}" alt="Aperçu de l'image" class="img-thumbnail" style="max-width: 100%; max-height: 150px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    @if($pressing)
                        <a href="{{ route('admin.pressing-services.index', ['pressing_id' => $pressing->id]) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                    @else
                        <a href="{{ route('admin.pressing-services.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                    @endif
                    
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prévisualisation de l'image
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endpush 