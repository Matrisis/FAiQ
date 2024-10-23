<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Cashier\Billable;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    use HasFactory;
    use Billable;

    protected $with = [
        'parameters', 'prompts'
    ];

    protected $fillable = [
        'name',
        'personal_team',
        'locked',
        'initial_invoice_id',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
        'has_paid',
    ];


    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
            'locked' => 'boolean'
        ];
    }

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    public function hasPaid(): bool
    {
        return $this->has_paid || $this->id === -1;
    }

    public function parameters()
    {
        return $this->hasOne(TeamParameters::class, 'team_id', "id");
    }

    public function prompts()
    {
        return $this->hasOne(TeamPrompt::class, 'team_id', "id");
    }
}
