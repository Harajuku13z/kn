<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'KLIN KLIN') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS and Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    @stack('styles')
    
    <style>
        :root {
            --sidebar-bg: #2a0e42;
            --primary-purple: #4c2975; 
            --primary-purple-darker: #3b1f59;
            --secondary-orange: #f26d50;
            --secondary-orange-darker: #d95f42;
            --light-purple-bg: #f2eef5; 
            --light-grey-bg: #f8f9fa; 
            --text-color: #333;
            --heading-color: #2c3e50; 
            --border-color: #dee2e6;
            
            --sidebar-link-color: #ffffff;
            --sidebar-link-active-bg: #dbffef;
            --sidebar-link-active-color: #3d735b;
            --sidebar-user-initial-bg: #dbffef;
            --sidebar-user-initial-color: #3d735b;
            --main-content-bg: #f8f9fa;
            --main-profile-icon-bg: #dbffef;
            --main-profile-icon-color: #3d735b;

            --card-plan-bg: #f2eef5;
            --card-plan-icon-color: #46186f;
            --card-plan-btn-bg: #46186f;
            --card-plan-btn-color: #ffffff;

            --card-orders-bg: #edfff7;
            --card-orders-icon-color: #3d735b;
            --card-orders-btn-bg: #89ffcb;
            --card-orders-btn-color: #2a0e42;

            --card-history-bg: #fffce7;
            --card-history-icon-color: #fbe133;
            --card-history-btn-bg: #fbe133;
            --card-history-btn-color: #000000;

            --card-activities-bg: #feefed;
            --card-activities-icon-color: #f25f4d;
            --card-activities-btn-bg: #f25f4d;
            --card-activities-btn-color: #ffffff;
            
            --accent-color-1: #f2eef5; /* light purple */
            --accent-color-2: #89ffcb; /* mint green */
            --accent-color-3: #fbe133; /* yellow */
            --accent-color-4: #f25f4d; /* coral red */
            
            --button-light-border-radius: 6px; 
            --button-full-border-radius: 25px; 
        }

        body {
            font-family: 'Montserrat', sans-serif; 
            background-color: var(--main-content-bg);
            color: var(--text-color);
            margin: 0;
            display: flex; 
        }

        /* Mobile Navbar */
        .mobile-navbar {
            background-color: #fff;
            padding: 0.5rem 0.75rem;
            margin: 0.75rem 1rem;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            height: auto;
        }

        .mobile-navbar .container-fluid {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .mobile-logo {
            height: 40px;
            max-width: 100%;
            object-fit: contain;
        }

        .user-avatar-mobile {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--sidebar-user-initial-bg);
            color: var(--sidebar-user-initial-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            border: 1px solid #e9ecef;
        }

        .mobile-navbar .btn-link {
            color: var(--text-color);
            padding: 0.25rem;
            line-height: 1;
        }

        .mobile-navbar .btn-link i {
            font-size: 1.3rem;
        }

        .mobile-navbar .navbar-brand {
            padding: 0;
            margin-right: 0;
            display: flex;
            align-items: center;
        }

        .mobile-navbar .badge {
            position: absolute;
            top: -4px;
            right: -4px;
        }

        /* Mobile Bottom Navbar */
        .mobile-bottom-navbar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 65px;
            background-color: var(--sidebar-bg);
            display: flex;
            justify-content: space-around;
            align-items: center;
            z-index: 1030;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            padding-bottom: env(safe-area-inset-bottom, 0);
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #fff;
            text-decoration: none;
            padding: 8px 0;
            font-size: 0.75rem;
            transition: all 0.2s ease;
            width: 20%;
            text-align: center;
        }

        .bottom-nav-item i {
            font-size: 1.4rem;
            margin-bottom: 4px;
        }

        .bottom-nav-item.active {
            color: var(--sidebar-link-active-bg);
        }

        .bottom-nav-item:hover, 
        .bottom-nav-item:focus {
            color: var(--sidebar-link-active-bg);
            text-decoration: none;
        }

        /* Mobile Menu Offcanvas */
        .offcanvas-bottom {
            height: auto;
            max-height: 70vh;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .offcanvas-header {
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
        }

        .offcanvas-header .offcanvas-title {
            color: var(--primary-purple);
            font-weight: 600;
        }

        .mobile-menu-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            padding: 1rem;
        }

        .mobile-menu-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--text-color);
            background-color: var(--light-grey-bg);
            border-radius: 12px;
            padding: 1.25rem 1rem;
            transition: all 0.2s ease;
            text-align: center;
            height: 100%;
        }

        .mobile-menu-item i {
            font-size: 1.8rem;
            margin-bottom: 0.75rem;
            color: var(--primary-purple);
        }

        .mobile-menu-item:hover,
        .mobile-menu-item:focus {
            background-color: var(--light-purple-bg);
            color: var(--text-color);
            text-decoration: none;
        }

        .btn-menu-logout {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            background: none;
            border: none;
            padding: 0;
            font: inherit;
            cursor: pointer;
            outline: inherit;
            color: inherit;
        }

        .mobile-navbar .navbar-brand img {
            max-height: 40px;
        }
        .mobile-navbar .navbar-toggler {
            border-color: rgba(255,255,255,0.5);
        }
        .mobile-navbar .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-link-color);
            height: 100vh; 
            position: fixed; 
            top: 0;
            left: 0;
            z-index: 1030; 
            padding: 1rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease-in-out;
            overflow-y: auto;
        }

        .sidebar-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .sidebar-logo a {
            display: inline-block;
            text-decoration: none;
        }

        .sidebar-logo img {
            max-width: 120px;
            margin-bottom: 1rem;
            transition: transform 0.2s ease;
        }

        .sidebar-logo img:hover {
            transform: scale(1.05);
        }

        .sidebar .nav-link {
            color: var(--sidebar-link-color);
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: var(--button-light-border-radius); 
            font-size: 0.95rem;
            font-weight: 500; 
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link.active { 
            background-color: var(--sidebar-link-active-bg);
            color: var(--sidebar-link-active-color);
            font-weight: 600; 
            border-radius: var(--button-full-border-radius); 
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
        }

        .sidebar hr {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        
        .user-avatar-initial {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--sidebar-user-initial-bg);
            color: var(--sidebar-user-initial-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600; 
            font-size: 1.2rem; 
        }

        .user-section .user-name {
            color: var(--sidebar-link-color); 
            font-size: 0.9rem;
            font-weight: 600;
        }

        .user-section .user-email {
            color: var(--sidebar-user-email-color, #b3aec5); 
            font-size: 0.8rem;
            font-weight: 400;
        }

        .btn-logout {
            background-color: transparent;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: var(--sidebar-link-color);
            text-align: left;
            font-weight: 500;
            border-radius: var(--button-light-border-radius);
        }
        .btn-logout:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--sidebar-link-color);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px; 
            flex-grow: 1; 
            padding: 2.5rem 3rem; 
            overflow-y: auto; 
            height: 100vh; 
            position: relative;
            min-height: 300px;
        }
        
        .greeting-header { 
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .main-profile-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: var(--main-profile-icon-bg);
            color: var(--main-profile-icon-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem; 
            flex-shrink: 0; 
            margin-bottom: 0.75rem;
        }

        .main-content h1 {
            color: var(--heading-color);
            font-weight: 700; 
            font-size: 1.75rem; 
        }
        .main-content h2 {
            font-weight: 700;
            color: var(--primary-purple);
            margin-bottom: 0.75rem;
        }
        .main-content .lead {
            font-weight: 400; 
        }

        .wave {
            display: inline-block;
            animation: wave-animation 2.5s infinite;
            transform-origin: 70% 70%;
        }

        @keyframes wave-animation {
            0% { transform: rotate( 0.0deg) } 10% { transform: rotate(14.0deg) }
            20% { transform: rotate(-8.0deg) } 30% { transform: rotate(14.0deg) }
            40% { transform: rotate(-4.0deg) } 50% { transform: rotate(10.0deg) }
            60% { transform: rotate( 0.0deg) } 100% { transform: rotate( 0.0deg) }
        }

        /* Card styles */
        .custom-card {
            border: none;
            border-radius: 12px;
            box-shadow: none; 
            border: 1px solid #e9ecef;
            padding: 1.5rem; 
            height: 100%;
            min-height: 300px;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease;
        }
        
        .custom-card:hover {
            transform: translateY(-4px);
            box-shadow: none;
        }

        .custom-card .card-icon-wrapper {
            font-size: 2.5rem; 
            margin-bottom: 0.75rem; 
        }
        
        .custom-card .card-body-content {
          flex-grow: 1; 
        }

        .custom-card .card-title {
            font-weight: 600; 
            margin-bottom: 0.5rem; 
            display: block; 
            font-size: 1.4rem;
        }

        .custom-card .card-text {
            font-size: 1rem;
            color: #555;
            margin-bottom: 1.5rem; 
            display: block; 
            font-weight: 400; 
        }
        
        /* Card specific styles */
        .card-plan { background-color: var(--card-plan-bg); }
        .card-plan .card-icon-wrapper { color: var(--card-plan-icon-color); }
        .card-plan .btn-primary-custom { background-color: var(--card-plan-btn-bg); color: var(--card-plan-btn-color); }
        .card-plan .btn-primary-custom:hover { background-color: #381358; }

        .card-orders { background-color: var(--card-orders-bg); }
        .card-orders .card-icon-wrapper { color: var(--card-orders-icon-color); }
        .card-orders .btn-primary-custom { background-color: var(--card-orders-btn-bg); color: var(--card-orders-btn-color); }
        .card-orders .btn-primary-custom:hover { background-color: #70e0b0; }

        .card-history { background-color: var(--card-history-bg); }
        .card-history .card-icon-wrapper { color: var(--card-history-icon-color); }
        .card-history .btn-primary-custom { background-color: var(--card-history-btn-bg); color: var(--card-history-btn-color); }
        .card-history .btn-primary-custom:hover { background-color: #e9d02a; }

        .card-activities { background-color: var(--card-activities-bg); }
        .card-activities .card-icon-wrapper { color: var(--card-activities-icon-color); }
        .card-activities .btn-primary-custom { background-color: var(--card-activities-btn-bg); color: var(--card-activities-btn-color); }
        .card-activities .btn-primary-custom:hover { background-color: #e04a38; }

        /* Standard card and styling */
        .card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: none;
            border: 1px solid #e9ecef;
            transition: transform 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-4px);
            box-shadow: none;
        }
        
        .card-header {
            padding: 1rem 1.25rem;
        }
        
        .card-header h5 {
            font-weight: 600;
        }
        
        .card-header.bg-primary {
            background-color: var(--primary-purple) !important;
        }
        
        .card-header.bg-info {
            background-color: #17a2b8 !important;
        }

        /* Button styles */
        .btn-primary-custom { 
            background-color: var(--primary-purple);
            color: white;
            border-radius: 50px;
            border: none;
            padding: 0.6rem 1.75rem;
            font-weight: 500;
            transition: background-color 0.2s ease;
            align-self: flex-start; 
            font-size: 0.9rem; 
        }
        
        .btn-primary-custom:hover {
            background-color: var(--primary-purple-darker);
            color: white;
        }
        
        .btn-secondary-orange {
            background-color: var(--secondary-orange); 
            border-color: var(--secondary-orange); 
            color: white;
            padding: 0.75rem 1.5rem; 
            border-radius: var(--button-light-border-radius); 
            font-weight: 500;
        }
        
        .btn-secondary-orange:hover { 
            background-color: var(--secondary-orange-darker); 
            border-color: var(--secondary-orange-darker); 
            color: white; 
        }
        
        .btn-light-purple {
            background-color: var(--light-purple-bg); 
            border-color: var(--light-purple-bg); 
            color: var(--primary-purple);
            padding: 0.5rem 1rem; 
            border-radius: var(--button-light-border-radius); 
            font-weight: 500;
        }
        
        .btn-light-purple:hover { 
            background-color: #dcd3e8; 
            color: var(--primary-purple);
        }

        /* Dashboard content specific styles */
        .dashboard-content {
            padding-bottom: 2rem;
        }
        
        .dashboard-content h2 {
            color: var(--primary-purple);
            font-weight: 600;
        }
        
        /* Form controls */
        .form-control, .form-select {
            border-radius: var(--button-light-border-radius); 
            padding: 0.75rem 1rem;
            font-size: 0.95rem; 
            border-color: #ced4da;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 0.2rem rgba(76, 41, 117, 0.25);
        }
        
        .form-label {
            font-weight: 500; 
            margin-bottom: 0.3rem; 
            font-size: 0.9rem;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-purple);
            border-color: var(--primary-purple);
        }

        /* Additional styles specific to dashboard pages */
        @yield('dashboard-styles')

        /* AJAX loading overlay */
        .ajax-loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .ajax-spinner {
            border: 5px solid rgba(76, 41, 117, 0.3);
            border-radius: 50%;
            border-top: 5px solid var(--primary-purple);
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive adjustments */
        @media (max-width: 991.98px) { 
            body {
                display: block; 
                padding-bottom: 65px; /* Space for bottom navbar */
                background-color: #f5f5fa;
            }
            
            .sidebar {
                display: none; /* Hide sidebar on mobile since we have bottom nav */
            }
            
            .main-content {
                margin-left: 0; 
                padding: 0 1rem 1rem;
                padding-bottom: calc(1rem + 65px); /* Bottom navbar height + padding */
                height: auto; 
                min-height: calc(100vh - 65px); /* Only accounting for bottom navbar */
                background-color: #f5f5fa;
            }
            
            .dashboard-content {
                margin-top: 0.5rem; /* Add space after the header */
            }
            
            .custom-card {
                 min-height: auto;
            }
            
            /* Adjust floating notifications container to not overlap with bottom navbar */
            .floating-notifications-container.position-fixed {
                bottom: 75px !important;
            }
        }
        
        @media (max-width: 575.98px) { 
            .main-content {
                padding: 0 0.75rem 0.75rem;
                padding-bottom: calc(0.75rem + 65px);
            }
            
            .main-content h1 {
                font-size: 1.5rem; 
            }
            
            .main-profile-icon-wrapper {
                width: 50px;
                height: 50px;
                font-size: 1.8rem;
            }
            
            .custom-card .card-title { 
                font-size: 1.25rem;
            }
            
            .custom-card .card-text { 
                font-size: 0.95rem;
            }
            
            /* Style des notifications flottantes sur mobile */
            .floating-notifications-container {
                width: 100% !important;
                padding: 0 !important;
                max-width: 100% !important;
                bottom: 75px !important; /* Adjusted to be above bottom navbar */
                top: auto !important;
                position: fixed !important;
                left: 0 !important;
                right: 0 !important;
            }
            
            .floating-notification {
                min-width: 100% !important;
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 auto !important;
                border-radius: 0 !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
            }
        }

        @media (min-width: 992px) { 
            .mobile-navbar,
            .mobile-bottom-navbar,
            #mobileMenuOffcanvas {
                display: none !important; 
            }
        }
        
        /* Style pour le conteneur de notifications flottantes */
        .floating-notifications-container {
            margin-top: 10px;
            z-index: 1080;
        }
        
        .notification-toast {
            max-width: 350px;
            border-left-width: 4px !important;
            position: relative;
            overflow: hidden;
        }
        
        .notification-toast .toast-header {
            border-bottom: none;
            background-color: transparent;
        }
        
        .notification-toast .toast-header i {
            font-size: 1.2rem;
        }
        
        .toast {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        /* Style pour que le bouton de fermeture soit plus visible */
        .toast .btn-close {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 0.5rem;
            border-radius: 50%;
            opacity: 0.8;
            z-index: 10;
            position: relative;
        }
        
        .toast .btn-close:hover {
            opacity: 1;
            background-color: white;
        }

        /* Indicateur de notification pour mobile */
        .notification-indicator {
            display: block;
            width: 8px;
            height: 8px;
            background-color: var(--bs-danger);
            border-radius: 50%;
            border: 1px solid white;
            transform: translate(50%, -50%);
        }
        
        .border-primary {
            border-color: var(--primary-purple) !important;
        }
        
        .border-success {
            border-color: var(--bs-success) !important;
        }
        
        .border-danger {
            border-color: var(--bs-danger) !important;
        }
        
        .border-warning {
            border-color: var(--bs-warning) !important;
        }
        
        .border-info {
            border-color: var(--bs-info) !important;
        }
        
        /* Notification badges */
        .notification-badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            min-width: 1.5rem;
        }
        
        /* Styles pour le menu déroulant Plus */
        #moreMenu .nav-link {
            padding-left: 1.5rem;
            font-size: 0.9rem;
            border-radius: 0;
            border-left: 2px solid transparent;
            transition: all 0.2s ease;
        }
        
        #moreMenu .nav-link:hover {
            border-left: 2px solid var(--klin-primary);
            background-color: rgba(var(--klin-primary-rgb), 0.05);
        }
        
        #moreMenu .nav-link.active {
            border-left: 2px solid var(--klin-primary);
            background-color: rgba(var(--klin-primary-rgb), 0.1);
            font-weight: 600;
        }
        
        #mobilePlusMenu .list-group-item {
            border-left: 0;
            border-right: 0;
            border-radius: 0;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
        }
        
        #mobilePlusMenu .list-group-item:hover {
            background-color: rgba(var(--klin-primary-rgb), 0.05);
        }
        
        #mobilePlusMenu .list-group-item:first-child {
            border-top: 0;
        }
        
        #mobilePlusMenu .list-group-item i {
            color: var(--klin-primary);
        }
    </style>
</head>
<body>
    <!-- Mobile Navbar -->
    <nav class="navbar mobile-navbar d-lg-none">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a href="{{ route('home') }}" class="navbar-brand">
                    <img src="{{ asset('img/logo.png') }}" alt="KLIN KLIN" class="mobile-logo" style="height: 40px;">
                </a>
            </div>
            <div class="d-flex align-items-center">
                <a href="{{ route('notifications.index') }}" class="btn btn-link position-relative me-2 dashboard-link notification-sidebar-link">
                    <i class="bi bi-bell-fill fs-5"></i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="badge bg-danger rounded-pill position-absolute top-0 start-75" style="font-size: 0.6rem;">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>
                @if(isset(Auth::user()->avatar_settings['avatar_type']) && Auth::user()->avatar_settings['avatar_type'] === 'gravatar')
                    <?php 
                        $style = Auth::user()->avatar_settings['gravatar_style'] ?? 'retro';
                        $gravatarUrl = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim(Auth::user()->email))) . '?s=40&d=' . $style;
                    ?>
                    <div class="user-avatar-mobile">
                        <img src="{{ $gravatarUrl }}" alt="{{ Auth::user()->name }}" class="img-fluid rounded-circle" style="width: 36px; height: 36px;">
                    </div>
                @else
                    <div class="user-avatar-mobile" style="background-color: #461871; color: white;">
                        <span>{{ Auth::user()->name[0] }}</span>
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Mobile Bottom Navbar -->
    <nav class="mobile-bottom-navbar d-lg-none">
        <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-ajax-link="true">
            <i class="bi bi-house-door"></i>
            <span>Accueil</span>
        </a>
        <a href="{{ route('profile.index') }}" class="bottom-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}" data-ajax-link="true">
            <i class="bi bi-person"></i>
            <span>Profil</span>
        </a>
        <a href="{{ route('orders.index') }}" class="bottom-nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }}" data-ajax-link="true">
            <i class="bi bi-box-seam"></i>
            <span>Commande</span>
        </a>
        <a href="#" class="bottom-nav-item" data-bs-toggle="offcanvas" data-bs-target="#mobileMenuOffcanvas">
            <i class="bi bi-grid"></i>
            <span>Menu</span>
        </a>
    </nav>

    <!-- Mobile Menu Offcanvas -->
    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="mobileMenuOffcanvas">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="mobile-menu-grid">
                <a href="{{ route('notifications.index') }}" class="mobile-menu-item position-relative" data-ajax-link="true">
                    <i class="bi bi-bell"></i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                    <span>Notifications</span>
                </a>
                <a href="{{ route('subscriptions.index') }}" class="mobile-menu-item" data-ajax-link="true">
                    <i class="bi bi-journal-text"></i>
                    <span>Mon abonnement</span>
                </a>
                <a href="#" class="mobile-menu-item" data-bs-toggle="collapse" data-bs-target="#mobilePlusMenu">
                    <i class="bi bi-three-dots"></i>
                    <span>Plus</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mobile-menu-item">
                    @csrf
                    <button type="submit" class="btn-menu-logout">
                        <i class="bi bi-box-arrow-left"></i>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
            
            <div class="collapse mt-3" id="mobilePlusMenu">
                <div class="list-group">
                    <a href="{{ route('addresses.index') }}" class="list-group-item list-group-item-action d-flex align-items-center" data-ajax-link="true">
                        <i class="bi bi-geo-alt-fill me-3"></i> Mes adresses
                    </a>
                    <a href="{{ route('support.index') }}" class="list-group-item list-group-item-action d-flex align-items-center" data-ajax-link="true">
                        <i class="bi bi-headset me-3"></i> Aide & Support
                    </a>
                    <a href="{{ route('activities.index') }}" class="list-group-item list-group-item-action d-flex align-items-center" data-ajax-link="true">
                        <i class="bi bi-activity me-3"></i> Activités
                    </a>
                    <a href="{{ route('history.index') }}" class="list-group-item list-group-item-action d-flex align-items-center" data-ajax-link="true">
                        <i class="bi bi-clock-history me-3"></i> Historique
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebarMenu">
        <div class="h-100 d-flex flex-column">
            <div class="sidebar-logo text-center">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="KLIN KLIN Logo" class="d-none d-lg-block">
                </a>
            </div>
            <div class="flex-grow-1 overflow-auto">
                <ul class="nav nav-pills nav-flush flex-column mb-auto">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link dashboard-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" aria-current="page" data-ajax-link="true">
                            <i class="bi bi-house-door"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('profile.index') }}" class="nav-link dashboard-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" data-ajax-link="true">
                            <i class="bi bi-person"></i>
                            <span>Mon profil</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('orders.index') }}" class="nav-link dashboard-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" data-ajax-link="true">
                            <i class="bi bi-box-seam"></i>
                            <span>Mes commandes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('subscriptions.index') }}" class="nav-link dashboard-link {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}" data-ajax-link="true">
                            <i class="bi bi-journal-text"></i>
                            <span>Mon abonnement</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('notifications.index') }}" class="nav-link dashboard-link notification-sidebar-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" data-ajax-link="true">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-bell me-2"></i> Notifications
                                </div>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <span class="notification-badge badge bg-danger rounded-pill">{{ Auth::user()->unreadNotifications->count() }}</span>
                                @endif
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link dashboard-link" data-bs-toggle="collapse" data-bs-target="#moreMenu" aria-expanded="{{ request()->routeIs('addresses.*') || request()->routeIs('support.*') || request()->routeIs('activities.*') || request()->routeIs('history.*') ? 'true' : 'false' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-three-dots me-2"></i> Plus
                                </div>
                                <i class="bi bi-chevron-down small"></i>
                            </div>
                        </a>
                        <div class="collapse {{ request()->routeIs('addresses.*') || request()->routeIs('support.*') || request()->routeIs('activities.*') || request()->routeIs('history.*') ? 'show' : '' }}" id="moreMenu">
                            <ul class="nav nav-pills nav-flush flex-column ms-3 mt-2">
                                <li class="nav-item">
                                    <a href="{{ route('addresses.index') }}" class="nav-link dashboard-link {{ request()->routeIs('addresses.*') ? 'active' : '' }}" data-ajax-link="true">
                                        <i class="bi bi-geo-alt-fill me-2"></i> Mes adresses
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('support.index') }}" class="nav-link dashboard-link {{ request()->routeIs('support.*') ? 'active' : '' }}" data-ajax-link="true">
                                        <i class="bi bi-headset me-2"></i> Aide & Support
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('activities.index') }}" class="nav-link dashboard-link {{ request()->routeIs('activities.*') ? 'active' : '' }}" data-ajax-link="true">
                                        <i class="bi bi-activity me-2"></i> Activités
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('history.index') }}" class="nav-link dashboard-link {{ request()->routeIs('history.*') ? 'active' : '' }}" data-ajax-link="true">
                                        <i class="bi bi-clock-history me-2"></i> Historique
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="user-section mt-3"> 
                <hr>
                <div class="d-flex align-items-center mb-2">
                    <div class="user-avatar-initial me-2" id="sidebar-user-avatar" style="background-color: #461871; color: white;">
                        @if(isset(Auth::user()->avatar_settings['avatar_type']) && Auth::user()->avatar_settings['avatar_type'] === 'gravatar')
                            <?php 
                                $style = Auth::user()->avatar_settings['gravatar_style'] ?? 'retro';
                                $gravatarUrl = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim(Auth::user()->email))) . '?s=40&d=' . $style;
                            ?>
                            <img src="{{ $gravatarUrl }}" alt="{{ Auth::user()->name }}" class="img-fluid rounded-circle" style="width: 40px; height: 40px;">
                        @else
                        {{ Auth::user()->name[0] }}
                        @endif
                    </div>
                    <div>
                        <small class="d-block fw-bold user-name">{{ Auth::user()->name }}</small>
                        <small class="user-email">{{ Auth::user()->email }}</small>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-logout w-100">
                        <i class="bi bi-box-arrow-left me-2"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content" id="ajax-dashboard-content">
        @yield('content')
    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Conteneur des notifications flottantes -->
    <div class="floating-notifications-container position-fixed p-3" style="z-index: 1070; max-width: 400px; right: 0; top: 0;">
        <!-- Les notifications seront ajoutées ici dynamiquement -->
    </div>
    
    <!-- Custom JS -->
    @stack('scripts')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fix for the "Plus" menu collapse functionality
            const moreMenuToggle = document.querySelector('[data-bs-toggle="collapse"][data-bs-target="#moreMenu"]');
            const moreMenuMobileToggle = document.querySelector('[data-bs-toggle="collapse"][data-bs-target="#mobilePlusMenu"]');
            const moreMenu = document.getElementById('moreMenu');
            const mobilePlusMenu = document.getElementById('mobilePlusMenu');
            
            // Initialize Bootstrap collapse for the "Plus" menu
            if (moreMenu) {
                const bsMoreMenu = new bootstrap.Collapse(moreMenu, {
                    toggle: false
                });
                
                // Toggle the menu when clicked
                moreMenuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    bsMoreMenu.toggle();
                    
                    // Toggle the chevron icon
                    const chevron = this.querySelector('.bi-chevron-down');
                    if (chevron) {
                        chevron.classList.toggle('bi-chevron-down');
                        chevron.classList.toggle('bi-chevron-up');
                    }
                });
            }
            
            // Initialize Bootstrap collapse for the mobile "Plus" menu
            if (mobilePlusMenu) {
                const bsMobilePlusMenu = new bootstrap.Collapse(mobilePlusMenu, {
                    toggle: false
                });
                
                // Toggle the menu when clicked
                if (moreMenuMobileToggle) {
                    moreMenuMobileToggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        bsMobilePlusMenu.toggle();
                    });
                }
            }
            
            /* La fonction updateProfileImages a été retirée car le système de photo de profil n'est plus utilisé */
            
            // Fonction pour mettre à jour l'avatar de l'utilisateur dans la barre latérale
            window.updateUserAvatar = function(avatarSettings) {
                const sidebarAvatar = document.getElementById('sidebar-user-avatar');
                if (!sidebarAvatar) return;
                
                // Appliquer le style de fond violet pour les initiales
                sidebarAvatar.style.backgroundColor = '#461871';
                sidebarAvatar.style.color = 'white';
                
                // Vider le contenu actuel
                sidebarAvatar.innerHTML = '';
                
                if (avatarSettings && avatarSettings.avatar_type === 'gravatar') {
                    const style = avatarSettings.gravatar_style || 'retro';
                    const email = '{{ Auth::user()->email }}';
                    const emailMd5 = '{{ md5(strtolower(trim(Auth::user()->email))) }}';
                    const gravatarUrl = 'https://www.gravatar.com/avatar/' + emailMd5 + '?s=40&d=' + style;
                    
                    const img = document.createElement('img');
                    img.src = gravatarUrl;
                    img.alt = '{{ Auth::user()->name }}';
                    img.className = 'img-fluid rounded-circle';
                    img.style = 'width: 40px; height: 40px;';
                    
                    sidebarAvatar.appendChild(img);
                } else {
                    // Mode initiale (par défaut)
                    sidebarAvatar.textContent = '{{ Auth::user()->name[0] }}';
                }
            };
            
            // Fonction pour afficher une notification système
            function showNotification(options) {
                // Permettre d'utiliser soit un objet de configuration, soit des paramètres individuels
                let title, message, type = 'primary', icon = 'bell-fill', autoHide = true;
                
                if (typeof options === 'object') {
                    // Format objet: {title, message, type, icon, autoHide}
                    title = options.title || 'Notification';
                    message = options.message || '';
                    type = options.type || 'primary';
                    icon = options.icon || 'bell-fill';
                    autoHide = options.autoHide !== undefined ? options.autoHide : true;
                } else {
                    // Format ancien: showNotification(title, message, type, icon, autoHide)
                    title = options;
                    message = arguments[1] || '';
                    type = arguments[2] || 'primary';
                    icon = arguments[3] || 'bell-fill';
                    autoHide = arguments[4] !== undefined ? arguments[4] : true;
                }
                
                const container = document.querySelector('.floating-notifications-container');
                
                // Créer la notification
                const toast = document.createElement('div');
                toast.className = `