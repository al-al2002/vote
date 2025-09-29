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

    // Status checks with datetime
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

    // Winners
    public function winners()
    {
        if (!$this->isClosed()) {
            return collect();
        }

        $candidates = $this->candidates()->withCount('votes')->get();
        if ($candidates->isEmpty()) return collect();

        $maxVotes = $candidates->max('votes_count');
        return $candidates->where('votes_count', $maxVotes);
    }

    public function winner()
    {
        return $this->winners()->first();
    }
}
