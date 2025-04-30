@extends('front.layout')

@section('title', 'Panier - Livres Gourmands')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('front.index') }}">Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Panier</li>
        </ol>
    </nav>

    <h1 class="mb-4">Votre Panier</h1>

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

    @if(count($cartItems) > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Articles ({{ count($cartItems) }})</h5>
                    </div>
                    <div class="card-body">
                        @foreach($cartItems as $item)
                            <div class="row mb-4 border-bottom pb-4">
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <img src="{{ asset('images/livres/default.jpg') }}" class="img-fluid rounded" alt="{{ $item['ouvrage']->titre }}">
                                </div>
                                <div class="col-md-9">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5>{{ $item['ouvrage']->titre }}</h5>
                                            <p class="text-muted mb-2">{{ $item['ouvrage']->auteur }}</p>
                                            <p class="mb-1">
                                                <span class="badge bg-secondary">{{ $item['ouvrage']->categorie->nom ?? 'Non classé' }}</span>
                                            </p>
                                            <p class="book-price">{{ number_format($item['ouvrage']->prix, 2) }} €</p>
                                        </div>
                                        <form action="{{ route('cart.remove', $item['ouvrage']->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div class="mt-3 d-flex align-items-center">
                                        <form action="{{ route('cart.update') }}" method="POST" class="d-flex align-items-center">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $item['ouvrage']->id }}">
                                            <div class="input-group input-group-sm" style="width: 130px;">
                                                <button class="btn btn-outline-secondary" type="button" onclick="decrementQuantity(this.parentNode)">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" name="quantity" class="form-control text-center" value="{{ $item['quantity'] }}" min="1" max="{{ $item['ouvrage']->stock ? $item['ouvrage']->stock->quantite : 0 }}">
                                                <button class="btn btn-outline-secondary" type="button" onclick="incrementQuantity(this.parentNode, {{ $item['ouvrage']->stock ? $item['ouvrage']->stock->quantite : 0 }})">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-primary ms-2">Mettre à jour</button>
                                        </form>
                                        <div class="ms-auto">
                                            <span class="fw-bold">{{ number_format($item['ouvrage']->prix * $item['quantity'], 2) }} €</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="d-flex">
                    <a href="{{ route('front.catalogue') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Continuer mes achats
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST" class="ms-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir vider votre panier ?')">
                            <i class="fas fa-trash me-2"></i>Vider le panier
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Récapitulatif de la commande</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Sous-total</span>
                            <span>{{ number_format($total, 2) }} €</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Frais de livraison</span>
                            <span>{{ $total >= 35 ? 'Gratuit' : number_format(4.99, 2).' €' }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold">{{ number_format($total >= 35 ? $total : $total + 4.99, 2) }} €</span>
                        </div>
                        <div class="d-grid">
                            <a href="{{ route('cart.checkout') }}" class="btn btn-primary">
                                Procéder au paiement
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="mb-3">Vous avez un code promo ?</h6>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Code promo">
                            <button class="btn btn-outline-secondary" type="button">Appliquer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card p-5 text-center">
            <div class="mb-4">
                <i class="fas fa-shopping-cart fa-4x text-muted"></i>
            </div>
            <h3>Votre panier est vide</h3>
            <p class="text-muted mb-4">Vous n'avez pas encore ajouté d'articles à votre panier.</p>
            <a href="{{ route('front.catalogue') }}" class="btn btn-primary">Parcourir le catalogue</a>
        </div>
    @endif
</div>

@section('scripts')
<script>
    function decrementQuantity(inputGroup) {
        const input = inputGroup.querySelector('input');
        let value = parseInt(input.value);
        if (value > 1) {
            input.value = value - 1;
        }
    }
    
    function incrementQuantity(inputGroup, max) {
        const input = inputGroup.querySelector('input');
        let value = parseInt(input.value);
        if (value < max) {
            input.value = value + 1;
        }
    }
</script>
@endsection
@endsection
