<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('order_id')->constrained();

            $table->tinyInteger('status')->default(0);
            $table->string('number')->nullable();
            $table->date('date')->nullable();

            $table->string('vat')->nullable();
            $table->string('name');
            $table->string('country');
            $table->string('region');
            $table->text('address');
            $table->string('city');
            $table->string('zip');
            $table->string('phone');

            $table->decimal('tax');
            $table->decimal('price_untaxed');
            $table->decimal('price');

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
        Schema::dropIfExists('invoices');
    }
}
