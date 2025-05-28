<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'voter_profile_id',
        'election_id',
        'candidate_id',
        'vote_type',
        'constituency',
        'is_verified',
        'ip_address',
        'device_id'
    ];

    public function voterProfile()
    {
        return $this->belongsTo(VoterProfile::class);
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }



}
