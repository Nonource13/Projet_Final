<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Commentaire;
use Illuminate\Http\Request;

class CommentaireApiController extends Controller
{
    /**
     * Retourne la liste de tous les commentaires avec leur ouvrage associé (API).
     * Utilisé pour l'affichage ou la gestion côté client ou admin.
     */
    public function index()
    {
        return Commentaire::with('ouvrage')->get();
    }

    /**
     * Enregistre un nouveau commentaire via l'API.
     * Valide les données et crée un commentaire.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ouvrage_id' => 'required|exists:ouvrages,id',
            'auteur' => 'required|string|max:255',
            'contenu' => 'required|string',
            'note' => 'required|integer|min:1|max:5',
            'valide' => 'boolean'
        ]);

        $commentaire = Commentaire::create($request->all());
        return response()->json($commentaire, 201);
    }

    /**
     * Retourne un commentaire précis avec son ouvrage (API).
     * Permet de consulter le détail d'un commentaire.
     */
    public function show($id)
    {
        return Commentaire::with('ouvrage')->findOrFail($id);
    }

    /**
     * Met à jour un commentaire existant via l'API.
     * Permet de modifier le contenu ou la validation d'un commentaire.
     */
    public function update(Request $request, $id)
    {
        $commentaire = Commentaire::findOrFail($id);
        $commentaire->update($request->all());
        return response()->json($commentaire);
    }

    /**
     * Supprime un commentaire via l'API.
     * Permet de retirer définitivement un commentaire.
     */
    public function destroy($id)
    {
        Commentaire::destroy($id);
        return response()->json(null, 204);
    }
}
