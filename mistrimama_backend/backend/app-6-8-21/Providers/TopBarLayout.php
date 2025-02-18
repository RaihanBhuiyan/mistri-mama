<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Notification;

class TopBarLayout extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('common.topbar', function ($view) {
            $auth_user_id = auth()->user()->id;
            $data['notification_counter'] = Notification::where(['notifiable_id' => $auth_user_id, 'read_at' => NULL])->count();
            $data['notifications'] = Notification::where(['notifiable_id' => $auth_user_id])->take(10)->orderBy('created_at', 'desc')->get();
            $view->with($data);
        });
    }
}
