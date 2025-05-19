@extends('layouts.dashboard')

@section('title', 'Mes adresses - KLINKLIN')

@section('content')
<div class="dashboard-content addresses-page">
    <div class="row mb-4 align-items-center">
        <div class="col-auto">
            <div class="notification-icon-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-geo-alt-fill text-klin-primary"></i>
            </div>
        </div>
        <div class="col">
            <h1 class="display-5 fw-bold text-klin-primary mb-1">Mes Adresses</h1>
            <p class="text-muted fs-5 mb-0">Gérez vos lieux de collecte et de livraison.</p>
        </div>
        <div class="col-auto text-end">
            <a href="{{ route('addresses.create') }}" class="btn btn-lg btn-primary klin-btn rounded-pill shadow-sm">
                <i class="bi bi-plus-circle-fill me-2"></i> Ajouter une Adresse
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($addresses->isEmpty())
        <div class="card custom-card-empty text-center shadow-sm">
            <div class="card-body p-lg-5 p-4">
                <i class="bi bi-map display-1 text-klin-primary opacity-50 mb-3"></i>
                <h4 class="fw-bold">Aucune adresse enregistrée</h4>
                <p class="text-muted mb-4 fs-5">Commencez par ajouter une adresse pour faciliter vos commandes.</p>
                <a href="{{ route('addresses.create') }}" class="btn btn-primary klin-btn btn-lg rounded-pill">
                    <i class="bi bi-plus-circle-fill me-2"></i> Ajouter ma première adresse
                </a>
            </div>
        </div>
    @else
        <div class="row gy-4"> {{-- gy-4 pour l'espacement vertical entre les cartes --}}
            @foreach ($addresses as $address)
                <div class="col-lg-6 col-md-12"> {{-- Sur grand écran, 2 colonnes, sinon pleine largeur --}}
                    <div class="card address-card h-100 shadow-sm {{ ($address->is_default_pickup || $address->is_default_delivery) ? 'is-default-address' : '' }}">
                        <div class="address-left-border"></div>
                        <div class="address-city-badge">{{ $address->city_name }}</div>
                        @if ($address->is_default_pickup || $address->is_default_delivery)
                            <div class="default-address-banner">
                                <i class="bi bi-star-fill me-1"></i> 
                                @if ($address->is_default_pickup && $address->is_default_delivery)
                                    Adresse par Défaut (Collecte & Livraison)
                                @elseif ($address->is_default_pickup)
                                    Adresse par Défaut (Collecte)
                                @elseif ($address->is_default_delivery)
                                    Adresse par Défaut (Livraison)
                                @endif
                            </div>
                        @endif
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="address-title-group flex-grow-1">
                                    <h5 class="card-title address-name mb-1 fs-4 fw-bold">
                                        <i class="bi {{ 
                                            match(strtolower($address->name)) {
                                                'domicile' => 'bi-house-heart-fill',
                                                'bureau', 'travail' => 'bi-briefcase-fill',
                                                default => 'bi-geo-alt-fill',
                                            } 
                                        }} me-2 address-name-icon"></i>
                                {{ $address->name }}
                            </h5>
                                    <div class="address-type-badges">
                                        @if (in_array($address->type, ['pickup', 'both']))
                                            <span class="badge bg-klin-accent-pickup"><i class="bi bi-truck me-1"></i> Collecte</span>
                                        @endif
                                        @if (in_array($address->type, ['delivery', 'both']))
                                            <span class="badge bg-klin-accent-delivery"><i class="bi bi-box-seam me-1"></i> Livraison</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <ul class="list-unstyled address-details-list">
                                <li class="mb-2">
                                    <i class="bi bi-signpost-2-fill me-2 list-icon"></i>
                                    <span>{{ $address->address }}</span>
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-pin-map-fill me-2 list-icon"></i>
                                    <span>{{ $address->city_name }}
                                        @if($address->postal_code), {{ $address->postal_code }}@endif
                                        @if($address->district) ({{ $address->district }}) @endif
                                    </span>
                                </li>
                                @if ($address->landmark)
                                    <li class="mb-2">
                                        <i class="bi bi-compass-fill me-2 list-icon"></i>
                                        <span>Point de repère: {{ $address->landmark }}</span>
                                    </li>
                                @endif
                                <li class="mb-2">
                                    <i class="bi bi-person-circle me-2 list-icon"></i>
                                    <span>Contact: {{ $address->contact_name ?? 'Non spécifié' }}</span>
                                </li>
                                <li>
                                    <i class="bi bi-telephone-inbound-fill me-2 list-icon"></i>
                                    <span>Tél: {{ $address->phone }}</span>
                                </li>
                            </ul>

                            <div class="card-actions mt-3 d-flex justify-content-end align-items-center">
                                <a href="{{ route('addresses.edit', $address) }}" class="btn btn-sm btn-outline-primary me-2">
                                    <i class="bi bi-pencil-square me-1"></i> Modifier
                                </a>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon-only text-muted" type="button" id="dropdownMenuButton{{ $address->id }}" data-bs-toggle="dropdown" aria-expanded="false" title="Options">
                                        <i class="bi bi-three-dots-vertical fs-5"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="dropdownMenuButton{{ $address->id }}">
                                        @if (in_array($address->type, ['pickup', 'both']) && !$address->is_default_pickup)
                                        <li>
                                            <form action="{{ route('addresses.default', $address) }}" method="POST" class="d-inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="type" value="pickup">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bi bi-star me-2"></i> Défaut Collecte
                                                </button>
                                            </form>
                                        </li>
                                        @endif

                                        @if (in_array($address->type, ['delivery', 'both']) && !$address->is_default_delivery)
                                        <li>
                                            <form action="{{ route('addresses.default', $address) }}" method="POST" class="d-inline">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="type" value="delivery">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bi bi-star me-2"></i> Défaut Livraison
                                                </button>
                                            </form>
                                        </li>
                                    @endif

                                        <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="d-inline delete-form">
                                                @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash3-fill me-2"></i> Supprimer
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if (method_exists($addresses, 'hasPages') && $addresses->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $addresses->links('pagination::bootstrap-5') }}
            </div>
        @endif
    @endif
</div>
@endsection

@push('styles')
<style>
    :root {
        --klin-primary: #4A148C;
        --klin-primary-dark: #38006b;
        --klin-secondary: #f26d50; /* Orange/Corail */
        --klin-light-bg: #f8f5fc; 
        --klin-border-color: #e0d8e7;
        --klin-text-muted: #6c757d;
        --klin-accent-pickup: #e6f2ff; /* Bleu clair pour collecte */
        --klin-accent-pickup-text: #005cb9;
        --klin-accent-delivery: #e4f8f0; /* Vert clair pour livraison */
        --klin-accent-delivery-text: #006a4e;
    }

    .addresses-page .display-5 { font-size: 2.25rem; }
    .text-klin-primary { color: var(--klin-primary) !important; }
    
    .klin-btn {
        background-color: var(--klin-primary) !important;
        border-color: var(--klin-primary) !important;
        color: white !important;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }
    .klin-btn:hover {
        background-color: var(--klin-primary-dark) !important;
        border-color: var(--klin-primary-dark) !important;
        transform: translateY(-2px);
    }

    .custom-card-empty {
        border: 2px dashed var(--klin-border-color);
        background-color: var(--klin-light-bg);
    }
    .custom-card-empty .display-1 { font-size: 4.5rem; }

    .address-card {
        border: 1px solid var(--klin-border-color);
        border-radius: 0.75rem; /* Bordures plus arrondies */
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        background-color: #fff;
        position: relative;
    }
    .address-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 .5rem 1.5rem rgba(74,20,140,.1) !important; /* Ombre subtile violette */
    }

    .is-default-address {
        border-color: var(--klin-secondary);
        position: relative;
    }
    .default-address-banner {
        position: absolute;
        top: -1px;
        left: -1px;
        background-color: var(--klin-secondary);
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 0.75rem 0 0.5rem 0;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        z-index: 10;
    }
    .is-default-address .card-body {
        /* background-color: #fff9f7; /* Fond très léger pour l'adresse par défaut */
    }


    .address-name-icon {
        color: var(--klin-primary);
        opacity: 0.8;
    }
    .is-default-address .address-name-icon {
        color: var(--klin-secondary);
    }
    .address-name { color: var(--klin-primary); }
    .is-default-address .address-name { color: var(--klin-secondary); }


    .address-type-badges .badge {
        font-size: 0.7rem;
        padding: 0.3em 0.65em;
        font-weight: 600;
    }
    .badge.bg-klin-accent-pickup {
        background-color: var(--klin-accent-pickup) !important;
        color: var(--klin-accent-pickup-text) !important;
    }
    .badge.bg-klin-accent-delivery {
        background-color: var(--klin-accent-delivery) !important;
        color: var(--klin-accent-delivery-text) !important;
    }

    .address-details-list {
        font-size: 0.9rem;
        color: #333;
    }
    .address-details-list li {
        display: flex;
        align-items: flex-start; /* Alignement pour les longues adresses */
        padding: 0.3rem 0;
    }
    .address-details-list .list-icon {
        color: var(--klin-primary);
        opacity: 0.7;
        margin-right: 0.75rem !important;
        font-size: 1.1em;
        margin-top: 0.15em; /* Petit ajustement vertical */
        flex-shrink: 0;
    }
    
    .btn-icon-only {
        background: transparent;
        border: none;
        padding: 0.25rem 0.5rem;
    }
    .btn-icon-only:hover {
        background-color: rgba(0,0,0,0.05);
        border-radius: 50%;
    }

    .dropdown-menu {
        font-size: 0.9rem;
        border-radius: 0.5rem;
        box-shadow: 0 0.25rem 1rem rgba(0,0,0,0.1);
    }
    .dropdown-item { padding: 0.5rem 1rem; }
    .dropdown-item i { color: var(--klin-text-muted); }
    .dropdown-item:hover i { color: var(--klin-primary); }
    .dropdown-item.text-danger i { color: var(--bs-danger); }
    .dropdown-item.text-danger:hover i { color: var(--bs-danger); }

    /* Pagination */
    .pagination .page-item.active .page-link {
        background-color: var(--klin-primary);
        border-color: var(--klin-primary);
        color: white;
    }
    .pagination .page-link { color: var(--klin-primary); }
    .pagination .page-link:hover { color: var(--klin-primary-dark, #38006b); }

    .address-city-badge {
        position: absolute;
        top: 0;
        right: 0;
        background: linear-gradient(135deg, #7c3aed 80%, #fff 100%);
        color: #fff;
        font-size: 0.95rem;
        font-weight: 700;
        padding: 0.45rem 1.1rem 0.45rem 1.5rem;
        border-radius: 0 1rem 0.5rem 1.5rem;
        z-index: 20;
        box-shadow: 0 2px 8px rgba(124,58,237,0.08);
        letter-spacing: 0.5px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.07);
    }
    .notification-icon-circle {
        width: 5rem;
        height: 5rem;
        background: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(74,20,140,0.10);
        border: 2px solid var(--klin-primary);
        margin-right: 1rem;
    }
    .notification-icon-circle i {
        color: var(--klin-primary);
        font-size: 2.8rem;
    }

    .address-left-border {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 6px;
        background: var(--klin-primary);
        border-radius: 4px 0 0 4px;
    }

    @media (max-width: 768px) {
        .notification-icon-circle {
            width: 4rem;
            height: 4rem;
            margin-right: 0.75rem;
        }
        .notification-icon-circle i {
            font-size: 2.2rem;
        }
        .display-5 {
            font-size: 1.75rem !important;
        }
        .fs-5 {
            font-size: 1rem !important;
        }
        .col-auto.text-end {
            margin-top: 1rem;
        }
    }

    .card-actions {
        border-top: 1px solid var(--klin-border-color);
        padding-top: 1rem;
    }

    .btn-outline-primary {
        border-color: var(--klin-primary);
        color: var(--klin-primary);
    }

    .btn-outline-primary:hover {
        background-color: var(--klin-primary);
        color: white;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirmation avant la suppression
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('Êtes-vous sûr de vouloir supprimer cette adresse ? Cette action est irréversible.')) {
                    this.submit();
                }
            });
        });

        // Activer les tooltips Bootstrap si vous en utilisez (ex: pour le bouton "Options")
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush