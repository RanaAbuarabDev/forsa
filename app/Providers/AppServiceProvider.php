<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Profile;
use App\Policies\ProfilePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;


class AuthServiceProvider extends ServiceProvider
{
    use HandlesAuthorization;
    protected $policies = [
        Profile::class => ProfilePolicy::class,
        \App\Models\Post::class => \App\Policies\PostPolicy::class,
    ];

    
    

    public function boot()
    {
        $this->registerPolicies();
    }
}
