<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Order;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\DishPolicy;
use App\Policies\OrderPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
//         'App\Models\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Category::class => CategoryPolicy::class,
        Dish::class => DishPolicy::class,
        Order::class => OrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
