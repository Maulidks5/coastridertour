<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::table('contact_inquiries', function(Blueprint $table){ $table->string('topic',40)->default('other')->after('source'); $table->string('preferred_contact',20)->default('email')->after('phone'); }); }
 public function down(): void { Schema::table('contact_inquiries', fn(Blueprint $table)=>$table->dropColumn(['topic','preferred_contact'])); }
};
