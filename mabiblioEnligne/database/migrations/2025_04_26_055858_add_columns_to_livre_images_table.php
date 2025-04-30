<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToLivreImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('livre_images', function (Blueprint $table) {
            $table->foreignId('ouvrage_id')->constrained('ouvrages')->onDelete('cascade');
            $table->string('chemin')->nullable();
            $table->boolean('is_principale')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('livre_images', function (Blueprint $table) {
            $table->dropForeign(['ouvrage_id']);
            $table->dropColumn(['ouvrage_id', 'chemin', 'is_principale']);
        });
    }
}
