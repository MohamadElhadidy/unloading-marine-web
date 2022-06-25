<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
class canView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $id)
    {
            if(auth()->user()->type != 4){
            $role = Role::where('user_id',  auth()->user()->id)->first();
            if($role){
            $roles = explode(',', urldecode($role->report_or_vessel));
            for($i=0;$i<count($roles);$i++){
                if ($roles[$i] == $id) {
                        return redirect('/');
                    }
            }
        }
            return $next($request);
        }else {
            $role = Role::where('user_id',  auth()->user()->id)->first();
            if($role){
            $roles = explode(',', urldecode($role->report_or_vessel));
            for($i=0;$i<count($roles);$i++){
                if ($roles[$i] == $id) {
                            return $next($request);
                    }
            }
        }
            return redirect('/');
        }
        
    }
}