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
            "name" => "Abonnement Pro",
            "init_id" => "prod_RJ4eV4MCoC8G2x",
            "price_init_id" => "price_1QQSVdAUBCE5hHWS8BS28N5o",
            "subscription_id" => "prod_RKYSHUSzXMoY8I",
            "subscription_price_id" => "price_1QRtlpAUBCE5hHWSayo9i6yU",
        ]);
        Pricing::create([
            "name" => "Abonnement Flexible",
            "init_id" => "prod_RJ4floTNGH9W4P",
            "price_init_id" => "price_1QQSWDAUBCE5hHWSHnVq4bon",
            "subscription_id" => "prod_RKYTfHv3sNdAWd",
            "subscription_price_id" => "price_1QRtpIAUBCE5hHWSBOqVpWMa",
        ]);

        if(app()->environment('local')) {
            (new CreateNewUser())->create([
                "name" => "Matthieu",
                "email" => "a@a.a",
                "company_name" => env("APP_NAME", "EasyFAiQ"),
                "company_slug" => mb_strtolower(env("APP_NAME", "EasyFAiQ")),
                "password" => "password",
                "password_confirmation" => "password",
                "pricing" => Pricing::find(1)->id,
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
