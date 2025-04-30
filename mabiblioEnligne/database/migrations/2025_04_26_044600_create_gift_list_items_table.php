<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftListItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gift_list_id')->constrained()->onDelete('cascade');
            $table->foreignId('ouvrage_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->integer('priority')->default(3); // 1-5 scale, 5 being highest priority
            $table->boolean('is_reserved')->default(false);
            $table->string('reserved_by')->nullable();
            $table->timestamps();
            
            // Un même ouvrage ne peut apparaître qu'une fois dans une liste
            $table->unique(['gift_list_id', 'ouvrage_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gift_list_items');
    }
}
