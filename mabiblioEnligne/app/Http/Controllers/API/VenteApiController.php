<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vente;
use Illuminate\Http\Request;

class VenteApiController extends Controller
{
    /**
     * Retourne la liste de toutes les ventes avec leur ouvrage associé (API).
     * Permet de suivre les ventes côté admin ou client.
     */
    public function index()
    {
        return Vente::with('ouvrage')->get();
    }

    /**
     * Enregistre une nouvelle vente via l'API.
     * Valide les données et crée une vente dans la base.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ouvrage_id' => 'required|exists:ouvrages,id',
            'quantite' => 'required|integer|min:1',
            'prix_total' => 'required|numeric|min:0',
            'date_vente' => 'required|date',
        ]);

        $vente = Vente::create($request->all());
        return response()->json($vente, 201);
    }

    /**
     * Retourne une vente précise avec son ouvrage (API).
     * Permet de consulter le détail d'une vente.
     */
    public function show($id)
    {
        return Vente::with('ouvrage')->findOrFail($id);
    }

    /**
     * Met à jour une vente existante via l'API.
     * Permet de modifier les informations d'une vente.
     */
    public function update(Request $request, $id)
    {
        $vente = Vente::findOrFail($id);
        $vente->update($request->all());
        return response()->json($vente);
    }

    /**
     * Supprime une vente via l'API.
     * Permet de retirer définitivement une vente de la base.
     */
    public function destroy($id)
    {
        Vente::destroy($id);
        return response()->json(null, 204);
    }
}
