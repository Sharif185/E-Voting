<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;
      protected $fillable = [
            'first_name',
            'last_name',
            'age',
            'gender',
            'nationality',
            'national_id',
            'election_id',
            'election_type',
            'constituency',
            'photo_path',
            'party',
            'biography_path',
            'manifesto_path',
            'status',
            'approved'
        ];


        public function election()
        {
            return $this->belongsTo(Election::class);
        }


}
