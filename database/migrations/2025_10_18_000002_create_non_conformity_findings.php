<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('non_conformity_findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('non_conformity_id')->constrained('non_conformities')->nullOnDelete()->cascadeOnUpdate();
            $table->date('detected_at')->nullable();       // Sana
            $table->unsignedSmallInteger('day_no')->nullable(); // Kun #
            $table->text('description');                   // Dastlabki kamchilik matni
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['non_conformity_id', 'detected_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('non_conformity_findings');
    }
};
