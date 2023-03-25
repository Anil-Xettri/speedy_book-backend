<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('cinema_hall_id');
            $table->unsignedBigInteger('movie_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('seat_id');
            $table->integer('quantity')->default(1);
            $table->double('sub_total', 8,2);
            $table->double('discount',8,2)->nullable();
            $table->double('tax_amount',8,2)->nullable();
            $table->double('total',8,2);

            $table->string('payment_method')->comment('Cash,Esewa, IME Pay, Khalti, Phone Pay')->default('Cash');
            $table->string('status')->comment('Available,Reserve,Sold Out, Unavailable')->default('Available');
            $table->longText('notes')->nullable();

            $table->foreign('cinema_hall_id')->references('id')->on('cinema_halls')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('bookings');
    }
}
