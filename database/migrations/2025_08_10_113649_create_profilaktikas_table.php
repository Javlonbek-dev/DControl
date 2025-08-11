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
        Schema::create('profilaktikas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hudud_id')->constrained('hududs');
            $table->text('korxona_nomi');
            $table->string('stir')->nullable();
            $table->index(['hudud_id', 'stir', 'korxona_nomi']);
            $table->text('mahsulot_nomi')->nullable();
            $table->text('soha_nomi')->nullable();
            $table->date('prof_sanasi')->nullable();
            $table->string('xat_raqami')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profilaktikas');
    }
};
