@extends('layouts.dashboard')

@section('title', 'Notifications - KLINKLIN')

@section('content')
<div class="dashboard-content-redesigned">
    <div class="row mb-4 align-items-center">
        <div class="col-auto">
            <div class="notification-icon-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-bell-fill text-klin-primary"></i>
            </div>
        </div>
        <div class="col">
            <h1 class="display-5 fw-bold text-klin-primary mb-1">Mes Notifications</h1>
            <p class="text-muted fs-5 mb-0">Consultez vos messages importants et alertes récentes.</p>
        </div>
        <div class="col-auto text-end">
                <form id="markAllReadForm" action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                    @csrf
                <button type="submit" class="btn btn-lg btn-primary klin-btn rounded-pill shadow-sm">
                        <i class="bi bi-check2-all me-1"></i> Tout marquer comme lu
                    </button>
                </form>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm mb-4 notification-control-bar">
        <div class="card-body">
            <div class="row gy-3 align-items-center">
                <div class="col-lg-5 col-md-12">
                    <div class="notification-counters d-flex flex-wrap">
                        <div class="counter-item me-3 mb-2 mb-md-0">
                            <i class="bi bi-collection me-1 text-muted"></i> Total:
                            <span class="counter-value total ms-1" id="notifications-count-total">{{ $notifications->total() }}</span>
                        </div>
                        <div class="counter-item me-3 mb-2 mb-md-0">
                            <i class="bi bi-envelope-exclamation me-1 text-danger"></i> Non lues:
                            <span class="counter-value unread text-danger ms-1" id="notifications-count-unread">{{ $notifications->where('read_at', null)->count() }}</span>
                        </div>
                        <div class="counter-item">
                            <i class="bi bi-envelope-check me-1 text-success"></i> Lues:
                            <span class="counter-value read text-success ms-1" id="notifications-count-read">{{ $notifications->whereNotNull('read_at')->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-md-12">
                    <form id="notificationFilterForm" action="{{ route('notifications.index') }}" method="GET" class="notification-filters">
                        <div class="d-flex flex-wrap align-items-center justify-content-lg-end gap-2">
                            <div class="filter-group">
                                <label for="statusFilter" class="form-label visually-hidden">Statut:</label>
                                <select class="form-select form-select-sm filter-select" id="statusFilter" name="status" aria-label="Filtrer par statut">
                                    <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>Toutes les notifications</option>
                                    <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Non lues seulement</option>
                                    <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Lues seulement</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <button type="submit" id="filterButton" class="btn btn-sm btn-primary klin-btn">Filtrer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="notifications-list" id="notifications-list-container">
        @include('notifications.partials.list')
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Styles pour la page de notifications */
    .dashboard-content-redesigned {
        padding: 1.5rem;
    }
    
    .notification-control-bar {
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }
    
    .notification-counters {
        font-size: 0.9rem;
    }
    
    .notification-counters .counter-item {
        display: flex;
        align-items: center;
    }
    
    .notification-counters .counter-value {
        font-weight: 600;
    }
    
    .notification-empty-card-redesigned {
        border-radius: 10px;
        border: none;
    }
    
    .notification-item {
        border-radius: 10px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-left-width: 4px !important;
        overflow: hidden;
    }
    
    .notification-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.1) !important;
    }
    
    .notification-item.unread {
        background-color: #f8f9fa;
    }
    
    .notification-item.read {
        background-color: #fff;
        opacity: 0.8;
    }
    
    .notification-status-indicator {
        width: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .notification-unread-dot {
        width: 8px;
        height: 8px;
        background-color: var(--bs-danger);
        border-radius: 50%;
    }
    
    .notification-item-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--klin-light-bg, #f8f5fc);
        color: var(--primary-purple);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .notification-date {
        font-size: 0.8rem;
    }
    
    /* Styles pour les bordures colorées */
    .border-primary {
        border-left-color: var(--primary-purple) !important;
    }
    
    .border-klin-primary {
        border-left-color: var(--klin-primary, #4A148C) !important;
    }
    
    .border-profile {
        border-left-color: #6200ea !important;
    }
    
    .border-order {
        border-left-color: #2962ff !important;
    }
    
    .border-address {
        border-left-color: #00c853 !important;
    }
    
    .border-payment {
        border-left-color: #ffab00 !important;
    }
    
    .border-quota {
        border-left-color: #d50000 !important;
    }
    
    .border-success {
        border-left-color: #28a745 !important;
    }
    
    .border-danger {
        border-left-color: #dc3545 !important;
    }
    
    .border-warning {
        border-left-color: #ffc107 !important;
    }
    
    .border-info {
        border-left-color: #17a2b8 !important;
    }
    
    /* Design du bouton */
    .klin-btn {
        background-color: var(--klin-primary, #4A148C);
        border-color: var(--klin-primary, #4A148C);
        color: white;
        transition: background-color 0.2s ease;
    }
    
    .klin-btn:hover {
        background-color: var(--klin-primary-dark, #38006b);
        border-color: var(--klin-primary-dark, #38006b);
        color: white;
    }
    
    /* Styles pour les filtres */
    .filter-select {
        border-radius: 0.375rem;
        border-color: var(--klin-border-color, #e0d8e7);
        min-width: 12rem;
    }
    
    /* Responsive styles */
    @media (max-width: 768px) {
        .notification-counters {
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .notification-filters {
            justify-content: center;
        }
        
        .notification-actions {
            margin-top: 0.75rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestionnaire pour les boutons "Marquer comme lu" et les liens d'action
    document.querySelectorAll('.mark-as-read-btn, .action-link').forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });

    // Capturer la soumission du formulaire de filtre pour le traiter en AJAX
    document.getElementById('notificationFilterForm').addEventListener('submit', function(event) {
            event.preventDefault();
        
        const form = this;
        const url = new URL(form.action);
        const formData = new FormData(form);
        
        // Ajouter les paramètres du formulaire à l'URL
        for (const [key, value] of formData.entries()) {
            url.searchParams.set(key, value);
        }
        
        // Naviguer vers cette URL (ce qui déclenchera le chargement AJAX)
        history.pushState({ path: url.toString() }, '', url.toString());
        window.dispatchEvent(new PopStateEvent('popstate', { state: { path: url.toString() }}));
    });
    
    // Capturer la soumission du formulaire "Tout marquer comme lu"
    document.getElementById('markAllReadForm').addEventListener('submit', function(event) {
        event.preventDefault();
        
        const form = this;
        const url = form.action;
        const formData = new FormData(form);
        
        // Ajouter les paramètres CSRF
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        // Envoyer la requête en AJAX
        fetch(url, {
                method: 'POST',
            body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
            }
            })
        .then(response => response.json())
            .then(data => {
                if (data.success) {
                // Recharger la page actuelle pour afficher les modifications
                window.location.reload();
                }
            })
            .catch(error => {
            console.error('Erreur lors du marquage des notifications:', error);
        });
    });
});
</script>
@endpush