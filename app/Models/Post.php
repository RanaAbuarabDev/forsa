<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    protected $fillable=[
        'title','description','place','salary','Number_of_hours','ApplyStatus','salary'
    ];
}
