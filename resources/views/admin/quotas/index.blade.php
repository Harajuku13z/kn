@extends('layouts.admin')

@section('title', 'Gestion des quotas - KLINKLIN Admin')

@section('page_title')
    <div class="d-sm-flex align-items-center justify-content-between">
        <h1 class="h3 mb-0 text-gray-800">Gestion des Quotas</h1>
        <a href="{{ route('admin.quotas.create') }}" class="btn btn-sm btn-primary rounded-pill">
            <i class="fas fa-plus me-1"></i> Nouveau quota
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des quotas</h6>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="quotas-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Utilisateur</th>
                            <th>Total (kg)</th>
                            <th>Utilisé (kg)</th>
                            <th>Disponible (kg)</th>
                            <th>Date d'expiration</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quotas as $quota)
                        <tr>
                            <td>{{ $quota->id }}</td>
                            <td>{{ $quota->user->name }}</td>
                            <td>{{ $quota->total_kg }}</td>
                            <td>{{ $quota->used_kg }}</td>
                            <td>{{ $quota->available_kg }}</td>
                            <td>{{ $quota->expiration_date ? $quota->expiration_date->format('d/m/Y') : 'Non défini' }}</td>
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
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.quotas.show', $quota->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.quotas.edit', $quota->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.quotas.destroy', $quota->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce quota ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $quotas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#quotas-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
            },
            order: [[0, 'desc']],
            paging: false,
            searching: true
        });
    });
</script>
@endpush 