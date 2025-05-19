@extends('layouts.dashboard')

@section('title', 'Mes Activités - KLINKLIN')

@section('content')
<div class="dashboard-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-klin-primary">Mes Activités</h1>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Retour au tableau de bord
            </a>
        </div>
    </div>
    
    <div class="alert alert-light shadow-sm border-0">
        <div class="d-flex align-items-center">
            <i class="bi bi-activity fs-3 me-3 text-klin-primary"></i>
            <div>
                <h5 class="mb-1">Toutes vos activités</h5>
                <p class="mb-0">Factures, collectes, paiements... tout est ici pour vous simplifier la vie.</p>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-klin-primary"><i class="bi bi-list-check me-2"></i>Historique d'activités</h5>
                    <div class="input-group input-group-sm w-auto">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-funnel"></i></span>
                        <select class="form-select form-select-sm filter-select" id="filterActivities">
                            <option value="all">Tout afficher</option>
                            <option value="order">Commandes</option>
                            <option value="subscription">Abonnements</option>
                            <option value="usage">Utilisations de quota</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($activities->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h5>Aucune activité récente</h5>
                            <p class="text-muted">Vos activités récentes apparaîtront ici.</p>
                        </div>
                    @else
                        <div class="activities-timeline">
                            @foreach($activities as $activity)
                                <div class="activity-item" data-type="{{ $activity['type'] }}">
                                    <div class="activity-date">
                                        <div class="activity-day">{{ $activity['date']->format('d') }}</div>
                                        <div class="activity-month">{{ $activity['date']->locale('fr')->shortMonthName }}</div>
                                        <div class="activity-year">{{ $activity['date']->format('Y') }}</div>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-icon">
                                            <i class="bi {{ $activity['icon'] }}"></i>
                                        </div>
                                        <div class="activity-details">
                                            <h6>{{ $activity['title'] }}</h6>
                                            <p class="text-muted mb-1">{{ $activity['description'] }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-muted small">{{ $activity['date']->format('H:i') }}</span>
                                                @if($activity['amount'])
                                                    <span class="fw-bold">{{ number_format($activity['amount'], 0, ',', ' ') }} FCFA</span>
                                                @endif
                                            </div>
                                            
                                            @if($activity['type'] === 'order')
                                                <div class="mt-2">
                                                    <a href="{{ route('history.show', $activity['data']->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye me-1"></i> Détails
                                                    </a>
                                                    <span class="badge bg-info small">Statut: {{ $activity['data']->status }}</span>
                                                    @if(in_array($activity['data']->status, ['delivered', 'completed']))
                                                    <a href="{{ route('orders.invoice.download', $activity['data']->id) }}" class="btn btn-sm btn-outline-success ms-2">
                                                        <i class="bi bi-file-earmark-pdf me-1"></i> Facture
                                                    </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination links -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $activities->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Statistiques -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-klin-primary"><i class="bi bi-graph-up me-2"></i>Résumé d'activités</h5>
                </div>
                <div class="card-body">
                    <div class="activity-stats">
                        <div class="activity-stat-item">
                            <div class="activity-stat-icon orders">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="activity-stat-content">
                                <h6>Commandes</h6>
                                <p class="mb-0">{{ $activities->where('type', 'order')->count() }}</p>
                            </div>
                        </div>
                        <div class="activity-stat-item">
                            <div class="activity-stat-icon subscriptions">
                                <i class="bi bi-credit-card"></i>
                            </div>
                            <div class="activity-stat-content">
                                <h6>Abonnements</h6>
                                <p class="mb-0">{{ $activities->where('type', 'subscription')->count() }}</p>
                            </div>
                        </div>
                        <div class="activity-stat-item">
                            <div class="activity-stat-icon quota">
                                <i class="bi bi-arrow-down-circle"></i>
                            </div>
                            <div class="activity-stat-content">
                                <h6>Utilisations</h6>
                                <p class="mb-0">{{ $activities->where('type', 'usage')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Documents récents -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-klin-primary"><i class="bi bi-file-earmark-text me-2"></i>Documents récents</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($recentDocuments as $activity)
                            <a href="{{ route('orders.invoice.download', $activity['data']->id) }}" class="list-group-item list-group-item-action d-flex align-items-center border-0 ps-0">
                                <div class="me-3">
                                    <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Facture #{{ $activity['data']->id }}</h6>
                                    <small class="text-muted">{{ $activity['date']->format('d/m/Y') }}</small>
                                </div>
                                <div class="ms-auto">
                                    <i class="bi bi-download"></i>
                                </div>
                            </a>
                        @empty
                            <p class="text-muted mb-0">Aucun document récent disponible.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Aide -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-klin-primary"><i class="bi bi-question-circle me-2"></i>Besoin d'aide ?</h5>
                </div>
                <div class="card-body">
                    <p>Vous avez des questions sur vos factures ou vos activités ?</p>
                    <div class="d-grid">
                        <a href="#" class="btn btn-outline-primary">
                            <i class="bi bi-headset me-1"></i> Contacter le support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .text-klin-primary {
        color: #4A148C;
    }
    
    /* Styles pour la chronologie d'activités */
    .activities-timeline {
        position: relative;
        padding: 1.5rem;
    }
    
    .activity-item {
        display: flex;
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .activity-item:last-child {
        margin-bottom: 0;
    }
    
    .activity-date {
        text-align: center;
        width: 70px;
        flex: 0 0 70px;
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 10px 0;
        margin-right: 20px;
    }
    
    .activity-day {
        font-size: 1.5rem;
        font-weight: bold;
        line-height: 1;
        color: #4A148C;
    }
    
    .activity-month {
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .activity-year {
        font-size: 0.75rem;
        color: #6c757d;
    }
    
    .activity-content {
        flex: 1;
        display: flex;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        padding: 1rem;
        border-left: 3px solid #4A148C;
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        background-color: rgba(74, 20, 140, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: #4A148C;
    }
    
    .activity-details {
        flex: 1;
    }
    
    .activity-details h6 {
        margin-bottom: 0.25rem;
        font-weight: 600;
    }
    
    /* Styles pour les statistiques */
    .activity-stats {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .activity-stat-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    
    .activity-stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: white;
        font-size: 1.5rem;
    }
    
    .activity-stat-icon.orders {
        background-color: #4A148C;
    }
    
    .activity-stat-icon.subscriptions {
        background-color: #2196F3;
    }
    
    .activity-stat-icon.quota {
        background-color: #4CAF50;
    }
    
    .activity-stat-content h6 {
        margin-bottom: 0;
        font-weight: 500;
    }
    
    .activity-stat-content p {
        font-size: 1.25rem;
        font-weight: 600;
        color: #4A148C;
    }
    
    /* Filter styles */
    .filter-select {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtrage des activités
    const filterSelect = document.getElementById('filterActivities');
    const activityItems = document.querySelectorAll('.activity-item');
    
    if (filterSelect) {
        console.log('Initialisation du filtre des activités');
        
        filterSelect.addEventListener('change', function() {
            const filterValue = this.value;
            console.log('Filtre sélectionné:', filterValue);
            
            activityItems.forEach(item => {
                const itemType = item.getAttribute('data-type');
                console.log('Item type:', itemType, 'Filter value:', filterValue);
                
                if (filterValue === 'all' || itemType === filterValue) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        // Appliquer le filtre au chargement de la page si un filtre est déjà sélectionné
        if (filterSelect.value !== 'all') {
            filterSelect.dispatchEvent(new Event('change'));
        }
    } else {
        console.error('Élément de filtre non trouvé');
    }
});
</script>
@endpush 