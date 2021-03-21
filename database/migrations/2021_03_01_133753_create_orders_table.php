<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(false);
            $table->string('ref');

            // User
            $table->foreignId('user_id');

            // Shipping_
            $table->foreignId('shipping_carrier_id');
            $table->decimal('shipping__price')->nullable();

            // Payment
            $table->foreignId('payment_method_id');
            $table->decimal('payment_method_price')->nullable();

            // Buyer details
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->integer('country_id')->nullable();
            $table->text('address')->nullable();
            $table->string('cp')->nullable();
            $table->string('city')->nullable();
            $table->integer('region_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('vat')->nullable();

            // Details
            $table->date('date');
            $table->decimal('price');                     // Products without taxes + discounts
            $table->decimal('total_price')->default(0);   // Shipping method (with taxes) + Payment method (with taxes) + Products with taxes

            // Payment data
            $table->string('payment_ref')->nullable();
            $table->timestamp('payment_date')->nullable();

            // Transport data
            $table->string('track_number')->nullable();

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
        Schema::dropIfExists('orders');
    }
}
