<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Election;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'voter_id',
        'profile_photo',
        'is_eligible',
        'eligibility_overridden',
        'role',
    ];

    protected $casts = [
        'is_eligible' => 'boolean',
        'eligibility_overridden' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function votes()
    {
        return $this->hasMany(Vote::class, 'user_id');
    }

    public function elections()
    {
        return $this->belongsToMany(Election::class, 'votes', 'user_id', 'election_id')
                    ->withTimestamps();
    }



    public function skippedElectionsCount(): int
    {
        return count($this->skippedElections());
    }

public function skippedElections(): array
{
    $endedElections = Election::where('end_date', '>=', $this->created_at)
                              ->where('end_date', '<', now())
                              ->get();

    $skipped = [];
    foreach ($endedElections as $election) {
        $voted = $this->votes()->where('election_id', $election->id)->exists();
        if (! $voted) {
            $skipped[] = $election->title; // assuming elections table has 'title'
        }
    }

    return $skipped;
}



    public function isAutoFlagged(): bool
    {
        return $this->skippedElectionsCount() >= 5;
    }

    // Final eligibility (respects override first, then auto-flagging)
    public function finalEligibility(): bool
    {
        if ($this->eligibility_overridden) {
            return $this->is_eligible;
        }

        return ! $this->isAutoFlagged();
    }



    public function overrideEligibility(bool $status): void
    {
        $this->is_eligible = $status;
        $this->eligibility_overridden = true;
        $this->save();
    }

    public function removeOverride(): void
    {
        $this->eligibility_overridden = false;
        $this->save();
    }
}
