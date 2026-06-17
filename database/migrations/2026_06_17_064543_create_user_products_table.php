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
        Schema::create('user_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('scan_request_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name')->nullable();
            $table->string('brand')->nullable();
            $table->string('barcode')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            // snapshot from scan
            $table->json('source_data')->nullable();
            // raw AI + OCR reference for traceability
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_products');
    }
};
