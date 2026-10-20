<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::table('transport_requests', function(Blueprint $table){ $table->string('trip_type',20)->default('one-way')->after('service_type'); $table->date('return_date')->nullable()->after('pickup_time'); $table->time('return_time')->nullable()->after('return_date'); $table->unsignedSmallInteger('luggage')->default(0)->after('passengers'); $table->unsignedSmallInteger('child_seats')->default(0)->after('luggage'); $table->string('airport_direction',20)->nullable()->after('child_seats'); $table->string('airline')->nullable()->after('airport_direction'); }); }
 public function down(): void { Schema::table('transport_requests', fn(Blueprint $table)=>$table->dropColumn(['trip_type','return_date','return_time','luggage','child_seats','airport_direction','airline'])); }
};
