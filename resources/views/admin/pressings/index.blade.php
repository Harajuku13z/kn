@extends('layouts.admin')

@section('title', 'Gestion des Pressings - KLINKLIN Admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Gestion des Pressings</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item active">Pressings</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <span><i class="bi bi-building me-1"></i>Liste des Pressings Partenaires</span>
                <a href="{{ route('admin.pressings.create') }}" class="btn btn-sm btn-light">
                    <i class="bi bi-plus-circle"></i> Ajouter un pressing
                </a>
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
            
            @if($pressings->isEmpty())
                <div class="alert alert-info">
                    Aucun pressing n'a été ajouté. <a href="{{ route('admin.pressings.create') }}">Ajouter un premier pressing</a>.
                </div>
            @else
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Logo</th>
                            <th>Nom</th>
                            <th>Adresse</th>
                            <th>Contact</th>
                            <th>Commission</th>
                            <th>Statut</th>
                            <th>Services</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pressings as $pressing)
                            <tr>
                                <td>
                                    @if($pressing->logo)
                                        <img src="{{ asset($pressing->logo) }}" alt="{{ $pressing->name }}" class="img-thumbnail" style="max-width: 50px; max-height: 50px;">
                                    @else
                                        <div class="bg-light text-center text-muted p-2 rounded" style="width: 50px; height: 50px;">
                                            <i class="bi bi-building"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $pressing->name }}</td>
                                <td>{{ Str::limit($pressing->address, 30) }}</td>
                                <td>{{ $pressing->phone }}</td>
                                <td>{{ $pressing->formatted_commission_rate }}</td>
                                <td>
                                    @if($pressing->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger">Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.pressing-services.index', ['pressing_id' => $pressing->id]) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-list-ul"></i> Services ({{ $pressing->services->count() }})
                                    </a>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.pressings.show', $pressing) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.pressings.edit', $pressing) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $pressing->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Modal de confirmation de suppression -->
                                    <div class="modal fade" id="deleteModal{{ $pressing->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $pressing->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $pressing->id }}">Confirmation de suppression</h5>
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $pressings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 