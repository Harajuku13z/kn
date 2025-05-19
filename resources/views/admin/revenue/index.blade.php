@extends('layouts.admin')

@section('page_title', 'Chiffre d\'affaires')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .revenue-card {
        transition: all 0.3s;
        border-radius: 10px;
        overflow: hidden;
    }
    .revenue-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .revenue-value {
        font-size: 1.75rem;
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
    .filter-card {
        background-color: #f8f9fc;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .period-selector .btn {
        border-radius: 0;
        padding: 0.5rem 1rem;
        font-weight: 500;
        font-size: 0.8rem;
        height: 38px;
    }
    .period-selector .btn:first-child {
        border-top-left-radius: 0.25rem;
        border-bottom-left-radius: 0.25rem;
    }
    .period-selector .btn:last-child {
        border-top-right-radius: 0.25rem;
        border-bottom-right-radius: 0.25rem;
    }
    .period-selector .btn.active {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        z-index: 1;
    }
    /* Custom filter styles */
    #dateRangeContainer .form-control-sm {
        height: 31px;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    #dateRangeContainer .btn-sm {
        height: 31px;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }
    .table-revenue th {
        background-color: #f8f9fc;
    }
    .card-header-custom {
        background-color: #fff;
        border-bottom: 1px solid rgba(0,0,0,.05);
    }
    /* Fix for btn-group in Bootstrap 5 */
    .btn-group {
        display: inline-flex;
    }
    .btn-group > .btn {
        position: relative;
        flex: 1 1 auto;
    }
    
    /* Fix for Flatpickr calendar display */
    .flatpickr-calendar {
        font-size: 14px !important;
        width: auto !important;
        max-width: 300px !important;
        box-shadow: 0 3px 13px rgba(0,0,0,0.08) !important;
        border-radius: 8px !important;
    }
    .flatpickr-day {
        font-size: 13px !important;
        line-height: 28px !important;
        height: 28px !important;
        width: 28px !important;
        max-width: 28px !important;
        margin: 2px !important;
        border-radius: 4px !important;
    }
    .flatpickr-months .flatpickr-month {
        height: 40px !important;
        background-color: var(--primary-color) !important;
    }
    .flatpickr-current-month {
        font-size: 14px !important;
        padding: 5px 0 0 0 !important;
        color: white !important;
    }
    .flatpickr-current-month .flatpickr-monthDropdown-months {
        background-color: transparent !important;
        color: white !important;
    }
    .flatpickr-weekdays {
        height: 25px !important;
        background-color: var(--primary-color) !important;
    }
    .flatpickr-weekday {
        font-size: 11px !important;
        height: 25px !important;
        line-height: 25px !important;
        color: rgba(255, 255, 255, 0.8) !important;
        background-color: var(--primary-color) !important;
    }
    .flatpickr-months .flatpickr-prev-month, 
    .flatpickr-months .flatpickr-next-month {
        padding: 5px !important;
        height: 30px !important;
        color: white !important;
        fill: white !important;
    }
    .flatpickr-months .flatpickr-prev-month:hover svg, 
    .flatpickr-months .flatpickr-next-month:hover svg {
        fill: rgba(255, 255, 255, 0.7) !important;
    }
    .flatpickr-months .flatpickr-prev-month svg, 
    .flatpickr-months .flatpickr-next-month svg {
        height: 16px !important;
        width: 16px !important;
    }
    /* Hide the default arrows and use custom ones */
    .numInputWrapper span.arrowUp,
    .numInputWrapper span.arrowDown {
        display: none !important;
    }
    /* Fix for calendar container */
    .flatpickr-calendar.open {
        z-index: 9999 !important;
        display: inline-block !important;
        opacity: 1 !important;
    }
    /* Fix for days container */
    .flatpickr-days {
        width: auto !important;
    }
    .dayContainer {
        width: 100% !important;
        min-width: auto !important;
        max-width: none !important;
    }
    /* Selected day styling */
    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
    }
    /* Today styling */
    .flatpickr-day.today {
        border-color: var(--primary-color) !important;
    }
    .flatpickr-day.today:hover {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: white !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chiffre d'affaires</h1>
        <div>
            <a href="{{ route('admin.revenue.export', request()->query()) }}" class="btn btn-success shadow-sm">
                <i class="fas fa-download fa-sm text-white-50 me-1"></i> Exporter Excel
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4 filter-card">
        <div class="card-body p-3">
            <form action="{{ route('admin.revenue.index') }}" method="GET" id="filterForm">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <label class="form-label small text-muted mb-1">Période</label>
                        <div class="period-selector btn-group w-100" role="group" aria-label="Sélecteur de période">
                            <button type="button" class="btn btn-outline-primary {{ $period == 'day' ? 'active' : '' }}" data-period="day">Jour</button>
                            <button type="button" class="btn btn-outline-primary {{ $period == 'week' ? 'active' : '' }}" data-period="week">Semaine</button>
                            <button type="button" class="btn btn-outline-primary {{ $period == 'month' ? 'active' : '' }}" data-period="month">Mois</button>
                            <button type="button" class="btn btn-outline-primary {{ $period == 'current_month' ? 'active' : '' }}" data-period="current_month">Mois en cours</button>
                            <button type="button" class="btn btn-outline-primary {{ $period == 'previous_month' ? 'active' : '' }}" data-period="previous_month">Mois passé</button>
                            <button type="button" class="btn btn-outline-primary {{ $period == 'last_3_months' ? 'active' : '' }}" data-period="last_3_months">3 mois</button>
                            <button type="button" class="btn btn-outline-primary {{ $period == 'last_6_months' ? 'active' : '' }}" data-period="last_6_months">6 mois</button>
                        </div>
                        <input type="hidden" name="period" id="periodInput" value="{{ $period }}">
                        <input type="hidden" name="start_date" id="startDateInput" value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}">
                        <input type="hidden" name="end_date" id="endDateInput" value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Période sélectionnée -->
    <div class="mb-4 text-center">
        <h5 class="text-primary">
            Période : 
            @if($period == 'day')
                {{ $startDate->format('d/m/Y') }}
            @elseif($period == 'week')
                Semaine du {{ $startDate->format('d/m/Y') }} au {{ $endDate->format('d/m/Y') }}
            @elseif($period == 'month')
                {{ $startDate->format('F Y') }}
            @elseif($period == 'current_month')
                Mois en cours ({{ $startDate->format('d/m/Y') }} au {{ $endDate->format('d/m/Y') }})
            @elseif($period == 'previous_month')
                Mois précédent ({{ $startDate->format('d/m/Y') }} au {{ $endDate->format('d/m/Y') }})
            @elseif($period == 'last_3_months')
                3 derniers mois ({{ $startDate->format('d/m/Y') }} au {{ $endDate->format('d/m/Y') }})
            @elseif($period == 'last_6_months')
                6 derniers mois ({{ $startDate->format('d/m/Y') }} au {{ $endDate->format('d/m/Y') }})
            @else
                {{ $startDate->format('d/m/Y') }} au {{ $endDate->format('d/m/Y') }}
            @endif
        </h5>
    </div>

    <!-- Revenue Overview -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 revenue-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Dépenses en abonnements</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($paidSubscriptionsRevenue, 0, ',', ' ') }} FCFA</div>
                            <div class="text-xs text-muted mt-1">(Abonnements payés)</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 revenue-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Commandes livrées</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($deliveredRevenue, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 revenue-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Commandes en attente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($pendingRevenue, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 revenue-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Paiements en cash</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($cashPaymentRevenue, 0, ',', ' ') }} FCFA</div>
                            <div class="text-xs text-muted mt-1">(Hors abonnements)</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Status -->
    <div class="row mb-4">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 revenue-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Commandes payées</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($paidOrdersRevenue, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 revenue-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Commandes en attente de paiement</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($unpaidOrdersRevenue, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscription Status -->
    <div class="row mb-4">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 revenue-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Abonnements payés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($paidSubscriptionsRevenue, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 revenue-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Abonnements en attente de paiement</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($unpaidSubscriptionsRevenue, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow mb-4 revenue-card">
                <div class="card-header card-header-custom py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-1"></i> Évolution du chiffre d'affaires
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Details Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow mb-4 revenue-card">
                <div class="card-header card-header-custom py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-table me-1"></i> Détails du chiffre d'affaires par jour
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-revenue">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Commandes</th>
                                    <th>Abonnements</th>
                                    <th>Cash</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($revenueByDay as $day)
                                <tr>
                                    <td>{{ $day['date'] }}</td>
                                    <td>{{ number_format($day['orders'], 0, ',', ' ') }} FCFA</td>
                                    <td>{{ number_format($day['subscriptions'], 0, ',', ' ') }} FCFA</td>
                                    <td>{{ number_format($day['cash'], 0, ',', ' ') }} FCFA</td>
                                    <td><strong>{{ number_format($day['total'], 0, ',', ' ') }} FCFA</strong></td>
                                </tr>
                                @endforeach
                                @if(count($revenueByDay) == 0)
                                <tr>
                                    <td colspan="5" class="text-center">Aucune donnée disponible pour cette période</td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr class="bg-light">
                                    <th>Total</th>
                                    <th>{{ number_format($deliveredRevenue, 0, ',', ' ') }} FCFA</th>
                                    <th>{{ number_format($paidSubscriptionsRevenue, 0, ',', ' ') }} FCFA</th>
                                    <th>{{ number_format($cashPaymentRevenue, 0, ',', ' ') }} FCFA</th>
                                    <th>{{ number_format($deliveredRevenue + $paidSubscriptionsRevenue + $cashPaymentRevenue, 0, ',', ' ') }} FCFA</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion des boutons de période
        const periodButtons = document.querySelectorAll('.period-selector .btn');
        const periodInput = document.getElementById('periodInput');
        const startDateInput = document.getElementById('startDateInput');
        const endDateInput = document.getElementById('endDateInput');
        const filterForm = document.getElementById('filterForm');
        
        periodButtons.forEach(button => {
            button.addEventListener('click', function() {
                const period = this.dataset.period;
                
                // Mettre à jour l'UI
                periodButtons.forEach(btn => {
                    btn.classList.remove('active');
                    btn.setAttribute('aria-pressed', 'false');
                });
                this.classList.add('active');
                this.setAttribute('aria-pressed', 'true');
                
                // Mettre à jour la valeur cachée
                periodInput.value = period;
                
                // Soumettre le formulaire
                filterForm.submit();
            });
        });
        
        // Configuration du graphique
        const revenueData = @json($revenueByDay);
        const labels = revenueData.map(item => item.date);
        const ordersData = revenueData.map(item => item.orders);
        const subscriptionsData = revenueData.map(item => item.subscriptions);
        const totalData = revenueData.map(item => item.total);
        
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Total',
                        data: totalData,
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderColor: '#4e73df',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#4e73df',
                        pointBorderColor: '#4e73df',
                        pointHoverRadius: 5,
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Commandes',
                        data: ordersData,
                        backgroundColor: 'transparent',
                        borderColor: '#1cc88a',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#1cc88a',
                        pointBorderColor: '#1cc88a',
                        pointHoverRadius: 5,
                        tension: 0.3
                    },
                    {
                        label: 'Abonnements',
                        data: subscriptionsData,
                        backgroundColor: 'transparent',
                        borderColor: '#36b9cc',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#36b9cc',
                        pointBorderColor: '#36b9cc',
                        pointHoverRadius: 5,
                        tension: 0.3
                    },
                    {
                        label: 'Cash',
                        data: revenueData.map(item => item.cash),
                        backgroundColor: 'transparent',
                        borderColor: '#f6c23e',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#f6c23e',
                        pointBorderColor: '#f6c23e',
                        pointHoverRadius: 5,
                        tension: 0.3
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR').format(value) + ' F';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + new Intl.NumberFormat('fr-FR').format(context.raw) + ' FCFA';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush 