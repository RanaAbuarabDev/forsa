<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationalQualification extends Model
{

    use HasFactory;
    protected $fillable = [
        'profile_id',
        'education_level',
        'institution_name',
        'major',
        'graduation_date',
    ];
    
    public function profile(){

        return $this->belongsTo(Profile::class);
    }

    
}
