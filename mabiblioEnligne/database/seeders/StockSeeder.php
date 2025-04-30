<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ouvrage;
use App\Models\Stock;

class StockSeeder extends Seeder
{
    /**
     * Remplit le stock de chaque ouvrage avec une quantité par défaut.
     *
     * @return void
     */
    public function run()
    {
        $ouvrages = Ouvrage::all();
        foreach ($ouvrages as $ouvrage) {
            // Si le stock n'existe pas déjà
            if (!$ouvrage->stock) {
                Stock::create([
                    'ouvrage_id' => $ouvrage->id,
                    'quantite' => 10 // Quantité par défaut
                ]);
            }
        }
    }
}
