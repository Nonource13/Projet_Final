<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonPremierControllerAPI extends Controller
{
    // Methode pour recuperer les donnees
    public function getDonnees(){
        return[
            "nom" => "Jean",
            "mail" => "Jea@gmail.comn",
            "adresse" => "montreal"
        ];
    }
}
