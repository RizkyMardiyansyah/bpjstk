<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('experience_year')->nullable();
            $table->string('experience_month')->nullable();            
            $table->string('expertise')->nullable();
            $table->string('linkedin')->nullable();
            $table->longText('reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('experience_year');
            $table->dropColumn('experience_month');
            $table->dropColumn('expertise');
            $table->dropColumn('linkedin');
            $table->dropColumn('reason');
        });
    }
}
