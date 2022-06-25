<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Vessel;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Notifications\SendNotifications;
use Illuminate\Support\Facades\Notification;

class CarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show($id)
    {
        $vessel = Vessel::where('vessel_id', $id)->first();

        $cars = Car::where([['vessel_id', $id], ['done',  $vessel->done]])->orderBy('start_date', 'DESC')->get();

        return DataTables::of($cars)->make(true);

    }
    public function update(Request $request, $id)
    {
        $car = Car::where('id', $id)->first();
        if (!$car->update($request->all())) {
            return response($car, 422);
        }
        $users = User::where('type', '1')->get();
        Notification::send($users, new SendNotifications('  تم إضافة تكاليف لسيارة  '.$car->car_no.'  بنجاح ', auth()->user()->id));
        return response($car, 201);
    }

}