<?php

namespace App\Http\Controllers;

use App\Models\LivreImage;
use Illuminate\Http\Request;
use PhpParser\Builder\Function_;
use PhpParser\Node\Expr\FuncCall;

class ControllerLivreImage extends Controller
{
    // public Function getLivreImage(){
    //     return LivreImage::all();
    // }
    public Function getLivreImage(){

        $LivreImage = LivreImage::all();
        return view("livreimage",compact("LivreImage"));
    }

    // methode d'ajout d'un livre
    public function addlivreImage(Request $request){
        $LivreImage = new LivreImage();
        $LivreImage->titre=$request->titre;
        $LivreImage->genre=$request->genre;
        $LivreImage->anneedepublication=$request->anneedepublication;
        // traitement de l'addlivreImage
        if ($request->hasFile("photo")){

            $file = $request->file("photo");
            $imagenam = time()."_".$file->getClientOriginalName();
            $file->move(public_path("assets/img"),$imagenam);

        }
        $LivreImage->photo=$imagenam;
        // Ajouter a la base de donnee
        $LivreImage->save();
        return redirect()->back()->with("Livre ajouter avec succes");
    }
}
