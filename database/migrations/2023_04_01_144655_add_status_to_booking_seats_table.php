<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToBookingSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_seats', function (Blueprint $table) {
            $table->string('status')->comment('Available,Reserve,Sold Out, Unavailable')->default('Available');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_seats', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
