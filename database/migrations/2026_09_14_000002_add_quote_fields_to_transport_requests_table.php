<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transport_requests', function (Blueprint $table) {
            $table->decimal('quote_amount', 10, 2)->nullable()->after('notes');
            $table->string('quote_currency', 3)->default('USD')->after('quote_amount');
            $table->string('vehicle_type')->nullable()->after('quote_currency');
            $table->unsignedSmallInteger('vehicle_capacity')->nullable()->after('vehicle_type');
            $table->unsignedSmallInteger('quote_valid_hours')->default(24)->after('vehicle_capacity');
            $table->text('quote_inclusions')->nullable()->after('quote_valid_hours');
            $table->text('quote_notes')->nullable()->after('quote_inclusions');
            $table->timestamp('quoted_at')->nullable()->after('quote_notes');
        });
    }

    public function down(): void
    {
        Schema::table('transport_requests', function (Blueprint $table) {
            $table->dropColumn(['quote_amount', 'quote_currency', 'vehicle_type', 'vehicle_capacity', 'quote_valid_hours', 'quote_inclusions', 'quote_notes', 'quoted_at']);
        });
    }
};
