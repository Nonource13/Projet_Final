<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Ouvrage;
use Illuminate\Http\Request;

class VenteController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Vente::with('ouvrage');

        if ($request->filled('date_debut')) {
            $query->whereDate('date_vente', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_vente', '<=', $request->date_fin);
        }

        $ventes = $query->get();

        // ✅ Regroupement par ouvrage pour analyse
        $ventesParOuvrage = $ventes->groupBy('ouvrage_id')->map(function ($ventes) {
            return [
                'titre' => $ventes->first()->ouvrage->titre ?? 'Inconnu',
                'quantite_totale' => $ventes->sum('quantite'),
                'revenu_total' => $ventes->sum('prix_total'),
            ];
        });

        return view('ventes.index', compact('ventes', 'ventesParOuvrage'));
    }



    public function create()
    {
        $ouvrages = Ouvrage::all();
        return view('ventes.create', compact('ouvrages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ouvrage_id' => 'required|exists:ouvrages,id',
            'quantite' => 'required|integer|min:1'
        ]);

        // Récupération de l'ouvrage et son prix
        $ouvrage = \App\Models\Ouvrage::findOrFail($request->ouvrage_id);
        
        // Vérification que le prix est défini et supérieur à zéro
        if ($ouvrage->prix <= 0) {
            // Mettre à jour le prix de l'ouvrage si nécessaire
            $ouvrage->prix = 19.99; // Prix par défaut si le prix est à zéro ou négatif
            $ouvrage->save();
        }
        
        $prix_unitaire = $ouvrage->prix;
        $prix_total = $prix_unitaire * $request->quantite;

        // Création de la vente SANS utiliser le champ prix_unitaire qui n'existe pas dans la table
        \App\Models\Vente::create([
            'ouvrage_id'    => $ouvrage->id,
            'quantite'      => $request->quantite,
            'prix_total'    => $prix_total,
            'date_vente'    => now()
        ]);

        return redirect()->route('ventes.index')->with('success', 'Vente enregistrée.');
    }



    public function destroy(Vente $vente)
    {
        $vente->delete();
        return redirect()->route('ventes.index')->with('success', 'Vente supprimée avec succès.');
    }
}
