<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transport_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('full_name');
            $table->string('phone', 40);
            $table->string('email')->nullable();
            $table->string('service_type');
            $table->string('pickup_location');
            $table->string('destination');
            $table->date('travel_date');
            $table->time('pickup_time')->nullable();
            $table->unsignedSmallInteger('passengers');
            $table->string('flight_number')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 30)->default('new')->index();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_requests');
    }
};
