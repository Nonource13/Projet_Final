<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ouvrage;
use App\Models\Categorie;
use App\Models\Commentaire;

class FrontController extends Controller
{
    /**
     * Affiche la page d'accueil du site.
     * Récupère les nouveautés (derniers livres ajoutés) et les catégories principales pour la page d'accueil.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Récupération des derniers livres ajoutés
        $nouveautes = Ouvrage::with(['categorie', 'stock'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
        
        // Récupération des catégories principales
        $categories = Categorie::withCount('ouvrages')->get();
        
        return view('front.index', compact('nouveautes', 'categories'));
    }
    
    /**
     * Affiche le catalogue complet des livres avec filtres et tri.
     * Permet de filtrer par catégorie, niveau d'expertise, recherche texte et de trier les résultats.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function catalogue(Request $request)
    {
        // Base query
        $query = Ouvrage::with(['categorie', 'stock']);
        
        // Filtres
        if ($request->has('categorie_id') && $request->categorie_id != '') {
            $query->where('categorie_id', $request->categorie_id);
        }
        
        if ($request->has('niveau_expertise') && $request->niveau_expertise != '') {
            $query->where('niveau_expertise', $request->niveau_expertise);
        }
        
        if ($request->has('search') && trim($request->search) != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('auteur', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Tri
        $sort = $request->sort ?? 'newest';
        switch ($sort) {
            case 'prix_asc':
                $query->orderBy('prix', 'asc');
                break;
            case 'prix_desc':
                $query->orderBy('prix', 'desc');
                break;
            case 'titre':
                $query->orderBy('titre', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        // Pagination
        $ouvrages = $query->paginate(12);
        
        // Catégories pour le filtre
        $categories = Categorie::all();
        
        return view('front.catalogue', compact('ouvrages', 'categories'));
    }

    /**
     * Affiche le détail d'un ouvrage (livre) spécifique.
     * Permet de voir les informations détaillées d'un livre et ses commentaires associés.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        $ouvrage = Ouvrage::with(['categorie', 'stock', 'commentaires' => function($q) {
            $q->where('valide', true);
        }])->findOrFail($id);
        
        // Livres similaires (même catégorie)
        $similaires = Ouvrage::where('categorie_id', $ouvrage->categorie_id)
            ->where('id', '!=', $ouvrage->id)
            ->take(4)
            ->get();
            
        return view('front.detail', compact('ouvrage', 'similaires'));
    }

    /**
     * Affiche la page "À propos" du site.
     */
    public function about()
    {
        return view('front.about');
    }

    /**
     * Affiche la page de contact.
     */
    public function contact()
    {
        return view('front.contact');
    }

    /**
     * Recherche d'ouvrages par mot-clé (titre, auteur, description).
     * Permet à l'utilisateur de chercher un livre selon un mot-clé saisi.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function recherche(Request $request)
    {
        if (!$request->has('q') || trim($request->q) == '') {
            return redirect()->route('front.catalogue');
        }
        
        $search = $request->q;
        
        $ouvrages = Ouvrage::where('titre', 'like', "%{$search}%")
            ->orWhere('auteur', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->with(['categorie', 'stock'])
            ->paginate(12);
            
        return view('front.recherche', compact('ouvrages', 'search'));
    }
}
