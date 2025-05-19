@extends('layouts.admin')

@section('title', $pressing ? 'Services de ' . $pressing->name : 'Tous les Services' . ' - KLINKLIN Admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ $pressing ? 'Services de ' . $pressing->name : 'Tous les Services de Pressing' }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
        
        @if($pressing)
            <li class="breadcrumb-item"><a href="{{ route('admin.pressings.index') }}">Pressings</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.pressings.show', $pressing) }}">{{ $pressing->name }}</a></li>
            <li class="breadcrumb-item active">Services</li>
        @else
            <li class="breadcrumb-item active">Services</li>
        @endif
    </ol>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <span><i class="bi bi-tags me-1"></i>Liste des Services</span>
                @if($pressing)
                    <a href="{{ route('admin.pressing-services.create', ['pressing_id' => $pressing->id]) }}" class="btn btn-sm btn-light">
                        <i class="bi bi-plus-circle"></i> Ajouter un service
                    </a>
                @else
                    <a href="{{ route('admin.pressing-services.create') }}" class="btn btn-sm btn-light">
                        <i class="bi bi-plus-circle"></i> Ajouter un service
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body">
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
            
            @if($services->isEmpty())
                <div class="alert alert-info">
                    @if($pressing)
                        Aucun service n'a été ajouté pour ce pressing. <a href="{{ route('admin.pressing-services.create', ['pressing_id' => $pressing->id]) }}">Ajouter un premier service</a>.
                    @else
                        Aucun service n'a été ajouté. <a href="{{ route('admin.pressing-services.create') }}">Ajouter un premier service</a>.
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Image</th>
                                @if(!$pressing)
                                    <th>Pressing</th>
                                @endif
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
                                    
                                    @if(!$pressing)
                                        <td>
                                            <a href="{{ route('admin.pressings.show', $service->pressing) }}">
                                                {{ $service->pressing->name }}
                                            </a>
                                        </td>
                                    @endif
                                    
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
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $service->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Modal de confirmation de suppression -->
                                        <div class="modal fade" id="deleteModal{{ $service->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $service->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $service->id }}">Confirmation de suppression</h5>
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
                    {{ $services->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 