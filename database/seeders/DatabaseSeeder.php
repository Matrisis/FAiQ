<?php

namespace Database\Seeders;

use App\Actions\Fortify\CreateNewUser;
use App\Models\Pricing;
use App\Models\Team;
use App\Models\TeamParameters;
use App\Models\User;
use App\Services\Old\Assistant\TeamGenesisService;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->withPersonalTeam()->create();

        Pricing::insert(["name" => "new", "price" => 0.03]);
        Pricing::insert(["name" => "old", "price" => 0.01]);

        if(app()->environment('local')) {
            (new CreateNewUser())->create([
                "name" => "Matthieu",
                "email" => "a@a.a",
                "company_name" => "Matthieu",
                "company_slug" => "matthieu",
                "password" => "password",
                "password_confirmation" => "password",
                "terms" => true
            ]);
            User::first()->update([
                "email_verified_at" => now(),
                "is_admin" => true,
            ]);

            AnswerSeeder::run();
        }
    }
}
