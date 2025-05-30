<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable=['img','BD','residence_area','gender','governorate_id','employment_status','cv_path'];


    public function user(){

        return $this->belongsTo(User::class);
    }

   

    public function skills(){

        return $this->belongsToMany(Skill::class);
    }

    public function governorate(){

        return $this->belongsTo(Governorate::class);
    }

    public function desiredJob(){
        return $this->hasOne(DesiredJob::class);
    }


    public function educationalQualification(){

        return $this->hasOne(EducationalQualification::class);
    }

    
    public function socialLinks(){

        return $this->hasMany(SocialLink::class);
    }

    public function certificates(){

        return $this->belongsToMany(Certificate::class)
                    ->withPivot('issued_at')
                    ->withTimestamps();
    }



    public function experiences(){

        return $this->belongsToMany(Experience::class, 'experience_profile')
                    ->withPivot('years_of_experience', 'job_description')
                    ->withTimestamps();

    }



}
