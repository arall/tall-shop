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
            $table->tinyInteger('status')->default(0);

            // User
            $table->foreignId('user_id');

            // Shipping
            $table->foreignId('shipping_carrier_id');
            $table->decimal('shipping_price')->nullable();

            // Payment
            $table->foreignId('payment_method_id');
            $table->decimal('payment_method_price')->nullable();

            // Shipping details
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('country')->nullable();
            $table->text('address')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('phone')->nullable();

            // Details
            $table->decimal('price');

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
