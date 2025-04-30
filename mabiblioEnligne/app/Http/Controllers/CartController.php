<?php

namespace App\Http\Controllers;

use App\Models\Ouvrage;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Afficher le contenu du panier.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $total = 0;
        
        foreach ($cart as $id => $details) {
            $ouvrage = Ouvrage::with('stock')->find($id);
            if ($ouvrage) {
                $cartItems[] = [
                    'ouvrage' => $ouvrage,
                    'quantity' => $details['quantity']
                ];
                $total += $ouvrage->prix * $details['quantity'];
            }
        }
        
        return view('cart.index', compact('cartItems', 'total'));
    }
    
    /**
     * Ajouter un produit au panier.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $id = $request->id;
        $quantity = $request->quantity ?? 1;
        
        $ouvrage = Ouvrage::with('stock')->findOrFail($id);
        
        // Vérifier la disponibilité du stock
        if (!$ouvrage->stock || $ouvrage->stock->quantite < $quantity) {
            return redirect()->back()->with('error', 'Stock insuffisant pour cet ouvrage.');
        }
        
        $cart = session()->get('cart', []);
        
        // Si l'article est déjà dans le panier, augmenter la quantité
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'quantity' => $quantity
            ];
        }
        
        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', 'Ouvrage ajouté au panier avec succès.');
    }
    
    /**
     * Mettre à jour la quantité d'un produit dans le panier.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $quantity = $request->quantity;
        
        if ($quantity <= 0) {
            return $this->remove($id);
        }
        
        $ouvrage = Ouvrage::with('stock')->findOrFail($id);
        
        // Vérifier la disponibilité du stock
        if (!$ouvrage->stock || $ouvrage->stock->quantite < $quantity) {
            return redirect()->back()->with('error', 'Stock insuffisant pour cet ouvrage.');
        }
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }
        
        return redirect()->back()->with('success', 'Panier mis à jour avec succès.');
    }
    
    /**
     * Supprimer un produit du panier.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        
        return redirect()->back()->with('success', 'Produit retiré du panier.');
    }
    
    /**
     * Vider complètement le panier.
     *
     * @return \Illuminate\Http\Response
     */
    public function clear()
    {
        session()->forget('cart');
        
        return redirect()->back()->with('success', 'Panier vidé avec succès.');
    }
    
    /**
     * Passer au processus de commande.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }
        
        // Vérifier que l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour passer une commande.');
        }
        
        $cartItems = [];
        $total = 0;
        
        foreach ($cart as $id => $details) {
            $ouvrage = Ouvrage::with('stock')->find($id);
            if ($ouvrage) {
                $cartItems[] = [
                    'ouvrage' => $ouvrage,
                    'quantity' => $details['quantity']
                ];
                $total += $ouvrage->prix * $details['quantity'];
            }
        }
        
        return view('cart.checkout', compact('cartItems', 'total'));
    }
}
