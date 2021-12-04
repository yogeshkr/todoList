<?php

namespace App\Providers;

use App\Extensions\AccessTokenGuard;
use App\Extensions\TokenToUserProvider;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\AuthServiceProvider as ServiceProvider;
class AuthServiceProvider extends ServiceProvider
{

    /**
     * The policy mappings for the application.
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register the application's policies.
     *
     * @return void
     */
    public function registerPolicies()
    {
        foreach ($this->policies() as $key => $value) {
            Gate::policy($key, $value);
        }
    }

    /**
     * Get the policies defined on the provider.
     *
     * @return array
     */
    public function policies()
    {
        return $this->policies;
    }

    /**
     * Register any authentication / authorization services.
     * @return void
     */
    public function boot () {
//
//        $this->app['auth']->viaRequest('api', function ($request) {
//            if ($request->header('Authorization')) {
//                $key = explode(' ', $request->header('Authorization'));
//                $user = Users::where('api_token', $key[1])->first();
//                if (!empty($user)) {
//                    $request->request->add(['user_id' => $user->id]);
//                }
//
//                return $user;
//            }
//        });
        $this->registerPolicies();
        Auth::extend('access_token', function ($app, $name, array $config) {
            // automatically build the DI, put it as reference
            $userProvider = app(TokenToUserProvider::class);
            $request = app('request');
            return new AccessTokenGuard($userProvider, $request, $config);
        });
    }
}
