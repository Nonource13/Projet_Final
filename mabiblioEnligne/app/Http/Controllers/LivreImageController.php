<?php

namespace App\Http\Controllers;

use App\Models\LivreImage;
use App\Models\Ouvrage;
use Illuminate\Http\Request;

class LivreImageController extends Controller
{
    public function index()
    {
        $images = LivreImage::with('ouvrage')->get();
        return view('images.index', compact('images'));
    }

    public function create()
    {
        $ouvrages = Ouvrage::all();
        return view('images.create', compact('ouvrages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ouvrage_id' => 'required',
            'chemin_image' => 'required|string'
        ]);

        LivreImage::create($request->all());

        return redirect()->route('images.index')->with('success', 'Image enregistrée.');
    }

    public function edit(LivreImage $image)
    {
        $ouvrages = Ouvrage::all();
        return view('images.edit', compact('image', 'ouvrages'));
    }

    public function update(Request $request, LivreImage $image)
    {
        $request->validate([
            'ouvrage_id' => 'required',
            'chemin_image' => 'required|string'
        ]);

        $image->update($request->all());

        return redirect()->route('images.index')->with('success', 'Image mise à jour.');
    }

    public function destroy(LivreImage $image)
    {
        $image->delete();
        return redirect()->route('images.index')->with('success', 'Image supprimée.');
    }
}
