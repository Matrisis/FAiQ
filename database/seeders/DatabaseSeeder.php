<?php

namespace Database\Seeders;

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

        if(app()->environment('local')) {
            $team = User::factory()->withPersonalTeam()->create([
                'name' => 'Test User',
                'email' => 'a@a.a',
                'password' => bcrypt('password'),
            ]);
            TeamParameters::firstOrCreate([
                "team_id" => $team->id,
            ], [
                "team_id" => $team->id,
            ]);


            AnswerSeeder::run();
        }
    }
}
