@extends('layouts.admin')

@section('page_title', 'Tableau de bord')

@push('styles')
<style>
    .stat-card {
        transition: all 0.3s;
        border-radius: 10px;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .revenue-card {
        border-radius: 10px;
        overflow: hidden;
    }
    .revenue-value {
        font-size: 1.5rem;
        font-weight: 700;
    }
    .revenue-label {
        font-size: 0.85rem;
        color: #6c757d;
    }
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    .dashboard-header {
        background-color: #f8f9fc;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .welcome-message {
        font-size: 1.1rem;
        color: #5a5c69;
    }
    .data-card {
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s;
    }
    .data-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.1) !important;
    }
    .card-header-custom {
        background-color: #fff;
        border-bottom: 1px solid rgba(0,0,0,.05);
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.02);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="dashboard-header shadow-sm">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="h3 mb-2 text-gray-800">Tableau de bord</h1>
                <p class="welcome-message mb-0">Bienvenue dans l'administration KLINKLIN. Voici un aperçu de vos activités.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('admin.orders.create') }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Nouvelle commande
                </a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-success shadow-sm ms-2">
                    <i class="fas fa-user-plus fa-sm text-white-50 me-1"></i> Nouvel utilisateur
                </a>
            </div>
        </div>
    </div>

    <!-- Revenue Overview -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Chiffre d'affaires aujourd'hui (commandes livrées)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($dailyRevenue, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.revenue.index', ['period' => 'day']) }}" class="text-success small stretched-link">Voir les détails</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Chiffre d'affaires total (commandes livrées)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.revenue.index') }}" class="text-primary small stretched-link">Voir tous les chiffres</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Commandes livrées</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['delivered_orders'] }}</div>
                            <div class="progress progress-sm mt-2">
                                <div class="progress-bar bg-info" role="progressbar" style="width: {{ $stats['total_orders'] > 0 ? ($stats['delivered_orders'] / $stats['total_orders'] * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Commandes en attente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_orders'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="text-warning small stretched-link">Traiter les commandes en attente</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Charts -->
    <div class="row mb-4">
        <!-- Daily Revenue Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4 revenue-card">
                <div class="card-header card-header-custom py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-1"></i> Chiffre d'affaires des 7 derniers jours (commandes livrées)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="dailyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4 revenue-card">
                <div class="card-header card-header-custom py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-1"></i> Répartition des commandes
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow mb-4 revenue-card">
                <div class="card-header card-header-custom py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-area me-1"></i> Chiffre d'affaires mensuel (commandes livrées)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthlyRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Commandes totales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_orders'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.orders.index') }}" class="text-primary small stretched-link">Voir toutes les commandes</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Utilisateurs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.users.index') }}" class="text-success small stretched-link">Gérer les utilisateurs</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Tickets Support</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['open_tickets'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-headset fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.support.index') }}" class="text-danger small stretched-link">Gérer les tickets support</a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 stat-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Prix actuel</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @if($stats['current_price'])
                                    {{ number_format($stats['current_price']->price_per_kg, 0, ',', ' ') }} FCFA/kg
                                @else
                                    Non défini
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.prices.index') }}" class="text-info small stretched-link">Gérer les prix</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Commandes récentes et Tickets -->
    <div class="row">
        <!-- Commandes récentes -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4 data-card">
                <div class="card-header card-header-custom py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shopping-cart me-1"></i> Commandes récentes
                    </h6>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">
                        Voir toutes
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="ordersTable" width="100%" cellspacing="0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : 
                                                ($order->status === 'completed' ? 'success' : 
                                                ($order->status === 'processing' ? 'info' : 
                                                ($order->status === 'cancelled' ? 'danger' : 'secondary'))) }} text-white">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-info" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">Aucune commande récente</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets de support récents -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4 data-card">
                <div class="card-header card-header-custom py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-headset me-1"></i> Tickets de support
                    </h6>
                    <a href="{{ route('admin.support.index') }}" class="btn btn-sm btn-danger">
                        Voir tous
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recent_tickets ?? [] as $ticket)
                            <a href="{{ route('admin.support.show', $ticket->id) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ Str::limit($ticket->subject, 30) }}</h6>
                                    <span class="badge bg-{{ $ticket->status === 'open' ? 'danger' : 
                                        ($ticket->status === 'in_progress' ? 'warning' : 
                                        ($ticket->status === 'resolved' ? 'success' : 'secondary')) }} text-white">
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                </div>
                                <p class="mb-1 text-muted small">{{ $ticket->user->name }}</p>
                                <small>{{ $ticket->created_at->format('d/m/Y H:i') }}</small>
                            </a>
                        @empty
                            <div class="text-center py-4">
                                <p class="mb-0 text-muted">Aucun ticket de support récent</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuration des couleurs
        const primaryColor = '#4e73df';
        const successColor = '#1cc88a';
        const infoColor = '#36b9cc';
        const warningColor = '#f6c23e';
        const dangerColor = '#e74a3b';
        const secondaryColor = '#858796';
        
        // Graphique des revenus quotidiens
        const dailyCtx = document.getElementById('dailyRevenueChart').getContext('2d');
        const dailyRevenueChart = new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: @json($last7DaysLabels),
                datasets: [{
                    label: 'Chiffre d\'affaires (FCFA)',
                    data: @json($last7Days),
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: primaryColor,
                    pointRadius: 3,
                    pointBackgroundColor: primaryColor,
                    pointBorderColor: primaryColor,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: primaryColor,
                    pointHoverBorderColor: primaryColor,
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return new Intl.NumberFormat('fr-FR').format(context.raw) + ' FCFA';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR').format(value) + ' F';
                            }
                        }
                    }
                }
            }
        });
        
        // Graphique des revenus mensuels
        const monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
        const monthlyRevenueChart = new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: @json($last12MonthsLabels),
                datasets: [{
                    label: 'Chiffre d\'affaires (FCFA)',
                    data: @json($last12Months),
                    backgroundColor: successColor,
                    borderColor: successColor,
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return new Intl.NumberFormat('fr-FR').format(context.raw) + ' FCFA';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR').format(value) + ' F';
                            }
                        }
                    }
                }
            }
        });
        
        // Graphique de répartition des commandes
        const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
        const orderStatusChart = new Chart(orderStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Livrées', 'En attente', 'Autres'],
                datasets: [{
                    data: [{{ $stats['delivered_orders'] }}, {{ $stats['pending_orders'] }}, {{ $stats['total_orders'] - $stats['delivered_orders'] - $stats['pending_orders'] }}],
                    backgroundColor: [successColor, warningColor, secondaryColor],
                    hoverBackgroundColor: [successColor, warningColor, secondaryColor],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush