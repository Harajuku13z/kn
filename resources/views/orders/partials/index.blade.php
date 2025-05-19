@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Mes commandes</h5>
                    <a href="{{ route('orders.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Nouvelle commande
                    </a>
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>N° Commande</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Poids total</th>
                                        <th>Montant total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @switch($order->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">En attente</span>
                                                        @break
                                                    @case('processing')
                                                        <span class="badge bg-info">En cours</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge bg-success">Terminée</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-danger">Annulée</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $order->status }}</span>
                                                @endswitch
                                            </td>
                                            <td>{{ $order->total_weight }} kg</td>
                                            <td>{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                                            <td>
                                                <a href="{{ route('orders.show', $order) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($order->status === 'pending')
                                                    <form action="{{ route('orders.cancel', $order) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?');">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <p class="text-muted">Aucune commande trouvée.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 