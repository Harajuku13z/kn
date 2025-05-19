<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Administration - KLINKLIN</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
        }

        body {
            background-color: #f8f9fc;
        }

        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 0%, #224abe 100%);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            z-index: 1000;
            overflow-y: auto;
        }

        /* Version desktop de la sidebar */
        @media (min-width: 992px) {
            .sidebar {
                min-height: 100vh;
                position: fixed;
                width: 280px;
                height: 100vh;
                overflow-y: auto;
            }
            
            .main-content {
                margin-left: 280px;
            }
            
            .sidebar-toggler {
                display: none;
            }
            
            /* Style de la scrollbar pour Webkit (Chrome, Safari, Edge) */
            .sidebar::-webkit-scrollbar {
                width: 6px;
            }
            
            .sidebar::-webkit-scrollbar-track {
                background: rgba(0, 0, 0, 0.1);
            }
            
            .sidebar::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.3);
                border-radius: 3px;
            }
            
            .sidebar::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.5);
            }
        }
        
        /* Version mobile de la sidebar */
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -280px;
                width: 280px;
                height: 100vh;
                transition: left 0.3s ease;
                overflow-y: auto;
                z-index: 1050;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1030;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
            
            /* Amélioration du bouton toggle pour mobile */
            .sidebar-toggler {
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 1040;
                background-color: var(--primary-color);
                color: white;
                border: none;
                padding: 0.6rem;
                border-radius: 0.35rem;
                box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.3);
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s;
            }
            
            .sidebar-toggler:hover {
                background-color: #2e59d9;
                transform: scale(1.05);
            }
            
            /* Ajout d'espace pour le bouton mobile */
            .topbar {
                margin-left: 3.5rem;
            }
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-radius: 0.35rem;
            margin: 0.2rem 0;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            color: #fff;
            font-weight: bold;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .sidebar-category {
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            font-size: 0.8rem;
            padding: 1rem 1rem 0.5rem;
            letter-spacing: 0.05rem;
        }

        .main-content {
            padding: 1.5rem;
        }

        .topbar {
            height: 4.375rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
            border-radius: 0.5rem;
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
        }

        .table th {
            background-color: #f8f9fc;
            border-bottom: 2px solid #e3e6f0;
        }

        .dropdown-menu {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .border-left-primary {
            border-left: 0.25rem solid var(--primary-color) !important;
        }
        
        .border-left-success {
            border-left: 0.25rem solid var(--success-color) !important;
        }
        
        .border-left-info {
            border-left: 0.25rem solid var(--info-color) !important;
        }
        
        .border-left-warning {
            border-left: 0.25rem solid var(--warning-color) !important;
        }
        
        .border-left-danger {
            border-left: 0.25rem solid var(--danger-color) !important;
        }
        
        .logo-wrapper {
            padding: 1rem;
            text-align: center;
        }
        
        .logo-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            letter-spacing: 1px;
        }
        
        .collapse-item {
            display: block;
            text-decoration: none;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        
        .collapse-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .collapse-item.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .divider {
            height: 0;
            margin: 0.5rem 0;
            overflow: hidden;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* Ajout pour rendre le menu plus convivial */
        .bg-gradient-primary {
            background: linear-gradient(180deg, var(--primary-color) 10%, #224abe 100%);
        }
        
        .sidebar-nav-wrapper {
            padding-bottom: 100px; /* Espace en bas pour faciliter le défilement jusqu'au dernier élément */
        }
        
        .sidebar .nav-item {
            margin-bottom: 2px;
        }
        
        .sidebar .nav-link {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Ajuster l'espacement des éléments pour PC */
        @media (min-width: 992px) {
            .sidebar-category {
                padding-top: 1.25rem;
                margin-top: 0.5rem;
            }
            
            .divider {
                margin: 0.25rem 0;
            }
        }

        /* Styles pour les notifications */
        .icon-circle {
            height: 2.5rem;
            width: 2.5rem;
            border-radius: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .dropdown-menu.animated--grow-in {
            animation-name: growIn;
            animation-duration: 200ms;
            animation-timing-function: transform cubic-bezier(0.18, 1.25, 0.4, 1), opacity cubic-bezier(0, 1, 0.4, 1);
        }
        
        @keyframes growIn {
            0% {
                transform: scale(0.9);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .dropdown-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
        }
        
        .dropdown-item {
            white-space: normal;
            padding: 0.5rem 1rem;
        }
        
        .dropdown-item:active, .dropdown-item:focus {
            background-color: rgba(78, 115, 223, 0.1);
            color: #6e707e;
        }
        
        .badge-counter {
            position: absolute;
            transform: scale(0.7);
            transform-origin: top right;
            right: 0.25rem;
            margin-top: -0.25rem;
        }
    </style>
</head>

<body id="admin-body">
    <div class="d-flex">
        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar Toggle Button for Mobile -->
        <button class="sidebar-toggler" type="button" id="sidebarToggler">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Sidebar -->
        <div class="sidebar p-0" id="sidebar">
            <div class="logo-wrapper py-4 position-sticky top-0 bg-gradient-primary" style="z-index: 10;">
                <div class="logo-text">KLINKLIN</div>
                <div class="text-white-50 small">Administration</div>
                <button class="d-md-none btn btn-sm btn-outline-light mt-2" id="sidebarCloseBtn">
                    <i class="fas fa-times"></i> Fermer
                </button>
            </div>
            
            <div class="divider"></div>
            
            <div class="sidebar-nav-wrapper">
                <ul class="nav flex-column px-3">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-fw fa-tachometer-alt me-2"></i>
                            Tableau de bord
                        </a>
                    </li>
                    
                    <div class="divider"></div>
                    <div class="sidebar-category">Gestion des commandes</div>
                    
                    <!-- Chiffre d'affaires -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.revenue.*') ? 'active' : '' }}" href="{{ route('admin.revenue.index') }}">
                            <i class="fas fa-fw fa-chart-line me-2"></i>
                            Chiffre d'affaires
                        </a>
                    </li>
                    
                    <!-- Commandes -->
                    <li class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.orders.index') }}">
                            <i class="fas fa-fw fa-shopping-cart"></i>
                            <span>Commandes</span>
                        </a>
                    </li>
                    
                    <!-- Orders Management (Alternative) -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('orders-management.*') ? 'active' : '' }}" href="{{ route('orders-management.index') }}">
                            <i class="fas fa-fw fa-tasks me-2"></i>
                            Gestion des commandes
                        </a>
                    </li>
                    
                    <div class="divider"></div>
                    <div class="sidebar-category">Services & Produits</div>
                    
                    <!-- Articles -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}" href="{{ route('admin.articles.index') }}">
                            <i class="fas fa-fw fa-tshirt me-2"></i>
                            Articles
                        </a>
                    </li>
                    
                    <!-- Prix -->
                        <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.prices.*') ? 'active' : '' }}" href="{{ route('admin.prices.index') }}">
                            <i class="fas fa-fw fa-tags me-2"></i>
                            Configuration des prix
                            </a>
                        </li>
                    
                    <!-- Pressings -->
                        <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.pressings.*') ? 'active' : '' }}" href="{{ route('admin.pressings.index') }}">
                            <i class="fas fa-fw fa-store me-2"></i>
                            Pressings partenaires
                            </a>
                        </li>
                    
                    <!-- Services de pressing -->
                        <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.pressing-services.*') ? 'active' : '' }}" href="{{ route('admin.pressing-services.index') }}">
                            <i class="fas fa-fw fa-list-alt me-2"></i>
                            Services de pressing
                            </a>
                        </li>
                    
                    <div class="divider"></div>
                    <div class="sidebar-category">Abonnements</div>
                    
                    <!-- Types d'Abonnement -->
                        <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.subscription-types.*') ? 'active' : '' }}" href="{{ route('admin.subscription-types.index') }}">
                            <i class="fas fa-fw fa-credit-card me-2"></i>
                            Types d'Abonnement
                            </a>
                        </li>
                    
                    <!-- Abonnements -->
                        <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}" href="{{ route('admin.subscriptions.index') }}">
                            <i class="fas fa-fw fa-clipboard-list me-2"></i>
                            Gestion des Abonnements
                            </a>
                        </li>
                        
                    <!-- Quotas -->
                        <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.quotas.*') ? 'active' : '' }}" href="{{ route('admin.quotas.index') }}">
                            <i class="fas fa-fw fa-weight me-2"></i>
                            Gestion des Quotas
                            </a>
                        </li>
                    
                    <!-- Codes Promo -->
                        <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}" href="{{ route('admin.coupons.index') }}">
                            <i class="fas fa-fw fa-ticket-alt me-2"></i>
                            Codes Promo
                            </a>
                        </li>
                    
                    <!-- Menu pour la gestion des bons de livraison -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/delivery-vouchers*') || request()->is('admin/automatic-vouchers*') ? 'active' : 'collapsed' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseVouchers" aria-expanded="{{ request()->is('admin/delivery-vouchers*') || request()->is('admin/automatic-vouchers*') ? 'true' : 'false' }}" aria-controls="collapseVouchers">
                            <div class="sb-nav-link-icon"><i class="fas fa-ticket-alt"></i></div>
                            Bons de livraison
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse {{ request()->is('admin/delivery-vouchers*') || request()->is('admin/automatic-vouchers*') ? 'show' : '' }}" id="collapseVouchers" aria-labelledby="headingVouchers" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link {{ request()->is('admin/delivery-vouchers*') ? 'active' : '' }}" href="{{ route('admin.delivery-vouchers.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>Tous les bons
                                </a>
                                <a class="nav-link {{ request()->is('admin/automatic-vouchers*') ? 'active' : '' }}" href="{{ route('admin.automatic-vouchers.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-magic"></i></div>Attribution automatique
                                </a>
                            </nav>
                        </div>
                    </li>
                    
                    <div class="divider"></div>
                    <div class="sidebar-category">Utilisateurs</div>
                    
                    <!-- Utilisateurs -->
                        <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="fas fa-fw fa-users me-2"></i>
                            Gestion des utilisateurs
                            </a>
                        </li>
                    
                    <div class="divider"></div>
                    <div class="sidebar-category">Support</div>
                    
                    <!-- Support Tickets -->
                    <li class="nav-item {{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.support.index') }}">
                            <i class="fas fa-fw fa-headset"></i>
                            <span>Support</span>
                            @php
                                $openTicketsCount = App\Models\SupportTicket::whereIn('status', ['open', 'in_progress'])->count();
                            @endphp
                            @if($openTicketsCount > 0)
                                <span class="badge bg-danger text-white ms-2">{{ $openTicketsCount }}</span>
                            @endif
                        </a>
                    </li>
                    
                    <div class="divider"></div>
                    <div class="sidebar-category">Paramètres</div>
                    
                    <!-- Configuration -->
                        <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.configuration.*') ? 'active' : '' }}" href="{{ route('admin.configuration.index') }}">
                            <i class="fas fa-fw fa-cogs me-2"></i>
                            Configuration
                            </a>
                        </li>
                    
                    <!-- Frais de livraison -->
                        <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.delivery-fees.*') ? 'active' : '' }}" href="{{ route('admin.delivery-fees.index') }}">
                            <i class="fas fa-fw fa-truck me-2"></i>
                            Frais de livraison
                            </a>
                        </li>
                </ul>
            </div>
        </div>

        <!-- Content -->
        <div class="main-content">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 rounded shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Title -->
                    <h4 class="d-none d-md-inline-block text-primary mb-0">
                        @yield('page_title', 'Administration KLINKLIN')
                    </h4>
                    
                    <!-- Mobile Navigation Dropdown for most used links -->
                    <div class="dropdown d-md-none ms-auto me-2">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="mobileNavDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-compass me-1"></i> Navigation
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mobileNavDropdown">
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i> Tableau de bord</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.orders.index') }}"><i class="fas fa-shopping-cart me-2"></i> Commandes</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="fas fa-users me-2"></i> Utilisateurs</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('admin.configuration.index') }}"><i class="fas fa-cogs me-2"></i> Configuration</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.delivery-fees.index') }}"><i class="fas fa-truck me-2"></i> Frais de livraison</a></li>
                        </ul>
                    </div>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Notifications -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                @php
                                    $openTicketsCount = App\Models\SupportTicket::whereIn('status', ['open', 'in_progress'])->count();
                                @endphp
                                @if($openTicketsCount > 0)
                                    <span class="badge bg-danger badge-counter">{{ $openTicketsCount }}</span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in">
                                <h6 class="dropdown-header">Centre de notifications</h6>
                                @php
                                    $recentTickets = App\Models\SupportTicket::whereIn('status', ['open', 'in_progress'])
                                        ->orderBy('created_at', 'desc')
                                        ->limit(5)
                                        ->get();
                                @endphp
                                
                                @if($recentTickets->count() > 0)
                                    @foreach($recentTickets as $ticket)
                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.support.show', $ticket->id) }}">
                                            <div class="me-3">
                                                <div class="icon-circle bg-primary">
                                                    <i class="fas fa-headset text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="small text-gray-500">{{ $ticket->created_at->format('d/m/Y H:i') }}</div>
                                                <span class="font-weight-bold">{{ Str::limit($ticket->subject, 30) }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-center small text-gray-500" href="{{ route('admin.support.index') }}">Voir tous les tickets</a>
                                @else
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="small text-center text-muted">Aucun ticket en attente</div>
                                    </a>
                                @endif
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- User Information -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <span class="me-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
                                <img class="img-profile rounded-circle" width="32" height="32" 
                                    src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(auth()->user()->email))) }}?s=80&d=mp">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                                    Profil
                                </a>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-home fa-sm fa-fw me-2 text-gray-400"></i>
                                    Retour au site
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Begin Page Content -->
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        // Suppression de l'initialisation automatique pour éviter les conflits
        // Chaque page initialisera ses propres DataTables
        
        // Gestion du toggle de la sidebar pour les appareils mobiles
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggler = document.getElementById('sidebarToggler');
            const sidebarToggleTop = document.getElementById('sidebarToggleTop');
            const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const adminBody = document.getElementById('admin-body');
            
            function toggleSidebar() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
                
                // Empêcher le défilement du body quand la sidebar est ouverte
                if (sidebar.classList.contains('show')) {
                    adminBody.style.overflow = 'hidden';
                } else {
                    adminBody.style.overflow = '';
                }
            }
            
            if (sidebarToggler) {
                sidebarToggler.addEventListener('click', toggleSidebar);
            }
            
            if (sidebarToggleTop) {
                sidebarToggleTop.addEventListener('click', toggleSidebar);
            }
            
            if (sidebarCloseBtn) {
                sidebarCloseBtn.addEventListener('click', toggleSidebar);
            }
            
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', toggleSidebar);
            }
            
            // Fermer le menu lors d'un clic sur un lien dans la sidebar (pour mobile)
            const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992 && sidebar.classList.contains('show')) {
                        toggleSidebar();
                    }
                });
            });
            
            // Ajuster le comportement lors du redimensionnement
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992 && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    adminBody.style.overflow = '';
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html> 