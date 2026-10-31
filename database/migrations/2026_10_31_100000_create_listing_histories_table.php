<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('listing_id')->index();
            $table->unsignedInteger('version');
            $table->json('snapshot');
            $table->foreignId('changed_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_histories');
    }
};
