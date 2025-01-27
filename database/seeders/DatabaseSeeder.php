<?php

namespace Database\Seeders;

use App\Actions\Fortify\CreateNewUser;
use App\Models\Pricing;
use App\Models\Team;
use App\Models\TeamParameters;
use App\Models\User;
use App\Services\Old\Assistant\TeamGenesisService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->withPersonalTeam()->create();

        Pricing::create([
            "name" => "Debutant",
            "subscription_id" => "prod_RPs8XHbO4HiRkA",
            "subscription_price_id" => "price_1QX2OQAUBCE5hHWSW65fmG5x",
            "meter_price_id" => "price_1QX2P9AUBCE5hHWSHaneckr2",
            "requests_count" => 400
        ]);

        if(app()->environment('local')) {
            (new CreateNewUser())->create([
                "name" => "Matthieu",
                "email" => "matthieu.driss@pm.me",
                "company_name" => env("APP_NAME", "EasyFAiQ"),
                "company_slug" => mb_strtolower(env("APP_NAME", "EasyFAiQ")),
                "password" => "password",
                "password_confirmation" => "password",
                "pricing_id" => Pricing::find(1)->id,
                "terms" => true
            ]);
            User::first()->update([
                "email_verified_at" => now(),
                "is_admin" => true,
            ]);
            Team::first()->update([
                "has_paid" => false,
                "locked" => false,
            ]);
            TeamParameters::first()->update([
                "accessible" => true,
            ]);

            AnswerSeeder::run();
        } else {
            $pw =  Str::random(16);
            (new CreateNewUser())->create([
                "name" => "Matthieu Driss",
                "email" => "matthieu.driss@pm.me",
                "company_name" => env("APP_NAME", "EasyFAiQ"),
                "company_slug" => mb_strtolower(env("APP_NAME", "EasyFAiQ")),
                "password" => $pw,
                "password_confirmation" => $pw,
                "terms" => true
            ]);
            User::first()->update([
                "email_verified_at" => now(),
                "is_admin" => true,
            ]);
            Team::first()->update([
                "has_paid" => true,
                "locked" => false,
            ]);
            TeamParameters::first()->update([
                "accessible" => true,
            ]);
        }
    }
}
