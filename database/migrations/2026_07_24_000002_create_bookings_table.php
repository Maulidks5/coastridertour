<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('reference', 24)->unique();
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('whatsapp', 40);
            $table->date('travel_date');
            $table->unsignedTinyInteger('adults')->default(1);
            $table->unsignedTinyInteger('children')->default(0);
            $table->string('hotel')->nullable();
            $table->text('special_requests')->nullable();
            $table->string('status', 30)->default('pending');
            $table->string('source', 30)->default('website');
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['travel_date', 'status']);
            $table->index('whatsapp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
