<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paramètres des bons de livraison automatiques
    |--------------------------------------------------------------------------
    |
    | Configuration des règles d'attribution automatique des bons de livraison gratuite
    |
    */

    // Première commande
    'first_order_enabled' => env('VOUCHER_FIRST_ORDER_ENABLED', false),
    'first_order_deliveries' => env('VOUCHER_FIRST_ORDER_DELIVERIES', 1),
    
    // Nième commande (fidélité)
    'nth_order_enabled' => env('VOUCHER_NTH_ORDER_ENABLED', false),
    'nth_order_count' => env('VOUCHER_NTH_ORDER_COUNT', 10),
    'nth_order_deliveries' => env('VOUCHER_NTH_ORDER_DELIVERIES', 1),
    
    // Période spéciale
    'period_enabled' => env('VOUCHER_PERIOD_ENABLED', false),
    'period_start' => env('VOUCHER_PERIOD_START', null),
    'period_end' => env('VOUCHER_PERIOD_END', null),
    'period_deliveries' => env('VOUCHER_PERIOD_DELIVERIES', 1),
]; 