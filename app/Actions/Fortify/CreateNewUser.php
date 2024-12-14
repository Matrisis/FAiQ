<?php

namespace App\Actions\Fortify;

use App\Models\Pricing;
use App\Models\Team;
use App\Models\TeamParameters;
use App\Models\User;
use App\Services\Old\Assistant\TeamGenesisService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'company_name' => ['required', 'string', 'max:255', 'unique:teams,name'],
            'company_slug' => ['required', 'string', 'max:255', 'unique:teams,name'],
            'pricing_id' => ['required', 'exists:pricings,id'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return DB::transaction(function () use ($input) {
            return tap(User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]), function (User $user) use ($input) {
                $this->createTeam($user, $input['company_name'], $input['company_slug'], $input['pricing']);
            });
        });
    }

    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user, string $name, string $slug, int $pricing): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => $name,
            'slug' => mb_strtolower($slug),
            'personal_team' => true,
            'pricing_id' => $pricing
        ]));


        $user->current_team_id = $user->ownedTeams()->first()->id;

        TeamParameters::create([
            "team_id" => $user->ownedTeams()->first()->id,
        ]);

        $user->save();

    }
}
