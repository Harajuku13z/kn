@extends('layouts.admin')

@section('title', 'Gestion des Villes')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des Villes</h1>
        <a href="{{ route('admin.cities.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle mr-1"></i> Ajouter une ville
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Villes</h6>
        </div>
        <div class="card-body">
            @if($cities->isEmpty())
                <div class="alert alert-info">
                    Aucune ville n'est disponible pour le moment.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="citiesTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="20%">Nom</th>
                                <th width="40%">Description</th>
                                <th width="10%">Quartiers</th>
                                <th width="10%">Statut</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cities as $city)
                                <tr>
                                    <td>{{ $city->id }}</td>
                                    <td>{{ $city->name }}</td>
                                    <td>{{ $city->description ?? 'Aucune description' }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-info">{{ $city->deliveryFees->count() }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($city->is_active)
                                            <span class="badge badge-success">Actif</span>
                                        @else
                                            <span class="badge badge-danger">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.cities.edit', $city) }}" class="btn btn-sm btn-primary" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.cities.toggle-status', $city) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir changer le statut de cette ville ?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-{{ $city->is_active ? 'warning' : 'success' }}" title="{{ $city->is_active ? 'Désactiver' : 'Activer' }}">
                                                    <i class="fas fa-{{ $city->is_active ? 'ban' : 'check' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette ville ? Cette action est irréversible.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" {{ $city->deliveryFees->count() > 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.delivery-fees.index') }}?city_id={{ $city->id }}" class="btn btn-sm btn-info" title="Voir les quartiers">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#citiesTable').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json"
            },
            order: [[0, 'desc']],
            pageLength: 25
        });
    });
</script>
@endpush 