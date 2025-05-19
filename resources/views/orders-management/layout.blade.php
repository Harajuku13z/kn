<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Système de Gestion des Commandes')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --app-primary-color: #4A148C; /* Violet principal */
            --app-secondary-color: #6c757d; 
            --app-cancel-color: #FF7043;   /* Orange/Corail */
            --app-light-bg: #f8f9fa;      
            --app-progress-inactive: #e0e0e0;
            --app-progress-text-inactive: #aaa;
        }

        body {
            background-color: #f4f5f7; 
        }

        .container-bootstrap {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            padding: 2rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .main-title {
            color: var(--app-primary-color);
        }

        .btn-primary-custom {
            background-color: var(--app-primary-color);
            border-color: var(--app-primary-color);
            color: white;
        }
        .btn-primary-custom:hover {
            background-color: #38006B; 
            border-color: #38006B;
            color: white;
        }

        .btn-secondary-custom {
            background-color: #f0f0f0; 
            border-color: #ddd;
            color: #333;
        }
        .btn-secondary-custom:hover {
            background-color: #e0e0e0;
        }

        .btn-cancel-custom {
            background-color: var(--app-cancel-color);
            border-color: var(--app-cancel-color);
            color: white;
        }
        .btn-cancel-custom:hover {
            background-color: #F4511E; 
            border-color: #F4511E;
            color: white;
        }

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .form-step {
            display: none; 
        }
        .form-step.active {
            display: block;
            animation: fadeIn 0.5s; 
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .progress-bar-custom .step-custom {
            flex-basis: 100px; 
            flex-shrink: 0;
            color: var(--app-progress-text-inactive);
            font-size: 0.85em;
        }

        .progress-bar-custom .step-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: var(--app-progress-inactive);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 8px auto;
            border: 2px solid var(--app-progress-inactive);
            font-weight: bold;
        }

        .progress-bar-custom .step-custom.active .step-icon {
            background-color: var(--app-primary-color);
            border-color: var(--app-primary-color);
        }
        .progress-bar-custom .step-custom.active .step-text {
            color: var(--app-primary-color);
            font-weight: bold;
        }

        .progress-bar-custom .step-custom.completed .step-icon {
            background-color: var(--app-primary-color); 
            border-color: var(--app-primary-color);
            color: white;
        }
        .progress-bar-custom .step-custom.completed .step-text {
            color: var(--app-primary-color); 
        }

        .progress-bar-custom .progress-line-custom {
            height: 4px;
            background-color: var(--app-progress-inactive);
            margin-top: 15px; 
        }

        .progress-bar-custom .progress-line-custom.active {
            background-color: var(--app-primary-color);
        }
    </style>

    @yield('styles')
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow-sm mb-4 mt-3">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('orders-management.index') }}">
                    <span class="text-primary fw-bold">Gestion des Commandes</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('orders-management.index') ? 'active' : '' }}" href="{{ route('orders-management.index') }}">
                                <i class="bi bi-list-check"></i> Liste des commandes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('orders-management.create') ? 'active' : '' }}" href="{{ route('orders-management.create') }}">
                                <i class="bi bi-plus-circle"></i> Nouvelle commande
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @yield('scripts')
</body>
</html> 