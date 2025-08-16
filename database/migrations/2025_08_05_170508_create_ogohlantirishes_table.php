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
        Schema::create('ogohlantirish', function (Blueprint $table) {
            $table->id();
            $table->string('stir');
            $table->string('korxona_nomi');
            $table->string('mahsulot_nomi')->nullable();
            $table->string('soha_nomi')->nullable();
            $table->string('faoliyat_turi')->nullable();
            $table->string('metralogiya')->nullable();
            $table->string('standart')->nullable();
            $table->string('sertifikat')->nullable();
            $table->date('ogohlantirish_xati_sanasi');
            $table->string('ogohlantirish_xati_raqami');
            $table->string('javob_sanasi')->nullable();
            $table->string('javob_raqami')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('region_id')->nullable()->constrained('regions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ogohlantirish');
    }
};
