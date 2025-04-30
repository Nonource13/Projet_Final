<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateLivreImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('livre_images');
        
        Schema::create('livre_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ouvrage_id')->constrained('ouvrages')->onDelete('cascade');
            $table->string('chemin_image')->nullable();
            $table->boolean('is_principale')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('livre_images');
    }
}
