<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropFieldDiary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diaries', function (Blueprint $table) {
            // Hapus kolom 'reference_type' jika sudah ada
            if (Schema::hasColumn('diaries', 'reference_type')) {
                $table->dropColumn('reference_type');
            }
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
            // Tambahkan kembali kolom 'reference_type' jika perlu
            $table->enum('reference_type', ['book', 'article', 'video', 'coaching', 'training', 'mentoring', 'other'])->nullable();
        });
    }
}
