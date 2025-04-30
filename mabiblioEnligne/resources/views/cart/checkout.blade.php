@extends('front.layout')

@section('title', 'Validation de commande - Livres Gourmands')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('front.index') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Panier</a></li>
            <li class="breadcrumb-item active" aria-current="page">Validation de commande</li>
        </ol>
    </nav>

    <h1 class="mb-4">Validation de votre commande</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Informations de livraison -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Adresse de livraison</h5>
                </div>
                <div class="card-body">
                    <form id="shipping-form">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" value="{{ Auth::user()->name ?? '' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" value="" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email ?? '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" required>
                        </div>

                        <div class="mb-3">
                            <label for="adresse" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="adresse" name="adresse" required>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="code_postal" class="form-label">Code postal</label>
                                <input type="text" class="form-control" id="code_postal" name="code_postal" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ville" class="form-label">Ville</label>
                                <input type="text" class="form-control" id="ville" name="ville" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="pays" class="form-label">Pays</label>
                                <select class="form-select" id="pays" name="pays" required>
                                    <option value="France">France</option>
                                    <option value="Belgique">Belgique</option>
                                    <option value="Suisse">Suisse</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="facturation_identique" name="facturation_identique" checked>
                            <label class="form-check-label" for="facturation_identique">L'adresse de facturation est identique à l'adresse de livraison</label>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Mode de livraison -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Mode de livraison</h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="shipping_method" id="shipping_standard" value="standard" checked>
                        <label class="form-check-label d-flex justify-content-between align-items-center" for="shipping_standard">
                            <div>
                                <strong>Livraison standard</strong>
                                <p class="text-muted mb-0">3-5 jours ouvrables</p>
                            </div>
                            <span>{{ $total >= 35 ? 'Gratuit' : '4.99 €' }}</span>
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="shipping_method" id="shipping_express" value="express">
                        <label class="form-check-label d-flex justify-content-between align-items-center" for="shipping_express">
                            <div>
                                <strong>Livraison express</strong>
                                <p class="text-muted mb-0">1-2 jours ouvrables</p>
                            </div>
                            <span>9.99 €</span>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="shipping_method" id="shipping_relay" value="relay">
                        <label class="form-check-label d-flex justify-content-between align-items-center" for="shipping_relay">
                            <div>
                                <strong>Point relais</strong>
                                <p class="text-muted mb-0">3-4 jours ouvrables</p>
                            </div>
                            <span>2.99 €</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Mode de paiement -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Mode de paiement</h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="carte" checked>
                        <label class="form-check-label" for="payment_card">
                            <strong>Carte bancaire</strong>
                            <div class="mt-2">
                                <div class="d-flex gap-2">
                                    <img src="https://via.placeholder.com/60x35?text=VISA" alt="Visa">
                                    <img src="https://via.placeholder.com/60x35?text=MC" alt="Mastercard">
                                    <img src="https://via.placeholder.com/60x35?text=AMEX" alt="American Express">
                                </div>
                            </div>
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment_paypal" value="paypal">
                        <label class="form-check-label" for="payment_paypal">
                            <strong>PayPal</strong>
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment_transfer" value="virement">
                        <label class="form-check-label" for="payment_transfer">
                            <strong>Virement bancaire</strong>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Récapitulatif de la commande -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Récapitulatif de la commande</h5>
                </div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('images/livres/default.jpg') }}" alt="{{ $item['ouvrage']->titre }}" class="img-thumbnail" style="width: 60px;">
                            </div>
                            <div class="ms-3">
                                <p class="mb-0">{{ Str::limit($item['ouvrage']->titre, 30) }}</p>
                                <small class="text-muted">Quantité: {{ $item['quantity'] }}</small>
                                <p class="mb-0">{{ number_format($item['ouvrage']->prix * $item['quantity'], 2) }} €</p>
                            </div>
                        </div>
                    @endforeach

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Sous-total</span>
                        <span>{{ number_format($total, 2) }} €</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Frais de livraison</span>
                        <span id="shipping-cost">{{ $total >= 35 ? 'Gratuit' : number_format(4.99, 2).' €' }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold" id="final-total">{{ number_format($total >= 35 ? $total : $total + 4.99, 2) }} €</span>
                    </div>
                    <div class="d-grid">
                        <button type="button" class="btn btn-primary" id="place-order-btn">
                            Confirmer et payer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Conditions générales -->
            <div class="card">
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="agree_terms" name="agree_terms" required>
                        <label class="form-check-label" for="agree_terms">
                            J'accepte les <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">conditions générales de vente</a>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal des CGV -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Conditions Générales de Vente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>1. Commandes</h6>
                <p>Toute commande passée sur notre site constitue un contrat conclu entre vous et Livres Gourmands.</p>
                
                <h6>2. Prix</h6>
                <p>Les prix affichés sont en euros et incluent la TVA applicable.</p>
                
                <h6>3. Livraison</h6>
                <p>Les délais de livraison sont donnés à titre indicatif et peuvent varier selon les circonstances.</p>
                
                <h6>4. Droit de rétractation</h6>
                <p>Vous disposez d'un délai de 14 jours pour vous rétracter sans avoir à justifier de motifs.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Formulaire caché pour la commande -->
<form id="order-form" action="{{ route('orders.store') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="shipping_address" id="shipping_address">
    <input type="hidden" name="shipping_city" id="shipping_city">
    <input type="hidden" name="shipping_postal_code" id="shipping_postal_code">
    <input type="hidden" name="shipping_country" id="shipping_country">
    <input type="hidden" name="billing_address" id="billing_address">
    <input type="hidden" name="billing_city" id="billing_city">
    <input type="hidden" name="billing_postal_code" id="billing_postal_code">
    <input type="hidden" name="billing_country" id="billing_country">
    <input type="hidden" name="payment_method" id="payment_method">
    <input type="hidden" name="same_as_shipping" id="same_as_shipping" value="0">
    <input type="hidden" name="notes" id="notes">
</form>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mise à jour des frais de livraison en fonction du mode choisi
        const shippingMethodInputs = document.querySelectorAll('input[name="shipping_method"]');
        const shippingCostElement = document.getElementById('shipping-cost');
        const finalTotalElement = document.getElementById('final-total');
        const subtotal = {{ $total }};
        
        shippingMethodInputs.forEach(input => {
            input.addEventListener('change', function() {
                let shippingCost = 0;
                
                if (this.value === 'standard') {
                    shippingCost = subtotal >= 35 ? 0 : 4.99;
                    shippingCostElement.textContent = subtotal >= 35 ? 'Gratuit' : '4.99 €';
                } else if (this.value === 'express') {
                    shippingCost = 9.99;
                    shippingCostElement.textContent = '9.99 €';
                } else if (this.value === 'relay') {
                    shippingCost = 2.99;
                    shippingCostElement.textContent = '2.99 €';
                }
                
                const finalTotal = subtotal + shippingCost;
                finalTotalElement.textContent = finalTotal.toFixed(2) + ' €';
            });
        });
        
        // Gestion de l'adresse de facturation
        const facturationIdentique = document.getElementById('facturation_identique');
        
        // Gestion de la soumission de la commande
        const placeOrderBtn = document.getElementById('place-order-btn');
        
        placeOrderBtn.addEventListener('click', function() {
            // Vérification que les champs obligatoires sont remplis
            const shippingForm = document.getElementById('shipping-form');
            const agreeTerms = document.getElementById('agree_terms');
            
            if (!shippingForm.checkValidity()) {
                alert('Veuillez remplir tous les champs obligatoires de l\'adresse de livraison.');
                return;
            }
            
            if (!agreeTerms.checked) {
                alert('Vous devez accepter les conditions générales de vente pour continuer.');
                return;
            }
            
            // Récupération des données du formulaire
            const adresse = document.getElementById('adresse').value;
            const ville = document.getElementById('ville').value;
            const codePostal = document.getElementById('code_postal').value;
            const pays = document.getElementById('pays').value;
            const sameAsShipping = facturationIdentique.checked;
            
            // Sélection du mode de paiement
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            
            // Remplissage du formulaire caché
            document.getElementById('shipping_address').value = adresse;
            document.getElementById('shipping_city').value = ville;
            document.getElementById('shipping_postal_code').value = codePostal;
            document.getElementById('shipping_country').value = pays;
            
            if (sameAsShipping) {
                document.getElementById('same_as_shipping').value = '1';
                document.getElementById('billing_address').value = adresse;
                document.getElementById('billing_city').value = ville;
                document.getElementById('billing_postal_code').value = codePostal;
                document.getElementById('billing_country').value = pays;
            }
            
            document.getElementById('payment_method').value = paymentMethod;
            
            // Soumission du formulaire
            document.getElementById('order-form').submit();
        });
    });
</script>
@endsection
