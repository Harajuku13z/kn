@extends('layouts.dashboard')

@section('title', 'Choisir un pressing - KLINKLIN')

@push('styles')
<style>
    :root {
        --klin-primary: #4A148C;
        --klin-primary-dark: #38006b;
        --klin-secondary: #f26d50;
        --klin-light-bg: #f8f5fc;
        --klin-border-color: #e0d8e7;
    }
    
    .pressing-card {
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        border: 1px solid var(--klin-border-color);
        border-radius: 10px;
        overflow: hidden;
    }
    
    .pressing-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .pressing-card .card-img-top {
        height: 180px;
        object-fit: cover;
    }
    
    .pressing-card .card-body {
        padding: 1.5rem;
    }
    
    .pressing-card .card-title {
        color: var(--klin-primary);
        font-weight: 600;
    }
    
    .pressing-card .badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
        margin-right: 0.3rem;
        margin-bottom: 0.3rem;
    }
    
    .pressing-card .rating {
        color: #FFD700;
    }
    
    .pressing-card .btn-select {
        background-color: var(--klin-primary);
        border-color: var(--klin-primary);
        color: white;
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        transition: background-color 0.2s;
    }
    
    .pressing-card .btn-select:hover {
        background-color: var(--klin-primary-dark);
    }
    
    .neighborhood-heading {
        margin-top: 2rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--klin-primary);
        color: var(--klin-primary);
    }
    
    .search-bar {
        background-color: var(--klin-light-bg);
        padding: 1.5rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="display-5 fw-bold" style="color: var(--klin-primary);">Choisir un Pressing</h1>
            <p class="lead">Sélectionnez un pressing partenaire pour votre commande</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('orders.create') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>
    
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="pressing-search" class="form-control" placeholder="Rechercher un pressing...">
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                    <select id="neighborhood-filter" class="form-select">
                        <option value="">Tous les quartiers</option>
                        @php
                            $neighborhoods = $pressings->pluck('neighborhood')->unique()->sort();
                        @endphp
                        @foreach($neighborhoods as $neighborhood)
                            <option value="{{ $neighborhood }}">{{ $neighborhood }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Group pressings by neighborhood -->
    @php
        $pressingsByNeighborhood = $pressings->groupBy('neighborhood')->sortKeys();
    @endphp
    
    @foreach($pressingsByNeighborhood as $neighborhood => $neighborhoodPressings)
        <div class="neighborhood-section" data-neighborhood="{{ $neighborhood }}">
            <h2 class="neighborhood-heading">
                <i class="bi bi-geo-alt-fill"></i> {{ $neighborhood }}
            </h2>
            
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
                @foreach($neighborhoodPressings as $pressing)
                    <div class="col pressing-item" data-name="{{ $pressing->name }}">
                        <div class="pressing-card card h-100">
                            @if($pressing->image)
                                <img src="{{ asset('storage/' . $pressing->image) }}" class="card-img-top" alt="{{ $pressing->name }}">
                            @else
                                <img src="{{ asset('images/default-pressing.jpg') }}" class="card-img-top" alt="{{ $pressing->name }}">
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $pressing->name }}</h5>
                                
                                <div class="mb-2">
                                    <div class="rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $pressing->rating)
                                                <i class="bi bi-star-fill"></i>
                                            @elseif($i - 0.5 <= $pressing->rating)
                                                <i class="bi bi-star-half"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                        <span class="text-muted ms-1">({{ $pressing->reviews_count ?? 0 }})</span>
                                    </div>
                                </div>
                                
                                <p class="card-text">
                                    <i class="bi bi-geo-alt text-secondary"></i> {{ $pressing->address }}<br>
                                    <i class="bi bi-telephone text-secondary"></i> {{ $pressing->phone }}
                                </p>
                                
                                <div class="mb-3">
                                    @if($pressing->is_express)
                                        <span class="badge bg-danger">Express</span>
                                    @endif
                                    @if($pressing->has_delivery)
                                        <span class="badge bg-success">Livraison</span>
                                    @endif
                                    @if($pressing->eco_friendly)
                                        <span class="badge bg-info">Éco-responsable</span>
                                    @endif
                                </div>
                                
                                <div class="mt-auto">
                                    <a href="{{ route('orders.create.pressing.show', $pressing->id) }}" class="btn btn-select w-100">
                                        Sélectionner
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('pressing-search');
        const neighborhoodFilter = document.getElementById('neighborhood-filter');
        const pressingItems = document.querySelectorAll('.pressing-item');
        const neighborhoodSections = document.querySelectorAll('.neighborhood-section');
        
        function filterPressings() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedNeighborhood = neighborhoodFilter.value;
            
            // First hide/show neighborhood sections based on filter
            neighborhoodSections.forEach(section => {
                const sectionNeighborhood = section.getAttribute('data-neighborhood');
                if (!selectedNeighborhood || selectedNeighborhood === sectionNeighborhood) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
            
            // Then filter individual pressing items by search term
            pressingItems.forEach(item => {
                const pressingName = item.getAttribute('data-name').toLowerCase();
                const matchesSearch = !searchTerm || pressingName.includes(searchTerm);
                
                // Only check neighborhood if the section is visible
                const section = item.closest('.neighborhood-section');
                const sectionVisible = section.style.display !== 'none';
                
                if (matchesSearch && sectionVisible) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Hide sections with no visible pressing items
            neighborhoodSections.forEach(section => {
                if (section.style.display !== 'none') {
                    const visibleItems = section.querySelectorAll('.pressing-item[style="display: block;"]');
                    if (visibleItems.length === 0) {
                        section.style.display = 'none';
                    }
                }
            });
        }
        
        searchInput.addEventListener('input', filterPressings);
        neighborhoodFilter.addEventListener('change', filterPressings);
    });
</script>
@endpush 