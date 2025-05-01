<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable=['img','BD','PhonNum','bio','governorate_id','age'];


    public function user(){

        return $this->belongsTo(User::class);
    }

   

    public function skills(){

        return $this->belongsToMany(Skill::class);
    }

    public function governorate(){

        return $this->belongsTo(Governorate::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function residence()
    {
        return $this->hasOne(Residence::class);
    }
}
