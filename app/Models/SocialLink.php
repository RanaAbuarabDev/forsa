<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasFactory;
    protected $fillable = ['profile_id', 'platform', 'url'];

    public function profile(){
        
        return $this->belongsTo(Profile::class);
    }

}
