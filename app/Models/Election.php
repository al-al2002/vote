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

    // Casts for automatic Carbon instances
    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | Custom Attributes
    |--------------------------------------------------------------------------
    */

    // Get the winner candidate (only works if closed and votes exist)
    public function getWinnerAttribute()
    {
        if (!$this->isClosed()) {
            return null;
        }

        return $this->candidates()
            ->withCount('votes')
            ->orderByDesc('votes_count')
            ->first();
    }
}
