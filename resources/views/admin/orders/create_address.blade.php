@extends('layouts.admin')

@section('title', 'Ajouter une adresse - KLINKLIN Admin')

@section('page_title')
    <div class="d-sm-flex align-items-center justify-content-between">
        <h1 class="h3 mb-0 text-gray-800">Ajouter une adresse</h1>
        @if($returnType == 'quota')
            <a href="{{ route('admin.orders.create.quota.form', $user->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
        @else
            <a href="{{ route('admin.orders.create.pressing.form', $user->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
        @endif
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map-marker-alt me-2"></i> Ajouter une adresse pour {{ $user->name }}
                    </h6>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Erreurs de validation</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.orders.address.store', $user->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="return_type" value="{{ $returnType }}">
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom de l'adresse <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" required placeholder="Ex: Domicile, Bureau, etc.">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type d'adresse <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-control" required>
                                        <option value="home">Domicile</option>
                                        <option value="office">Bureau</option>
                                        <option value="other">Autre</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Adresse complète <span class="text-danger">*</span></label>
                                    <textarea name="address" id="address" class="form-control" rows="3" required placeholder="Numéro, rue, etc."></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city_id" class="form-label">Ville <span class="text-danger">*</span></label>
                                    <select name="city_id" id="city_id" class="form-control" required>
                                        <option value="">Sélectionner une ville</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="district" class="form-label">Quartier <span class="text-danger">*</span></label>
                                    <input type="text" name="district" id="district" class="form-control" required placeholder="Nom du quartier">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_name" class="form-label">Nom du contact <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_name" id="contact_name" class="form-control" required value="{{ $user->name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" id="phone" class="form-control" required value="{{ $user->phone ?? '' }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="landmark" class="form-label">Point de repère</label>
                                    <input type="text" name="landmark" id="landmark" class="form-control" placeholder="Ex: Près de la pharmacie, en face de l'école, etc.">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_default" id="is_default" value="1">
                                    <label class="form-check-label" for="is_default">
                                        Définir comme adresse par défaut
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            @if($returnType == 'quota')
                                <a href="{{ route('admin.orders.create.quota.form', $user->id) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Retour
                                </a>
                            @else
                                <a href="{{ route('admin.orders.create.pressing.form', $user->id) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Retour
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Enregistrer l'adresse
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 