<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(TourCatalogSeeder::class);
        $this->call(SiteSettingSeeder::class);
        $this->call(HomeContentSeeder::class);
        $this->call(HeroSlideSeeder::class);
        $this->call(TestimonialSeeder::class);
        $this->call(FaqSeeder::class);

        if (env('ADMIN_EMAIL') && env('ADMIN_PASSWORD')) {
            User::query()->updateOrCreate(
                ['email' => env('ADMIN_EMAIL')],
                [
                    'name' => env('ADMIN_NAME', 'Coast Rider Administrator'),
                    'password' => Hash::make(env('ADMIN_PASSWORD')),
                    'is_admin' => true,
                    'role' => 'super_admin',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ],
            );
        }
    }
}
