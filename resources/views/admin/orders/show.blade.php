@extends('layouts.admin')

@section('title', 'Commande #' . $order->id . ' - KLINKLIN Admin')

@section('page_title', 'Détails de la commande #' . $order->id)

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête avec statut -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <div class="bg-purple-50 text-purple-700 rounded-circle p-3 me-3">
                                <i class="fas fa-shopping-bag fa-2x"></i>
                            </div>
                            <div>
                                <h4 class="mb-1">Commande #{{ $order->id }}</h4>
                                <div class="d-flex flex-wrap align-items-center">
                                    <span class="badge rounded-pill bg-{{ 
                                        $order->status === 'pending' ? 'warning' : 
                                        ($order->status === 'completed' || $order->status === 'delivered' ? 'success' : 
                                        ($order->status === 'cancelled' ? 'danger' : 'primary')) 
                                    }} me-2">
                                        {{ ucfirst($order->status ?? 'non défini') }}
                                    </span>
                                    <span class="badge rounded-pill bg-{{ 
                                        $order->payment_status === 'paid' ? 'success' : 
                                        ($order->payment_status === 'pending' ? 'warning' : 
                                        ($order->payment_status === 'failed' ? 'danger' : 'secondary')) 
                                    }} me-2">
                                        Paiement: {{ ucfirst($order->payment_status ?? 'non défini') }}
                                    </span>
                                    <span class="text-muted small ms-1">
                                        <i class="far fa-calendar-alt me-1"></i> {{ $order->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap">
                            @if($order->status == 'delivered' || $order->status == 'completed')
                            <a href="{{ route('admin.orders.download-invoice', $order) }}" class="btn btn-sm btn-outline-success me-2 mb-2">
                                <i class="fas fa-file-invoice me-1"></i> Télécharger facture
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-info me-2 mb-2" data-bs-toggle="modal" data-bs-target="#sendInvoiceModal">
                                <i class="fas fa-envelope me-1"></i> Envoyer facture
                            </button>
                            @endif
                            <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-primary me-2 mb-2">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger mb-2" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-1"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Alertes -->
    @if($order->payment_method == 'cash_on_delivery' && $order->payment_status != 'paid')
    <div class="alert alert-warning alert-dismissible border-0 shadow-sm fade show mb-4" role="alert">
        <div class="d-flex">
            <div class="me-3">
                <i class="fas fa-exclamation-triangle fa-2x"></i>
            </div>
            <div>
                <strong>Attention!</strong> Cette commande doit être payée à la {{ $order->payment_method == 'cash_on_delivery' ? 'livraison' : 'collecte' }}. 
                <br>Veuillez confirmer le paiement une fois effectué.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif
    
    @if($order->payment_method == 'quota' && $order->payment_status != 'paid')
    <div class="alert alert-danger alert-dismissible border-0 shadow-sm fade show mb-4" role="alert">
        <div class="d-flex">
            <div class="me-3">
                <i class="fas fa-exclamation-circle fa-2x"></i>
            </div>
            <div>
                <strong>Important!</strong> Cette commande a été effectuée avec un quota qui n'a pas encore été payé.
                <br>Veuillez valider le paiement du quota avant de finaliser la commande.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif
    
    @if(isset($order->quota_payment_pending) && $order->quota_payment_pending)
    <div class="alert alert-danger alert-dismissible border-0 shadow-sm fade show mb-4" role="alert">
        <div class="d-flex">
            <div class="me-3">
                <i class="fas fa-exclamation-circle fa-2x"></i>
            </div>
            <div>
                <strong>Attention!</strong> Cette commande utilise un quota provenant d'un abonnement dont le paiement n'a pas été validé.
                <br>Veuillez vérifier le statut de paiement de l'abonnement associé à ce quota.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Grille d'informations principale -->
    <div class="row mb-4">
        <!-- Colonne principale -->
        <div class="col-lg-8 col-md-7">
            <!-- Carte du client et détails de commande -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Infos client -->
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light rounded-circle p-2 me-2">
                                    <i class="fas fa-user text-primary"></i>
                                </div>
                                <h6 class="fw-bold mb-0">Client</h6>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <div class="avatar bg-primary rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-size: 18px;">
                                        {{ substr($order->user->name ?? 'C', 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    @if($order->user)
                                        <h5 class="mb-0"><a href="{{ route('admin.users.show', $order->user) }}" class="text-decoration-none">{{ $order->user->name }}</a></h5>
                                        <p class="mb-0 text-secondary small">
                                            <i class="fas fa-envelope me-1"></i> {{ $order->user->email }}<br>
                                            @if($order->user->phone)
                                                <i class="fas fa-phone me-1"></i> {{ $order->user->phone }}
                                            @endif
                                        </p>
                                    @else
                                        <h5 class="mb-0">Client inconnu</h5>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <h6 class="fw-semibold mb-2"><i class="fas fa-money-bill-wave me-2 text-success"></i>Paiement</h6>
                                <div class="d-flex align-items-center flex-wrap">
                                    <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 
                                        ($order->payment_status === 'pending' ? 'warning' : 
                                        ($order->payment_status === 'failed' ? 'danger' : 'secondary')) }} me-2">
                                        {{ ucfirst($order->payment_status ?? 'non défini') }}
                                    </span>
                                    <span class="text-secondary small">
                                        @if($order->payment_method == 'cash_on_delivery') 
                                            <i class="fas fa-money-bill-alt me-1"></i> Paiement à la livraison
                                        @elseif($order->payment_method == 'quota')
                                            <i class="fas fa-cubes me-1"></i> Quota d'abonnement
                                        @elseif($order->payment_method == 'card')
                                            <i class="far fa-credit-card me-1"></i> Carte bancaire
                                        @elseif($order->payment_method == 'mobile_money')
                                            <i class="fas fa-mobile-alt me-1"></i> Mobile Money
                                        @else
                                            {{ ucfirst($order->payment_method ?? 'non définie') }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Détails commande -->
                        <div class="col-lg-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light rounded-circle p-2 me-2">
                                    <i class="fas fa-info-circle text-primary"></i>
                                </div>
                                <h6 class="fw-bold mb-0">Détails de la commande</h6>
                            </div>
                                                        
                            <div class="mb-3">
                                <h6 class="fw-semibold mb-2"><i class="fas fa-weight-hanging me-2 text-info"></i>Poids total</h6>
                                <p class="mb-0 lead">{{ number_format($order->weight, 1, ',', ' ') }} kg</p>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-semibold mb-2"><i class="fas fa-coins me-2 text-warning"></i>Montant total</h6>
                                <h4 class="text-primary fw-bold">{{ number_format($order->total, 0, '.', ' ') }} FCFA</h4>
                            </div>

                            <div class="mb-3">
                                <h6 class="fw-semibold mb-2"><i class="fas fa-calendar-alt me-2 text-secondary"></i>Dates planifiées</h6>
                                <p class="mb-1 d-flex align-items-center">
                                    <span class="badge bg-soft-info text-info me-2"><i class="fas fa-arrow-up"></i></span>
                                    <span>
                                        Collecte: 
                                        <strong>{{ $order->pickup_date ? $order->pickup_date->format('d/m/Y') : 'Non définie' }}</strong>
                                        <small class="d-block text-muted">{{ $order->pickup_time_slot ?? 'Horaire non défini' }}</small>
                                    </span>
                                </p>
                                <p class="mb-0 d-flex align-items-center">
                                    <span class="badge bg-soft-success text-success me-2"><i class="fas fa-arrow-down"></i></span>
                                    <span>
                                        Livraison: 
                                        <strong>{{ $order->delivery_date ? $order->delivery_date->format('d/m/Y') : 'Non définie' }}</strong>
                                        <small class="d-block text-muted">{{ $order->delivery_time_slot ?? 'Horaire non défini' }}</small>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Carte des adresses -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light rounded-circle p-2 me-2">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                        </div>
                        <h6 class="fw-bold mb-0">Adresses</h6>
                    </div>
                    
                    <div class="row g-4">
                        <!-- Adresse de collecte -->
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100 position-relative">
                                <h6 class="mb-2 text-primary"><i class="fas fa-arrow-up me-2"></i>Adresse de collecte</h6>
                                <div class="mb-0">
                                    @if(is_object($order->pickupAddress))
                                        <p class="mb-1 fw-semibold">{{ $order->pickupAddress->name ?? 'Adresse' }}</p>
                                        <p class="mb-1">{{ $order->pickupAddress->address ?? '' }}</p>
                                        <p class="mb-1">{{ $order->pickupAddress->neighborhood ?? '' }}</p>
                                        @if($order->pickupAddress->landmark)
                                            <p class="mb-1 small"><i class="fas fa-info-circle me-1"></i> {{ $order->pickupAddress->landmark }}</p>
                                        @endif
                                        @if($order->pickupAddress->phone)
                                            <p class="mb-0"><i class="fas fa-phone me-1"></i> {{ $order->pickupAddress->phone }}</p>
                                        @endif
                                    @elseif(is_numeric($order->pickup_address))
                                        @php
                                            $address = \App\Models\Address::find($order->pickup_address);
                                        @endphp
                                        @if($address)
                                            <p class="mb-1 fw-semibold">{{ $address->name ?? 'Adresse' }}</p>
                                            <p class="mb-1">{{ $address->address ?? '' }}</p>
                                            <p class="mb-1">{{ $address->neighborhood ?? '' }}</p>
                                            @if($address->landmark)
                                                <p class="mb-1 small"><i class="fas fa-info-circle me-1"></i> {{ $address->landmark }}</p>
                                            @endif
                                            @if($address->phone)
                                                <p class="mb-0"><i class="fas fa-phone me-1"></i> {{ $address->phone }}</p>
                                            @endif
                                        @else
                                            <p class="mb-0 text-muted">Adresse non trouvée (ID: {{ $order->pickup_address }})</p>
                                        @endif
                                    @else
                                        <p class="mb-0 text-muted">Non définie</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Adresse de livraison -->
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100 position-relative">
                                <h6 class="mb-2 text-primary"><i class="fas fa-arrow-down me-2"></i>Adresse de livraison</h6>
                                <div class="mb-0">
                                    @if(is_object($order->deliveryAddress))
                                        <p class="mb-1 fw-semibold">{{ $order->deliveryAddress->name ?? 'Adresse' }}</p>
                                        <p class="mb-1">{{ $order->deliveryAddress->address ?? '' }}</p>
                                        <p class="mb-1">{{ $order->deliveryAddress->neighborhood ?? '' }}</p>
                                        @if($order->deliveryAddress->landmark)
                                            <p class="mb-1 small"><i class="fas fa-info-circle me-1"></i> {{ $order->deliveryAddress->landmark }}</p>
                                        @endif
                                        @if($order->deliveryAddress->phone)
                                            <p class="mb-0"><i class="fas fa-phone me-1"></i> {{ $order->deliveryAddress->phone }}</p>
                                        @endif
                                    @elseif(is_numeric($order->delivery_address))
                                        @php
                                            $address = \App\Models\Address::find($order->delivery_address);
                                        @endphp
                                        @if($address)
                                            <p class="mb-1 fw-semibold">{{ $address->name ?? 'Adresse' }}</p>
                                            <p class="mb-1">{{ $address->address ?? '' }}</p>
                                            <p class="mb-1">{{ $address->neighborhood ?? '' }}</p>
                                            @if($address->landmark)
                                                <p class="mb-1 small"><i class="fas fa-info-circle me-1"></i> {{ $address->landmark }}</p>
                                            @endif
                                            @if($address->phone)
                                                <p class="mb-0"><i class="fas fa-phone me-1"></i> {{ $address->phone }}</p>
                                            @endif
                                        @else
                                            <p class="mb-0 text-muted">Adresse non trouvée (ID: {{ $order->delivery_address }})</p>
                                        @endif
                                    @else
                                        <p class="mb-0 text-muted">Non définie</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Articles de la commande -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-shopping-basket me-2 text-primary"></i> Articles de la commande
                    </h6>
                    <span class="badge bg-soft-primary text-primary rounded-pill px-3">
                        {{ $order->items->count() }} article(s)
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Article</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-end">Prix unitaire</th>
                                    <th class="text-end pe-4">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->items as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="icon-box bg-light rounded-circle p-2 me-3">
                                                    @if($item->service)
                                                        <i class="fas fa-tshirt text-primary"></i>
                                                    @elseif($item->pressingService)
                                                        <i class="fas fa-archive text-info"></i>
                                                    @else
                                                        <i class="fas fa-box text-secondary"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">
                                                        @if($item->service)
                                                            {{ $item->service->name }}
                                                        @elseif($item->pressingService)
                                                            {{ $item->pressingService->name }}
                                                        @else
                                                            {{ $item->description ?? 'Article #'.$item->item_type }}
                                                        @endif
                                                    </h6>
                                                    @if($item->pressingService && $item->pressingService->pressing)
                                                        <small class="text-muted">Pressing: {{ $item->pressingService->pressing->name }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-soft-dark text-dark rounded-pill px-3">
                                                {{ $item->quantity }}
                                            </span>
                                        </td>
                                        <td class="text-end">{{ number_format($item->unit_price, 0, '.', ' ') }} FCFA</td>
                                        <td class="text-end fw-bold pe-4">{{ number_format($item->quantity * $item->unit_price, 0, '.', ' ') }} FCFA</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="py-3">
                                                <i class="fas fa-shopping-basket fa-2x text-muted mb-2"></i>
                                                <p class="mb-0 text-muted">Aucun article dans cette commande</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">Sous-total:</th>
                                    <th class="text-end pe-4">
                                        {{ number_format($order->items->sum(function($item) { return $item->quantity * $item->unit_price; }), 0, '.', ' ') }} FCFA
                                    </th>
                                </tr>
                                @if($order->pickup_fee > 0)
                                <tr>
                                    <td colspan="3" class="text-end">Frais de collecte:</td>
                                    <td class="text-end pe-4">{{ number_format($order->pickup_fee, 0, '.', ' ') }} FCFA</td>
                                </tr>
                                @endif
                                @if($order->drop_fee > 0)
                                <tr>
                                    <td colspan="3" class="text-end">Frais de livraison:</td>
                                    <td class="text-end pe-4">{{ number_format($order->drop_fee, 0, '.', ' ') }} FCFA</td>
                                </tr>
                                @elseif($order->deliveryVoucher)
                                <tr>
                                    <td colspan="3" class="text-end">Frais de livraison:</td>
                                    <td class="text-end pe-4">
                                        <span class="text-success">
                                            <i class="fas fa-ticket-alt me-1"></i> Gratuit
                                            <small>(Bon {{ $order->deliveryVoucher->code }})</small>
                                        </span>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th class="text-end pe-4 text-primary fs-5">{{ number_format($order->total, 0, '.', ' ') }} FCFA</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @if($order->status == 'delivered' || $order->status == 'completed')
                <div class="card-footer bg-white p-3 d-flex justify-content-end">
                    <a href="{{ route('admin.orders.download-invoice', $order) }}" class="btn btn-outline-success me-2">
                        <i class="fas fa-download me-1"></i> Télécharger la facture
                    </a>
                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#sendInvoiceModal">
                        <i class="fas fa-envelope me-1"></i> Envoyer la facture par email
                    </button>
                </div>
                @endif
            </div>
            
            <!-- Notes et commentaires -->
            @if($order->notes)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-clipboard me-2 text-primary"></i> Notes
                    </h6>
                </div>
                <div class="card-body">
                    <div class="p-3 bg-light rounded">
                        <p class="mb-0 fst-italic">{{ $order->notes }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-lg-4 col-md-5">
            <!-- Mise à jour du statut -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-sync-alt me-2 text-primary"></i> Mettre à jour le statut
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label for="order_status" class="form-label fw-semibold">Statut de la commande</label>
                            <select class="form-select border-0 bg-light py-3" id="order_status" name="order_status" required>
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="collected" {{ $order->status === 'collected' ? 'selected' : '' }}>Collecté</option>
                                <option value="in_transit" {{ $order->status === 'in_transit' ? 'selected' : '' }}>En transit</option>
                                <option value="washing" {{ $order->status === 'washing' ? 'selected' : '' }}>Lavage</option>
                                <option value="ironing" {{ $order->status === 'ironing' ? 'selected' : '' }}>Repassage</option>
                                <option value="ready_for_delivery" {{ $order->status === 'ready_for_delivery' ? 'selected' : '' }}>Prêt pour livraison</option>
                                <option value="delivering" {{ $order->status === 'delivering' ? 'selected' : '' }}>En cours de livraison</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Livré</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="payment_status" class="form-label fw-semibold">Statut du paiement</label>
                            <select class="form-select border-0 bg-light py-3" id="payment_status" name="payment_status" required>
                                <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Payé</option>
                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Échoué</option>
                                <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Remboursé</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-3">
                            <i class="fas fa-save me-1"></i> Mettre à jour le statut
                        </button>
                    </form>
                </div>
            </div>

            <!-- Envoyer un email -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-envelope me-2 text-primary"></i> Envoyer un email au client
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.orders.send-email', $order) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email_subject" class="form-label fw-semibold">Sujet</label>
                            <input type="text" class="form-control border-0 bg-light py-3" id="email_subject" name="email_subject" placeholder="Entrez le sujet de l'email" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="email_message" class="form-label fw-semibold">Message</label>
                            <textarea class="form-control border-0 bg-light py-3" id="email_message" name="email_message" rows="5" placeholder="Saisissez votre message ici..." required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-info w-100 py-3">
                            <i class="fas fa-paper-plane me-1"></i> Envoyer l'email
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Historique -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-history me-2 text-primary"></i> Historique de la commande
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline-modern">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Commande créée</h6>
                                <p class="timeline-date">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($order->collected_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Articles collectés</h6>
                                <p class="timeline-date">{{ $order->collected_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->washing_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info">
                                <i class="fas fa-tint"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Lavage en cours</h6>
                                <p class="timeline-date">{{ $order->washing_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->ironing_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info">
                                <i class="fas fa-iron"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Repassage en cours</h6>
                                <p class="timeline-date">{{ $order->ironing_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->ready_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Prêt pour livraison</h6>
                                <p class="timeline-date">{{ $order->ready_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->completed_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Livré / Terminé</h6>
                                <p class="timeline-date">{{ $order->completed_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->cancelled_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Commande annulée</h6>
                                <p class="timeline-date">{{ $order->cancelled_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Boutons d'action -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                        </a>
                        <div class="d-flex flex-wrap mt-3 mt-md-0">
                            <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary me-2 mb-2 mb-md-0">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-1"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p>Êtes-vous sûr de vouloir supprimer la commande <strong>#{{ $order->id }}</strong> ?</p>
                    <p class="text-danger small">Cette action est irréversible.</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour envoyer la facture par email -->
<div class="modal fade" id="sendInvoiceModal" tabindex="-1" aria-labelledby="sendInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="sendInvoiceModalLabel">Envoyer la facture par email</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.orders.send-invoice', $order) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="invoice_email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control border-0 bg-light py-3" id="invoice_email" name="email" value="{{ $order->user->email ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="invoice_subject" class="form-label fw-semibold">Sujet</label>
                        <input type="text" class="form-control border-0 bg-light py-3" id="invoice_subject" name="subject" value="Votre facture pour la commande #{{ $order->id }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="invoice_message" class="form-label fw-semibold">Message</label>
                        <textarea class="form-control border-0 bg-light py-3" id="invoice_message" name="message" rows="4" required>Cher(e) {{ $order->user->name ?? 'client' }},

Veuillez trouver ci-joint votre facture pour la commande #{{ $order->id }}.

Merci de faire confiance à KLINKLIN pour vos besoins de blanchisserie.

Cordialement,
L'équipe KLINKLIN</textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i> Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Couleurs personnalisées */
.bg-purple-50 {
    background-color: rgba(70, 24, 113, 0.1);
}
.text-purple-700 {
    color: #461871;
}
.bg-soft-primary {
    background-color: rgba(13, 110, 253, 0.1);
}
.bg-soft-info {
    background-color: rgba(13, 202, 240, 0.1);
}
.bg-soft-success {
    background-color: rgba(25, 135, 84, 0.1);
}
.bg-soft-dark {
    background-color: rgba(33, 37, 41, 0.1);
}

/* Timeline moderne */
.timeline-modern {
    position: relative;
    padding: 0;
    list-style: none;
    padding-left: 30px;
}

.timeline-modern:before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 15px;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 25px;
    z-index: 1;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    top: 0;
    left: -29px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    text-align: center;
    line-height: 28px;
    color: #fff;
    font-size: 12px;
}

.timeline-content {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}

.timeline-title {
    margin: 0;
    font-size: 0.95rem;
    font-weight: 600;
}

.timeline-date {
    margin: 5px 0 0;
    font-size: 0.8rem;
    color: #6c757d;
}

/* Améliorations responsives */
@media (max-width: 767.98px) {
    .container-fluid {
        padding-left: 12px;
        padding-right: 12px;
    }
    
    .card-body {
        padding: 20px 15px;
    }
    
    .timeline-modern {
        padding-left: 25px;
    }
    
    .timeline-modern:before {
        left: 12px;
    }
    
    .timeline-marker {
        left: -25px;
        width: 24px;
        height: 24px;
        line-height: 24px;
        font-size: 10px;
    }
    
    .timeline-content {
        padding: 12px;
    }
}
</style>
@endpush 