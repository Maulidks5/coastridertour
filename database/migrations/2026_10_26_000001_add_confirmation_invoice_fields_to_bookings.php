<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('deposit_amount', 10, 2)->nullable()->after('quoted_currency');
            $table->decimal('amount_paid', 10, 2)->default(0)->after('deposit_amount');
            $table->string('payment_status', 20)->default('unpaid')->after('amount_paid');
            $table->string('invoice_number', 40)->nullable()->unique()->after('reference');
            $table->timestamp('confirmation_sent_at')->nullable()->after('confirmed_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropUnique(['invoice_number']);
            $table->dropColumn(['deposit_amount', 'amount_paid', 'payment_status', 'invoice_number', 'confirmation_sent_at']);
        });
    }
};
