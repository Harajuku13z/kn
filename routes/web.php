<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\QuotaUsageController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\PriceConfigurationController;
use App\Models\PriceConfiguration;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ConfigurationController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\SubscriptionTypeController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TestMailController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\Admin\SupportController as AdminSupportController;
use App\Http\Controllers\Admin\AutoReplyTemplateController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\Admin\PressingController;
use App\Http\Controllers\Admin\PressingServiceController;

// Route principale
Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Routes d'authentification
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Routes d'inscription
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Routes de réinitialisation de mot de passe
/* Commenté car les contrôleurs n'existent pas encore
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
*/

// Route de test pour une vue de design
Route::get('/test', function () {
    return view('test'); // Assurez-vous d'avoir une vue 'test.blade.php' dans le dossier resources/views
})->name('test.design');

// Routes protégées par l'authentification
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(\App\Http\Middleware\RedirectAdminToDashboard::class);
    
    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Routes de modification du profil séparées
    Route::get('/profile/edit/name', [ProfileController::class, 'editName'])->name('profile.edit.name');
    Route::get('/profile/edit/email', [ProfileController::class, 'editEmail'])->name('profile.edit.email');
    Route::get('/profile/edit/phone', [ProfileController::class, 'editPhone'])->name('profile.edit.phone');
    Route::get('/profile/edit/password', [ProfileController::class, 'editPassword'])->name('profile.edit.password');
    Route::get('/profile/edit/avatar', [ProfileController::class, 'editAvatar'])->name('profile.edit.avatar');
    Route::get('/profile/login-history', [ProfileController::class, 'loginHistory'])->name('profile.login-history');
    
    // Ajout de route pour assurer la compatibilité
    Route::redirect('/profile/edit', '/profile');
    // Définir explicitement la route profile.edit
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    
    // Historique des commandes
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::get('/history/{order}', [HistoryController::class, 'show'])->name('history.show');
    Route::get('/history/{order}/invoice', [HistoryController::class, 'invoice'])->name('history.invoice');
    
    // Activités
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    
    // Adresses
    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    Route::put('/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');
    
    // AJAX Adresses
    Route::get('/ajax/addresses/create', [AddressController::class, 'ajaxCreate'])->name('addresses.ajax.create');
    Route::post('/ajax/addresses', [AddressController::class, 'ajaxStore'])->name('addresses.ajax.store');
    Route::get('/ajax/addresses', [AddressController::class, 'getAddressesJson'])->name('addresses.ajax.list');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::match(['get', 'post'], '/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::match(['get', 'post'], '/notifications/{id}/read-ajax', [NotificationController::class, 'readAjax'])->name('notifications.read.ajax');
    Route::get('/ajax/notifications/latest', [NotificationController::class, 'getLatestAjax'])->name('notifications.latest.ajax');
    Route::match(['get', 'post'], '/ajax/notifications/{id}/dismiss', [NotificationController::class, 'dismissAjax'])->name('notifications.dismiss.ajax');
    
    // Abonnements et Quotas
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('/subscriptions/{subscription}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::put('/subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
    Route::get('/subscriptions/{subscription}/payment/success', [SubscriptionController::class, 'paymentSuccess'])->name('subscriptions.payment.success');
    Route::post('/subscriptions/{subscription}/confirm-payment', [SubscriptionController::class, 'confirmPayment'])->name('subscriptions.confirm-payment');
    Route::post('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::get('/subscriptions/create/payement/{subscriptionType}', [SubscriptionController::class, 'payementForm'])->name('subscriptions.payement.form');
    Route::post('/subscriptions/create/payement', [SubscriptionController::class, 'payementStore'])->name('subscriptions.payement.store');
    
    // Historique d'utilisation de quota
    Route::get('/quota-usages', [QuotaUsageController::class, 'index'])->name('quota-usages.index');
    Route::get('/quota-usages/{quotaUsage}', [QuotaUsageController::class, 'show'])->name('quota-usages.show');
    
    // Commandes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/orders/{order}/invoice', [HistoryController::class, 'invoice'])->name('orders.invoice');
    Route::get('/orders/{order}/invoice-stream', [HistoryController::class, 'streamInvoice'])->name('orders.invoice.stream');
    Route::get('/orders/{order}/invoice-download', [HistoryController::class, 'downloadInvoice'])->name('orders.invoice.download');
    Route::post('/orders/save-temp', [OrderController::class, 'saveTemp'])->name('orders.save_temp');
    Route::get('/orders/create/pressing', [OrderController::class, 'createPressing'])->name('orders.create.pressing');
    Route::get('/orders/create/pressing/{id}', [OrderController::class, 'showPressingDetails'])->name('orders.create.pressing.show');
    Route::post('/orders/create/pressing/services', [OrderController::class, 'selectPressingServices'])->name('orders.create.pressing.services');
    Route::get('/orders/create/quota', [OrderController::class, 'createQuota'])->name('orders.create.quota');
    Route::get('/orders/test-create', function () {
        return view('orders.test-create');
    })->name('orders.test.create');

    // Routes pour les commandes Pressing
    Route::get('/orders/pressing/{id}/services', [OrderController::class, 'getPressingServices'])->name('orders.pressing.services');
});

// Routes administrateur protégées par middleware admin
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Chiffre d'affaires
    Route::get('/revenue', [App\Http\Controllers\Admin\RevenueController::class, 'index'])->name('revenue.index');
    Route::get('/revenue/export', [App\Http\Controllers\Admin\RevenueController::class, 'export'])->name('revenue.export');
    
    // Gestion des quotas
    Route::resource('quotas', App\Http\Controllers\Admin\QuotaController::class);
    Route::get('/quotas/create/{userId}', [App\Http\Controllers\Admin\QuotaController::class, 'createForm'])->name('quotas.create.form');
    
    // Gestion des types d'abonnement
    Route::resource('subscription-types', SubscriptionTypeController::class);
    Route::get('subscription-types/{subscriptionType}/features', [SubscriptionTypeController::class, 'getFeatures'])->name('subscription-types.features');
    
    // Gestion des abonnements
    Route::resource('subscriptions', App\Http\Controllers\Admin\SubscriptionController::class);
    Route::post('/subscriptions/{subscription}/confirm-payment', [App\Http\Controllers\Admin\SubscriptionController::class, 'confirmPayment'])->name('subscriptions.confirm-payment');
    Route::post('/subscriptions/{subscription}/cancel', [App\Http\Controllers\Admin\SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    
    // Gestion des codes promo
    Route::resource('coupons', App\Http\Controllers\Admin\CouponController::class);
    
    // Gestion des bons de livraison
    Route::resource('delivery-vouchers', App\Http\Controllers\Admin\DeliveryVoucherController::class);
    
    // Gestion des articles
    Route::resource('articles', ArticleController::class);
    
    // Gestion des prix
    Route::resource('prices', PriceConfigurationController::class);
    
    // Gestion des commandes
    Route::resource('orders', AdminOrderController::class);
    Route::patch('/orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/send-email', [AdminOrderController::class, 'sendEmail'])->name('orders.send-email');
    Route::get('/orders/{order}/download-invoice', [AdminOrderController::class, 'downloadInvoice'])->name('orders.download-invoice');
    Route::post('/orders/{order}/send-invoice', [AdminOrderController::class, 'sendInvoice'])->name('orders.send-invoice');
    Route::get('/orders/create/quota', [AdminOrderController::class, 'createQuota'])->name('orders.create.quota');
    Route::get('/orders/create/pressing', [AdminOrderController::class, 'createPressing'])->name('orders.create.pressing');
    
    // Nouvelles routes pour la création de commandes avec étapes
    Route::get('/orders/create/quota/select-user', [AdminOrderController::class, 'selectUserForQuota'])->name('orders.create.quota.select-user');
    Route::get('/orders/create/pressing/select-user', [AdminOrderController::class, 'selectUserForPressing'])->name('orders.create.pressing.select-user');
    Route::get('/orders/create/quota/{userId}', [AdminOrderController::class, 'createQuotaForm'])->name('orders.create.quota.form');
    Route::get('/orders/create/pressing/{userId}', [AdminOrderController::class, 'createPressingForm'])->name('orders.create.pressing.form');
    
    // Routes pour la gestion des adresses dans le flux de création de commande
    Route::get('/orders/address/{userId}/create', [AdminOrderController::class, 'createAddress'])->name('orders.address.create');
    Route::post('/orders/address/{userId}/store', [AdminOrderController::class, 'storeAddress'])->name('orders.address.store');
    
    // Gestion des utilisateurs
    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Gestion des frais de livraison
    Route::resource('delivery-fees', App\Http\Controllers\Admin\DeliveryFeeController::class);
    Route::post('/delivery-fees/bulk-update', [App\Http\Controllers\Admin\DeliveryFeeController::class, 'bulkUpdate'])->name('delivery-fees.bulk-update');
    Route::get('/delivery-fees/city/{city_id}', [App\Http\Controllers\Admin\DeliveryFeeController::class, 'getFeesForCity'])->name('delivery-fees.by-city');
    
    // Gestion des villes
    Route::resource('cities', App\Http\Controllers\Admin\CityController::class);
    Route::patch('/cities/{city}/toggle-status', [App\Http\Controllers\Admin\CityController::class, 'toggleStatus'])->name('cities.toggle-status');
    
    // Configuration
    Route::get('/configuration', [ConfigurationController::class, 'index'])->name('configuration.index');
    
    // Paramètres de facturation
    Route::get('/invoice-settings', [App\Http\Controllers\Admin\InvoiceSettingsController::class, 'index'])->name('invoice-settings.index');
    Route::post('/invoice-settings', [App\Http\Controllers\Admin\InvoiceSettingsController::class, 'update'])->name('invoice-settings.update');
    Route::get('/invoice-settings/preview', [App\Http\Controllers\Admin\InvoiceSettingsController::class, 'preview'])->name('invoice-settings.preview');
    
    // Gestion des pressings et services
    Route::resource('pressings', PressingController::class);
    Route::resource('pressing-services', PressingServiceController::class);

    // Routes pour les bons de livraison automatiques
    Route::get('automatic-vouchers', [App\Http\Controllers\Admin\AutomaticVoucherController::class, 'index'])
        ->name('automatic-vouchers.index');
    Route::put('automatic-vouchers', [App\Http\Controllers\Admin\AutomaticVoucherController::class, 'update'])
        ->name('automatic-vouchers.update');
});

// Routes pour le système d'aide et support côté admin
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin/support')->name('admin.support.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\SupportController::class, 'index'])->name('index');
    Route::get('/stats', [\App\Http\Controllers\Admin\SupportController::class, 'stats'])->name('stats');
    Route::get('/{id}', [\App\Http\Controllers\Admin\SupportController::class, 'show'])->name('show');
    Route::post('/{id}/reply', [\App\Http\Controllers\Admin\SupportController::class, 'reply'])->name('reply');
    Route::post('/{id}/status', [\App\Http\Controllers\Admin\SupportController::class, 'updateStatus'])->name('update-status');
    Route::post('/{id}/priority', [\App\Http\Controllers\Admin\SupportController::class, 'updatePriority'])->name('update-priority');
    Route::post('/{id}/category', [\App\Http\Controllers\Admin\SupportController::class, 'updateCategory'])->name('update-category');
    Route::delete('/{id}', [\App\Http\Controllers\Admin\SupportController::class, 'destroy'])->name('destroy');
    
    // Routes pour les modèles de réponses automatiques
    Route::prefix('templates')->name('templates.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AutoReplyTemplateController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\AutoReplyTemplateController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\Admin\AutoReplyTemplateController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [\App\Http\Controllers\Admin\AutoReplyTemplateController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Admin\AutoReplyTemplateController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\AutoReplyTemplateController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/get', [\App\Http\Controllers\Admin\AutoReplyTemplateController::class, 'getTemplate'])->name('get');
        Route::post('/{id}/toggle', [\App\Http\Controllers\Admin\AutoReplyTemplateController::class, 'toggleActive'])->name('toggle');
    });
});

// Routes pour la gestion des commandes (interface admin alternative)
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('orders-management')->name('orders-management.')->group(function () {
    Route::get('/', [AdminOrderController::class, 'index'])->name('index');
    Route::get('/create', [AdminOrderController::class, 'create'])->name('create');
    Route::post('/', [AdminOrderController::class, 'store'])->name('store');
    Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
    Route::get('/{order}/edit', [AdminOrderController::class, 'edit'])->name('edit');
    Route::put('/{order}', [AdminOrderController::class, 'update'])->name('update');
    Route::delete('/{order}', [AdminOrderController::class, 'destroy'])->name('destroy');
    
    // Routes spécifiques pour mise à jour du statut et envoi d'emails
    Route::patch('/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('update-status');
    Route::post('/{order}/send-email', [AdminOrderController::class, 'sendEmail'])->name('send-email');
});

// Routes Statiques - Pages d'information
Route::prefix('services')->group(function() {
    Route::get('/', function() { return view('pages.services.index'); })->name('services.index');
    Route::get('/lavage', function() { return view('pages.services.lavage'); })->name('services.lavage');
    Route::get('/repassage', function() { return view('pages.services.repassage'); })->name('services.repassage');
    Route::get('/pressing', function() { return view('pages.services.pressing'); })->name('services.pressing');
});

// Route Abonnements
Route::get('/abonnements', [AbonnementController::class, 'index'])->name('abonnements');

// Routes Aide
Route::prefix('aide')->group(function() {
    Route::get('/faq', function() { return view('pages.aide.faq'); })->name('aide.faq');
    Route::get('/conditions-generales', function() { return view('pages.aide.conditions-generales'); })->name('aide.conditions-generales');
    Route::get('/politique-confidentialite', function() { return view('pages.aide.politique-confidentialite'); })->name('aide.politique-confidentialite');
});

// Route Contact
Route::get('/contact', function() { 
    return view('pages.contact'); 
})->name('contact');
Route::post('/contact/submit', [ContactController::class, 'submit'])->name('contact.submit');

// Route Simulateur
Route::get('/simulateur', function() {
    $currentPrice = \App\Models\PriceConfiguration::getCurrentPrice();
    $pricePerKg = $currentPrice ? $currentPrice->price_per_kg : 500;
    return view('pages.simulateur', compact('currentPrice', 'pricePerKg'));
})->name('simulateur');

// Routes de vérification d'email
Route::get('/email/verify/{id}/{hash}', [RegisterController::class, 'verify'])->middleware(['signed'])->name('verification.verify');
Route::post('/email/verification-notification', [RegisterController::class, 'resend'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Route temporaire pour diagnostiquer les commandes
Route::get('/diagnostic-orders/{id?}', function ($id = null) {
    if ($id) {
        $order = App\Models\Order::find($id);
        
        if (!$order) {
            return response()->json(['error' => 'Commande non trouvée'], 404);
        }
        
        $orderItems = App\Models\OrderItem::where('order_id', $id)->get();
        $pickupAddress = null;
        $deliveryAddress = null;
        
        if ($order->pickup_address) {
            $pickupAddress = App\Models\Address::find($order->pickup_address);
        }
        
        if ($order->delivery_address) {
            $deliveryAddress = App\Models\Address::find($order->delivery_address);
        }
        
        return response()->json([
            'order' => $order,
            'items' => $orderItems,
            'pickup_address' => $pickupAddress,
            'delivery_address' => $deliveryAddress
        ]);
    } else {
        $orders = App\Models\Order::all();
        return response()->json(['orders' => $orders]);
    }
})->name('diagnostic.orders');

// Route temporaire pour diagnostiquer les dates des commandes
Route::get('/diagnostic-date/{id}', function ($id) {
    // Requête pour récupérer la date brute de la base de données
    $rawDateData = DB::table('orders')
        ->where('id', $id)
        ->select(['pickup_date', 'delivery_date'])
        ->first();
    
    // Requête pour récupérer l'objet complet
    $order = App\Models\Order::find($id);
    
    // Affichage des dates et formats
    return response()->json([
        // Données brutes de la DB
        'raw_data' => $rawDateData,
        
        // Données avec casting du model
        'pickup_date' => $order->pickup_date,
        'pickup_date_formatted' => $order->pickup_date ? $order->pickup_date->format('d/m/Y H:i:s') : null,
        'pickup_date_iso' => $order->pickup_date ? $order->pickup_date->toISOString() : null,
        
        'delivery_date' => $order->delivery_date,
        'delivery_date_formatted' => $order->delivery_date ? $order->delivery_date->format('d/m/Y H:i:s') : null,
        'delivery_date_iso' => $order->delivery_date ? $order->delivery_date->toISOString() : null,
        
        // Données de l'adresse
        'pickup_address_id' => $order->pickup_address,
        'pickup_address_exists' => DB::table('addresses')->where('id', $order->pickup_address)->exists(),
        'delivery_address_id' => $order->delivery_address,
        'delivery_address_exists' => DB::table('addresses')->where('id', $order->delivery_address)->exists(),
    ]);
})->name('diagnostic.dates');

// Test mail route
Route::get('/test-mail', [TestMailController::class, 'testMail'])->middleware(['auth']);

// Routes pour le système d'aide et support
Route::middleware(['auth'])->prefix('support')->name('support.')->group(function () {
    Route::get('/', [SupportController::class, 'index'])->name('index');
    Route::get('/faq', [SupportController::class, 'faq'])->name('faq');
    Route::get('/create', [SupportController::class, 'create'])->name('create');
    Route::post('/store', [SupportController::class, 'store'])->name('store');
    Route::get('/{id}', [SupportController::class, 'show'])->name('show');
    Route::post('/{id}/reply', [SupportController::class, 'reply'])->name('reply');
    Route::post('/{id}/close', [SupportController::class, 'close'])->name('close');
    Route::post('/{id}/reopen', [SupportController::class, 'reopen'])->name('reopen');
});
