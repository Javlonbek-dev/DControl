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
        Schema::create('talabnomas', function (Blueprint $table) {
            $table->id();
            $table->text('korxona_nomi');
            $table->string('inn');
            $table->string('faoliyat_turi');
            $table->foreignId('hudud_is')->nullable()->constrained('hududs');
            $table->string('tuman')->nullable();
            $table->timestamp('start_tekshiruv')->nullable();
            $table->timestamp('end_tekshiruv')->nullable();
            $table->timestamp('yubroilgan_vaqti')->nullable();
            $table->string('talabnoma_raq')->nullable();
            $table->string('jarima_sum')->nullable();
            $table->integer('jarima_foizi')->nullable();
            $table->string('tekshiruv_holati');
            $table->string('tulangan_sum')->nullable();
            $table->integer('tulangan_foizi')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->text('huquqbuzarlik_mazmuni')->nullable();
            $table->text('qounun_moddasi')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talabnomas');
    }
};
