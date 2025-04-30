<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockApiController extends Controller
{
    // GET /api/stocks
    public function index()
    {
        return Stock::with('ouvrage')->get();
    }

    // POST /api/stocks
    public function store(Request $request)
    {
        $request->validate([
            'ouvrage_id' => 'required|exists:ouvrages,id',
            'quantite' => 'required|integer|min:0',
        ]);

        $stock = Stock::create($request->all());
        return response()->json($stock, 201);
    }

    // GET /api/stocks/{id}
    public function show($id)
    {
        return Stock::with('ouvrage')->findOrFail($id);
    }

    // PUT /api/stocks/{id}
    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);
        $stock->update($request->all());
        return response()->json($stock);
    }

    // DELETE /api/stocks/{id}
    public function destroy($id)
    {
        Stock::destroy($id);
        return response()->json(null, 204);
    }
}
