<?php

namespace App\Providers;

use App\Models\Video;
use App\Policies\VideoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Video::class => VideoPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->passportConfig();


        /*Gate::before(function ($user, $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });*/

    }

    /**
     * passprot package config
     */
    private function passportConfig()
    {

        /*Passport::routes();*/

        Passport::tokensExpireIn(now()->addDays(config('auth.passport_Expire_day')));
    }
}
