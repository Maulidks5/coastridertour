<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('name', 120);
            $table->string('email', 190);
            $table->string('country', 100)->nullable();
            $table->string('tour_name', 150)->nullable();
            $table->unsignedTinyInteger('rating');
            $table->text('content');
            $table->string('status', 20)->default('pending')->index();
            $table->boolean('is_featured')->default(false);
            $table->timestamp('approved_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
