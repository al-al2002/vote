<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'voter_id',
        'password',
        'role',
        'is_eligible',
        'has_voted',
        'profile_photo',
    ];

    protected $casts = [
        'is_eligible' => 'boolean',
        'has_voted' => 'boolean',
    ];

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function skippedElectionsCount()
    {
        $total = \App\Models\Election::count();
        $voted = $this->votes()->distinct('election_id')->count();
        return $total - $voted;
    }

    // Check if voter should be auto-flagged visually

public function isAutoFlagged()
{
    return $this->skippedElectionsCount() >= 3;
}

}
