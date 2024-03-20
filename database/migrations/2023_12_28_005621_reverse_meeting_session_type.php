<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReverseMeetingSessionType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reserve_meetings', function (Blueprint $table) {
            $table->string('session_type')->default('mentoring');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reserve_meetings', function (Blueprint $table) {
            $table->dropColumn('session_type');
        });
    }
}
