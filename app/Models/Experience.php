<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = ['profile_id', 'job_title', 'years_of_experience', 'job_description'];

    public function profiles(){

        return $this->belongsToMany(Profile::class, 'experience_profile')
                    ->withPivot('years_of_experience', 'job_description')
                    ->withTimestamps();
    }


    public function posts(){

        return $this->hasMany(Post::class);
    }

}
