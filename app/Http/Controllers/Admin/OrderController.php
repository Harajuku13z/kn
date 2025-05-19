<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatusUpdated;
use App\Notifications\OrderMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Events\OrderCreated as OrderCreatedEvent;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Order::latest();
        
        // Filtrage par statut
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Filtrage par date
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Recherche par texte (ID ou nom d'utilisateur)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $orders = $query->paginate(15)->withQueryString();
        
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.orders.create');
    }

    /**
     * Show the form for creating a new quota order.
     *
     * @return \Illuminate\Http\Response
     */
    public function createQuota()
    {
        $users = User::where('role', 'user')->orderBy('name')->get();
        $currentPrice = \App\Models\PriceConfiguration::getCurrentPricePerKg(1000);
        
        return view('admin.orders.create_quota', compact('users', 'currentPrice'));
    }

    /**
     * Show the form for creating a new pressing order.
     *
     * @return \Illuminate\Http\Response
     */
    public function createPressing()
    {
        $users = User::where('role', 'user')->orderBy('name')->get();
        $pressings = \App\Models\Pressing::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.orders.create_pressing', compact('users', 'pressings'));
    }

    /**
     * Display the user selection step for quota order creation.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectUserForQuota()
    {
        $users = User::where('role', 'user')->orderBy('name')->get();
        return view('admin.orders.select_user', [
            'users' => $users,
            'orderType' => 'quota',
            'nextRoute' => 'admin.orders.create.quota.form'
        ]);
    }

    /**
     * Display the user selection step for pressing order creation.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectUserForPressing()
    {
        $users = User::where('role', 'user')->orderBy('name')->get();
        return view('admin.orders.select_user', [
            'users' => $users,
            'orderType' => 'pressing',
            'nextRoute' => 'admin.orders.create.pressing.form'
        ]);
    }

    /**
     * Show the form for creating a quota order for a specific user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function createQuotaForm($userId)
    {
        $user = User::findOrFail($userId);
        $addresses = \App\Models\Address::with('city')->where('user_id', $userId)->get();
        $currentPrice = \App\Models\PriceConfiguration::getCurrentPricePerKg(1000);
        
        // Récupérer les articles de la table articles
        // Articles de type vêtement
        $clothingArticles = \App\Models\Article::where('is_active', true)
            ->where(function($query) {
                $query->whereJsonContains('type', 'clothing')
                      ->orWhereJsonContains('type', 'vêtement')
                      ->orWhereJsonContains('type', 'vetement');
            })
            ->orderBy('name')
            ->get();
            
        // Articles de type linge de maison
        $householdArticles = \App\Models\Article::where('is_active', true)
            ->where(function($query) {
                $query->whereJsonContains('type', 'household')
                      ->orWhereJsonContains('type', 'linge')
                      ->orWhereJsonContains('type', 'maison');
            })
            ->orderBy('name')
            ->get();
        
        // Récupérer tous les frais de livraison par district
        $deliveryFees = \App\Models\DeliveryFee::where('is_active', true)->get();
        
        // Frais de livraison par défaut si aucun district correspondant n'est trouvé
        $defaultDeliveryFee = 500;
        
        // Préparer un tableau associatif des frais par district pour un accès facile en JavaScript
        $deliveryFeesByDistrict = [];
        foreach ($deliveryFees as $fee) {
            $key = $fee->city_id . '-' . strtolower($fee->district);
            $deliveryFeesByDistrict[$key] = $fee->fee;
        }
        
        return view('admin.orders.create_quota_form', compact(
            'user', 
            'addresses', 
            'currentPrice',
            'clothingArticles',
            'householdArticles',
            'defaultDeliveryFee',
            'deliveryFees',
            'deliveryFeesByDistrict'
        ));
    }

    /**
     * Show the form for creating a pressing order for a specific user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function createPressingForm($userId)
    {
        $user = User::findOrFail($userId);
        $addresses = \App\Models\Address::where('user_id', $userId)->get();
        $pressings = \App\Models\Pressing::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.orders.create_pressing_form', compact('user', 'addresses', 'pressings'));
    }

    /**
     * Show the form for creating a new address for a user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function createAddress($userId)
    {
        $user = User::findOrFail($userId);
        $cities = \App\Models\City::where('is_active', true)->orderBy('name')->get();
        $returnType = request('return_type', 'quota');
        
        return view('admin.orders.create_address', compact('user', 'cities', 'returnType'));
    }

    /**
     * Store a new address for a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function storeAddress(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'district' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'landmark' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'contact_name' => 'required|string|max:255',
            'type' => 'required|in:home,office,other',
            'is_default' => 'boolean'
        ]);
        
        $validatedData['user_id'] = $userId;
        
        // If this is set as default, unset all other defaults
        if (isset($validatedData['is_default']) && $validatedData['is_default']) {
            \App\Models\Address::where('user_id', $userId)->update(['is_default' => false]);
        }
        
        $address = \App\Models\Address::create($validatedData);
        
        // Calculate delivery fee for this district
        $deliveryFee = \App\Models\DeliveryFee::getFeeForDistrict($validatedData['district'], $validatedData['city_id']);
        
        $returnType = $request->input('return_type', 'quota');
        $route = $returnType === 'quota' ? 'admin.orders.create.quota.form' : 'admin.orders.create.pressing.form';
        
        return redirect()->route($route, $userId)
            ->with('success', 'Adresse ajoutée avec succès!')
            ->with('address_id', $address->id)
            ->with('delivery_fee', $deliveryFee);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'order_type' => 'required|in:kilogram,pressing',
            'pressing_id' => 'required_if:order_type,pressing|nullable|exists:pressings,id',
            'status' => 'required|in:pending,collected,in_transit,washing,ironing,ready_for_delivery,delivering,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'pickup_date' => 'required|date',
            'pickup_time_slot' => 'required|string',
            'delivery_date' => 'required|date|after_or_equal:pickup_date',
            'delivery_time_slot' => 'required|string',
            'pickup_address' => 'required|exists:addresses,id',
            'delivery_address' => 'required|exists:addresses,id',
            'notes' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0.5',
            'estimated_price' => 'nullable|numeric|min:0',
            'pickup_fee' => 'nullable|numeric|min:0',
            'drop_fee' => 'nullable|numeric|min:0',
            'articles' => 'nullable|array',
            'article_qty' => 'nullable|array',
        ]);
        
        // Convertir les dates au format DateTime
        $validatedData['pickup_date'] = \Carbon\Carbon::parse($validatedData['pickup_date']);
        $validatedData['delivery_date'] = \Carbon\Carbon::parse($validatedData['delivery_date']);
        
        // Si le type de commande est au kilo, on s'assure que pressing_id est null
        if ($validatedData['order_type'] === 'kilogram') {
            $validatedData['pressing_id'] = null;
        }
        
        // Ajouter les notes comme special_instructions si présentes
        if (isset($validatedData['notes'])) {
            $validatedData['special_instructions'] = $validatedData['notes'];
            unset($validatedData['notes']); // Supprimer notes car ce n'est pas une colonne dans la table
        }
        
        // Récupérer les adresses et les convertir au format attendu par la base de données
        if (isset($validatedData['pickup_address'])) {
            $pickupAddressId = $validatedData['pickup_address'];
            $pickupAddress = \App\Models\Address::find($pickupAddressId);
            if ($pickupAddress) {
                // Stocker l'adresse complète au format texte
                $validatedData['pickup_address'] = $pickupAddress->address . ', ' . $pickupAddress->district;
                if ($pickupAddress->city) {
                    $validatedData['pickup_address'] .= ', ' . $pickupAddress->city->name;
                }
            }
        }
        
        if (isset($validatedData['delivery_address'])) {
            $deliveryAddressId = $validatedData['delivery_address'];
            $deliveryAddress = \App\Models\Address::find($deliveryAddressId);
            if ($deliveryAddress) {
                // Stocker l'adresse complète au format texte
                $validatedData['delivery_address'] = $deliveryAddress->address . ', ' . $deliveryAddress->district;
                if ($deliveryAddress->city) {
                    $validatedData['delivery_address'] .= ', ' . $deliveryAddress->city->name;
                }
            }
        }
        
        // Stocker les articles comme JSON si présents
        if (isset($validatedData['articles'])) {
            // Si c'est un tableau d'articles, le convertir en JSON
            if (is_array($validatedData['articles'])) {
                // Récupérer les informations détaillées des articles si possible
                $articleDetails = [];
                foreach ($validatedData['articles'] as $articleName) {
                    // Essayer de trouver l'article dans la base de données
                    $article = \App\Models\Article::where('name', $articleName)->first();
                    
                    if ($article) {
                        $articleDetail = [
                            'name' => $article->name,
                            'weight' => $article->average_weight,
                            'type' => $article->type
                        ];
                        
                        // Ajouter la quantité si disponible
                        if (isset($validatedData['article_qty'][$article->id])) {
                            $articleDetail['quantity'] = (int) $validatedData['article_qty'][$article->id];
                        } else {
                            $articleDetail['quantity'] = 1;
                        }
                        
                        $articleDetails[] = $articleDetail;
                    } else {
                        // Si l'article n'est pas trouvé, ajouter juste le nom
                        $articleDetails[] = [
                            'name' => $articleName,
                            'quantity' => 1
                        ];
                    }
                }
                
                $validatedData['items'] = json_encode($articleDetails);
            } else {
                // Si ce n'est pas un tableau, le convertir tel quel
                $validatedData['items'] = json_encode($validatedData['articles']);
            }
            
            unset($validatedData['articles']); // Supprimer articles car ce n'est pas une colonne dans la table
        }
        
        // Supprimer les quantités d'articles car ce n'est pas une colonne dans la table
        if (isset($validatedData['article_qty'])) {
            unset($validatedData['article_qty']);
        }
        
        // Calculer le prix total (prix estimé + frais de livraison)
        $estimatedPrice = $validatedData['estimated_price'] ?? 0;
        $pickupFee = $validatedData['pickup_fee'] ?? 0;
        $dropFee = $validatedData['drop_fee'] ?? 0;
        
        // Stocker le prix final qui inclut les frais de livraison
        $validatedData['final_price'] = $estimatedPrice + $pickupFee + $dropFee;
        
        $order = Order::create($validatedData);
        
        // Déclencher l'événement pour traiter les bons automatiques
        event(new OrderCreatedEvent($order));
        
        // Envoyer une notification à l'utilisateur
        try {
            $user = \App\Models\User::find($validatedData['user_id']);
            if ($user) {
                // Envoyer la notification OrderCreated
                $user->notify(new \App\Notifications\OrderCreated($order));
                
                // Log l'envoi de la notification
                \Illuminate\Support\Facades\Log::info('Notification de création de commande envoyée', [
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            }
        } catch (\Exception $e) {
            // Log l'erreur mais continuer l'exécution
            \Illuminate\Support\Facades\Log::error('Erreur lors de l\'envoi de la notification de création de commande: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'stack_trace' => $e->getTraceAsString()
            ]);
        }
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Commande créée avec succès! Une notification a été envoyée au client.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        // Charger les adresses explicitement
        $pickupAddress = null;
        $deliveryAddress = null;
        
        if (is_numeric($order->pickup_address)) {
            $pickupAddress = \App\Models\Address::find($order->pickup_address);
            $order->pickupAddress = $pickupAddress;
        }
        
        if (is_numeric($order->delivery_address)) {
            $deliveryAddress = \App\Models\Address::find($order->delivery_address);
            $order->deliveryAddress = $deliveryAddress;
        }
        
        // Charger toutes les relations nécessaires
        $order->load(['user', 'items.service', 'items.pressingService', 'quota']);
        
        // Vérifier le statut du quota si utilisé comme méthode de paiement
        if ($order->payment_method == 'quota' && $order->quota) {
            $quotaSubscription = $order->quota->subscription;
            if ($quotaSubscription) {
                $order->quota_payment_pending = ($quotaSubscription->payment_status != 'paid');
            }
        }
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:pending,collected,in_transit,washing,ironing,ready_for_delivery,delivering,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'pickup_date' => 'required|date',
            'pickup_time_slot' => 'required|string',
            'delivery_date' => 'required|date|after_or_equal:pickup_date',
            'delivery_time_slot' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        
        $order->update($validatedData);
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Commande mise à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        
        return redirect()->route('admin.orders.index')
            ->with('success', 'Commande supprimée avec succès!');
    }
    
    /**
     * Update the order and payment status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'order_status' => 'required|in:pending,collected,in_transit,washing,ironing,ready_for_delivery,delivering,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);
        
        $oldOrderStatus = $order->status;
        $oldPaymentStatus = $order->payment_status;
        
        // Mettre à jour le statut de la commande (noter le changement de nom de champ)
        $order->update([
            'status' => $validatedData['order_status'],
            'payment_status' => $validatedData['payment_status'],
        ]);
        
        // Enregistrer les horodatages pour les transitions de statut
        if ($validatedData['order_status'] === 'collected' && is_null($order->collected_at)) {
            $order->update(['collected_at' => now()]);
        }

        if ($validatedData['order_status'] === 'washing' && is_null($order->washing_at)) {
            $order->update(['washing_at' => now()]);
        }

        if ($validatedData['order_status'] === 'ironing' && is_null($order->ironing_at)) {
            $order->update(['ironing_at' => now()]);
        }

        if ($validatedData['order_status'] === 'ready_for_delivery' && is_null($order->ready_at)) {
            $order->update(['ready_at' => now()]);
        }
        
        if ($validatedData['order_status'] === 'delivered' && is_null($order->completed_at)) {
            $order->update(['completed_at' => now()]);
        }

        if ($validatedData['order_status'] === 'cancelled' && is_null($order->cancelled_at)) {
            $order->update(['cancelled_at' => now()]);
        }
        
        // Envoyer une notification au client si le statut a changé
        $notificationSent = false;
        
        if ($oldOrderStatus !== $validatedData['order_status'] || $oldPaymentStatus !== $validatedData['payment_status']) {
            try {
                if ($order->user) {
                    // Charger l'objet utilisateur complètement pour éviter des problèmes avec la notification
                    $user = \App\Models\User::find($order->user->id);
                    
                    if (!$user) {
                        throw new \Exception('Utilisateur introuvable');
                    }
                    
                    // Utiliser la notification OrderStatusUpdated
                    $user->notify(new \App\Notifications\OrderStatusUpdated($order));
                    $notificationSent = true;
                    
                    \Illuminate\Support\Facades\Log::info('Notification de statut envoyée pour la commande #' . $order->id, [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'new_status' => $validatedData['order_status'],
                        'notification_type' => 'OrderStatusUpdated'
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send order status notification: ' . $e->getMessage(), [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'stack_trace' => $e->getTraceAsString()
                ]);
            }
        }
        
        $successMessage = 'Statut de la commande mis à jour avec succès!';
        if ($notificationSent) {
            $successMessage .= ' Un email de confirmation a été envoyé au client.';
        }
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', $successMessage);
    }
    
    /**
     * Get the display label for a status
     * 
     * @param string $status
     * @return string
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'En attente',
            'collected' => 'Collecté',
            'in_transit' => 'En transit', 
            'washing' => 'Lavage',
            'ironing' => 'Repassage',
            'ready_for_delivery' => 'Prêt pour livraison',
            'delivering' => 'En cours de livraison',
            'delivered' => 'Livré',
            'cancelled' => 'Annulé'
        ];
        
        return $labels[$status] ?? ucfirst($status);
    }
    
    /**
     * Send an email to the customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function sendEmail(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'email_subject' => 'required|string|max:255',
            'email_message' => 'required|string',
        ]);
        
        try {
            if ($order->user) {
                // Charger l'utilisateur complet
                $user = \App\Models\User::find($order->user->id);
                
                if (!$user) {
                    throw new \Exception('Utilisateur introuvable');
                }
                
                // Envoyer la notification - explicitly cast message to string
                $user->notify(new OrderMessage(
                    $order,
                    $validatedData['email_subject'],
                    (string) $validatedData['email_message']
                ));
                
                \Illuminate\Support\Facades\Log::info('Email envoyé manuellement pour la commande #' . $order->id, [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'subject' => $validatedData['email_subject']
                ]);
            } else {
                throw new \Exception('User not found for this order');
            }
            
            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Email envoyé avec succès au client!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send email to customer: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Échec lors de l\'envoi de l\'email: ' . $e->getMessage());
        }
    }
    
    /**
     * Download the invoice for the order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function downloadInvoice(Order $order)
    {
        try {
            // Vérifier que la commande est livrée ou terminée
            if (!in_array($order->status, ['delivered', 'completed'])) {
                return redirect()->route('admin.orders.show', $order)
                    ->with('error', 'La facture n\'est disponible que pour les commandes livrées ou terminées.');
            }
            
            // Générer la facture PDF
            $pdf = $this->generateInvoicePdf($order);
            
            // Retourner le PDF en téléchargement
            return $pdf->download('facture_' . $order->id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Failed to download invoice: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Échec lors de la génération de la facture: ' . $e->getMessage());
        }
    }
    
    /**
     * Send the invoice by email to the customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function sendInvoice(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        try {
            // Vérifier que la commande est livrée ou terminée
            if (!in_array($order->status, ['delivered', 'completed'])) {
                return redirect()->route('admin.orders.show', $order)
                    ->with('error', 'La facture n\'est disponible que pour les commandes livrées ou terminées.');
            }
            
            // Générer la facture PDF
            $pdf = $this->generateInvoicePdf($order);
            $pdfContent = $pdf->output();
            
            // Augmenter les timeout pour s'assurer que l'email a le temps d'être envoyé
            set_time_limit(300); // 5 minutes
            
            // Envoyer l'email avec la facture en pièce jointe
            Mail::send([], [], function ($message) use ($validatedData, $pdfContent, $order) {
                $message->to($validatedData['email'])
                    ->subject($validatedData['subject'])
                    ->setBody($validatedData['message'], 'text/plain')
                    ->attachData($pdfContent, 'facture_' . $order->id . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
            });
            
            // Vérifier si l'email a été envoyé correctement
            if (count(Mail::failures()) > 0) {
                throw new \Exception('Échec de l\'envoi de l\'email: ' . implode(', ', Mail::failures()));
            }
            
            \Illuminate\Support\Facades\Log::info('Facture envoyée par email pour la commande #' . $order->id, [
                'recipient' => $validatedData['email'],
                'order_id' => $order->id,
            ]);
            
            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Facture envoyée avec succès par email!');
        } catch (\Exception $e) {
            Log::error('Failed to send invoice by email: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'email' => $validatedData['email'] ?? 'non définie',
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Échec lors de l\'envoi de la facture par email: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate the invoice PDF for an order.
     *
     * @param  \App\Models\Order  $order
     * @return \Barryvdh\DomPDF\PDF
     */
    private function generateInvoicePdf(Order $order)
    {
        // Configurer PDF
        $pdf = PDF::loadView('admin.orders.invoice', [
            'order' => $order,
            'date' => now()->format('d/m/Y'),
        ]);
        
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif'
        ]);
        
        return $pdf;
    }
} 