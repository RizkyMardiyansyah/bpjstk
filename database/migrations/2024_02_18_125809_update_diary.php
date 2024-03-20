<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDiary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('diaries', function (Blueprint $table) {
            // Tambahkan kolom 'reference_type' dengan tipe data ENUM
            $table->enum('reference_type', ['book', 'article', 'video', 'coaching', 'training', 'mentoring', 'other']);

            // Tambahkan kolom 'feedback' sebagai long text dan bisa kosong
            $table->text('feedback')->nullable();
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
            // Hapus kolom 'reference_type' jika sudah ada
            if (Schema::hasColumn('diaries', 'reference_type')) {
                $table->dropColumn('reference_type');
            }

            // Hapus kolom 'feedback' jika sudah ada
            if (Schema::hasColumn('diaries', 'feedback')) {
                $table->dropColumn('feedback');
            }
        });
    }
}
