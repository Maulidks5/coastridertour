<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table): void {
            $table->string('private_pricing_model', 30)->default('group_total')->after('private_available');
            $table->decimal('private_1_person_price', 10, 2)->nullable()->after('private_pricing_model');
            $table->decimal('private_2_person_price', 10, 2)->nullable()->after('private_1_person_price');
            $table->decimal('private_3_person_price', 10, 2)->nullable()->after('private_2_person_price');
            $table->decimal('private_4_plus_person_price', 10, 2)->nullable()->after('private_3_person_price');
            $table->decimal('private_child_price', 10, 2)->nullable()->after('private_4_plus_person_price');
            $table->decimal('private_infant_price', 10, 2)->nullable()->after('private_child_price');
            $table->unsignedSmallInteger('private_max_online_guests')->default(10)->after('private_infant_price');
            $table->string('pricing_note', 500)->nullable()->after('large_group_quote');
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table): void {
            $table->dropColumn([
                'private_pricing_model', 'private_1_person_price', 'private_2_person_price',
                'private_3_person_price', 'private_4_plus_person_price', 'private_child_price',
                'private_infant_price', 'private_max_online_guests', 'pricing_note',
            ]);
        });
    }
};
