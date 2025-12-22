<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->foreignId('umkm_id')->constrained('umkms')->onDelete('cascade');
            $table->string('nama_menu');
            $table->integer('harga');
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropForeign(['umkm_id']);
            $table->dropColumn(['umkm_id', 'nama_menu', 'harga', 'deskripsi', 'gambar']);
        });
    }
};
