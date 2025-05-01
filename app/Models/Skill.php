<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{

    protected $fillable = ['name']; 
    
    public function profiles(){

        return $this->belongsToMany(Profile::class);
    }


    public function posts(){
        return $this->belongsToMany(Post::class);
    }


}
