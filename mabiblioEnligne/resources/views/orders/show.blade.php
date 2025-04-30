@extends('front.layout')

@section('title', 'Détails de la commande #' . $order->order_number . ' - Livres Gourmands')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('front.index') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Mes commandes</a></li>
            <li class="breadcrumb-item active" aria-current="page">Commande #{{ $order->order_number }}</li>
        </ol>
    </nav>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- En-tête de la commande -->
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Commande #{{ $order->order_number }}</h5>
                    <div>
                        @switch($order->status)
                            @case('pending')
                                <span class="badge bg-warning text-dark">En attente</span>
                                @break
                            @case('confirmed')
                                <span class="badge bg-info">Confirmée</span>
                                @break
                            @case('processing')
                                <span class="badge bg-primary">En traitement</span>
                                @break
                            @case('shipped')
                                <span class="badge bg-secondary">Expédiée</span>
                                @break
                            @case('delivered')
                                <span class="badge bg-success">Livrée</span>
                                @break
                            @case('cancelled')
                                <span class="badge bg-danger">Annulée</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ $order->status }}</span>
                        @endswitch
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Date de la commande :</strong> {{ date('d/m/Y H:i', strtotime($order->created_at)) }}</p>
                            <p class="mb-1"><strong>Mode de paiement :</strong> 
                                @switch($order->payment_method)
                                    @case('carte')
                                        Carte bancaire
                                        @break
                                    @case('paypal')
                                        PayPal
                                        @break
                                    @case('virement')
                                        Virement bancaire
                                        @break
                                    @default
                                        {{ $order->payment_method }}
                                @endswitch
                            </p>
                            <p class="mb-1"><strong>Statut du paiement :</strong> 
                                @switch($order->payment_status)
                                    @case('pending')
                                        <span class="badge bg-warning text-dark">En attente</span>
                                        @break
                                    @case('paid')
                                        <span class="badge bg-success">Payé</span>
                                        @break
                                    @case('failed')
                                        <span class="badge bg-danger">Échec</span>
                                        @break
                                    @case('refunded')
                                        <span class="badge bg-info">Remboursé</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $order->payment_status }}</span>
                                @endswitch
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <a href="{{ route('orders.invoice', $order->id) }}" class="btn btn-outline-secondary mb-2">
                                <i class="fas fa-file-invoice me-1"></i>Voir la facture
                            </a>
                            @if($order->status == 'pending')
                                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger mb-2" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                        <i class="fas fa-times me-1"></i>Annuler la commande
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Articles commandés -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Articles commandés</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Article</th>
                                    <th class="text-center">Prix unitaire</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('images/livres/default.jpg') }}" alt="{{ $item->ouvrage->titre }}" class="img-thumbnail me-2" style="width: 50px;">
                                                <div>
                                                    <a href="{{ route('front.detail', $item->ouvrage->id) }}" class="text-decoration-none">
                                                        {{ $item->ouvrage->titre }}
                                                    </a>
                                                    <div class="text-muted small">{{ $item->ouvrage->auteur }}</div>
                                                    @if($item->gift_list_item)
                                                        <span class="badge bg-info">Liste de cadeaux</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ number_format($item->price, 2) }} €</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->subtotal, 2) }} €</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                                    <td class="text-end"><strong>{{ number_format($order->total_amount, 2) }} €</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Adresse de livraison -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Adresse de livraison</h5>
                </div>
                <div class="card-body">
                    <address class="mb-0">
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_postal_code }} {{ $order->shipping_city }}<br>
                        {{ $order->shipping_country }}
                    </address>
                </div>
            </div>

            <!-- Adresse de facturation -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Adresse de facturation</h5>
                </div>
                <div class="card-body">
                    <address class="mb-0">
                        {{ $order->billing_address ?? $order->shipping_address }}<br>
                        {{ $order->billing_postal_code ?? $order->shipping_postal_code }} {{ $order->billing_city ?? $order->shipping_city }}<br>
                        {{ $order->billing_country ?? $order->shipping_country }}
                    </address>
                </div>
            </div>

            <!-- Notes de commande -->
            @if($order->notes)
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Notes</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
