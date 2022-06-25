<?php
namespace App\Http\Controllers;

use App\Models\Vessel;
use Illuminate\Support\Facades\DB;
use App\Notifications\SendNotifications;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $users = User::where('type', '1')->get();
            Notification::send($users, new SendNotifications('تم الدخول على live', auth()->user()->id));
        return $next($request);
    });
    }

    public function index()
    {
         
            return view('reports.live');
    }
}