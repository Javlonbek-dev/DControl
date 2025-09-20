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
        Schema::create('nonconformity_criterion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nonconformity_id');
            $table->unsignedBigInteger('criteria_id');
            $table->primary(['nonconformity_id', 'criteria_id']);
            $table->foreign('nonconformity_id')->references('id')->on('non_conformities')->onDelete('cascade');
            $table->foreign('criteria_id')->references('id')->on('criterias')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nonconformity_criterion');
    }
};
