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
        'start_date' => 'datetime:Y-m-d H:i',
        'end_date'   => 'datetime:Y-m-d H:i',
    ];

    // Relationships
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    // Status checks
    public function isActive(): bool
    {
        $now = Carbon::now();
        return $this->start_date <= $now && $this->end_date >= $now;
    }

    public function isUpcoming(): bool
    {
        $now = Carbon::now();
        return $this->start_date > $now;
    }

    public function isClosed(): bool
    {
        $now = Carbon::now();
        return $this->end_date < $now;
    }

    // 🏆 Winners (with tie support)
    public function winners()
    {
        // Only show winners for closed elections
        if (!$this->isClosed()) {
            return collect();
        }

        // Get all candidates with their vote counts
        $candidates = $this->candidates()
            ->withCount('votes')
            ->get();

        if ($candidates->isEmpty()) {
            return collect();
        }

        // Get the highest number of votes
        $maxVotes = $candidates->max('votes_count');

        // Return all candidates with the highest vote count (tie supported)
        return $candidates->where('votes_count', $maxVotes)->values();
    }

    // 🪄 This allows $election->winners (no parentheses) to work
    public function getWinnersAttribute()
    {
        return $this->winners();
    }

    // 🥇 Single winner (optional)
    public function winner()
    {
        $winners = $this->winners();
        return $winners->count() === 1 ? $winners->first() : null;
    }
}
