<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kyc_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('country', 2);
            $table->string('requested_role');
            $table->json('documents')->nullable();
            $table->json('beneficial_owners')->nullable();
            $table->string('status')->default('PENDING_VERIFICATION');
            $table->string('decision')->nullable();
            $table->text('decision_reason')->nullable();
            $table->unsignedBigInteger('submitted_by')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamp('screened_at')->nullable();
            $table->json('screening')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kyc_submissions');
    }
};
