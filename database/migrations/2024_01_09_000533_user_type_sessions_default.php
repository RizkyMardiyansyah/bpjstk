<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserTypeSessionsDefault extends Migration
{



    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Change the default value of the type_of_sessions column in the users table to ""

        Schema::table('users', function (Blueprint $table) {
            $table->string('type_of_sessions')->default('')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Change the default value of the type_of_sessions column in the users table to "coaching,mentoring,training"
        Schema::table('users', function (Blueprint $table) {
            $table->string('type_of_sessions')->default('coaching,mentoring,training')->change();
        });
    }
}
