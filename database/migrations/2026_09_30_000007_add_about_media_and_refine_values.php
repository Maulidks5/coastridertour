<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
 public function up(): void {
  DB::table('site_settings')->updateOrInsert(['key'=>'about_image_path'],['group'=>'media','label'=>'About page image','type'=>'image','value'=>DB::table('site_settings')->where('key','cta_image_path')->value('value') ?: '/images/coast-rider-placeholder.jpg','sort_order'=>3,'created_at'=>now(),'updated_at'=>now()]);
  DB::table('home_content_items')->where('section','why')->where('title','Fast WhatsApp Support')->update(['description'=>'Direct WhatsApp support from our local team.']);
 }
 public function down(): void { DB::table('site_settings')->where('key','about_image_path')->delete(); }
};
