<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{

    use HasFactory ;
    protected $table = 'posts';
    protected $fillable = [
        'type',
        'description',
        'governorate_id',
        'experience_id',
        'user_id',
        'job_type',
        'online',
        'salary',
    ];


    public function governorate(){
        return $this->belongsTo(Governorate::class);
    }

    public function skills(){
        return $this->belongsToMany(Skill::class);
    }


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function favoritedBy(){
        return $this->belongsToMany(User::class, 'user_favorites');
    }

    public function applications() {
        return $this->hasMany(Application::class);
    }
    
    public function applicants() {
        return $this->belongsToMany(User::class, 'applications');
    }

    public function experience(){
        return $this->belongsTo(Experience::class);
    }

}
