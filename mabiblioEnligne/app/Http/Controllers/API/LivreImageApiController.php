<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LivreImage;
use Illuminate\Http\Request;

class LivreImageApiController extends Controller
{
    // GET /api/livre-images
    public function index()
    {
        return LivreImage::with('ouvrage')->get();
    }

    // POST /api/livre-images
    public function store(Request $request)
    {
        $request->validate([
            'ouvrage_id' => 'required|exists:ouvrages,id',
            'chemin_image' => 'required|string|max:255',
        ]);

        $image = LivreImage::create($request->all());
        return response()->json($image, 201);
    }

    // GET /api/livre-images/{id}
    public function show($id)
    {
        return LivreImage::with('ouvrage')->findOrFail($id);
    }

    // PUT /api/livre-images/{id}
    public function update(Request $request, $id)
    {
        $image = LivreImage::findOrFail($id);
        $image->update($request->all());
        return response()->json($image);
    }

    // DELETE /api/livre-images/{id}
    public function destroy($id)
    {
        LivreImage::destroy($id);
        return response()->json(null, 204);
    }
}
