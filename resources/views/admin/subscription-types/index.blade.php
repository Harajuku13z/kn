@extends('layouts.admin')

@section('page_title', 'Types d\'abonnements')

@section('content')
<div class="container-fluid">
    <!-- En-tête de la page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Types d'abonnements</h1>
        <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createSubscriptionTypeModal">
            <i class="fas fa-plus fa-sm text-white-50"></i> Nouveau type
        </button>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Liste des types d'abonnements -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des types d'abonnements</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="subscriptionTypesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Quota (kg)</th>
                            <th>Durée (jours)</th>
                            <th>Prix (FCFA)</th>
                            <th>Niveau de service</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscriptionTypes as $type)
                        <tr>
                            <td>{{ $type->name }}</td>
                            <td>{{ Str::limit($type->description, 50) }}</td>
                            <td>{{ $type->quota }}</td>
                            <td>{{ $type->duration }}</td>
                            <td>{{ number_format($type->price, 0, ',', ' ') }}</td>
                            <td>
                                @if($type->service_level == 'standard')
                                    <span class="badge bg-secondary text-white">Standard</span>
                                @elseif($type->service_level == 'priority')
                                    <span class="badge bg-info text-white">Prioritaire</span>
                                @elseif($type->service_level == 'express')
                                    <span class="badge bg-warning text-white">Express</span>
                                @else
                                    <span class="badge bg-secondary text-white">{{ $type->formatted_service_level }}</span>
                                @endif
                            </td>
                            <td>
                                @if($type->is_active)
                                    <span class="badge bg-success text-white">Actif</span>
                                @else
                                    <span class="badge bg-danger text-white">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary edit-btn" 
                                    data-id="{{ $type->id }}"
                                    data-name="{{ $type->name }}"
                                    data-description="{{ $type->description }}"
                                    data-quota="{{ $type->quota }}"
                                    data-duration="{{ $type->duration }}"
                                    data-price="{{ $type->price }}"
                                    data-service-level="{{ $type->service_level }}"
                                    data-is-active="{{ $type->is_active }}"
                                    data-bs-toggle="modal" data-bs-target="#editSubscriptionTypeModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('admin.subscription-types.destroy', $type->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de création -->
<div class="modal fade" id="createSubscriptionTypeModal" tabindex="-1" aria-labelledby="createSubscriptionTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSubscriptionTypeModalLabel">Nouveau type d'abonnement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.subscription-types.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="price" class="form-label">Prix (FCFA)</label>
                            <input type="number" class="form-control" id="price" name="price" min="0" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="quota" class="form-label">Quota (kg)</label>
                            <input type="number" class="form-control" id="quota" name="quota" min="0" step="0.1" required>
                        </div>
                        <div class="col-md-6">
                            <label for="duration" class="form-label">Durée (jours)</label>
                            <input type="number" class="form-control" id="duration" name="duration" min="1" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="service_level" class="form-label">Niveau de service</label>
                            <select class="form-control" id="service_level" name="service_level" required>
                                <option value="standard">Standard</option>
                                <option value="priority">Prioritaire</option>
                                <option value="express">Express</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active">
                                    Actif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Caractéristiques du service</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="feature_pickup" name="service_features[]" value="Collecte à domicile">
                                    <label class="form-check-label" for="feature_pickup">Collecte à domicile</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="feature_delivery" name="service_features[]" value="Livraison à domicile">
                                    <label class="form-check-label" for="feature_delivery">Livraison à domicile</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="feature_priority" name="service_features[]" value="Traitement prioritaire">
                                    <label class="form-check-label" for="feature_priority">Traitement prioritaire</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="feature_express" name="service_features[]" value="Livraison express">
                                    <label class="form-check-label" for="feature_express">Livraison express</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="feature_discount" name="service_features[]" value="Remises exclusives">
                                    <label class="form-check-label" for="feature_discount">Remises exclusives</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="feature_support" name="service_features[]" value="Support client prioritaire">
                                    <label class="form-check-label" for="feature_support">Support client prioritaire</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'édition -->
<div class="modal fade" id="editSubscriptionTypeModal" tabindex="-1" aria-labelledby="editSubscriptionTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubscriptionTypeModalLabel">Modifier le type d'abonnement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_name" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_price" class="form-label">Prix (FCFA)</label>
                            <input type="number" class="form-control" id="edit_price" name="price" min="0" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_quota" class="form-label">Quota (kg)</label>
                            <input type="number" class="form-control" id="edit_quota" name="quota" min="0" step="0.1" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_duration" class="form-label">Durée (jours)</label>
                            <input type="number" class="form-control" id="edit_duration" name="duration" min="1" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_service_level" class="form-label">Niveau de service</label>
                            <select class="form-control" id="edit_service_level" name="service_level" required>
                                <option value="standard">Standard</option>
                                <option value="priority">Prioritaire</option>
                                <option value="express">Express</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active" value="1">
                                <label class="form-check-label" for="edit_is_active">
                                    Actif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Caractéristiques du service</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input edit-feature" type="checkbox" id="edit_feature_pickup" name="service_features[]" value="Collecte à domicile">
                                    <label class="form-check-label" for="edit_feature_pickup">Collecte à domicile</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input edit-feature" type="checkbox" id="edit_feature_delivery" name="service_features[]" value="Livraison à domicile">
                                    <label class="form-check-label" for="edit_feature_delivery">Livraison à domicile</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input edit-feature" type="checkbox" id="edit_feature_priority" name="service_features[]" value="Traitement prioritaire">
                                    <label class="form-check-label" for="edit_feature_priority">Traitement prioritaire</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input edit-feature" type="checkbox" id="edit_feature_express" name="service_features[]" value="Livraison express">
                                    <label class="form-check-label" for="edit_feature_express">Livraison express</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input edit-feature" type="checkbox" id="edit_feature_discount" name="service_features[]" value="Remises exclusives">
                                    <label class="form-check-label" for="edit_feature_discount">Remises exclusives</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input edit-feature" type="checkbox" id="edit_feature_support" name="service_features[]" value="Support client prioritaire">
                                    <label class="form-check-label" for="edit_feature_support">Support client prioritaire</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialisation de DataTables
    $('#subscriptionTypesTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
        }
    });

    // Confirmation de suppression
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        if (confirm('Êtes-vous sûr de vouloir supprimer ce type d\'abonnement ?')) {
            this.submit();
        }
    });

    // Remplir le formulaire d'édition
    $('.edit-btn').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const description = $(this).data('description');
        const quota = $(this).data('quota');
        const duration = $(this).data('duration');
        const price = $(this).data('price');
        const serviceLevel = $(this).data('service-level');
        const isActive = $(this).data('is-active');

        $('#editForm').attr('action', `/admin/subscription-types/${id}`);
        $('#edit_name').val(name);
        $('#edit_description').val(description);
        $('#edit_quota').val(quota);
        $('#edit_duration').val(duration);
        $('#edit_price').val(price);
        $('#edit_service_level').val(serviceLevel);
        $('#edit_is_active').prop('checked', isActive == 1);
        
        // Réinitialiser les caractéristiques
        $('.edit-feature').prop('checked', false);
        
        // Charger les caractéristiques existantes via AJAX
        $.ajax({
            url: `/admin/subscription-types/${id}/features`,
            type: 'GET',
            success: function(response) {
                if (response.features) {
                    response.features.forEach(feature => {
                        $(`.edit-feature[value="${feature}"]`).prop('checked', true);
                    });
                }
            }
        });
    });
});
</script>
@endpush
@endsection 