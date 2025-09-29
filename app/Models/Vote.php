<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'election_id',
        'candidate_id',
    ];

    /**
     * The user who cast this vote.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The election that this vote belongs to.
     */
    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id');
    }

    /**
     * The candidate that received this vote.
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}
