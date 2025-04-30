<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('ouvrage_id')->constrained();
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // Prix unitaire au moment de l'achat
            $table->decimal('subtotal', 10, 2); // Prix total pour cet article
            $table->boolean('gift_list_item')->default(false); // Indique si l'article provient d'une liste de cadeaux
            $table->foreignId('gift_list_id')->nullable()->constrained()->nullOnDelete();
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
        Schema::dropIfExists('order_items');
    }
}
