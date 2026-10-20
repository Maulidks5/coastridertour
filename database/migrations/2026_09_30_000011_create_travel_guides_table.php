<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
  Schema::create('travel_guides', function (Blueprint $table) {
   $table->id(); $table->string('title'); $table->string('slug')->unique(); $table->string('category',80);
   $table->text('excerpt'); $table->longText('content'); $table->string('image_path')->nullable();
   $table->unsignedSmallInteger('reading_time')->default(5); $table->boolean('is_featured')->default(false);
   $table->boolean('is_published')->default(false); $table->timestamp('published_at')->nullable();
   $table->string('meta_title')->nullable(); $table->string('meta_description',320)->nullable(); $table->timestamps();
   $table->index(['is_published','published_at']);
  });
  $guides = [
   ['best-time-to-visit-zanzibar','Best Time to Visit Zanzibar','Planning','/images/experiences/hero-zanzibar.webp','Understand Zanzibar’s seasons, weather and the best months for beaches, snorkeling and relaxed island travel.',"Zanzibar is a year-round destination, but the right month depends on the experience you want. The drier periods are generally easier for beach days, ocean trips and walking tours.

June to October usually brings comfortable temperatures and lower humidity. It is a popular period for combining Zanzibar with a Tanzania safari, so arranging key activities early is sensible.

December to February is warmer and excellent for travellers who enjoy tropical beach weather. Short showers can occur, but many days remain bright.

The longer rains are commonly associated with March to May. Some travellers appreciate the quieter atmosphere, while ocean conditions and individual operators’ schedules should be checked before booking.

Share your dates with our local team. We can suggest experiences suited to the weather, tides and your preferred pace."],
   ['what-to-pack-for-zanzibar','What to Pack for Zanzibar','Planning','/images/experiences/kendwa-beach.webp','A simple packing checklist for beach days, cultural visits, boat trips and everyday comfort in Zanzibar.',"Pack light, breathable clothing for Zanzibar’s warm tropical climate. A hat, sunglasses and reef-conscious sunscreen are useful for beach and ocean days.

Bring comfortable sandals plus secure walking shoes for Stone Town, spice farms and nature visits. A light rain layer can be helpful, especially during transitional months.

Zanzibar has a predominantly Muslim culture. Clothing that covers shoulders and knees is respectful when visiting villages, Stone Town and places away from resort beaches.

For boat trips, carry a small waterproof bag, swimwear, a towel and any personal medication. Keep copies of travel documents separately from the originals.

Your exact packing list may change by activity. Ask us about footwear, snorkeling equipment or safari luggage before departure."],
   ['zanzibar-airport-transfer-guide','Zanzibar Airport Transfer Guide','Transport','/images/experiences/village-tour.webp','Know what details to provide, how pickup works and how to plan a smooth transfer from Zanzibar Airport.',"A pre-arranged airport transfer is the simplest way to begin your Zanzibar stay. Your driver needs the arrival date, flight number, landing time, passenger count and hotel name.

Arrival times can change, so provide a reachable WhatsApp number. Our team uses the flight details and your updates to coordinate the pickup.

Travel time depends on your destination. Stone Town is close to the airport, while Nungwi, Kendwa, Paje and other coastal areas require a longer drive.

Tell us about large luggage, child seats or mobility needs before the journey. We will confirm the appropriate vehicle and final price before the transfer.

For departure transfers, allow enough time for road conditions, airport procedures and airline check-in requirements."],
   ['safari-from-zanzibar-what-to-expect','Safari from Zanzibar: What to Expect','Safari','/images/experiences/tanzania-safaris.webp','A practical introduction to flights, parks, accommodation and planning a mainland Tanzania safari from Zanzibar.',"A safari from Zanzibar normally involves a domestic flight to the mainland followed by a guided game drive or a longer lodge itinerary. The best route depends on your available days and wildlife priorities.

Short fly-in safaris can work for travellers with limited time. Longer itineraries allow a more relaxed pace and can combine parks such as Tarangire, Serengeti and Ngorongoro.

Your quote should clearly list flights or transfers, park fees, vehicle arrangements, accommodation, meals and anything excluded. Accommodation can range from practical camps to premium lodges.

Pack soft-sided luggage when flight restrictions apply, neutral comfortable clothing, sun protection and a light layer for early mornings.

We recommend sharing your dates and comfort level early so availability and the most efficient route can be checked."],
   ['best-zanzibar-tours-for-couples','Best Zanzibar Tours for Couples','Inspiration','/images/experiences/cta-zanzibar.webp','Romantic and relaxed Zanzibar experiences for couples, honeymoons and memorable time together.',"Couples often enjoy a mix of private ocean time, local culture and unhurried evenings. A sunset dhow trip offers a calm introduction to Zanzibar’s coastline.

Nakupenda Sandbank and Mnemba-area snorkeling are strong choices for travellers who love clear water and scenic boat journeys. Private arrangements can create a quieter experience.

A spice farm and Stone Town day adds history, food and local character to a beach-focused holiday. Couples with more time can also combine Zanzibar with a tailored mainland safari.

The best plan depends on hotel location, weather, tides and how active you want the day to feel. Tell us whether you prefer adventure, relaxation or a balanced combination."],
   ['best-zanzibar-tours-for-families','Best Zanzibar Tours for Families','Inspiration','/images/experiences/nungwi-aquarium.webp','Family-friendly Zanzibar ideas with manageable timing, engaging activities and flexible private arrangements.',"A good family itinerary balances activity with rest and avoids unnecessary travel time. Private tours make it easier to adjust pickup, stops and pace around children.

Prison Island’s giant tortoises, a spice farm visit and selected beach experiences can be engaging for different ages. Jozani Forest is another memorable nature option when children are comfortable walking with a guide.

For boat and water activities, confirm minimum ages, swimming confidence, safety equipment and weather conditions. Carry sun protection, water and familiar snacks for younger travellers.

Tell us the children’s ages, hotel area and preferred duration. We will recommend suitable options and explain transport, inclusions and any child pricing clearly."],
  ];
  foreach ($guides as $i => [$slug,$title,$category,$image,$excerpt,$content]) DB::table('travel_guides')->insert(['slug'=>$slug,'title'=>$title,'category'=>$category,'image_path'=>$image,'excerpt'=>$excerpt,'content'=>$content,'reading_time'=>5,'is_featured'=>$i<2,'is_published'=>true,'published_at'=>now(),'meta_title'=>$title.' | Coast Rider Tour','meta_description'=>$excerpt,'created_at'=>now(),'updated_at'=>now()]);
 }
 public function down(): void { Schema::dropIfExists('travel_guides'); }
};
