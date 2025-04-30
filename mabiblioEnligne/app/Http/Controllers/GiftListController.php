<?php

namespace App\Http\Controllers;

use App\Models\GiftList;
use App\Models\Ouvrage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GiftListController extends Controller
{
    /**
     * Constructeur - Applique le middleware d'authentification
     * Permet de protéger les routes sauf l'affichage public des listes.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'showByCode']);
    }

    /**
     * Affiche la liste des listes de cadeaux de l'utilisateur connecté.
     * Récupère toutes les listes créées par l'utilisateur.
     */
    public function index()
    {
        $giftLists = GiftList::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('gift_lists.index', compact('giftLists'));
    }

    /**
     * Affiche le formulaire de création d'une liste de cadeaux.
     * Permet à l'utilisateur de préparer une nouvelle liste pour un événement.
     */
    public function create()
    {
        return view('gift_lists.create');
    }

    /**
     * Enregistre une nouvelle liste de cadeaux.
     * Valide les données et crée une nouvelle liste associée à l'utilisateur.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'occasion' => 'nullable|string|max:100',
            'event_date' => 'nullable|date',
            'is_public' => 'boolean',
            'expiry_date' => 'nullable|date|after:today',
        ]);

        $giftList = new GiftList($request->all());
        $giftList->user_id = Auth::id();
        $giftList->access_code = GiftList::generateAccessCode();
        $giftList->save();

        return redirect()->route('gift-lists.show', $giftList->id)
            ->with('success', 'Votre liste de cadeaux a été créée avec succès !');
    }

    /**
     * Affiche une liste de cadeaux spécifique.
     * Permet à l'utilisateur ou à un invité de consulter une liste (publique ou privée).
     */
    public function show($id)
    {
        $giftList = GiftList::with('ouvrages')->findOrFail($id);
        
        // Vérifier si l'utilisateur est autorisé à voir cette liste
        if ($giftList->user_id != Auth::id() && !$giftList->is_public) {
            return redirect()->route('gift-lists.index')
                ->with('error', 'Vous n\'êtes pas autorisé à voir cette liste.');
        }
        
        return view('gift_lists.show', compact('giftList'));
    }

    /**
     * Affiche une liste de cadeaux à partir d'un code d'accès public.
     * Permet de partager une liste avec des invités sans compte.
     */
    public function showByCode($code)
    {
        $giftList = GiftList::where('access_code', $code)->firstOrFail();
        return view('gift_lists.show_public', compact('giftList'));
    }

    /**
     * Affiche le formulaire d'édition d'une liste de cadeaux.
     * Permet de modifier une liste existante.
     */
    public function edit($id)
    {
        $giftList = GiftList::findOrFail($id);
        
        // Vérifier si l'utilisateur est autorisé à modifier cette liste
        if ($giftList->user_id != Auth::id()) {
            return redirect()->route('gift-lists.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette liste.');
        }
        
        return view('gift_lists.edit', compact('giftList'));
    }

    /**
     * Met à jour une liste de cadeaux existante.
     * Valide les modifications et les applique à la liste.
     */
    public function update(Request $request, $id)
    {
        $giftList = GiftList::findOrFail($id);
        
        // Vérifier si l'utilisateur est autorisé à modifier cette liste
        if ($giftList->user_id != Auth::id()) {
            return redirect()->route('gift-lists.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette liste.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'occasion' => 'nullable|string|max:100',
            'event_date' => 'nullable|date',
            'is_public' => 'boolean',
            'expiry_date' => 'nullable|date|after:today',
        ]);
        
        $giftList->update($request->all());
        
        return redirect()->route('gift-lists.show', $giftList->id)
            ->with('success', 'Votre liste de cadeaux a été mise à jour avec succès !');
    }

    /**
     * Supprime une liste de cadeaux spécifique.
     * Permet à l'utilisateur de retirer une liste définitivement.
     */
    public function destroy($id)
    {
        $giftList = GiftList::findOrFail($id);
        
        // Vérifier si l'utilisateur est autorisé à supprimer cette liste
        if ($giftList->user_id != Auth::id()) {
            return redirect()->route('gift-lists.index')
                ->with('error', 'Vous n\'êtes pas autorisé à supprimer cette liste.');
        }
        
        $giftList->delete();
        
        return redirect()->route('gift-lists.index')
            ->with('success', 'Liste supprimée avec succès.');
    }

    /**
     * Ajouter un ouvrage à la liste de cadeaux.
     * Permet à l'utilisateur d'ajouter un ouvrage à sa liste.
     */
    public function addItem(Request $request, $id)
    {
        $giftList = GiftList::findOrFail($id);
        
        // Vérifier si l'utilisateur est autorisé à modifier cette liste
        if ($giftList->user_id != Auth::id()) {
            return redirect()->route('gift-lists.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette liste.');
        }
        
        $request->validate([
            'ouvrage_id' => 'required|exists:ouvrages,id',
            'quantity' => 'required|integer|min:1',
            'priority' => 'required|integer|min:1|max:5',
        ]);
        
        // Vérifier si l'ouvrage est déjà dans la liste
        if ($giftList->ouvrages()->where('ouvrage_id', $request->ouvrage_id)->exists()) {
            return redirect()->back()
                ->with('error', 'Cet ouvrage est déjà dans votre liste.');
        }
        
        $giftList->ouvrages()->attach($request->ouvrage_id, [
            'quantity' => $request->quantity,
            'priority' => $request->priority,
            'is_reserved' => false,
        ]);
        
        return redirect()->route('gift-lists.show', $giftList->id)
            ->with('success', 'Ouvrage ajouté à votre liste de cadeaux.');
    }

    /**
     * Retirer un ouvrage de la liste de cadeaux.
     * Permet à l'utilisateur de retirer un ouvrage de sa liste.
     */
    public function removeItem($listId, $ouvrageId)
    {
        $giftList = GiftList::findOrFail($listId);
        
        // Vérifier si l'utilisateur est autorisé à modifier cette liste
        if ($giftList->user_id != Auth::id()) {
            return redirect()->route('gift-lists.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette liste.');
        }
        
        $giftList->ouvrages()->detach($ouvrageId);
        
        return redirect()->route('gift-lists.show', $giftList->id)
            ->with('success', 'Ouvrage retiré de votre liste de cadeaux.');
    }

    /**
     * Réserver un ouvrage de la liste de cadeaux (pour les amis).
     * Permet à un invité de réserver un ouvrage pour un événement.
     */
    public function reserveItem(Request $request, $code, $ouvrageId)
    {
        $giftList = GiftList::where('access_code', $code)->firstOrFail();
        
        $request->validate([
            'reserved_by' => 'required|string|max:255',
        ]);
        
        $giftList->ouvrages()->updateExistingPivot($ouvrageId, [
            'is_reserved' => true,
            'reserved_by' => $request->reserved_by,
        ]);
        
        return redirect()->back()
            ->with('success', 'Vous avez réservé cet ouvrage avec succès !');
    }

    /**
     * Générer un nouveau code d'accès pour la liste.
     * Permet à l'utilisateur de générer un nouveau code pour partager sa liste.
     */
    public function regenerateCode($id)
    {
        $giftList = GiftList::findOrFail($id);
        
        // Vérifier si l'utilisateur est autorisé à modifier cette liste
        if ($giftList->user_id != Auth::id()) {
            return redirect()->route('gift-lists.index')
                ->with('error', 'Vous n\'êtes pas autorisé à modifier cette liste.');
        }
        
        $giftList->access_code = GiftList::generateAccessCode();
        $giftList->save();
        
        return redirect()->route('gift-lists.show', $giftList->id)
            ->with('success', 'Un nouveau code d\'accès a été généré pour votre liste.');
    }
}
