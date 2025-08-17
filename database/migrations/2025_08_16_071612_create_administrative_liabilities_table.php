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
            $table->integer('number');
            $table->date('registration_date');
            $table->foreignId('decision_type_id')->nullable()->constrained('decision_types');
            $table->date('decision_date');
            $table->float('imposed_fine');
            $table->boolean('is_paid');
            $table->foreignId('bxm_id')->nullable()->constrained('bxms');
            $table->string('person_full_name');
            $table->string('person_passport');
            $table->foreignId('profession_id')->nullable()->constrained('professions');
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
