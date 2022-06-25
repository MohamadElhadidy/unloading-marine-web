<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Move;
use App\Models\Vessel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DatatableController extends Controller
{
    public function management()
    {
        $vessels = Vessel::where('done', '0')->orderBy('vessel_id', 'DESC')->get();
        
        return DataTables::of($vessels)
            ->addColumn('action', function ($vessels) {
                if(auth()->user()->type== 1 ){
                    return '<a  href="/vessels/' . $vessels->id . '/edit" class="edit-button"><i class="fas fa-edit"></i> تعديل</a>
                    <a  coords="' . $vessels->name . '"  id="' . $vessels->id . '" onclick="getId(this.id)"  href="#" class="delete-button"><i class="fas fa-trash-alt"></i> حذف</a>
                    <a  coords="' . $vessels->name . '"  rel="' . $vessels->id . '" id="end' . $vessels->id . '" onclick="end(this.id)"  href="#" class="end-button"><i class="fas fa-trash-alt"></i> إنهاء </a>';
                }else{
                    return '<a  href="/vessels/' . $vessels->id . '/edit" class="edit-button"><i class="fas fa-edit"></i> تعديل</a>
                    <a  coords="' . $vessels->name . '"  rel="' . $vessels->id . '" id="end' . $vessels->id . '" onclick="end(this.id)"  href="#" class="end-button"><i class="fas fa-trash-alt"></i> إنهاء </a>';
                }
                 
            })
            ->make(true);
    }
    public function users()
    {
       $users =  DB::table('users')
            ->join('auth', 'auth.id', '=', 'users.type')
            ->select('users.*', 'auth.name as auth')
            ->get();

        return DataTables::of($users)
            ->addColumn('action', function ($users) {
                if($users->type == 1 ){
                        return '<a  href="/users/' . $users->id . '/edit" class="edit-button"><i class="fas fa-edit"></i> تعديل</a>';
                }else{
                        return '<a  href="/users/' . $users->id . '/edit" class="edit-button"><i class="fas fa-edit"></i> تعديل</a>
                    <a  coords="' . $users->name . '"  id="' . $users->id . '" onclick="getId(this.id)"  href="#" class="delete-button"><i class="fas fa-trash-alt"></i> حذف</a>';
                }
               
            })
            ->make(true);
    }
    public function archive()
    {
        $vessels = Vessel::where('done', '1')->orderBy('vessel_id', 'DESC')->get();

        return DataTables::of($vessels)
            ->addColumn('total_moves', function ($vessels) {
                $total_moves = Move::where([['vessel_id', $vessels->vessel_id], ['arrival', 1]])->count();

                return $total_moves;
            })
            ->addColumn('action', function ($vessels) {
                return '<a  href="/vessels/' . $vessels->vessel_id . '" class="edit-button"><i class="fas fa-table"></i> التقارير</a>';
            })
            ->make(true);
    }
    public function loading($id)
    {
        $loading = DB::table('loading')
            ->join('cars', 'loading.sn', '=', 'cars.sn')
            ->select('loading.*', 'cars.car_no as car_no')
            ->where('loading.vessel_id', '=', $id)
            ->where('cars.vessel_id', '=', $id)
            ->orderByDesc('date')
            ->get();
        return DataTables::of($loading)->make(true);
    } 
    
    public function live()
    {
       $vessels = Vessel::where('done', 0)->get();
        foreach ($vessels as $vessel) {
            $qnt_sum = DB::table('loading')
            ->select(DB::raw('SUM(jumbo) as total_jumbo'), DB::raw('SUM(qnt) as total_qnt'),  DB::raw('count(*) as count'))
            ->where([['vessel_id', $vessel->vessel_id]])
            ->where('is_delete', '=', 0)
            ->whereNotNull('qnt_date')
            ->first();
            if ($qnt_sum->total_jumbo == 0 ) $qnt_sum->total_jumbo='';
            else $qnt_sum->total_jumbo= ' ('. $qnt_sum->total_jumbo.')';

            $move_count = DB::table('move')
            ->select( DB::raw('count(*) as moves_count'))
            ->where([['vessel_id', $vessel->vessel_id] ,[ 'arrival' , '1']])
            ->where('is_delete', '=', 0)
            ->first();

            $vessel->qnt = '('.number_format((float)$qnt_sum->total_qnt, 3, '.', '').')';
            $vessel->archive = '('. $qnt_sum->count.')';
            $vessel->phones = $qnt_sum->total_jumbo;
            $vessel->notes = $move_count->moves_count;
        }
        return  [
            'vessels' => $vessels,
        ];
    }
    public function arrival($id)
    {
        $arrival = DB::table('arrival')
            ->join('cars', 'arrival.sn', '=', 'cars.sn')
            ->select('arrival.*', 'cars.car_no as car_no')
            ->where('arrival.vessel_id', '=', $id)
            ->where('cars.vessel_id', '=', $id)
            ->orderByDesc('date')
            ->get();
     
        return DataTables::of($arrival)->make(true);
    }
      public function direct($id)
    {
        $direct = DB::table('direct')
            ->join('cars', 'direct.sn', '=', 'cars.sn')
            ->select('direct.*', 'cars.car_no as car_no', 'cars.car_no2 as car_no2')
            ->where('direct.vessel_id', '=', $id)
            ->where('cars.vessel_id', '=', $id)
            ->where('direct.is_delete', '=', 0)
            ->orderByDesc('date')
            ->get();
        return DataTables::of($direct)->make(true);
    }
    public function stops($id)
    {
        $stop = DB::table('stop')
            ->select('stop.*')
            ->where('vessel_id', '=', $id)
            ->get();
        return DataTables::of($stop)->make(true);
    }
    public function minus($id)
    {
              $minus = DB::table('minus')
            ->join('cars', 'minus.sn', '=', 'cars.sn')
            ->select('minus.*','minus.cause as car_no', 'cars.car_no as car_no', 'cars.car_no2 as car_no2')
            ->where('minus.vessel_id', '=', $id)
            ->where('cars.vessel_id', '=', $id)
            ->get();
     foreach ($minus as $min) {
            $min->car_no = $min->car_no.' - ' .$min->car_no2;
        }
        return DataTables::of($minus)->make(true);
    }
    public function travels($id)
    {
        $travels = DB::table('cars')
            ->select('cars.*')
            ->where('vessel_id', '=', $id)
            ->where('done', '=', 0)
            ->get();

        return DataTables::of($travels)
            ->addColumn('duration', function ($travels) {

                $move = DB::table('move')
                    ->select('move.*')
                    ->where('vessel_id', '=', $travels->vessel_id)
                    ->where('sn', '=', $travels->sn)
                    ->orderBy('load_date', 'desc')
                    ->limit(1)
                    ->first();
                if(is_null($move)){
                    $time_now = '';
                    return $time_now;
                }
                elseif ($move->arrival == 1) {
                    $to = strtotime($move->arrival_date);
                    $from =  strtotime(date("Y-m-d H:i:s"));
                    $diff =  $from - $to;
                    $day = $diff / 86400 % 7 . " " . "يوم";
                    $hour = $diff / 3600 % 24 . " " . "ساعة";
                    $minute = $diff / 60 % 60 . " " . "دقيقة";
                    if($diff / 86400 % 7 == 0 )    $day ='';
                    if($diff / 3600 % 24 == 0 )    $hour ='';
                    if($diff / 60 % 60 == 0 )    $minute ='';
                    $time_now = $minute . " " . $hour . " " . $day;
                    return $time_now;

                } elseif($move->arrival == 0) {
                    $to = Carbon::parse($move->load_date);
                    $to = strtotime($move->load_date);
                    $from =  strtotime(date("Y-m-d H:i:s"));
                    $diff =  $from - $to;
                    $day = $diff / 86400 % 7 . " " . "يوم";
                    $hour = $diff / 3600 % 24 . " " . "ساعة";
                    $minute = $diff / 60 % 60 . " " . "دقيقة";
                    if($diff / 86400 % 7 == 0 )    $day ='';
                    if($diff / 3600 % 24 == 0 )    $hour ='';
                    if($diff / 60 % 60 == 0 )    $minute ='';
                    $time_now = $minute . " " . $hour . " " . $day;
                    return $time_now;

                } 

            })
            ->addColumn('last_date', function ($travels) {
                $move = DB::table('move')
                    ->select('move.*')
                    ->where('vessel_id', '=', $travels->vessel_id)
                    ->where('sn', '=', $travels->sn)
                    ->orderBy('load_date', 'desc')
                    ->limit(1)
                    ->first();
                if(is_null($move)){
                    return '';
                }elseif($move->arrival == 1) {
                    return $move->arrival_date;
                }elseif($move->arrival == 0) {
                    return $move->load_date;
                }

            })
            ->addColumn('where', function ($travels) {
                $move = DB::table('move')
                    ->select('move.*')
                    ->where('vessel_id', '=', $travels->vessel_id)
                    ->where('sn', '=', $travels->sn)
                    ->orderBy('load_date', 'desc')
                    ->limit(1)
                    ->first();
                if(is_null($move)){
                    return 'لم يتم   تحميل او تعتيق السيارة بعد';
                }
                elseif($move->arrival == 1) {
                        return 'الى الرصيف';
                }elseif($move->arrival == 0) {
                        return 'الى المخزن';

                }

            })
            ->make(true);

    }


     public function cars($id)
    {
       $vessel = Vessel::where('vessel_id', $id)->first();

        $cars = Car::where([['vessel_id', $id], ['done',  $vessel->done]])->orderBy('start_date', 'DESC')->get();

        return DataTables::of($cars)->make(true);

    }

}