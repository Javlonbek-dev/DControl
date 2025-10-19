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
        Schema::create('administrative_liabilities', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->date('registration_date');
            $table->foreignId('decision_type_id')->nullable()->constrained('decision_types')->nullOnDelete()->cascadeOnUpdate();
            $table->date('decision_date')->nullable();
            $table->date('court_date')->nullable();
            $table->float('imposed_fine')->nullable();
            $table->boolean('is_paid')->nullable();
            $table->foreignId('bxm_id')->nullable()->constrained('bxms')->nullOnDelete()->cascadeOnUpdate();
            $table->string('person_full_name');
            $table->string('person_passport');
            $table->foreignId('profession_id')->nullable()->constrained('professions')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrative_liabilities');
    }
};
