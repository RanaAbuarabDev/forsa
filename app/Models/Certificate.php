<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{

    use HasFactory;
    protected $fillable = ['profile_id','name','issued_at',];
    
    public function profiles(){

        return $this->belongsToMany(Profile::class)
                    ->withPivot('issued_at')
                    ->withTimestamps();
    }
    
}
