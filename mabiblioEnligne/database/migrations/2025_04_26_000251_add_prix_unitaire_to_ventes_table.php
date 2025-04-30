<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrixUnitaireToVentesTable extends Migration
{
    public function up()
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->decimal('prix_unitaire', 8, 2)->after('quantite');
        });
    }

    public function down()
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->dropColumn('prix_unitaire');
        });
    }
}
