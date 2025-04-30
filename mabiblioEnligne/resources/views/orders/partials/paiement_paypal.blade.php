{{-- Bouton de paiement PayPal pour la commande --}}
@if(isset($order) && $order->payment_status !== 'paid')
    <a href="{{ route('paiement.paypal', $order->id) }}" class="btn btn-primary">
        Payer avec PayPal
    </a>
@endif
