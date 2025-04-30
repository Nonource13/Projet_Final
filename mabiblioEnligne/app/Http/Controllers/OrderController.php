<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ouvrage;
use App\Models\Stock;
use App\Models\Vente;
use App\Models\GiftList;
use App\Models\GiftListItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Constructeur - Applique le middleware d'authentification
     * Permet de s'assurer que seules les personnes connectées peuvent accéder aux commandes.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche la liste des commandes de l'utilisateur connecté.
     * Récupère toutes les commandes associées à l'utilisateur et les affiche dans la vue orders.index.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('orders.index', compact('orders'));
    }

    /**
     * Affiche les détails d'une commande spécifique de l'utilisateur.
     * Permet de voir les articles commandés, leur quantité et leur prix.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::with('items.ouvrage')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('orders.show', compact('order'));
    }

    /**
     * Crée une nouvelle commande à partir du panier utilisateur.
     * Valide les informations de livraison et de paiement, puis enregistre la commande et les articles associés.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation des données de livraison et paiement
        $request->validate([
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_postal_code' => 'required|string',
            'shipping_country' => 'required|string',
            'payment_method' => 'required|in:carte,paypal,virement',
        ]);

        // Vérifier que le panier n'est pas vide
        if (!session()->has('cart') || count(session()->get('cart')) == 0) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $cart = session()->get('cart');
        $totalAmount = 0;

        // Calcul du montant total de la commande
        foreach ($cart as $id => $details) {
            $ouvrage = Ouvrage::findOrFail($id);
            $totalAmount += $ouvrage->prix * $details['quantity'];

            // Vérifier la disponibilité en stock
            $stock = Stock::where('ouvrage_id', $id)->first();
            if (!$stock || $stock->quantite < $details['quantity']) {
                return redirect()->route('cart.index')->with('error', 'Le livre "' . $ouvrage->titre . '" n\'est plus disponible en quantité suffisante.');
            }
        }

        try {
            DB::beginTransaction();

            // Création de la commande
            $order = new Order();
            $order->user_id = Auth::id();
            $order->order_number = Order::generateOrderNumber();
            $order->total_amount = $totalAmount;
            $order->status = 'pending';
            $order->payment_method = $request->payment_method;
            $order->payment_status = 'pending';
            $order->shipping_address = $request->shipping_address;
            $order->shipping_city = $request->shipping_city;
            $order->shipping_postal_code = $request->shipping_postal_code;
            $order->shipping_country = $request->shipping_country;
            
            // Copier l'adresse de livraison comme adresse de facturation si nécessaire
            if ($request->has('same_as_shipping')) {
                $order->billing_address = $request->shipping_address;
                $order->billing_city = $request->shipping_city;
                $order->billing_postal_code = $request->shipping_postal_code;
                $order->billing_country = $request->shipping_country;
            } else {
                $order->billing_address = $request->billing_address;
                $order->billing_city = $request->billing_city;
                $order->billing_postal_code = $request->billing_postal_code;
                $order->billing_country = $request->billing_country;
            }
            
            $order->notes = $request->notes;
            $order->save();

            // Création des lignes de commande
            foreach ($cart as $id => $details) {
                $ouvrage = Ouvrage::findOrFail($id);
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->ouvrage_id = $id;
                $orderItem->quantity = $details['quantity'];
                $orderItem->price = $ouvrage->prix;
                $orderItem->subtotal = $ouvrage->prix * $details['quantity'];

                // Si l'article provient d'une liste de cadeaux
                if (isset($details['gift_list_id'])) {
                    $orderItem->gift_list_item = true;
                    $orderItem->gift_list_id = $details['gift_list_id'];
                }

                $orderItem->save();

                // Mise à jour du stock
                $stock = Stock::where('ouvrage_id', $id)->first();
                if ($stock) {
                    $stock->quantite -= $details['quantity'];
                    $stock->save();
                }

                // Enregistrement de la vente
                $vente = new Vente();
                $vente->ouvrage_id = $id;
                $vente->quantite = $details['quantity'];
                $vente->prix_total = $ouvrage->prix * $details['quantity'];
                $vente->date_vente = now();
                $vente->save();

                // Si l'article provient d'une liste de cadeaux, le marquer comme acheté
                if (isset($details['gift_list_id'])) {
                    $giftListItem = GiftListItem::where('gift_list_id', $details['gift_list_id'])
                        ->where('ouvrage_id', $id)
                        ->first();
                        
                    if ($giftListItem) {
                        $giftListItem->is_purchased = true;
                        $giftListItem->purchased_by = Auth::user()->name;
                        $giftListItem->save();
                    }
                }
            }

            DB::commit();

            // Vider le panier
            session()->forget('cart');

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Votre commande a été passée avec succès ! Votre numéro de commande est : ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors du traitement de votre commande. Veuillez réessayer.');
        }
    }

    /**
     * Annule une commande en attente.
     * Permet à l'utilisateur de supprimer une commande qui n'a pas encore été traitée.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        
        if (!$order->isPending()) {
            return redirect()->back()->with('error', 'Vous ne pouvez annuler que les commandes en attente.');
        }
        
        try {
            DB::beginTransaction();
            
            // Mettre à jour le statut de la commande
            $order->status = 'cancelled';
            $order->save();
            
            // Pour chaque ligne de commande, remettre les articles en stock
            foreach ($order->items as $item) {
                $stock = Stock::where('ouvrage_id', $item->ouvrage_id)->first();
                if ($stock) {
                    $stock->quantite += $item->quantity;
                    $stock->save();
                }
                
                // Supprimer la vente correspondante
                Vente::where('ouvrage_id', $item->ouvrage_id)
                    ->where('quantite', $item->quantity)
                    ->where('date_vente', $order->created_at)
                    ->delete();
                    
                // Si l'article provient d'une liste de cadeaux, annuler l'achat
                if ($item->gift_list_item && $item->gift_list_id) {
                    $giftListItem = GiftListItem::where('gift_list_id', $item->gift_list_id)
                        ->where('ouvrage_id', $item->ouvrage_id)
                        ->first();
                        
                    if ($giftListItem) {
                        $giftListItem->is_purchased = false;
                        $giftListItem->purchased_by = null;
                        $giftListItem->save();
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('orders.index')
                ->with('success', 'Votre commande a été annulée avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors de l\'annulation de votre commande.');
        }
    }

    /**
     * Génère la facture PDF pour une commande.
     * Permet à l'utilisateur de télécharger une facture pour une commande spécifique.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function invoice($id)
    {
        $order = Order::with('items.ouvrage', 'user')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        // Ici, on pourrait générer un PDF avec une bibliothèque comme dompdf
        // Pour simplifier, on affiche juste une vue
        return view('orders.invoice', compact('order'));
    }
}
