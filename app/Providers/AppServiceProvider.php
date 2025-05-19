<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Utiliser le thème Bootstrap 4 pour la pagination
        Paginator::useBootstrap();
        
        // Ajouter une classe personnalisée à tous les paginateurs
        Paginator::defaultView('pagination::bootstrap-4');
        Paginator::defaultSimpleView('pagination::simple-bootstrap-4');
        
        // Configuration de Carbon pour utiliser le français
        \Carbon\Carbon::setLocale('fr');
        
        // Pour que les noms de mois soient en français 
        setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR', 'fr');
    }
}
