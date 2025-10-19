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
        Schema::create('economic_sanctions', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->date('registration_date');
            $table->double('assessed_fine');
            $table->text('court_name')->nullable();
            $table->date('decision_date')->nullable();
            $table->integer('decision_number')->nullable();
            $table->foreignId('decision_type_id')->nullable()->constrained('decision_types')->nullOnDelete()->cascadeOnUpdate();
            $table->double('imposed_fine')->nullable();
            $table->boolean('is_paid')->nullable();
            $table->foreignId('sanction_id')->nullable()->constrained('sanction_payment_requests')->nullOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('economic_sanctions');
    }
};
