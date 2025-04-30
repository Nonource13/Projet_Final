@extends('front.layout')

@section('title', 'Mes commandes - Livres Gourmands')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('front.index') }}">Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mes commandes</li>
        </ol>
    </nav>

    <h1 class="mb-4">Historique de mes commandes</h1>

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

    @if(count($orders) > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>N° de commande</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Paiement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="text-decoration-none">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>{{ date('d/m/Y H:i', strtotime($order->created_at)) }}</td>
                            <td>{{ number_format($order->total_amount, 2) }} €</td>
                            <td>
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
                            </td>
                            <td>
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
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary" title="Voir le détail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('orders.invoice', $order->id) }}" class="btn btn-outline-secondary" title="Voir la facture">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                    @if($order->status == 'pending')
                                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger" title="Annuler la commande" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="card p-5 text-center">
            <div class="mb-4">
                <i class="fas fa-shopping-bag fa-4x text-muted"></i>
            </div>
            <h3>Vous n'avez pas encore passé de commande</h3>
            <p class="text-muted mb-4">Explorez notre catalogue et trouvez les meilleurs livres de cuisine.</p>
            <a href="{{ route('front.catalogue') }}" class="btn btn-primary">Découvrir le catalogue</a>
        </div>
    @endif
</div>
@endsection
