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
        Schema::create('scan_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('scan_request_id')->constrained()->cascadeOnDelete();
            $table->text('raw_text')->nullable(); // OCR output
            $table->string('barcode')->nullable(); // if detected
            $table->json('ai_data')->nullable();
            $table->float('confidence')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scan_results');
    }
};
