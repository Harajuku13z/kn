@extends('layouts.admin')

@section('title', $pressing->name . ' - KLINKLIN Admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ $pressing->name }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.pressings.index') }}">Pressings</a></li>
        <li class="breadcrumb-item active">{{ $pressing->name }}</li>
    </ol>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="row">
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-building me-1"></i> Informations du pressing
                    </div>
                    <div>
                        @if($pressing->is_active)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-danger">Inactif</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($pressing->logo)
                            <img src="{{ asset($pressing->logo) }}" alt="{{ $pressing->name }}" class="img-fluid rounded mb-3" style="max-height: 150px;">
                        @else
                            <div class="bg-light text-center text-muted p-5 rounded mb-3">
                                <i class="bi bi-building" style="font-size: 3rem;"></i>
                                <p class="mt-2">Aucun logo</p>
                            </div>
                        @endif
                        <h4 class="fw-bold">{{ $pressing->name }}</h4>
                    </div>
                    
                    <div class="list-group mb-4">
                        <div class="list-group-item">
                            <div class="fw-bold"><i class="bi bi-geo-alt me-2"></i>Adresse</div>
                            <p class="mb-0">{{ $pressing->address }}</p>
                        </div>
                        <div class="list-group-item">
                            <div class="fw-bold"><i class="bi bi-telephone me-2"></i>Contact</div>
                            <p class="mb-0">{{ $pressing->phone }}</p>
                            @if($pressing->email)
                                <p class="mb-0">{{ $pressing->email }}</p>
                            @endif
                        </div>
                        @if($pressing->opening_hours)
                            <div class="list-group-item">
                                <div class="fw-bold"><i class="bi bi-clock me-2"></i>Horaires d'ouverture</div>
                                <p class="mb-0">{!! $pressing->formatted_opening_hours !!}</p>
                            </div>
                        @endif
                        <div class="list-group-item">
                            <div class="fw-bold"><i class="bi bi-percent me-2"></i>Commission</div>
                            <p class="mb-0">{{ $pressing->formatted_commission_rate }}</p>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.pressings.edit', $pressing) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i> Modifier
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-1"></i> Supprimer
                        </button>
                    </div>
                    
                    <!-- Modal de confirmation de suppression -->
                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Êtes-vous sûr de vouloir supprimer le pressing <strong>{{ $pressing->name }}</strong> ?</p>
                                    <p class="text-danger"><small>Cette action est irréversible. Le pressing ne pourra pas être supprimé s'il a des services ou des commandes associés.</small></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <form action="{{ route('admin.pressings.destroy', $pressing) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($pressing->description)
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-info-circle me-1"></i> Description
                    </div>
                    <div class="card-body">
                        {!! $pressing->description !!}
                    </div>
                </div>
            @endif
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-geo-fill me-1"></i> Zones de couverture
                </div>
                <div class="card-body">
                    @if($pressing->coverage_areas && is_array(json_decode($pressing->coverage_areas, true)) && count(json_decode($pressing->coverage_areas, true)) > 0)
                        <ul class="list-group">
                            @foreach(json_decode($pressing->coverage_areas, true) as $area)
                                <li class="list-group-item">{{ $area }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Utilise la zone de couverture globale de KLINKLIN.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-tags me-1"></i> Services proposés
                    </div>
                    <a href="{{ route('admin.pressing-services.create', ['pressing_id' => $pressing->id]) }}" class="btn btn-sm btn-light">
                        <i class="bi bi-plus-circle me-1"></i> Ajouter un service
                    </a>
                </div>
                <div class="card-body">
                    @if($services->isEmpty())
                        <div class="alert alert-info">
                            Aucun service n'a été ajouté pour ce pressing. <a href="{{ route('admin.pressing-services.create', ['pressing_id' => $pressing->id]) }}">Ajouter un premier service</a>.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Image</th>
                                        <th>Nom</th>
                                        <th>Catégorie</th>
                                        <th>Prix</th>
                                        <th>Délai</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services as $service)
                                        <tr>
                                            <td>
                                                @if($service->image)
                                                    <img src="{{ asset($service->image) }}" alt="{{ $service->name }}" class="img-thumbnail" style="max-width: 50px; max-height: 50px;">
                                                @else
                                                    <div class="bg-light text-center text-muted p-2 rounded" style="width: 50px; height: 50px;">
                                                        <i class="bi bi-tag"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $service->name }}</td>
                                            <td>{{ $service->category ?? 'Non catégorisé' }}</td>
                                            <td>{{ $service->formatted_price }}</td>
                                            <td>{{ $service->formatted_time }}</td>
                                            <td>
                                                @if($service->is_available)
                                                    <span class="badge bg-success">Disponible</span>
                                                @else
                                                    <span class="badge bg-danger">Indisponible</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.pressing-services.edit', $service) }}" class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteServiceModal{{ $service->id }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                                
                                                <!-- Modal de confirmation de suppression -->
                                                <div class="modal fade" id="deleteServiceModal{{ $service->id }}" tabindex="-1" aria-labelledby="deleteServiceModalLabel{{ $service->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger text-white">
                                                                <h5 class="modal-title" id="deleteServiceModalLabel{{ $service->id }}">Confirmation de suppression</h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Êtes-vous sûr de vouloir supprimer le service <strong>{{ $service->name }}</strong> ?</p>
                                                                <p class="text-danger"><small>Cette action est irréversible. Le service ne pourra pas être supprimé s'il est utilisé dans des commandes.</small></p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                <form action="{{ route('admin.pressing-services.destroy', $service) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $services->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 