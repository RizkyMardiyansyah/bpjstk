<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableDiaries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diaries', function (Blueprint $table) {
            // Change 'description' to 'longtext'
            $table->longText('description')->change();

            // Drop 'theme' and 'dated_at'
            $table->dropColumn('theme');
            $table->dropColumn('dated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('diaries', function (Blueprint $table) {
            // Reverse the changes made in the 'up' method
            $table->string('description')->change();
            $table->string('theme');
            $table->timestamp('dated_at')->nullable();
        });
    }
}

