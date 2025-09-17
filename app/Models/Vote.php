<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;       // import User model
use App\Models\Election;   // import Election model
use App\Models\Candidate;  // import Candidate model

class Vote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'election_id',
        'candidate_id',
    ];

    /**
     * Get the user who cast this vote.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the election associated with this vote.
     */
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Get the candidate that received this vote.
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
