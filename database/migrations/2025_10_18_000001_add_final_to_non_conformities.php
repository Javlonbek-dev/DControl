<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('non_conformities', function (Blueprint $table) {
            $table->text('final_description')->nullable()->after('normative_documents');
            $table->timestamp('finalized_at')->nullable()->after('final_description');
            $table->foreignId('finalized_by')->nullable()->constrained('users')->nullOnDelete()->after('finalized_at');
        });
    }

    public function down(): void
    {
        Schema::table('non_conformities', function (Blueprint $table) {
            $table->dropConstrainedForeignId('finalized_by');
            $table->dropColumn(['final_description', 'finalized_at']);
        });
    }
};
