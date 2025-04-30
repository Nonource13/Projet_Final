<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieApiController extends Controller
{
    // GET /api/categories
    public function index()
    {
        return Categorie::all();
    }

    // POST /api/categories
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
        ]);

        $categorie = Categorie::create($request->all());
        return response()->json($categorie, 201);
    }

    // GET /api/categories/{id}
    public function show($id)
    {
        return Categorie::findOrFail($id);
    }

    // PUT /api/categories/{id}
    public function update(Request $request, $id)
    {
        $categorie = Categorie::findOrFail($id);
        $categorie->update($request->all());
        return response()->json($categorie);
    }

    // DELETE /api/categories/{id}
    public function destroy($id)
    {
        Categorie::destroy($id);
        return response()->json(null, 204);
    }
}
