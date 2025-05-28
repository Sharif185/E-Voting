<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Election extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'type',
        'start_date',
        'end_date',
        'voting_duration_hours',
        'constituency',
        'description',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'datetime:Y-m-d H:i:s',
        'end_date' => 'datetime:Y-m-d H:i:s',
        'is_active' => 'boolean',
    ];

    // Always store in UTC
    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = $value instanceof \DateTime
            ? $value->setTimezone('UTC')
            : Carbon::parse($value)->setTimezone('UTC');
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = $value instanceof \DateTime
            ? $value->setTimezone('UTC')
            : Carbon::parse($value)->setTimezone('UTC');
    }

    // Convert to local time when accessing
    public function getStartDateAttribute($value)
    {
        return Carbon::parse($value)->setTimezone(config('app.timezone'));
    }

    public function getEndDateAttribute($value)
    {
        return Carbon::parse($value)->setTimezone(config('app.timezone'));
    }

    /**
     * Get the candidates for the election.
     */
    public function candidates()
    {
        return $this->hasMany(Candidate::class, 'election_id', 'id')
                   ->where('approved', true)
                   ->where('status', 'Active');
    }

    /**
     * Check if election is currently active.
     */
    public function getIsActiveAttribute()
    {
        $now = now(config('app.timezone'));
        return $now->between($this->start_date, $this->end_date);
    }

    public function isActive()
    {
        $now = now(); // current time
        return $this->start_date <= $now && $this->end_date >= $now;
    }

    public function isFuture()
    {
        return $this->start_date > now();
    }

    public function results(): HasMany
        {
            return $this->hasMany(ElectionResult::class);
        }


}
