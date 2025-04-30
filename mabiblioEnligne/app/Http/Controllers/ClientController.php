<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Affiche la liste des clients.
     * Récupère tous les clients de la base de données et les affiche dans la vue clients.index.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::orderBy('nom')->get();
        return view('clients.index', compact('clients'));
    }

    /**
     * Affiche le formulaire de création d'un client.
     * Permet d'afficher un formulaire pour ajouter un nouveau client.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Enregistre un nouveau client dans la base de données.
     * Valide les données du formulaire puis crée un nouvel enregistrement Client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:20',
            'pays' => 'nullable|string|max:100',
        ]);
        
        // Création du client avec tous les attributs validés
        $client = new Client();
        $client->nom = $request->nom;
        $client->prenom = $request->prenom;
        $client->email = $request->email;
        $client->telephone = $request->telephone;
        $client->adresse = $request->adresse;
        $client->ville = $request->ville;
        $client->code_postal = $request->code_postal;
        $client->pays = $request->pays;
        $client->date_inscription = now();
        $client->actif = true;
        $client->save();
        
        return redirect()->route('clients.index')
            ->with('success', 'Client ajouté avec succès.');
    }

    /**
     * Affiche les détails d'un client spécifique.
     * Permet de consulter toutes les informations d'un client donné.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    /**
     * Affiche le formulaire d'édition d'un client.
     * Permet de modifier les informations d'un client existant.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Met à jour les informations d'un client dans la base de données.
     * Valide les données puis applique les modifications sur le client existant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:20',
            'pays' => 'nullable|string|max:100',
        ]);
        
        $client->update($request->all());
        
        return redirect()->route('clients.index')
            ->with('success', 'Client modifié avec succès.');
    }

    /**
     * Supprime un client de la base de données.
     * Permet de retirer un client définitivement.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé avec succès.');
    }

    /**
     * Désactive ou active un client.
     * Permet d'activer ou de désactiver le compte d'un client sans le supprimer.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus(Client $client)
    {
        $client->actif = !$client->actif;
        $client->save();
        
        $status = $client->actif ? 'activé' : 'désactivé';
        return redirect()->route('clients.index')
            ->with('success', "Le compte client a été $status avec succès.");
    }
}
