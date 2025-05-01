<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    protected $fillable = ['name','post_id'];


    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
