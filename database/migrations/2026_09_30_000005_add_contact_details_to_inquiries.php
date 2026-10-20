<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::table('contact_inquiries', function (Blueprint $table) { $table->string('phone', 40)->nullable()->after('email'); $table->string('source', 30)->default('contact')->after('subject'); }); }
    public function down(): void { Schema::table('contact_inquiries', fn (Blueprint $table) => $table->dropColumn(['phone','source'])); }
};
