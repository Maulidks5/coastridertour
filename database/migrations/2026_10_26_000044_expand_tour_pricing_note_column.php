<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table): void {
            $table->text('pricing_note')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Keep the wider column to avoid truncating published pricing details.
    }
};
