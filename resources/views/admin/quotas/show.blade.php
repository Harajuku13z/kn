@extends('layouts.admin')

@section('title', 'Détails du quota - KLINKLIN Admin')

@section('page_title')
    <div class="d-sm-flex align-items-center justify-content-between">
        <h1 class="h3 mb-0 text-gray-800">Détails du Quota #{{ $quota->id }}</h1>
        <div>
            <a href="{{ route('admin.quotas.edit', $quota->id) }}" class="btn btn-sm btn-warning rounded-pill">
                <i class="fas fa-edit me-1"></i> Modifier
            </a>
            <a href="{{ route('admin.quotas.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill ms-2">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i> Informations du quota
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 30%">ID</th>
                                    <td>{{ $quota->id }}</td>
                                </tr>
                                <tr>
                                    <th>Utilisateur</th>
                                    <td>
                                        <a href="{{ route('admin.users.show', $quota->user_id) }}">
                                            {{ $quota->user->name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total (kg)</th>
                                    <td>{{ $quota->total_kg }} kg</td>
                                </tr>
                                <tr>
                                    <th>Utilisé (kg)</th>
                                    <td>{{ $quota->used_kg }} kg</td>
                                </tr>
                                <tr>
                                    <th>Disponible (kg)</th>
                                    <td>{{ $quota->available_kg }} kg</td>
                                </tr>
                                <tr>
                                    <th>Prix</th>
                                    <td>{{ number_format($quota->price, 0, ',', ' ') }} FCFA</td>
                                </tr>
                                <tr>
                                    <th>Date de création</th>
                                    <td>{{ $quota->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Date d'expiration</th>
                                    <td>
                                        @if($quota->expiration_date)
                                            {{ $quota->expiration_date->format('d/m/Y') }}
                                            @if($quota->expiration_date <= now())
                                                <span class="badge bg-danger ms-2">Expiré</span>
                                            @endif
                                        @else
                                            Non défini
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Statut</th>
                                    <td>
                                        @if($quota->is_active && $quota->expiration_date > now() && $quota->available_kg > 0)
                                            <span class="badge bg-success">Actif</span>
                                        @elseif(!$quota->is_active)
                                            <span class="badge bg-secondary">Inactif</span>
                                        @elseif($quota->expiration_date <= now())
                                            <span class="badge bg-danger">Expiré</span>
                                        @elseif($quota->available_kg <= 0)
                                            <span class="badge bg-warning text-dark">Épuisé</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user me-2"></i> Informations utilisateur
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Nom:</strong> {{ $quota->user->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> {{ $quota->user->email }}
                    </div>
                    <div class="mb-3">
                        <strong>Téléphone:</strong> {{ $quota->user->phone ?? 'Non renseigné' }}
                    </div>
                    <div class="mb-3">
                        <strong>Date d'inscription:</strong> {{ $quota->user->created_at->format('d/m/Y') }}
                    </div>
                    
                    <a href="{{ route('admin.users.show', $quota->user_id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye me-1"></i> Voir le profil
                    </a>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i> Utilisation
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="progress mb-3" style="height: 20px;">
                            @php
                                $percentage = $quota->total_kg > 0 ? ($quota->used_kg / $quota->total_kg) * 100 : 0;
                            @endphp
                            <div class="progress-bar {{ $percentage > 75 ? 'bg-danger' : ($percentage > 50 ? 'bg-warning' : 'bg-success') }}" 
                                role="progressbar" 
                                style="width: {{ $percentage }}%;" 
                                aria-valuenow="{{ $percentage }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                {{ round($percentage) }}%
                            </div>
                        </div>
                        <div class="text-muted">
                            {{ $quota->used_kg }} kg utilisés sur {{ $quota->total_kg }} kg
                        </div>
                    </div>
                    
                    @if($quota->is_active && $quota->expiration_date > now() && $quota->available_kg > 0)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-1"></i> Ce quota est actif et utilisable.
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i> Ce quota n'est pas utilisable.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 