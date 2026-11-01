<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CatalogueMigrationConsistencyTest extends TestCase
{
    /** @return array<string, array{string, int}> */
    public static function marketAlignedStartingPrices(): array
    {
        return [
            'safari-blue' => ['safari-blue', 102],
            'stone-town-tour' => ['stone-town-tour', 40],
            'spice-tour' => ['spice-tour', 60],
            'prison-island' => ['prison-island', 70],
            'nakupenda-beach' => ['nakupenda-beach', 87],
            'mnemba-island' => ['mnemba-island', 110],
            'jozani-forest' => ['jozani-forest', 80],
            'nungwi-turtles-sunset' => ['nungwi-turtles-sunset', 135],
            'prison-island-nakupenda' => ['prison-island-nakupenda', 155],
            'spice-prison-stone-town' => ['spice-prison-stone-town', 178],
        ];
    }

    /** @return array<string, array{string}> */
    public static function catalogueMigrations(): array
    {
        $files = glob(dirname(__DIR__, 2).'/database/migrations/2026_11_01_0000*_refine_*_catalogue.php');

        self::assertIsArray($files);
        sort($files);

        $cases = [];
        foreach ($files as $file) {
            $cases[basename($file)] = [$file];
        }

        return $cases;
    }

    public function test_all_priority_catalogue_migrations_are_present(): void
    {
        $this->assertCount(10, self::catalogueMigrations());
    }

    #[DataProvider('marketAlignedStartingPrices')]
    public function test_market_alignment_has_the_expected_starting_price(string $slug, int $expectedPrice): void
    {
        $source = (string) file_get_contents(dirname(__DIR__, 2).'/database/migrations/2026_11_01_000011_align_zanzibar_catalogue_with_market_reference.php');
        $slugPosition = strpos($source, "'{$slug}' => [");

        $this->assertNotFalse($slugPosition, "Missing {$slug} from the market-alignment migration.");

        $nextTourPosition = strpos($source, "\n            '", $slugPosition + strlen($slug));
        $tourBlock = substr($source, $slugPosition, $nextTourPosition === false ? null : $nextTourPosition - $slugPosition);

        $this->assertStringContainsString(", {$expectedPrice},", $tourBlock, "Unexpected 4+ adult starting price for {$slug}.");
        $this->assertStringContainsString("'included' =>", $tourBlock);
        $this->assertStringContainsString("'excluded' =>", $tourBlock);
    }

    #[DataProvider('catalogueMigrations')]
    public function test_starting_price_matches_the_four_plus_adult_tier(string $file): void
    {
        $source = file_get_contents($file);

        $this->assertIsString($source);
        $this->assertMatchesRegularExpression("/'price' => (\\d+(?:\\.\\d+)?)/", $source);
        $this->assertMatchesRegularExpression("/'private_4_plus_person_price' => (\\d+(?:\\.\\d+)?)/", $source);

        preg_match("/'price' => (\\d+(?:\\.\\d+)?)/", $source, $publicPrice);
        preg_match("/'private_4_plus_person_price' => (\\d+(?:\\.\\d+)?)/", $source, $fourPlusPrice);

        $this->assertSame((float) $fourPlusPrice[1], (float) $publicPrice[1]);
    }

    #[DataProvider('catalogueMigrations')]
    public function test_adult_tiers_do_not_increase_as_the_group_grows(string $file): void
    {
        $source = file_get_contents($file);
        $fields = [
            'private_1_person_price',
            'private_2_person_price',
            'private_3_person_price',
            'private_4_plus_person_price',
        ];
        $prices = [];

        foreach ($fields as $field) {
            preg_match("/'{$field}' => (\\d+(?:\\.\\d+)?)/", (string) $source, $match);
            $this->assertNotEmpty($match, "Missing {$field} in ".basename($file));
            $prices[] = (float) $match[1];
        }

        $sorted = $prices;
        rsort($sorted, SORT_NUMERIC);

        $this->assertSame($sorted, $prices, 'Adult prices must stay equal or decrease as group size grows.');
    }

    #[DataProvider('catalogueMigrations')]
    public function test_each_catalogue_entry_has_complete_customer_information(string $file): void
    {
        $source = (string) file_get_contents($file);

        foreach ([
            "'title' =>",
            "'excerpt' =>",
            "'description' =>",
            "'pricing_note' =>",
            "'pickup_details' =>",
            "'meta_title' =>",
            "'meta_description' =>",
            "DB::table('tour_inclusions')",
            "DB::table('tour_itinerary_items')",
            "DB::table('tour_faqs')",
        ] as $requiredContent) {
            $this->assertStringContainsString($requiredContent, $source, basename($file).' is missing '.$requiredContent);
        }

        $this->assertMatchesRegularExpression(
            "/replace(?:Labels|Items)\\('tour_highlights'/",
            $source,
            basename($file).' is missing its highlight replacement.',
        );
    }
}
