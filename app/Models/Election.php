<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Election extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];

    /* ----------------------- Relationships ----------------------- */

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /* ----------------------- Status Helpers ----------------------- */

    public function isActive(): bool
    {
        $now = Carbon::now();
        return $this->start_date <= $now && $this->end_date >= $now;
    }

    public function isUpcoming(): bool
    {
        return $this->start_date > Carbon::now();
    }

    public function isClosed(): bool
    {
        return $this->end_date < Carbon::now();
    }

    /* ----------------------- Winner Helpers ----------------------- */

    /**
     * Return the candidate(s) with the highest votes.
     * Supports ties.
     */
    public function winners()
    {
        if (!$this->isClosed()) {
            return collect(); // Empty collection if election is not closed
        }

        // Ensure candidates are loaded with votes count
        $candidates = $this->candidates()->withCount('votes')->get();

        if ($candidates->isEmpty()) {
            return collect();
        }

        $maxVotes = $candidates->max('votes_count');

        return $candidates->where('votes_count', $maxVotes);
    }

    /**
     * Return a single winner (first in case of tie)
     */
    public function winner()
    {
        return $this->winners()->first();
    }
}
