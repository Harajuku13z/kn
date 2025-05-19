<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Article;
use App\Models\Address;
use App\Models\PriceConfiguration;
use App\Models\Quota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Notifications\OrderCreated;
use App\Events\OrderCreated as OrderCreatedEvent;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Créer une requête de base avec une limite sur le nombre de résultats
        $query = Order::where('user_id', Auth::id());
        
        // Filtrage par statut
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Eager load the address relationships
        $query->with(['pickupAddress', 'deliveryAddress']);
        
        // Limiter le nombre de commandes affichées et optimiser l'ordre
        $orders = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString(); // Conserver les paramètres de filtre dans la pagination
        
        // Renvoyer la vue avec les commandes
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Vérifier si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour créer une commande.');
        }

        // Afficher uniquement la page de sélection du type de commande
        return view('orders.create');
    }

    /**
     * Affiche le formulaire de création pour une commande de type pressing
     */
    public function createPressing()
    {
        // Vérifier si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour créer une commande.');
        }

        // Récupérer les pressings actifs
        $pressings = \App\Models\Pressing::where('is_active', true)->orderBy('name')->get();
        
        // Si aucun pressing actif n'est disponible
        if ($pressings->isEmpty()) {
            return redirect()->route('orders.create')
                ->with('error', 'Aucun pressing n\'est disponible pour le moment. Veuillez essayer plus tard.');
        }

        // Récupérer les adresses de l'utilisateur
        $addresses = Address::where('user_id', Auth::id())->get();
        
        // Si l'utilisateur n'a pas d'adresse, le rediriger pour en créer une
        if ($addresses->isEmpty()) {
            return redirect()->route('addresses.create')
                ->with('info', 'Vous devez créer au moins une adresse avant de passer commande.');
        }
        
        // Force reset of the step to 0 when on main pressing page
        $tempCart = [
            'step' => 0, // Commencer à l'étape 0 (sélection du pressing)
            'collection_address_id' => null,
            'collection_date' => date('Y-m-d'),
            'collection_time_slot' => null,
            'same_address_for_delivery' => false,
            'delivery_address_id' => null,
            'pressing_services' => [],
            'payment_method' => 'cash',
            'delivery_fee' => 0
        ];
        
        // Explicitly update the session
        session(['temp_order_cart' => $tempCart]);
        
        // Définir les créneaux horaires disponibles
        $timeSlots = [
            '08:00-10:00' => '08:00 - 10:00',
            '10:00-12:00' => '10:00 - 12:00',
            '14:00-16:00' => '14:00 - 16:00',
            '16:00-18:00' => '16:00 - 18:00'
        ];
        
        // Variables pour les services et catégories (initialement vides)
        $pressingServices = [];
        $serviceCategories = [];
        $pressingId = null;
        
        // Récupérer tous les frais de livraison par quartier
        $deliveryFees = \App\Models\DeliveryFee::where('is_active', true)->get();
        
        // Récupérer les codes promo disponibles
        $availableCoupons = \App\Models\Coupon::getAvailableForUser(Auth::id());
        
        // Récupérer le quota disponible de l'utilisateur
        $totalQuota = Auth::user()->getTotalAvailableQuota() ?? 0;
        
        return view('orders.create_pressing_new', compact(
            'pressings',
            'addresses',
            'tempCart',
            'timeSlots',
            'pressingServices',
            'serviceCategories',
            'pressingId',
            'deliveryFees',
            'availableCoupons',
            'totalQuota'
        ));
    }

    /**
     * Affiche les détails d'un pressing spécifique et ses services
     */
    public function showPressingDetails($id)
    {
        // Vérifier si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour créer une commande.');
        }
        
        // Récupérer le pressing demandé
        $pressing = \App\Models\Pressing::findOrFail($id);
        
        // Vérifier si le pressing est actif
        if (!$pressing->is_active) {
            return redirect()->route('orders.create.pressing')
                ->with('error', 'Ce pressing n\'est pas disponible pour le moment.');
        }
        
        // Récupérer les pressings actifs
        $pressings = \App\Models\Pressing::where('is_active', true)->orderBy('name')->get();
        
        // Récupérer les adresses de l'utilisateur
        $addresses = Address::where('user_id', Auth::id())->get();
        
        // Récupérer les données du panier temporaire
        $tempCart = session('temp_order_cart', []);
        
        // Mettre à jour le panier avec les informations du pressing
        $tempCart = array_merge([
            'step' => 1, // Passer à l'étape 1 (collecte) après sélection du pressing
            'collection_address_id' => null,
            'collection_date' => date('Y-m-d'),
            'collection_time_slot' => null,
            'same_address_for_delivery' => false,
            'delivery_address_id' => null,
            'pressing_services' => [],
            'payment_method' => 'cash',
            'delivery_fee' => 0
        ], $tempCart);
        
        // Sauvegarder en session
        session(['temp_order_cart' => $tempCart]);
        
        // Définir les créneaux horaires disponibles
        $timeSlots = [
            '08:00-10:00' => '08:00 - 10:00',
            '10:00-12:00' => '10:00 - 12:00',
            '14:00-16:00' => '14:00 - 16:00',
            '16:00-18:00' => '16:00 - 18:00'
        ];
        
        // Charger les services du pressing
        $pressingServices = $pressing->services()
            ->where('is_available', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();
            
        // Ajouter des attributs formatés pour l'affichage
        $pressingServices->each(function ($service) {
            $service->formatted_price = number_format($service->price, 0, ',', ' ') . ' FCFA';
            
            // Formatage du temps estimé
            if ($service->estimated_time) {
                if ($service->estimated_time < 24) {
                    $service->formatted_time = $service->estimated_time . ' heure(s)';
                } else {
                    $days = floor($service->estimated_time / 24);
                    $hours = $service->estimated_time % 24;
                    
                    if ($hours == 0) {
                        $service->formatted_time = $days . ' jour(s)';
                    } else {
                        $service->formatted_time = $days . ' jour(s) et ' . $hours . ' heure(s)';
                    }
                }
            } else {
                $service->formatted_time = null;
            }
        });
        
        // Extraction des catégories uniques des services
        $categories = $pressingServices->pluck('category')->unique()->filter()->values();
        $serviceCategories = collect($categories)->map(function ($name) {
            return [
                'name' => $name,
                'icon' => 'bi-tag'
            ];
        });
        
        // Définir l'ID du pressing
        $pressingId = $pressing->id;
        
        // Récupérer tous les frais de livraison par quartier
        $deliveryFees = \App\Models\DeliveryFee::where('is_active', true)->get();
        
        // Récupérer les codes promo disponibles
        $availableCoupons = \App\Models\Coupon::getAvailableForUser(Auth::id());
        
        // Récupérer le quota disponible de l'utilisateur
        $totalQuota = Auth::user()->getTotalAvailableQuota() ?? 0;
        
        return view('orders.create_pressing_new', compact(
            'pressings',
            'addresses', 
            'tempCart',
            'timeSlots',
            'pressingServices',
            'serviceCategories',
            'pressingId',
            'deliveryFees',
            'availableCoupons',
            'totalQuota'
        ));
    }

    /**
     * Traite la sélection des services et continue vers l'étape suivante
     */
    public function selectPressingServices(Request $request)
    {
        // Valider les données
        $validatedData = $request->validate([
            'pressing_id' => 'required|exists:pressings,id',
            'services' => 'required|array',
            'services.*' => 'integer|min:1',
            'special_instructions' => 'nullable|string|max:500',
        ]);
        
        // Récupérer le pressing
        $pressing = \App\Models\Pressing::findOrFail($validatedData['pressing_id']);
        
        // Récupérer les services sélectionnés
        $selectedServices = [];
        $totalAmount = 0;
        
        foreach ($validatedData['services'] as $serviceId => $quantity) {
            if ($quantity > 0) {
                $service = \App\Models\Service::findOrFail($serviceId);
                $selectedServices[$serviceId] = [
                    'name' => $service->name,
                    'price' => $service->price,
                    'quantity' => $quantity,
                    'subtotal' => $service->price * $quantity
                ];
                $totalAmount += $service->price * $quantity;
            }
        }
        
        // Stocker les informations dans la session
        session()->put('temp_order_cart', [
            'step' => 3,
            'order_type' => 'pressing',
            'pressing_id' => $pressing->id,
            'pressing_name' => $pressing->name,
            'selected_services' => $selectedServices,
            'total_amount' => $totalAmount,
            'special_instructions' => $validatedData['special_instructions'] ?? null,
        ]);
        
        // Rediriger vers le formulaire existant des adresses et dates
        return redirect()->route('orders.create.pressing')
            ->with('success', 'Services sélectionnés avec succès. Veuillez continuer avec les détails de livraison.');
    }
    
    /**
     * Affiche le formulaire de création pour une commande au kilo
     */
    public function createQuota()
    {
        // Vérifier si l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour créer une commande.');
        }

        // Récupérer tous les articles disponibles
        $articles = Article::all();
        
        // Récupérer les adresses de l'utilisateur
        $addresses = Address::where('user_id', Auth::id())->get();
        
        // Si l'utilisateur n'a pas d'adresse, le rediriger pour en créer une
        if ($addresses->isEmpty()) {
            return redirect()->route('addresses.create')
                ->with('info', 'Vous devez créer au moins une adresse avant de passer commande.');
        }
        
        // Récupérer le prix courant par kg
        $currentPrice = PriceConfiguration::getCurrentPrice();
        $laundryPricePerKg = $currentPrice ? $currentPrice->price_per_kg : 500;
        
        // Récupérer les informations de quota de l'utilisateur
        $user = Auth::user();
        $totalQuota = 0;
        $isPendingQuota = false;
        
        if ($user) {
            $totalQuota = $user->getTotalAvailableQuota();
            
            // Vérifier si l'utilisateur a des quotas en attente
            $paidQuota = $user->getPaidSubscriptionQuota();
            $pendingQuota = $user->getPendingSubscriptionQuota();
            $isPendingQuota = ($pendingQuota > 0 && $paidQuota < 1);
        }
        
        // Récupérer les données du panier temporaire (si présentes en session)
        $tempCart = session('temp_order_cart', [
            'step' => 1,
            'collection_address_id' => null,
            'collection_date' => date('Y-m-d'),
            'collection_time_slot' => null,
            'same_address_for_delivery' => false,
            'delivery_address_id' => null,
            'articles' => [],
            'payment_method' => 'cash'
        ]);
        
        // Définir les créneaux horaires disponibles
        $timeSlots = [
            '08:00-10:00' => '08:00 - 10:00',
            '10:00-12:00' => '10:00 - 12:00',
            '14:00-16:00' => '14:00 - 16:00',
            '16:00-18:00' => '16:00 - 18:00'
        ];
        
        // Récupérer les codes promo disponibles
        $availableCoupons = \App\Models\Coupon::getAvailableForUser(Auth::id());
        
        return view('orders.create_quota', compact(
            'articles', 
            'addresses', 
            'laundryPricePerKg', 
            'totalQuota', 
            'isPendingQuota',
            'tempCart',
            'timeSlots',
            'availableCoupons'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Valider les données de base
            $validatedData = $request->validate([
                'order_type' => 'required|in:kilogram,pressing',
                'pressing_id' => 'required_if:order_type,pressing|nullable|exists:pressings,id',
                'collection_address_id' => 'required|exists:addresses,id',
                'collection_date' => 'required|date|after_or_equal:today',
                'collection_time_slot' => 'required|string',
                'delivery_date' => 'required|date|after:collection_date',
                'delivery_time_slot' => 'required|string',
                'payment_method' => 'required|in:cash,mobile_money,quota',
                'delivery_voucher_code' => 'nullable|string',
                'use_delivery_voucher' => 'nullable|boolean',
            ]);
            
            // Valider l'adresse de livraison si "same_address_for_delivery" n'est pas coché
            if (!$request->has('same_address_for_delivery')) {
                $validatedData = $request->validate([
                    'delivery_address_id' => 'required|exists:addresses,id',
                ]);
            }
            
            // Validation spécifique selon le type de commande
            if ($request->order_type === 'kilogram') {
                // Valider qu'au moins un article est sélectionné pour la commande au kilo
                if (!$request->has('articles') || empty($request->articles)) {
                    throw new \Exception('Veuillez sélectionner au moins un article.');
                }
                
                // Vérifier qu'au moins un article a une quantité > 0
                $hasItems = false;
                foreach ($request->articles as $articleId => $data) {
                    $quantity = is_array($data) ? ($data['quantity'] ?? 0) : (int)$data;
                    if ($quantity > 0) {
                        $hasItems = true;
                        break;
                    }
                }
                
                if (!$hasItems) {
                    throw new \Exception('Veuillez sélectionner au moins un article.');
                }
            } else {
                // Valider qu'au moins un service de pressing est sélectionné
                if (!$request->has('pressing_services') || empty($request->pressing_services)) {
                    throw new \Exception('Veuillez sélectionner au moins un service de pressing.');
                }
                
                // Vérifier qu'au moins un service a une quantité > 0
                $hasServices = false;
                foreach ($request->pressing_services as $serviceId => $data) {
                    $quantity = is_array($data) ? ($data['quantity'] ?? 0) : (int)$data;
                    if ($quantity > 0) {
                        $hasServices = true;
                        break;
                    }
                }
                
                if (!$hasServices) {
                    throw new \Exception('Veuillez sélectionner au moins un service de pressing.');
                }
            }
            
            DB::beginTransaction();
            
            // Récupérer les adresses
            $pickupAddress = Address::where('id', $request->collection_address_id)
                                    ->where('user_id', Auth::id())
                                    ->firstOrFail();
            
            // Déterminer l'adresse de livraison
            if ($request->has('same_address_for_delivery')) {
                $deliveryAddressId = $pickupAddress->id;
                $deliveryAddress = $pickupAddress;
            } else {
                $deliveryAddress = Address::where('id', $request->delivery_address_id)
                                          ->where('user_id', Auth::id())
                                          ->firstOrFail();
                $deliveryAddressId = $deliveryAddress->id;
            }
            
            // Calculer les frais de livraison en fonction des quartiers des adresses
            if ($pickupAddress->district === $deliveryAddress->district) {
                // Si même quartier, utiliser un seul frais et le diviser pour collecte/livraison
                $singleFee = \App\Models\DeliveryFee::getFeeForDistrict($pickupAddress->district);
                $pickupFee = $singleFee / 2;
                $dropFee = $singleFee / 2;
            } else {
                // Si quartiers différents, calculer les frais séparément
                $pickupFee = \App\Models\DeliveryFee::getFeeForDistrict($pickupAddress->district);
                $dropFee = \App\Models\DeliveryFee::getFeeForDistrict($deliveryAddress->district);
            }
            
            $totalDeliveryFee = $pickupFee + $dropFee;
            
            // Vérifier si un bon de livraison est utilisé
            $deliveryVoucher = null;
            $useDeliveryVoucher = $request->has('use_delivery_voucher') && $request->use_delivery_voucher;
            
            if ($useDeliveryVoucher && $request->delivery_voucher_code) {
                $deliveryVoucher = \App\Models\DeliveryVoucher::where('code', $request->delivery_voucher_code)
                    ->where('user_id', Auth::id())
                    ->where('is_active', true)
                    ->first();
                    
                if ($deliveryVoucher) {
                    // Vérifier si le bon n'a pas expiré
                    if ($deliveryVoucher->valid_until && $deliveryVoucher->valid_until < now()) {
                        $deliveryVoucher = null; // Bon expiré
                    } 
                    // Vérifier si le bon a encore des livraisons disponibles
                    else if ($deliveryVoucher->used_deliveries >= $deliveryVoucher->number_of_deliveries) {
                        $deliveryVoucher = null; // Aucune livraison disponible
                    }
                }
                
                // Si le bon est valide, appliquer la livraison gratuite
                if ($deliveryVoucher) {
                    $totalDeliveryFee = 0;
                    $pickupFee = 0;
                    $dropFee = 0;
                }
            }
            
            // Initialiser les variables pour le total
            $totalWeight = 0;
            $estimatedPrice = 0;
            
            // Traitement spécifique selon le type de commande
            if ($request->order_type === 'kilogram') {
                // Récupérer les articles sélectionnés pour la commande au kilo
                $selectedArticles = [];
                $pricePerKg = PriceConfiguration::getCurrentPricePerKg(1000);
                
                foreach ($request->articles as $articleId => $data) {
                    $quantity = is_array($data) ? ($data['quantity'] ?? 0) : (int)$data;
                    if ($quantity > 0) {
                        $article = Article::findOrFail($articleId);
                        $selectedArticles[$articleId] = [
                            'article' => $article,
                            'quantity' => $quantity
                        ];
                        
                        // Calculer le poids et le prix
                        $itemWeight = $article->average_weight * $quantity;
                        $totalWeight += $itemWeight;
                        $estimatedPrice += $itemWeight * $pricePerKg;
                    }
                }
                
                if (empty($selectedArticles)) {
                    throw new \Exception('Veuillez sélectionner au moins un article.');
                }
            } else {
                // Récupérer les services sélectionnés pour la commande pressing
                $pressing = \App\Models\Pressing::findOrFail($request->pressing_id);
                $selectedServices = [];
                
                foreach ($request->pressing_services as $serviceId => $data) {
                    $quantity = is_array($data) ? ($data['quantity'] ?? 0) : (int)$data;
                    if ($quantity > 0) {
                        $service = \App\Models\PressingService::where('id', $serviceId)
                                                           ->where('pressing_id', $pressing->id)
                                                           ->where('is_available', true)
                                                           ->firstOrFail();
                        
                        $selectedServices[$serviceId] = [
                            'service' => $service,
                            'quantity' => $quantity
                        ];
                        
                        // Calculer le prix
                        $estimatedPrice += $service->price * $quantity;
                    }
                }
                
                if (empty($selectedServices)) {
                    throw new \Exception('Veuillez sélectionner au moins un service de pressing.');
                }
            }
            
            // Formater les dates et heures
            $pickupDateTime = date('Y-m-d H:i:s', strtotime($request->collection_date . ' ' . explode('-', $request->collection_time_slot)[0]));
            $deliveryDateTime = date('Y-m-d H:i:s', strtotime($request->delivery_date . ' ' . explode('-', $request->delivery_time_slot)[0]));
            
            // Ajouter les frais de livraison au prix estimé
            $estimatedPrice += $totalDeliveryFee;
            
            // Créer la commande
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_type' => $request->order_type,
                'pressing_id' => $request->order_type === 'pressing' ? $request->pressing_id : null,
                'weight' => $totalWeight,
                'estimated_price' => $estimatedPrice,
                'final_price' => $estimatedPrice,
                'pickup_address' => $pickupAddress->id,
                'delivery_address' => $deliveryAddressId,
                'pickup_date' => $pickupDateTime,
                'delivery_date' => $deliveryDateTime,
                'pickup_time_slot' => $request->collection_time_slot,
                'delivery_time_slot' => $request->delivery_time_slot,
                'special_instructions' => $request->delivery_notes ?? null,
                'pickup_fee' => $pickupFee, // Frais de collecte
                'drop_fee' => $dropFee, // Frais de livraison
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'quota' ? 'paid' : 'pending',
                'delivery_voucher_id' => $deliveryVoucher ? $deliveryVoucher->id : null,
            ]);
            
            // Créer les articles/services de la commande selon le type
            if ($request->order_type === 'kilogram') {
                // Créer les articles pour commande au kilo
                foreach ($selectedArticles as $articleId => $data) {
                    $article = $data['article'];
                    $quantity = $data['quantity'];
                    OrderItem::create([
                        'order_id' => $order->id,
                        'item_type' => $articleId,
                        'quantity' => $quantity,
                        'weight' => $article->average_weight * $quantity,
                        'unit_price' => $article->average_weight * $pricePerKg,
                    ]);
                }
            } else {
                // Créer les services pour commande pressing
                foreach ($selectedServices as $serviceId => $data) {
                    $service = $data['service'];
                    $quantity = $data['quantity'];
                    
                    // Vérifier s'il s'agit d'un nouveau modèle Service ou de l'ancien PressingService
                    if ($service instanceof \App\Models\Service) {
                        // Nouveau modèle Service
                        OrderItem::create([
                            'order_id' => $order->id,
                            'item_type' => 'new_service_'.$serviceId,
                            'service_id' => $serviceId, // Nouveau champ pour le nouveau modèle
                            'quantity' => $quantity,
                            'unit_price' => $service->price,
                        ]);
                    } else {
                        // Ancien modèle PressingService
                    OrderItem::create([
                        'order_id' => $order->id,
                            'item_type' => 'service_'.$serviceId,
                        'pressing_service_id' => $serviceId,
                        'quantity' => $quantity,
                        'unit_price' => $service->price,
                    ]);
                    }
                }
            }
            
            // Si on utilise un bon de livraison, augmenter son compteur d'utilisation
            if ($deliveryVoucher) {
                $deliveryVoucher->increment('used_deliveries');
            }
            
            // Si on utilise le quota, déduire du quota disponible
            if ($request->payment_method === 'quota') {
                $quotaUsageController = new QuotaUsageController();
                
                if ($request->order_type === 'kilogram') {
                    // Pour les commandes au kilo, on utilise directement le poids
                    $quotaUsageController->recordUsage(Auth::user(), $totalWeight, $order->id, 'Commande #' . $order->id);
                    
                    // Si les frais de livraison n'ont pas été annulés par un bon, les déduire aussi du quota
                    if ($totalDeliveryFee > 0) {
                        $pricePerKg = PriceConfiguration::getCurrentPricePerKg(1000);
                        $deliveryWeightEquivalent = $totalDeliveryFee / $pricePerKg;
                        
                        $quotaUsageController->recordUsage(
                            Auth::user(), 
                            $deliveryWeightEquivalent, 
                            $order->id, 
                            'Frais de livraison pour commande #' . $order->id . ' (Équivalent à ' . number_format($deliveryWeightEquivalent, 2) . ' kg)'
                        );
                    }
                } else if ($request->order_type === 'pressing') {
                    // Pour les commandes pressing, on convertit le prix en équivalent poids
                    $pricePerKg = PriceConfiguration::getCurrentPricePerKg(1000);
                    $weightEquivalent = $estimatedPrice / $pricePerKg;
                    
                    // Vérifier si l'utilisateur a assez de quota
                    $availableQuota = Auth::user()->getTotalAvailableQuota();
                    if ($availableQuota < $weightEquivalent) {
                        throw new \Exception('Quota insuffisant. Vous avez ' . $availableQuota . ' kg disponible, mais cette commande nécessite l\'équivalent de ' . number_format($weightEquivalent, 2) . ' kg.');
                    }
                    
                    // Enregistrer l'utilisation avec le poids équivalent
                    $quotaUsageController->recordUsage(
                        Auth::user(), 
                        $weightEquivalent, 
                        $order->id, 
                        'Commande Pressing #' . $order->id . ' (Équivalent à ' . number_format($weightEquivalent, 2) . ' kg)'
                    );
                }
            }
            
            // Déclencher l'événement pour traiter les bons automatiques
            event(new OrderCreatedEvent($order));
            
            // Envoyer la notification à l'utilisateur
            $user = Auth::user();
            $user->notify(new OrderCreated($order));
            
            DB::commit();
            
            // Vider le panier temporaire
            session()->forget('temp_order_cart');
            
            // Déterminer le type de réponse en fonction du type de requête
            $isAjaxRequest = $request->ajax() || 
                            $request->wantsJson() || 
                            $request->header('Accept') === 'application/json' ||
                            $request->header('X-Requested-With') === 'XMLHttpRequest';
            
            if ($isAjaxRequest) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('orders.index'),
                    'message' => 'Commande créée avec succès!'
                ]);
            }
            
            return redirect()->route('orders.index')
                ->with('success', 'Commande #' . $order->id . ' créée avec succès ! Une notification a été envoyée.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log l'erreur pour le débogage côté serveur
            Log::error('Erreur création commande: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            
            // Déterminer le type de réponse en fonction du type de requête
            $isAjaxRequest = $request->ajax() || 
                            $request->wantsJson() || 
                            $request->header('Accept') === 'application/json' ||
                            $request->header('X-Requested-With') === 'XMLHttpRequest';
            
            if ($isAjaxRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue: ' . $e->getMessage()
                ], 400);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // Vérifier que l'utilisateur peut accéder à cette commande
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->route('orders.index')
                ->with('error', 'Vous n\'êtes pas autorisé à accéder à cette commande.');
        }
        
        // Ajouter des logs pour debug
        \Log::info('Affichage de la commande #' . $order->id, [
            'order_id' => $order->id,
            'pickup_date' => $order->pickup_date,
            'delivery_date' => $order->delivery_date,
            'pickup_address' => $order->pickup_address,
            'delivery_address' => $order->delivery_address,
            'weight' => $order->weight,
            'status' => $order->status,
            'items_count' => $order->items->count()
        ]);
        
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        // Vérifier que l'utilisateur peut modifier cette commande
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->route('orders.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette commande.');
        }
        
        // Vérifier que la commande peut être modifiée (statut)
        if (!in_array($order->status, ['pending'])) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Cette commande ne peut plus être modifiée.');
        }
        
        $addresses = Address::where('user_id', Auth::id())->get();
        $articles = Article::all();
        
        return view('orders.edit', compact('order', 'addresses', 'articles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        // Vérifier que l'utilisateur peut modifier cette commande
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->route('orders.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette commande.');
        }
        
        // Vérifier que la commande peut être modifiée (statut)
        if (!in_array($order->status, ['pending'])) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Cette commande ne peut plus être modifiée.');
        }
        
        try {
            $validated = $request->validate([
                'pickup_address' => 'required|exists:addresses,id',
                'pickup_date' => 'required|date',
                'pickup_time_slot' => 'required|string',
                'delivery_date' => 'required|date|after:pickup_date',
                'delivery_time_slot' => 'required|string',
                'special_instructions' => 'nullable|string',
            ]);
            
            DB::beginTransaction();
            
            // Mettre à jour les informations de base de la commande
            $order->update([
                'pickup_address' => $request->pickup_address,
                'delivery_address' => $request->has('same_address_for_delivery') 
                                   ? $request->pickup_address 
                                   : $request->delivery_address,
                'pickup_date' => date('Y-m-d H:i:s', strtotime($request->pickup_date . ' ' . explode('-', $request->pickup_time_slot)[0])),
                'delivery_date' => date('Y-m-d H:i:s', strtotime($request->delivery_date . ' ' . explode('-', $request->delivery_time_slot)[0])),
                'special_instructions' => $request->special_instructions,
            ]);
            
            DB::commit();
            
            return redirect()->route('orders.show', $order)
                ->with('success', 'Commande mise à jour avec succès!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Une erreur est survenue: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // Vérifier que l'utilisateur peut supprimer cette commande
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return redirect()->route('orders.index')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer cette commande.');
        }
        
        // Vérifier que la commande peut être supprimée (statut)
        if (!in_array($order->status, ['pending'])) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Cette commande ne peut plus être supprimée.');
        }
        
        try {
            // Si la commande a été payée avec quota, rembourser le quota
            if ($order->payment_method === 'quota' && $order->payment_status === 'paid') {
                $quotaUsageController = new QuotaUsageController();
                $quotaUsageController->refundUsage(Auth::user(), $order->id);
            }
            
            $order->delete();
            
            return redirect()->route('orders.index')
                ->with('success', 'Commande supprimée avec succès!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Sauvegarde les données temporaires du panier en session
     */
    public function saveTemp(Request $request)
    {
        try {
            // Si le paramètre "clear" est présent, vider les données temporaires
            if ($request->has('clear')) {
                session()->forget('temp_order_cart');
                return response()->json([
                    'success' => true,
                    'message' => 'Données temporaires effacées'
                ]);
            }
            
            $data = $request->all();
            Log::info('Sauvegarde données temporaires', ['data' => $data]);
            
            session(['temp_order_cart' => $data]);
            
            return response()->json([
                'success' => true,
                'message' => 'Données temporaires sauvegardées'
            ]);
        } catch (\Exception $e) {
            // Log l'erreur pour le débogage
            Log::error('Erreur sauvegarde temp: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la sauvegarde: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Récupérer les services d'un pressing via AJAX
     */
    public function getPressingServices($id)
    {
        try {
            // Récupérer le pressing demandé
            $pressing = \App\Models\Pressing::findOrFail($id);
            
            // Vérifier si le pressing est actif
            if (!$pressing->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce pressing n\'est pas disponible pour le moment.'
                ], 404);
            }
            
            // Charger les services du pressing
            $pressingServices = $pressing->services()
                ->where('is_available', true)
                ->orderBy('category')
                ->orderBy('name')
                ->get();
                
            // Ajouter des attributs formatés pour l'affichage
            $pressingServices->each(function ($service) {
                $service->formatted_price = number_format($service->price, 0, ',', ' ') . ' FCFA';
                $service->service_category_id = $service->category ?? 'default';
                
                // Formatage du temps estimé
                if ($service->estimated_time) {
                    if ($service->estimated_time < 24) {
                        $service->formatted_time = $service->estimated_time . ' heure(s)';
                    } else {
                        $days = floor($service->estimated_time / 24);
                        $hours = $service->estimated_time % 24;
                        
                        if ($hours == 0) {
                            $service->formatted_time = $days . ' jour(s)';
                        } else {
                            $service->formatted_time = $days . ' jour(s) et ' . $hours . ' heure(s)';
                        }
                    }
                } else {
                    $service->formatted_time = null;
                }
            });
            
            // Extraction des catégories uniques des services
            $categories = $pressingServices->pluck('category')->unique()->filter()->values();
            $serviceCategories = collect($categories)->map(function ($name, $index) {
                return [
                    'id' => $name ?? 'default',
                    'name' => $name ?? 'Services généraux',
                    'icon' => 'bi-tag'
                ];
            })->toArray();
            
            return response()->json([
                'success' => true,
                'services' => $pressingServices,
                'categories' => $serviceCategories
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue: ' . $e->getMessage()
            ], 500);
        }
    }
}
