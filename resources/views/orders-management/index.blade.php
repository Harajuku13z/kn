@extends('orders-management.layout')

@section('title', 'Liste des commandes')

@section('content')
<div class="container-bootstrap">
    <div class="header-actions">
        <h1 class="main-title">Liste des commandes</h1>
        <a href="{{ route('orders-management.create') }}" class="btn btn-primary-custom">
            <i class="bi bi-plus-circle"></i> Nouvelle commande
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date de collecte</th>
                    <th>Adresse de livraison</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->collection_date ? $order->collection_date->format('d/m/Y') : 'N/A' }} - {{ $order->collection_time_slot ?? 'N/A' }}</td>
                    <td>
                        {{ $order->delivery_address ?? 'N/A' }}, 
                        {{ $order->delivery_city ?? '' }} {{ $order->delivery_postal_code ?? '' }}
                    </td>
                    <td>{{ number_format($order->total, 2) }} €</td>
                    <td>
                        <span class="badge bg-{{ $order->order_status == 'completed' ? 'success' : ($order->order_status == 'pending' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($order->order_status ?? 'non spécifié') }}
                        </span>
                    </td>
                    <td>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('orders-management.show', $order) }}" class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('orders-management.edit', $order) }}" class="btn btn-sm btn-warning text-white">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('orders-management.destroy', $order) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Aucune commande trouvée</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection 