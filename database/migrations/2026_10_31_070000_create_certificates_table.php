<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->string('type')->nullable();
            $table->string('registry');
            $table->string('serial_number')->nullable();
            $table->string('serial_normalized');
            $table->string('status')->default('PENDING_REVIEW');
            $table->text('rejection_reason')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['registry', 'serial_normalized']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
