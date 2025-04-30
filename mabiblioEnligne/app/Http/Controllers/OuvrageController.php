<?php

namespace App\Http\Controllers;

use App\Models\Ouvrage;
use App\Models\Categorie;
use App\Models\LivreImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OuvrageController extends Controller
{
    public function index()
    {
        $ouvrages = Ouvrage::with('categorie')->get();
        return view('ouvrages.index', compact('ouvrages'));
    }

    public function create()
    {
        $categories = Categorie::all();
        return view('ouvrages.create', compact('categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required',
            'auteur' => 'required',
            'description' => 'required',
            'niveau_expertise' => 'required',
            'categorie_id' => 'required',
            'prix' => 'required|numeric',
            'date_publication' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation de l'image
        ]);

        // Création de l'ouvrage
        $ouvrage = Ouvrage::create($request->except('image'));

        // Gestion de l'image si présente
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Str::slug($ouvrage->titre) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/livres'), $imageName);
            
            // Création d'une entrée dans la table livre_images
            $livreImage = new LivreImage([
                'ouvrage_id' => $ouvrage->id,
                'chemin_image' => $imageName,
                'is_principale' => true
            ]);
            $livreImage->save();
        }

        return redirect()->route('ouvrages.index')->with('success', 'Ouvrage ajouté.');
    }

    public function edit($id)
    {
        $ouvrage = Ouvrage::findOrFail($id);
        $categories = Categorie::all();
        return view('ouvrages.edit', compact('ouvrage', 'categories'));
    }


    public function update(Request $request, Ouvrage $ouvrage)
    {
        // Validation mise à jour pour inclure description et niveau d'expertise
        // Ces champs sont requis pour que l'Éditeur puisse remplir son rôle
        $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'description' => 'required|string', // Ajout de la validation pour la description
            'niveau_expertise' => 'required|in:débutant,amateur,chef', // Validation du niveau d'expertise
            'prix' => 'nullable|numeric',
            'categorie_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation de l'image
        ]);

        // Mise à jour des informations de l'ouvrage
        $ouvrage->update($request->except('image'));

        // Gestion de l'image
        if ($request->hasFile('image')) {
            // Enregistrement de l'image
            $image = $request->file('image');
            $imageName = Str::slug($ouvrage->titre) . '-' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/livres'), $imageName);
            
            // Enregistrement dans la base de données
            // Si l'ouvrage a déjà une image, on la remplace
            if ($ouvrage->images->count() > 0) {
                $livreImage = $ouvrage->images->first();
                $livreImage->chemin_image = $imageName;
                $livreImage->save();
            } else {
                // Sinon on crée une nouvelle entrée
                $livreImage = new LivreImage([
                    'ouvrage_id' => $ouvrage->id,
                    'chemin_image' => $imageName,
                    'is_principale' => true
                ]);
                $livreImage->save();
            }
        }

        return redirect()->route('ouvrages.index')->with('success', 'Ouvrage mis à jour avec succès.');
    }


    public function destroy(Ouvrage $ouvrage)
    {
        $ouvrage->delete();
        return redirect()->route('ouvrages.index')->with('success', 'Ouvrage supprimé.');
    }
    
}
