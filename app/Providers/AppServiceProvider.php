<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Models\Role;

class AppServiceProvider extends ServiceProvider
{

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
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        date_default_timezone_set('Africa/Cairo');

        Blade::if('userType', function ($type) {

            if (isset(auth()->user()->auth->name)) {
                for ($i = 0; $i < count($type); $i++) {
                    if (auth()->user()->auth->name == $type[$i]) {
                        return true;
                    }
                }
            }
            return false;
        });

        Blade::if('NoAccessWithId', function ($id) {

            if (isset(auth()->user()->auth->name)) {
                for ($i = 0; $i < count($id); $i++) {
                    if (auth()->user()->id == $id[$i]) {
                        return false;
                    }
                }
            }
            return true;
        });

        Blade::if('NoAccessWithType', function ($type) {

            if (isset(auth()->user()->auth->name)) {
                for ($i = 0; $i < count($type); $i++) {
                    if (auth()->user()->auth->name == $type[$i]) {
                        return false;
                    }
                }
            }
            return true;
        });

        Blade::if('canView', function ($id) {
            if(auth()->user()->type != 4){
            $role = Role::where('user_id',  auth()->user()->id)->first();
            if($role){
            $roles = explode(',', urldecode($role->report_or_vessel));
            for($i=0;$i<count($roles);$i++){
                if ($roles[$i] == $id) {
                        return false;
                    }
            }
        }
            return true;
        }else {
            $role = Role::where('user_id',  auth()->user()->id)->first();
            if($role){
            $roles = explode(',', urldecode($role->report_or_vessel));
            for($i=0;$i<count($roles);$i++){
                if ($roles[$i] == $id) {
                        return true;
                    }
            }
            }
            return false;
        }
        });
    

    }
}