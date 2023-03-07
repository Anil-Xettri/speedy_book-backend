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
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('customer_address');
            $table->longText('seat_no')->nullable();
            $table->integer('quantity')->default(1);
            $table->double('sub_total', 8,2);
            $table->double('discount',8,2)->nullable();
            $table->double('tax_amount',8,2)->nullable();
            $table->double('total',8,2);

            $table->string('payment_method')->comment('Cash,Esewa, IME Pay, Khalti, Phone Pay')->default('Cash');
            $table->string('status')->comment('Inactive, Pending, Confirmed, Cancelled')->default('Inactive');
            $table->longText('notes')->nullable();

            $table->foreign('cinema_hall_id')->references('id')->on('cinema_halls')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade')->onUpdate('cascade');
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
