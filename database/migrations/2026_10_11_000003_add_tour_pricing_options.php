<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->boolean('shared_available')->default(true)->after('price');
            $table->decimal('shared_adult_price', 10, 2)->nullable()->after('shared_available');
            $table->decimal('shared_child_price', 10, 2)->nullable()->after('shared_adult_price');
            $table->decimal('shared_infant_price', 10, 2)->nullable()->after('shared_child_price');
            $table->boolean('private_available')->default(true)->after('shared_infant_price');
            $table->decimal('private_1_2_price', 10, 2)->nullable()->after('private_available');
            $table->decimal('private_3_4_price', 10, 2)->nullable()->after('private_1_2_price');
            $table->decimal('private_5_6_price', 10, 2)->nullable()->after('private_3_4_price');
            $table->decimal('private_7_10_price', 10, 2)->nullable()->after('private_5_6_price');
            $table->decimal('private_child_supplement', 10, 2)->nullable()->after('private_7_10_price');
            $table->boolean('large_group_quote')->default(true)->after('private_child_supplement');
        });

        DB::table('tours')->where('price', '>', 0)->update([
            'shared_adult_price' => DB::raw('price'),
            'shared_available' => true,
        ]);
        DB::table('tours')->where('price', '<=', 0)->update([
            'shared_available' => false,
            'private_available' => true,
        ]);
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn([
                'shared_available', 'shared_adult_price', 'shared_child_price', 'shared_infant_price',
                'private_available', 'private_1_2_price', 'private_3_4_price', 'private_5_6_price',
                'private_7_10_price', 'private_child_supplement', 'large_group_quote',
            ]);
        });
    }
};
