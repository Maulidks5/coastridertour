<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_type', 20)->default('shared')->after('tour_id');
            $table->decimal('estimated_price', 10, 2)->nullable()->after('booking_type');
            $table->string('estimated_currency', 3)->nullable()->after('estimated_price');
            $table->json('children_ages')->nullable()->after('children');
            $table->string('pickup_zone', 40)->nullable()->after('children_ages');
            $table->string('preferred_contact', 20)->default('whatsapp')->after('whatsapp');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['booking_type', 'estimated_price', 'estimated_currency', 'children_ages', 'pickup_zone', 'preferred_contact']);
        });
    }
};
