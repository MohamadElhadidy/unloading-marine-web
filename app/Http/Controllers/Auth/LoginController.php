<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Notifications\SendNotifications;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index()
    {
          $username =  Redis::get('username');
        $password =  Redis::get('password');
        $data = array(
    "username" => $username,
    "password" => $password 
    );
        if($username != null AND $password  != null ) {
            if(auth()->attempt( $data,'on')){
                return redirect('/');
            }
        }
        return  view('auth.login');
    }

    public function store(Request $request)
    {
        $msg = ' اسم المستخدم أو كلمة السر غير صحيح ';
        $this->validate($request,[
            'username'=>'required',
            'password'=>'required'
        ]);
        $users = User::where('type', '1')->get();
           
      
        if(!auth()->attempt($request->only('username', 'password'),'on')){
             Notification::send($users, new SendNotifications('اسم الدخول أو كلمة السر عير صحيحية', $request->username));
            return response($msg, 422 );
        }
         Notification::send($users, new SendNotifications(' تم الدخول على تطبيق الشحن', $request->username));
        return  true;
    }
}