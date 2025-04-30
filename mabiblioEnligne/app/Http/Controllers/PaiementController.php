<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\Order;

class PaiementController extends Controller
{
    /**
     * Lance le paiement PayPal pour la commande.
     *
     * @param Request $request
     * @param int $orderId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function payerAvecPayPal(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $paypal = new PayPalClient;
        $paypal->setApiCredentials(config('paypal'));
        $token = $paypal->getAccessToken();

        $response = $paypal->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "EUR",
                        "value" => $order->total_amount
                    ]
                ]
            ],
            "application_context" => [
                "return_url" => route('paiement.success', $order->id),
                "cancel_url" => route('paiement.cancel', $order->id),
            ]
        ]);

        if (isset($response['id']) && $response['status'] == 'CREATED') {
            foreach ($response['links'] as $link) {
                if ($link['rel'] == 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        }

        return redirect()->route('orders.show', $order->id)->with('error', 'Erreur lors de la création du paiement PayPal.');
    }

    /**
     * Callback succès PayPal.
     *
     * @param Request $request
     * @param int $orderId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paiementSucces(Request $request, $orderId)
    {
        $paypal = new PayPalClient;
        $paypal->setApiCredentials(config('paypal'));
        $token = $paypal->getAccessToken();

        $response = $paypal->capturePaymentOrder($request->token);

        if ($response['status'] == 'COMPLETED') {
            $order = Order::findOrFail($orderId);
            $order->payment_status = 'paid';
            $order->save();
            return redirect()->route('orders.show', $order->id)->with('success', 'Paiement PayPal réussi !');
        }

        return redirect()->route('orders.show', $orderId)->with('error', 'Échec du paiement PayPal.');
    }

    /**
     * Callback annulation PayPal.
     *
     * @param int $orderId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paiementAnnule($orderId)
    {
        return redirect()->route('orders.show', $orderId)->with('error', 'Paiement annulé.');
    }
}
