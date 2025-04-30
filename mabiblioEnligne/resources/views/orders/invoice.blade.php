@extends('front.layout')

@section('title', 'Facture - Commande #' . $order->order_number . ' - Livres Gourmands')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('front.index') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Mes commandes</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.show', $order->id) }}">Commande #{{ $order->order_number }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Facture</li>
        </ol>
    </nav>

    <div class="card mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Facture - Commande #{{ $order->order_number }}</h5>
            <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                <i class="fas fa-print me-1"></i>Imprimer
            </button>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-4">
                        <h4 class="mb-3">Livres Gourmands</h4>
                        <p class="mb-1">123 Rue de la Cuisine</p>
                        <p class="mb-1">75001 Paris</p>
                        <p class="mb-1">France</p>
                        <p class="mb-1">SIRET : 123 456 789 00012</p>
                        <p class="mb-1">TVA : FR12 123 456 789</p>
                    </div>
                    
                    <div>
                        <h5 class="mb-2">Facturé à</h5>
                        <p class="mb-1">{{ $order->user->name }}</p>
                        <p class="mb-1">{{ $order->billing_address ?? $order->shipping_address }}</p>
                        <p class="mb-1">{{ $order->billing_postal_code ?? $order->shipping_postal_code }} {{ $order->billing_city ?? $order->shipping_city }}</p>
                        <p class="mb-1">{{ $order->billing_country ?? $order->shipping_country }}</p>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="mb-4">
                        <h5 class="mb-2">Facture</h5>
                        <p class="mb-1"><strong>N° de facture :</strong> F-{{ $order->order_number }}</p>
                        <p class="mb-1"><strong>Date :</strong> {{ date('d/m/Y', strtotime($order->created_at)) }}</p>
                    </div>
                    
                    <div>
                        <h5 class="mb-2">Livré à</h5>
                        <p class="mb-1">{{ $order->user->name }}</p>
                        <p class="mb-1">{{ $order->shipping_address }}</p>
                        <p class="mb-1">{{ $order->shipping_postal_code }} {{ $order->shipping_city }}</p>
                        <p class="mb-1">{{ $order->shipping_country }}</p>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Référence</th>
                            <th>Description</th>
                            <th class="text-center">Prix unitaire</th>
                            <th class="text-center">Quantité</th>
                            <th class="text-end">Total HT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>LIV-{{ $item->ouvrage->id }}</td>
                                <td>
                                    <strong>{{ $item->ouvrage->titre }}</strong><br>
                                    <small class="text-muted">{{ $item->ouvrage->auteur }}</small>
                                </td>
                                <td class="text-center">{{ number_format($item->price / 1.2, 2) }} €</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">{{ number_format(($item->price / 1.2) * $item->quantity, 2) }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total HT</strong></td>
                            <td class="text-end">{{ number_format($order->total_amount / 1.2, 2) }} €</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>TVA (20%)</strong></td>
                            <td class="text-end">{{ number_format($order->total_amount - ($order->total_amount / 1.2), 2) }} €</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total TTC</strong></td>
                            <td class="text-end"><strong>{{ number_format($order->total_amount, 2) }} €</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <h5 class="mb-2">Informations de paiement</h5>
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
                    <p class="mb-1"><strong>Statut :</strong> 
                        @switch($order->payment_status)
                            @case('pending')
                                En attente
                                @break
                            @case('paid')
                                Payé
                                @break
                            @case('failed')
                                Échec
                                @break
                            @case('refunded')
                                Remboursé
                                @break
                            @default
                                {{ $order->payment_status }}
                        @endswitch
                    </p>
                    @if($order->payment_method == 'virement')
                        <div class="mt-3">
                            <p><strong>Coordonnées bancaires :</strong></p>
                            <p class="mb-1">IBAN : FR76 1234 5678 9012 3456 7890 123</p>
                            <p class="mb-1">BIC : LIVRFR2P</p>
                            <p class="mb-1">Banque : Banque des Livres</p>
                            <p class="mb-1">Titulaire : Livres Gourmands SARL</p>
                            <p class="mb-1"><strong>Référence à indiquer :</strong> {{ $order->order_number }}</p>
                        </div>
                    @endif
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="mt-4">
                        <p>Pour toute question concernant cette facture,<br>veuillez nous contacter à <a href="mailto:contact@livresgourmands.net">contact@livresgourmands.net</a></p>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="text-center text-muted">
                <p class="mb-1">Livres Gourmands - SARL au capital de 10 000 € - RCS Paris 123 456 789</p>
                <p class="mb-0">123 Rue de la Cuisine - 75001 Paris - France</p>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .container {
            width: 100%;
            max-width: 100%;
        }
        nav, header, footer, .breadcrumb, .btn {
            display: none !important;
        }
        .card {
            border: none !important;
        }
        .card-header {
            background-color: white !important;
            color: black !important;
        }
    }
</style>
@endsection
