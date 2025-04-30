<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Ouvrage;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::with('ouvrage');

        if ($request->filled('quantite')) {  // ici
            $query->where('quantite', '<=', $request->quantite); // ici aussi
        }

        $stocks = $query->get();

        return view('stocks.index', compact('stocks'));
    }


    // Afficher le formulaire pour ajouter du stock
    public function create()
    {
        $ouvrages = Ouvrage::all();
        return view('stocks.create', compact('ouvrages'));
    }

    // Enregistrer un nouvel ajout de stock
    public function store(Request $request)
    {
        $request->validate([
            'ouvrage_id' => 'required|exists:ouvrages,id',
            'quantite' => 'required|integer|min:1'
        ]);

        $stock = Stock::firstOrNew(['ouvrage_id' => $request->ouvrage_id]);
        $stock->quantite = ($stock->quantite ?? 0) + $request->quantite;
        $stock->save();

        return redirect()->route('stocks.index')->with('success', 'Stock ajouté avec succès.');
    }

    // Afficher le formulaire de retrait de stock
    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        return view('stocks.edit', compact('stock'));
    }

    // Méthode corrigée pour ajouter un article au stock
    public function ajouter($id)
    {
        // Trouver le stock par son ID
        $stock = Stock::findOrFail($id);
        
        // Incrémenter la quantité
        $stock->increment('quantite');
        
        // Redirige avec un message de succès
        return redirect()->route('stocks.index')->with('success', 'Quantité augmentée pour ' . ($stock->ouvrage->titre ?? 'l\'article') . '.');
    }

    // Méthode corrigée pour retirer un article du stock
    public function retirer($id)
    {
        // Trouver le stock par son ID
        $stock = Stock::findOrFail($id);
        
        // Vérifie qu'il y a au moins un article en stock
        if ($stock->quantite > 0) {
            // Décrémente directement la quantité en base de données
            $stock->decrement('quantite');
            $message = 'Quantité diminuée pour ' . ($stock->ouvrage->titre ?? 'l\'article') . '.';
        } else {
            $message = 'Impossible de retirer : le stock de ' . ($stock->ouvrage->titre ?? 'l\'article') . ' est déjà à zéro.';
        }
        
        // Redirige avec un message approprié
        return redirect()->route('stocks.index')->with('success', $message);
    }

    // Mettre à jour le stock après retrait
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1'
        ]);

        $stock = Stock::findOrFail($id);
        $stock->quantite = max(0, $stock->quantite - $request->quantite);
        $stock->save();

        return redirect()->route('stocks.index')->with('success', 'Stock mis à jour.');
    }
}
