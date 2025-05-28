<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vote;

class VoterProfile extends Model
{
    // app/Models/VoterProfile.php
    protected $fillable = [
        'user_id',
        'firstname',
        'lastname',
        'age',
        'gender',
        'national_id',
        'constituency',
        'election_type',
        'is_approved'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // app/Models/VoterProfile.php

    public function hasVotedInElection($electionId)
    {
        return $this->votes()
                   ->where('election_id', $electionId)
                   ->exists();
    }


    // app/Models/VoterProfile.php

    public function votes()
    {
        return $this->hasMany(Vote::class, 'voter_profile_id');
    }
}
