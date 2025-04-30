<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ouvrage;
use Illuminate\Http\Request;

class OuvrageApiController extends Controller
{
    /**
     * Retourne la liste de tous les ouvrages avec leur catégorie (API).
     * Permet d'afficher tous les livres disponibles côté client ou admin.
     */
    public function index()
    {
        return Ouvrage::with('categorie')->get();
    }

    /**
     * Enregistre un nouvel ouvrage via l'API.
     * Valide les données et crée un livre dans la base.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'description' => 'required|string',
            'niveau_expertise' => 'required|in:débutant,amateur,chef',
            'categorie_id' => 'required|exists:categories,id',
            'prix' => 'required|numeric',
            'date_publication' => 'required|date',
        ]);

        $ouvrage = Ouvrage::create($request->all());
        return response()->json($ouvrage, 201);
    }

    /**
     * Retourne un ouvrage précis avec sa catégorie (API).
     * Permet de consulter le détail d'un livre.
     */
    public function show($id)
    {
        return Ouvrage::with('categorie')->findOrFail($id);
    }

    /**
     * Met à jour un ouvrage existant via l'API.
     * Permet de modifier les informations d'un livre.
     */
    public function update(Request $request, $id)
    {
        $ouvrage = Ouvrage::findOrFail($id);
        $ouvrage->update($request->all());
        return response()->json($ouvrage);
    }

    /**
     * Supprime un ouvrage via l'API.
     * Permet de retirer définitivement un livre de la base.
     */
    public function destroy($id)
    {
        Ouvrage::destroy($id);
        return response()->json(null, 204);
    }
    
    /**
     * Recherche des ouvrages par titre ou auteur (utilisé pour les listes de cadeaux).
     * Permet de trouver rapidement un livre pour l'ajouter à une liste.
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query) || strlen($query) < 3) {
            return response()->json([]);
        }
        
        $ouvrages = Ouvrage::where('titre', 'like', "%{$query}%")
            ->orWhere('auteur', 'like', "%{$query}%")
            ->with(['categorie', 'images'])
            ->orderBy('titre')
            ->limit(20)
            ->get();
        
        // Préparer les données pour la réponse JSON
        $result = $ouvrages->map(function ($ouvrage) {
            return [
                'id' => $ouvrage->id,
                'titre' => $ouvrage->titre,
                'auteur' => $ouvrage->auteur,
                'prix' => $ouvrage->prix,
                'niveau_expertise' => $ouvrage->niveau_expertise,
                'categorie' => $ouvrage->categorie ? $ouvrage->categorie->nom : null,
                'image' => $ouvrage->images->first() ? $ouvrage->images->first()->chemin : 'default.jpg'
            ];
        });
        
        return response()->json($result);
    }
}
