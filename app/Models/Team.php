<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Cashier\Billable;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    use HasFactory;
    use Billable;
    use SoftDeletes;

    protected $with = [
        'parameters', 'prompts', 'pricing'
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
        'pricing_id',
    ];


    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
            'has_paid' => 'boolean',
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


    public function hasActiveSubscription()
    {
        $sub = $this->subscription($this->pricing->name);
        return $sub && $sub->active();
    }

    public function hasPaid(): bool
    {
        return $this->has_paid;
    }

    public function isLocked() : bool
    {
        return $this->locked;
    }

    public function isAccessible(): bool
    {
        return !$this->isLocked() && $this->hasPaid() && $this->subscribed();
    }

    public function parameters()
    {
        return $this->hasOne(TeamParameters::class, 'team_id', "id");
    }

    public function prompts()
    {
        return $this->hasOne(TeamPrompt::class, 'team_id', "id");
    }

    public function pricing()
    {
        return $this->hasOne(Pricing::class, 'id', "pricing_id");
    }
}
