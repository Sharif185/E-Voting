<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 'admin', 'voter', etc.
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the voter profile associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function voterProfile()
    {
        return $this->hasOne(VoterProfile::class, 'user_id');
    }

    /**
     * Check if the user has completed their voter profile.
     *
     * @return bool
     */
    public function hasCompletedProfile()
    {
        return $this->voterProfile()->exists();
    }

    /**
     * Check if the user is an approved voter.
     *
     * @return bool
     */
    public function isApprovedVoter()
    {
        return $this->role === 'voter' &&
               ($this->voterProfile->is_approved ?? false);
    }

    /**
     * Scope a query to only include approved voters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApprovedVoters($query)
    {
        return $query->where('role', 'voter')
                    ->whereHas('voterProfile', function($q) {
                        $q->where('is_approved', true);
                    });
    }

    /**
     * Scope a query to only include pending approval voters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendingApprovalVoters($query)
    {
        return $query->where('role', 'voter')
                    ->whereHas('voterProfile', function($q) {
                        $q->where('is_approved', false);
                    });
    }


}
