<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country', 2);
            $table->string('registration_number')->nullable();
            $table->string('kyc_status')->default('NOT_SUBMITTED');
            // Assigned by the server after KYC approval; never user input.
            $table->string('company_role')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->timestamps();

            $table->index('kyc_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
