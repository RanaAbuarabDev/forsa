<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesiredJob extends Model
{

    protected $fillable = [
        'profile_id',
        'job_title',
        'job_location',
        'employment_type',
    ];

    
    public function profile(){
        return $this->belongsTo(Profile::class);
    }

}
