<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('quoted_price', 10, 2)->nullable()->after('tour_id');
            $table->string('quoted_currency', 3)->nullable()->after('quoted_price');
            $table->text('admin_notes')->nullable()->after('special_requests');
            $table->timestamp('contacted_at')->nullable()->after('source');
            $table->timestamp('confirmed_at')->nullable()->after('contacted_at');
            $table->timestamp('cancelled_at')->nullable()->after('confirmed_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'quoted_price',
                'quoted_currency',
                'admin_notes',
                'contacted_at',
                'confirmed_at',
                'cancelled_at',
            ]);
        });
    }
};
