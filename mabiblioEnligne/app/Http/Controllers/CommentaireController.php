<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use Illuminate\Http\Request;

class CommentaireController extends Controller
{
    /**
     * Affiche la liste des commentaires.
     * Récupère tous les commentaires avec leur ouvrage associé et les affiche dans la vue commentaires.index.
     */
    public function index()
    {
        $commentaires = Commentaire::with('ouvrage')->get();
        return view('commentaires.index', compact('commentaires'));
    }

    /**
     * Affiche le formulaire de création d'un commentaire.
     * Permet à l'utilisateur de laisser un avis sur un ouvrage.
     */
    public function create()
    {
        $ouvrages = \App\Models\Ouvrage::all();
        return view('commentaires.create', compact('ouvrages'));
    }

    /**
     * Valide un commentaire (modération).
     * Permet à un éditeur d'approuver un commentaire pour qu'il soit visible publiquement.
     */
    public function valider($id)
    {
        $commentaire = Commentaire::findOrFail($id);
        $commentaire->valide = true;
        $commentaire->save();

        return redirect()->back()->with('success', 'Commentaire validé.');
    }

    /**
     * Supprime un commentaire.
     * Permet de retirer un commentaire de la base de données.
     */
    public function supprimer($id)
    {
        Commentaire::destroy($id);
        return redirect()->back()->with('success', 'Commentaire supprimé.');
    }

    /**
     * Affiche le formulaire d'édition d'un commentaire.
     * Permet de modifier le contenu d'un commentaire existant.
     */
    public function edit($id)
    {
        $commentaire = \App\Models\Commentaire::findOrFail($id);
        $ouvrages = \App\Models\Ouvrage::all();
        return view('commentaires.edit', compact('commentaire', 'ouvrages'));
    }

    /**
     * Enregistre un nouveau commentaire.
     * Valide les données et crée un nouveau commentaire (non validé par défaut).
     */
    public function store(Request $request)
    {
        $request->validate([
            'ouvrage_id' => 'required|exists:ouvrages,id',
            'auteur' => 'required|string|max:255',
            'contenu' => 'required|string',
            'note' => 'required|integer|min:1|max:5',
        ]);

        // Par défaut, les commentaires ne sont pas validés jusqu'à ce qu'un éditeur les approuve
        $commentaire = new Commentaire($request->all());
        $commentaire->valide = false;
        $commentaire->save();

        return redirect()->back()->with('success', 'Merci ! Votre avis a été soumis et sera publié après validation.');
    }
}
