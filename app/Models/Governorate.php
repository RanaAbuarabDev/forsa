<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    
    protected $fillable = ['name'];


    public function users(){
        return $this->hasMany(Profile::class);
    }



    public function posts(){
        return $this->hasMany(Post::class);
    }
}
