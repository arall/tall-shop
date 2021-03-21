<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id');
            $table->foreignId('category_id')->nullable();
            $table->foreignId('cover_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('ref')->nullable()->unique();
            $table->decimal('price')->default(0)->nullable();         // Price without tax & without discount
            $table->decimal('tax')->default(0)->nullable();           // Tax %
            $table->decimal('discount')->nullable();                  // Discount %
            $table->decimal('total_price')->default(0)->nullable();   // Price with tax & with discount
            $table->decimal('weight')->nullable();
            $table->integer('quantity')->default(0);
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
        Schema::dropIfExists('products');
    }
}
