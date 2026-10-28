<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const PUBLIC_TOURS = [
        'stone-town-tour',
        'spice-tour',
        'jozani-forest',
        'safari-blue',
        'mnemba-island',
        'nungwi-turtles-sunset',
        'stone-town-prison-island',
        'stone-town-spice-tour',
        'spice-jozani-forest',
        'prison-island-nakupenda',
        'private-sunset-dinner-cruise',
        'design-your-day-private-tour',
    ];

    private const FEATURED_TOURS = [
        'safari-blue',
        'prison-island-nakupenda',
        'mnemba-island',
        'jozani-forest',
        'nungwi-turtles-sunset',
    ];

    private const DRAFT_TOURS = [
        'fishing-game-tour', 'snapper-fishing', 'nungwi-aquarium', 'salaam-cave',
        'mangapwani-coral-cave', 'butterfly-tour', 'village-tour', 'swahili-cooking-class',
        'kendwa-beach', 'the-rock-restaurant', 'glass-kayaking', 'stand-up-paddleboarding',
        'kitesurfing', 'spearfishing', 'jet-ski-adventure', 'water-sports-tour',
        'muyuni-village-tour', 'swahili-food-experience', 'taste-of-north-zanzibar',
        'mama-zanzibar-handcrafts', 'zanzibar-traditions-people', 'uzi-island-muyuni-village',
        'behind-the-scenes-uzi-island', 'spice-prison-island', 'spice-muyuni-village',
        'jozani-prison-island', 'jozani-muyuni-village', 'stone-town-freddie-mercury',
        'masingini-forest-private-tour',
        'prison-island', 'nakupenda-beach', 'spice-prison-stone-town',
    ];

    public function up(): void
    {
        DB::table('tours')
            ->whereIn('slug', self::DRAFT_TOURS)
            ->update(['is_published' => false, 'is_featured' => false, 'updated_at' => now()]);

        DB::table('tours')
            ->whereIn('slug', self::PUBLIC_TOURS)
            ->update(['is_published' => true, 'is_featured' => false, 'updated_at' => now()]);

        DB::table('tours')
            ->whereIn('slug', self::FEATURED_TOURS)
            ->update(['is_featured' => true, 'is_popular' => true, 'updated_at' => now()]);
    }

    public function down(): void
    {
        // Catalogue visibility is editorial data. Keep the current admin choices on rollback.
    }
};
