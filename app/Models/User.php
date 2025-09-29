<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'eligibility_overridden', // ✅ new field to track admin override
    ];

    protected $casts = [
        'is_eligible' => 'boolean',
        'eligibility_overridden' => 'boolean',
    ];

    /**
     * A user has many votes.
     */
    public function votes()
    {
        return $this->hasMany(Vote::class, 'user_id');
    }

    /**
     * Elections this user participated in (through votes).
     */
    public function elections()
    {
        return $this->belongsToMany(Election::class, 'votes', 'user_id', 'election_id')
                    ->withTimestamps();
    }

    /**
     * Count how many elections this voter skipped (finished elections only).
     */
    public function skippedElectionsCount()
    {
        $totalElections = Election::where('end_date', '<', now())->count();
        $votedElections = $this->votes()
            ->whereHas('election', function ($query) {
                $query->where('end_date', '<', now());
            })
            ->distinct('election_id')
            ->count('election_id');

        return max(0, $totalElections - $votedElections);
    }

    /**
     * Auto check if voter is flagged (skipped 5 or more elections).
     */
    public function isAutoFlagged()
    {
        return $this->skippedElectionsCount() >= 5;
    }

    /**
     * Get final eligibility.
     * If admin has overridden, respect their choice.
     * If not, apply auto-flag rule.
     */
    public function finalEligibility()
    {
        if ($this->eligibility_overridden) {
            return $this->is_eligible;
        }

        return !$this->isAutoFlagged();
    }

    /**
     * Update eligibility automatically, unless overridden by admin.
     */
    public function updateAutoEligibility()
    {
        if (!$this->eligibility_overridden) {
            $this->is_eligible = !$this->isAutoFlagged();
            $this->save();
        }
    }

    /**
     * Admin manually sets eligibility.
     */
    public function overrideEligibility($status)
    {
        $this->is_eligible = $status;
        $this->eligibility_overridden = true;
        $this->save();
    }

    /**
     * Admin removes override and system goes back to auto mode.
     */
    public function removeOverride()
    {
        $this->eligibility_overridden = false;
        $this->updateAutoEligibility();
    }
}
