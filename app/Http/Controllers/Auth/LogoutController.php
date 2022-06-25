<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\SendNotifications;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
class LogoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
         $users = User::where('type', '1')->get();
        Notification::send($users, new SendNotifications('تم  تسجيل الخروج من تطبيق الشحن  ', auth()->user()->id));
        auth()->logout();

        return redirect()->route('/');
    }
}