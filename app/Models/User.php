<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use App\Models\Profile;
use Spatie\Permission\Traits\HasRoles; 
use Illuminate\Database\Eloquent\Relations\HasOne;



/**
 * @property-read \App\Models\Profile|null $profile
 */
class User extends Authenticatable implements JWTSubject {
    use HasFactory;
    use Notifiable {
        notify as traitNotify;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'notifications_enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'notifications_enabled' => 'boolean',
    ];

     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    /**
     * Get the user's profile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile(){

        return $this->hasOne(Profile::class);
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function favorites(){
        return $this->belongsToMany(Post::class, 'user_favorites');
    }


    public function appliedPosts() {
        return $this->belongsToMany(Post::class, 'applications');
    }
    
    public function applications(){
        return $this->hasMany(\App\Models\Application::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }


    
    public function notify($instance): void
    {
        if ($this->notifications_enabled) {
            $this->traitNotify($instance);
        }
    }

    protected static function booted(){
        static::deleting(function ($user) {
            $user->profile()->delete();
        });
    }


}