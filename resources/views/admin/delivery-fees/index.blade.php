@extends('layouts.admin')

@section('title', 'Gestion des Frais de Livraison')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des Frais de Livraison</h1>
        <div>
            <a href="{{ route('admin.cities.index') }}" class="btn btn-info mr-2">
                <i class="fas fa-city mr-1"></i> Gérer les Villes
            </a>
            <a href="{{ route('admin.delivery-fees.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle mr-1"></i> Ajouter un Frais
            </a>
        </div>
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

    <!-- Filtres par ville -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtrer par ville</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="filter_city">Sélectionner une ville</label>
                        <select class="form-control" id="filter_city" name="filter_city">
                            <option value="">Toutes les villes</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }} ({{ $city->deliveryFees->count() }} quartiers)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Frais de Livraison</h6>
            <form method="POST" action="{{ route('admin.delivery-fees.bulk-update') }}" id="bulk-update-form">
                @csrf
                <button type="submit" class="btn btn-sm btn-success" id="bulk-update-button" style="display: none;">
                    <i class="fas fa-save mr-1"></i> Enregistrer les modifications
                </button>
            </form>
        </div>
        <div class="card-body">
            @if($deliveryFees->isEmpty())
                <div class="alert alert-info">
                    Aucun frais de livraison n'est disponible pour le moment.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="delivery-fees-table" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="15%">Ville</th>
                                <th width="20%">Quartier</th>
                                <th width="15%">Frais</th>
                                <th width="25%">Description</th>
                                <th width="10%">Statut</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deliveryFees as $fee)
                                <tr class="city-{{ $fee->city_id ?? '0' }}">
                                    <td>{{ $fee->id }}</td>
                                    <td>{{ $fee->city ? $fee->city->name : 'Non définie' }}</td>
                                    <td>{{ $fee->district }}</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm editable-fee" 
                                            data-id="{{ $fee->id }}" 
                                            data-original="{{ $fee->fee }}" 
                                            value="{{ $fee->fee }}" 
                                            min="0">
                                        <input type="hidden" name="fees[{{ $fee->id }}][id]" form="bulk-update-form" value="{{ $fee->id }}">
                                        <input type="hidden" name="fees[{{ $fee->id }}][fee]" form="bulk-update-form" value="{{ $fee->fee }}" class="fee-value">
                                    </td>
                                    <td>{{ $fee->description ?? 'Aucune description' }}</td>
                                    <td class="text-center">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input editable-status" 
                                                id="status_{{ $fee->id }}" 
                                                data-id="{{ $fee->id }}" 
                                                data-original="{{ $fee->is_active ? 'true' : 'false' }}" 
                                                {{ $fee->is_active ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="status_{{ $fee->id }}"></label>
                                            <input type="hidden" name="fees[{{ $fee->id }}][is_active]" form="bulk-update-form" value="{{ $fee->is_active ? '1' : '0' }}" class="status-value">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.delivery-fees.edit', $fee) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.delivery-fees.destroy', $fee) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce frais de livraison ?');">
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
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialisation de DataTables
        var table = $('#delivery-fees-table').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json"
            },
            order: [[0, 'desc']],
            pageLength: 25
        });
        
        // Filtrage par ville
        $('#filter_city').change(function() {
            var city_id = $(this).val();
            if (city_id) {
                window.location.href = "{{ route('admin.delivery-fees.index') }}?city_id=" + city_id;
            } else {
                window.location.href = "{{ route('admin.delivery-fees.index') }}";
            }
        });
        
        // Détection des modifications des frais
        $('.editable-fee').on('change', function() {
            var id = $(this).data('id');
            var originalValue = $(this).data('original');
            var newValue = $(this).val();
            
            // Mettre à jour la valeur cachée pour le formulaire de mise à jour groupée
            $('input[name="fees[' + id + '][fee]"]').val(newValue);
            
            // Vérifier si une modification a été effectuée
            checkForChanges();
            
            // Mettre en évidence les modifications
            if (originalValue != newValue) {
                $(this).addClass('border-warning');
            } else {
                $(this).removeClass('border-warning');
            }
        });
        
        // Détection des modifications de statut
        $('.editable-status').on('change', function() {
            var id = $(this).data('id');
            var originalValue = $(this).data('original');
            var newValue = $(this).prop('checked') ? 'true' : 'false';
            
            // Mettre à jour la valeur cachée pour le formulaire de mise à jour groupée
            $('input[name="fees[' + id + '][is_active]"]').val($(this).prop('checked') ? '1' : '0');
            
            // Vérifier si une modification a été effectuée
            checkForChanges();
            
            // Mettre en évidence les modifications
            if (originalValue != newValue) {
                $(this).closest('.custom-switch').addClass('border-warning');
            } else {
                $(this).closest('.custom-switch').removeClass('border-warning');
            }
        });
        
        // Vérifier s'il y a des modifications pour afficher le bouton de sauvegarde
        function checkForChanges() {
            var hasChanges = false;
            
            $('.editable-fee').each(function() {
                if ($(this).data('original') != $(this).val()) {
                    hasChanges = true;
                    return false; // Sortir de la boucle
                }
            });
            
            if (!hasChanges) {
                $('.editable-status').each(function() {
                    var currentValue = $(this).prop('checked') ? 'true' : 'false';
                    if ($(this).data('original') != currentValue) {
                        hasChanges = true;
                        return false; // Sortir de la boucle
                    }
                });
            }
            
            if (hasChanges) {
                $('#bulk-update-button').show();
            } else {
                $('#bulk-update-button').hide();
            }
        }
        
        // Filtrage par ville s'il y a un paramètre city_id dans l'URL
        @if(request()->has('city_id'))
            table.column(1).search('{{ $cities->find(request('city_id'))->name ?? "" }}').draw();
        @endif
    });
</script>
@endpush 