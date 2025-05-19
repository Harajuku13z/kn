@extends('layouts.admin')

@section('title', 'Configuration - KLINKLIN Admin')

@section('page_title')
<div class="d-sm-flex align-items-center justify-content-between">
    <h1 class="h3 mb-0 text-gray-800">Configuration du système</h1>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Paramètres de l'application</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-left-primary shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">
                                        <i class="fas fa-tags me-2"></i> Prix des services
                                    </h5>
                                    <p class="card-text">
                                        Configurez les prix des services de lavage et de pressing.
                                    </p>
                                    <a href="{{ route('admin.prices.index') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-arrow-right me-1"></i> Gérer les prix
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-left-success shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-success">
                                        <i class="fas fa-truck me-2"></i> Frais de livraison
                                    </h5>
                                    <p class="card-text">
                                        Configurez les tarifs de livraison par quartier pour les commandes.
                                    </p>
                                    <a href="{{ route('admin.delivery-fees.index') }}" class="btn btn-success mt-2">
                                        <i class="fas fa-arrow-right me-1"></i> Gérer les frais de livraison
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Paramètres de facturation -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-left-warning shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-warning">
                                        <i class="fas fa-file-invoice me-2"></i> Paramètres de facturation
                                    </h5>
                                    <p class="card-text">
                                        Configurez les informations de société et mentions légales pour vos factures.
                                    </p>
                                    <a href="{{ route('admin.invoice-settings.index') }}" class="btn btn-warning mt-2">
                                        <i class="fas fa-arrow-right me-1"></i> Gérer les factures
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Paramètres généraux -->
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-left-info shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-info">
                                        <i class="fas fa-cogs me-2"></i> Paramètres généraux
                                    </h5>
                                    <p class="card-text">
                                        Configurez les paramètres généraux de l'application KLINKLIN.
                                    </p>
                                    <a href="#" class="btn btn-info mt-2 disabled">
                                        <i class="fas fa-arrow-right me-1"></i> Paramètres généraux
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 