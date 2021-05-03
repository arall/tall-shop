<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id');
            $table->foreignId('product_id')->nullable()->onDelete('set null');
            $table->text('product_name');

            // Variants
            $table->text('variants')->nullable();

            // Prices
            $table->integer('units');
            $table->decimal('unit_price');
            $table->decimal('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_products');
    }
}
