<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id');
            $table->foreignId('product_id');

            // Attributes
            $table->text('attributes')->nullable();

            // Prices
            $table->integer('units');
            $table->decimal('price')->nullable();               // Product price without discount & with taxes
            $table->decimal('discount')->nullable();            // Tax %
            $table->decimal('tax')->nullable();                 // Discount %
            $table->decimal('unit_total_price')->nullable();    // Product price + discount + taxes
            $table->decimal('total_price')->nullable();         // Product total price * units

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
        Schema::dropIfExists('order_product');
    }
}
