<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('type')->index();
            $table->unsignedSmallInteger('capacity_mw')->nullable();
            $table->decimal('volume_available_mw', 12, 2)->nullable();
            $table->decimal('price_per_kwh', 12, 4)->nullable();
            $table->unsignedSmallInteger('duration_years')->nullable();
            $table->date('start_date')->nullable();
            $table->string('location')->nullable();
            $table->string('permit_reference')->nullable();
            $table->string('photo')->nullable();
            $table->unsignedInteger('quantity_tco2e')->nullable();
            $table->unsignedSmallInteger('vintage')->nullable();
            $table->string('registry_standard')->nullable();
            $table->string('serial_number')->nullable();
            $table->decimal('price_per_ton', 12, 4)->nullable();
            $table->string('status')->default('DRAFT');
            $table->unsignedInteger('version')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
