<?php

namespace App\Http\Controllers;

use App\Events\Reports;
use App\Models\Car;
use App\Models\Vessel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Route;
use App\Notifications\SendNotifications;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
class ReportController extends Controller
{

        public function __construct()
        {
             if(Route::current()){
        $id = Route::current()->parameter('id');
        $this->middleware("auth");
        $this->middleware("canView:$id", [
        'only' => [
            'stats' ,

            ]
    ]);
}
    }
    //get  cars
    public function cars($id)
    {

        $vessel = Vessel::where('vessel_id', $id)->first();
  if($vessel ){
        $total_cars = Car::where([['vessel_id', $id], ['type', 'normal']])->groupBy('car_no')->get()->count();
        $active_cars = Car::where([['vessel_id', $id], ['done', $vessel->done], ['type', 'normal']])->groupBy('car_no')->get()->count();
        $toktok_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة توكتوك'], ['done', $vessel->done], ['type', 'normal']])->groupBy('car_no')->get()->count();
        $qlab_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة قلاب'], ['done', $vessel->done], ['type', 'normal']])->groupBy('car_no')->get()->count();
        $company_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة الشركة'], ['done', $vessel->done], ['type', 'normal']])->groupBy('car_no')->get()->count();
        $grar_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة جرار'], ['done', $vessel->done], ['type', 'normal']])->groupBy('car_no')->get()->count();
       $users = User::where('type', '1')->get();
            Notification::send($users, new SendNotifications(' تم الدخول على تقرير السيارات المفعلة '.$vessel->name, auth()->user()->id));
        return view('reports.cars', [
            'vessel' => $vessel,
            'total_cars' => $total_cars,
            'active_cars' => $active_cars,
            'toktok_cars' => $toktok_cars,
            'qlab_cars' => $qlab_cars,
            'company_cars' => $company_cars,
            'grar_cars' => $grar_cars,
        ]);
        }
          return redirect('/');
    }

    //get  cars
    public function analysis($id)
    {
        $vessel = Vessel::where('vessel_id', $id)->first();
        
         if($vessel){
               $users = User::where('type', '1')->get();
            Notification::send($users, new SendNotifications(' تم الدخول على تقرير بيان تحليلى بالكميات '.$vessel->name, auth()->user()->id));
        return view('reports.analysis', [
            'vessel' => $vessel,
        ]); 
         }
          return redirect('/');
    }
    public function stats($id)
    {
        $vessel = Vessel::where('vessel_id', $id)->first();
         if($vessel){
               $users = User::where('type', '1')->get();
            Notification::send($users, new SendNotifications(' تم الدخول على تقرير معدلات الباخرة '.$vessel->name, auth()->user()->id));
        return view('reports.stats', [
            'vessel' => $vessel,
        ]);
         }
          return redirect('/');
    }
    //get  stats
    public function DStats($id)
    {
        $vessel = Vessel::where('vessel_id', $id)->first();

        if ($vessel->done == 0) {
            $now = Carbon::now();
        } else {
            $now = $vessel->end_date;
        }


    $days=0;
    $hours=0;
    $minutes=0;

$arrival = DB::table('move')
            ->select('*')
            ->where([['vessel_id', $vessel->vessel_id],['arrival', 1]])
            ->where('is_delete', '=', 0)
            ->orderby('arrival_date', 'asc')
            ->get()->first();
            
 if(isset($arrival->arrival_date)){
        $normal_date =strtotime($arrival->arrival_date);

        if($vessel->done == 1){
            $arrivals2 =   DB::table('move')
                ->select('*')
                ->where([['vessel_id', $vessel->vessel_id],['arrival', 1]])
                ->where('is_delete', '=', 0)
                ->orderby('arrival_date', 'desc')
                ->first();

            $now = Carbon::parse($arrivals2->arrival_date);

        }else{
            $now = Carbon::now();
        }

        $normal_date0 = Carbon::parse($normal_date);
        $diff = $normal_date0->diff($now);

        $days = $diff->d *24;
        $hours = $diff->h + $days;

        }


        $direct_cars = Car::where([['vessel_id', $id],['cars.type', 'direct']])->groupBy('car_no')->get()->count();
        $total_cars = Car::where([['vessel_id', $id],['cars.type', 'normal']])->groupBy('car_no')->get()->count();
        $active_cars = Car::where([['vessel_id', $id], ['done', $vessel->done],['cars.type', 'normal']])->groupBy('car_no')->get()->count();
        $toktok_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة توكتوك'], ['done', $vessel->done],['cars.type', 'normal']])->groupBy('car_no')->get()->count();
        $qlab_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة قلاب'], ['done', $vessel->done],['cars.type', 'normal']])->groupBy('car_no')->get()->count();
        $company_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة الشركة'], ['done', $vessel->done],['cars.type', 'normal']])->groupBy('car_no')->get()->count();
        $grar_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة جرار'], ['done', $vessel->done],['cars.type', 'normal']])->groupBy('car_no')->get()->count();

   
    
            
            
            $direct_all = DB::table('move')
            ->select(DB::raw('count(*) as count_all'))
            ->where([['vessel_id', $id], ['arrival', 1], ['move_type', 'direct']])
            ->where('is_delete', '=', 0)
            ->first();

    
            $direct_sum = DB::table('direct')
            ->select(DB::raw('SUM(jumbo) as jumbo'), DB::raw('count(*) as count'), DB::raw('SUM(qnt) as qnt'))
            ->where([['vessel_id', $id]])
            ->where('is_delete', '=', 0)
            ->whereNotNull('qnt_date')
            ->first();

            $normal_all = DB::table('move')
            ->select(DB::raw('count(*) as count_all'))
            ->where([['vessel_id', $id], ['arrival', 1], ['move_type', 'normal']])
            ->where('is_delete', '=', 0)
            ->first();

    
    $normal_sum = DB::table('move')
            ->select(DB::raw('SUM(jumbo) as jumbo'), DB::raw('count(*) as count'), DB::raw('SUM(qnt) as qnt'))
            ->where([['vessel_id', $id], ['arrival', 1], ['move_type', 'normal']])
            ->where('is_delete', '=', 0)
            ->first();

    

 $all_count = $direct_sum->count +   $normal_sum->count; 
 $all_qnt = $direct_sum->qnt +   $normal_sum->qnt;
 $all_jumbo = $direct_sum->jumbo +   $normal_sum->jumbo;



        $room_sum = DB::table('move')
            ->select('*', DB::raw('SUM(qnt) as total_qnt'), DB::raw('count(*) as moves_count'), DB::raw('SUM(qnt) as total_qnt'))
            ->where([['vessel_id', $id], ['arrival', 1]])
            ->where('is_delete', '=', 0)
            ->groupBy('room_no')
            ->orderby('room_no', 'asc')
            ->get();

        $hobar_sum = DB::table('move')
            ->select('*', DB::raw('SUM(qnt) as total_qnt'), DB::raw('count(*) as moves_count'), DB::raw('SUM(qnt) as total_qnt'))
            ->where([['vessel_id', $id], ['arrival', 1]])
            ->where('is_delete', '=', 0)
            ->groupBy('hobar')
         ->orderby('hobar', 'asc')
            ->get();
            $hobar_room=[];
        for($f=0;$f< $hobar_sum->count();$f++){
            for($i=0;$i< $room_sum->count();$i++){
              
            $hobar_room[$f][$i]  = DB::table('move')
            ->select('*', DB::raw('SUM(qnt) as total_qnt'), DB::raw('count(*) as moves_count'))
            ->where([['vessel_id', $id], ['hobar', $hobar_sum[$f]->hobar ], ['room_no', $room_sum[$i]->room_no]])
            ->where('is_delete', '=', 0)
             ->orderby('room_no', 'asc')
            ->get();

            }
        }
          


        $kbsh_sum = DB::table('move')
            ->select('*', DB::raw('SUM(qnt) as total_qnt'), DB::raw('count(*) as moves_count'), DB::raw('SUM(qnt) as total_qnt'))
            ->where([['vessel_id', $id], ['arrival', 1]])
            ->where('is_delete', '=', 0)
            ->groupBy('kbsh')
            ->get();

        $crane_sum = DB::table('move')
            ->select('*', DB::raw('SUM(qnt) as total_qnt'), DB::raw('count(*) as moves_count'))
            ->where([['vessel_id', $id], ['arrival', 1], ['crane','!=', 'بدون أوناش']])
            ->where('is_delete', '=', 0)
            ->groupBy('crane')
            ->orderby('crane', 'asc')
            ->get();
             $crane_room =[];
        for($f=0;$f< $crane_sum->count();$f++){
            for($i=0;$i< $room_sum->count();$i++){
              
            $crane_room[$f][$i]  = DB::table('move')
            ->select('*', DB::raw('SUM(qnt) as total_qnt'), DB::raw('count(*) as moves_count'))
            ->where([['vessel_id', $id], ['crane', $crane_sum[$f]->crane ], ['room_no', $room_sum[$i]->room_no]])
            ->where('is_delete', '=', 0)
             ->orderby('room_no', 'asc')
            ->get();

            }
        }
          

        $type_sum = DB::table('move')
            ->select('*', DB::raw('SUM(qnt) as total_qnt'), DB::raw('count(*) as moves_count'))
            ->where([['vessel_id', $id], ['arrival', 1], ['is_delete', 0]])
            ->groupBy('type')
             ->orderby('type', 'asc')
            ->get();
        $type_room=[];
        for($f=0;$f< $type_sum->count();$f++){
            for($i=0;$i< $room_sum->count();$i++){
              
            $type_room[$f][$i]  = DB::table('move')
            ->select('*', DB::raw('SUM(qnt) as total_qnt'), DB::raw('count(*) as moves_count'))
            ->where([['vessel_id', $id], ['type', $type_sum[$f]->type ], ['room_no', $room_sum[$i]->room_no], ['is_delete', 0]])
             ->orderby('room_no', 'asc')
            ->get();
            }
        }
        $store_no_sum = DB::table('move')
            ->select('*', DB::raw('SUM(qnt) as total_qnt'), DB::raw('count(*) as moves_count'), DB::raw('SUM(qnt) as total_qnt'))
            ->where([['vessel_id', $id], ['arrival', 1]])
            ->where('is_delete', '=', 0)
            ->groupBy('store_no')
            ->get();

        $car_owners = DB::table('cars')
            ->select('*')
            ->where([['cars.vessel_id', $id],['cars.type', 'normal']])
            ->groupBy('car_owner')
            ->get();
        foreach ($car_owners as $car_owner) {

            $car_owner_cars = DB::table('cars')
                ->select('*')
                ->where([['vessel_id', $id], ['car_owner', $car_owner->car_owner]])
                ->get();

                $moves_count = 0;
                $qnts = 0;
                
                foreach ($car_owner_cars as $car) {
                        $moves = DB::table('move')
                        ->select(DB::raw('count(*) as moves_count'),DB::raw('sum(qnt) as qnts'))
                        ->where([['vessel_id', $id], ['sn', $car->sn],  ['arrival', 1]])
                        ->where('is_delete', '=', 0)
                        ->get();


                        $moves_count +=  $moves[0]->moves_count;
                        $qnts +=  $moves[0]->qnts;
                }
          
                $count = DB::table('cars')
                ->select('*')
                ->where([['vessel_id', $id], ['car_owner', $car_owner->car_owner]])
                ->groupBy('car_no')
                ->get()->count();

                $car_owner->limits =  $count;
                $car_owner->vacant =  $moves_count;
                $car_owner->car_no2 = $qnts;
                 
        }
       
 

        $cars = DB::table('cars')
            ->select('*', 'limits as moves_count', 'vacant as qnts',  'car_no  as avg')
            ->where([['cars.vessel_id', $id],['cars.type', 'normal']])
            ->orderby('id','asc')
            ->groupBy('car_no')
            ->get();
       
            foreach ($cars as $car) {
                    $car->moves_count = 0 ;
                    $car->qnts = 0 ;
                    $car->avg = 0;
                    $car->sn = '';
                    $cars_no = Car::where([['vessel_id', $id], ['car_no',$car->car_no]])->get();
                    $num = $cars_no->count() - 1;
                    for($i=0;$i < $cars_no->count(); $i++){
                            $car->sn .=  '  '.$cars_no[$i]->sn.'  ';
                            $moves = DB::table('move')
                            ->select(DB::raw('count(*) as moves_count'),DB::raw('sum(qnt) as qnts'))
                            ->where([['vessel_id', $id], ['sn', $cars_no[$i]->sn],  ['arrival', 1]])
                            ->where('is_delete', '=', 0)
                            ->get();
                            $car->moves_count +=  $moves[0]->moves_count;
                            $car->qnts +=  $moves[0]->qnts; 
                            
                            if ($moves[0]->moves_count != 0) {
                                $car->avg +=   $moves[0]->qnts /  $moves[0]->moves_count; 
                            }else {
                                $car->avg = 0;
                            }
                    } 
                    $car->qnts =  number_format($car->qnts, 3, '.', ''); 
                    $car->avg =   number_format($car->avg, 3, '.', ' '); 

                 
                    $last = DB::table('cars')
            ->select('*')
            ->where([['vessel_id', $id],['car_no', $car->car_no]])
            ->orderby('id','desc')
            ->first();
                    if($last->exit_date == null){
                        $car->done = 0;
                        $car->exit_date = '----------';
                        $car->status = 'ما زالت على الباخرة';
                    }else {
                        $car->done = 1;
                        $car->exit_date =$last->exit_date;
                        $car->status = 'خرجت';
                    }
                    }


        return view('reports.stats_load', [
            'vessel' => $vessel,
            'direct_cars' => $direct_cars,
            'total_cars' => $total_cars,
            'active_cars' => $active_cars,
            'toktok_cars' => $toktok_cars,
            'qlab_cars' => $qlab_cars,
            'company_cars' => $company_cars,
            'grar_cars' => $grar_cars,
            'days' => $days,
            'hours' => $hours,
            'direct_sum' => $direct_sum,
            'direct_all' => $direct_all,
            'normal_sum' => $normal_sum,
            'normal_all' => $normal_all,
            'all_count' => $all_count,
            'all_qnt' => $all_qnt,
            'all_jumbo' => $all_jumbo,
            'room_sum' => $room_sum,
            'hobar_sum' => $hobar_sum,
            'hobar_room' => $hobar_room,
            'kbsh_sum' => $kbsh_sum,
            'crane_sum' => $crane_sum,
            'crane_room' => $crane_room,
            'type_sum' => $type_sum,
            'type_room' => $type_room,
            'store_no_sum' => $store_no_sum,
            'car_owners' => $car_owners,
            'cars' => $cars
        ]);
    }

    //get  stats
    public function loading($id)
    {
        $vessel = Vessel::where('vessel_id', $id)->first();
        if($vessel ){
            $total_cars = Car::where([['vessel_id', $id], ['type', 'normal']])->groupBy('car_no')->get()->count();
            $active_cars = Car::where([['vessel_id', $id], ['done', $vessel->done], ['type', 'normal']])->groupBy('car_no')->get()->count();
            $toktok_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة توكتوك'], ['type', 'normal']])->groupBy('car_no')->get()->count();
            $qlab_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة قلاب'], ['type', 'normal']])->groupBy('car_no')->get()->count();
            $company_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة الشركة'] , ['type', 'normal']])->groupBy('car_no')->get()->count();
            $grar_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة جرار'], ['type', 'normal']])->groupBy('car_no')->get()->count();
            $users = User::where('type', '1')->get();
            Notification::send($users, new SendNotifications(' تم الدخول على تقرير التحميل من المخزن '.$vessel->name, auth()->user()->id));
        return view('reports.loading', [
            'vessel' => $vessel,
              'total_cars' => $total_cars,
                'active_cars' => $active_cars,
                'toktok_cars' => $toktok_cars,
                'qlab_cars' => $qlab_cars,
                'company_cars' => $company_cars,
                'grar_cars' => $grar_cars
        ]);
         }
          return redirect('/');
    }
    //get  stats
    public function arrival($id)
    {
        $vessel = Vessel::where('vessel_id', $id)->first();
         if($vessel ){
              $total_cars = Car::where([['vessel_id', $id], ['type', 'normal']])->groupBy('car_no')->get()->count();
            $active_cars = Car::where([['vessel_id', $id], ['done', $vessel->done], ['type', 'normal']])->groupBy('car_no')->get()->count();
            $toktok_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة توكتوك'], ['type', 'normal']])->groupBy('car_no')->get()->count();
            $qlab_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة قلاب'], ['type', 'normal']])->groupBy('car_no')->get()->count();
            $company_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة الشركة'] , ['type', 'normal']])->groupBy('car_no')->get()->count();
            $grar_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة جرار'], ['type', 'normal']])->groupBy('car_no')->get()->count();
               $users = User::where('type', '1')->get();
            Notification::send($users, new SendNotifications(' تم الدخول على تقرير الشحن على الرصيف '.$vessel->name, auth()->user()->id));
        return view('reports.arrival', [
            'vessel' => $vessel,
              'total_cars' => $total_cars,
                'active_cars' => $active_cars,
                'toktok_cars' => $toktok_cars,
                'qlab_cars' => $qlab_cars,
                'company_cars' => $company_cars,
                'grar_cars' => $grar_cars
        ]);
         }
          return redirect('/');
    }
  public function direct($id)
    {
        $vessel = Vessel::where('vessel_id', $id)->first();
         if($vessel ){
            $direct_cars = Car::where([['vessel_id', $id], ['type', 'direct']])->groupBy('car_no')->get()->count();
    
            
               $users = User::where('type', '1')->get();
            Notification::send($users, new SendNotifications(' تم الدخول على تقرير  الصرف المباشر  '.$vessel->name, auth()->user()->id));
        return view('reports.direct', [
            'vessel' => $vessel,
            'direct_cars' => $direct_cars
        ]);
         }
          return redirect('/');
    }


    //get  stats
    public function quantity($id)
    {
        $vessel = Vessel::where('vessel_id', $id)->first();
          if($vessel ){
        $start_date = Carbon::parse($vessel->start_date);

        if ($vessel->done == 0) {
            $now = Carbon::now();
        } else {
            $now = $vessel->end_date;
        }

         $total_cars = Car::where([['vessel_id', $id], ['type', 'normal']])->groupBy('car_no')->get()->count();
        $active_cars = Car::where([['vessel_id', $id], ['done', $vessel->done], ['type', 'normal']])->groupBy('car_no')->get()->count();
        $toktok_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة توكتوك'], ['done', $vessel->done], ['type', 'normal']])->groupBy('car_no')->get()->count();
        $qlab_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة قلاب'], ['done', $vessel->done], ['type', 'normal']])->groupBy('car_no')->get()->count();
        $company_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة الشركة'], ['done', $vessel->done], ['type', 'normal']])->groupBy('car_no')->get()->count();
        $grar_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة جرار'], ['done', $vessel->done], ['type', 'normal']])->groupBy('car_no')->get()->count();

        $diff = $start_date->diff($now);
        $days = $diff->d;
        $hours = $diff->h;
        $minutes = $diff->m;

        $move_sum = DB::table('move')
            ->select(DB::raw('SUM(jumbo) as total_jumbo'), DB::raw('count(*) as moves_count'))
            ->where([['vessel_id', $id], ['arrival', 1]])
            ->first();
        $loading_sum = DB::table('loading')
            ->select(DB::raw('SUM(jumbo) as total_jumbo'), DB::raw('count(*) as moves_count'), DB::raw('SUM(qnt) as total_qnt'))
            ->where([['vessel_id', $id]])
             ->whereNotNull('qnt_date')
            ->first();

        $loadings = DB::table('loading')
            ->join('cars', 'cars.sn', 'loading.sn')
            ->select('loading.*', 'cars.car_no as car_no')
            ->where([['loading.vessel_id', $id],['cars.vessel_id', $id]])
            ->orderByDesc('loading.date')
            ->get();
        $total_moves = $loadings->count();
            $users = User::where('type', '1')->get();
            Notification::send($users, new SendNotifications(' تم الدخول على تقرير مطابقة الكميات مع الموازين '.$vessel->name , auth()->user()->id));
        return view('reports.quantity', [
            'vessel' => $vessel,
            'total_cars' => $total_cars,
            'active_cars' => $active_cars,
            'toktok_cars' => $toktok_cars,
            'qlab_cars' => $qlab_cars,
            'company_cars' => $company_cars,
            'grar_cars' => $grar_cars,
            'days' => $days,
            'hours' => $hours,
            'minutes' => $minutes,
            'move_sum' => $move_sum,
            'loading_sum' => $loading_sum,
            'loadings' => $loadings,
            'total_moves' => $total_moves,

        ]);
 }
          return redirect('/');
    }
    public function stops($id)
    {
        $vessel = Vessel::where('vessel_id', $id)->first();
        if($vessel ){
            $users = User::where('type', '1')->get();
            Notification::send($users, new SendNotifications(' تم الدخول على تقرير التوقفات '.$vessel->name, auth()->user()->id));
        return view('reports.stops', [
            'vessel' => $vessel,
        ]);
         }
        return redirect('/');
    }
    public function minus($id)
    {
        $vessel = Vessel::where('vessel_id', $id)->first();
        if($vessel ){
             $users = User::where('type', '1')->get();
            Notification::send($users, new SendNotifications(' تم الدخول على تقرير الخصومات على سيارات النقل '.$vessel->name, auth()->user()->id));
        return view('reports.minus', [
            'vessel' => $vessel,
        ]);
         }
        return redirect('/');
    }
    public function travels($id)
    {
        $vessel = Vessel::where('vessel_id', $id)->first();
        if($vessel ){
            $users = User::where('type', '1')->get();
            Notification::send($users, new SendNotifications(' تم الدخول على تقرير رحلات السيارات '.$vessel->name, auth()->user()->id));
        return view('reports.travels', [
            'vessel' => $vessel,
        ]);
         }
        return redirect('/');
    }
 //get  stats
    public function live()
    {
        $vessels = DB::table('vessels_log')
            ->select('*', 'qnt as quantity', 'phones as direct_moves', 'quay as arrival_moves', 'client as car_count', 'client as all_count', 'client as all_qnt', 'client as all_jumbo', 'qnt as quantity', 'archive as days', 'notes as hours', 'quay as minutes')
            ->where([['done', 0]])
            ->get();
        foreach ($vessels as $vessel) {
             $vessel->hours = 0;
            // $qnt_sum = DB::table('loading')
            // ->select(DB::raw('SUM(jumbo) as total_jumbo'), DB::raw('SUM(qnt) as total_qnt'),  DB::raw('count(*) as count'))
            // ->where([['vessel_id', $vessel->vessel_id]])
            // ->whereNotNull('qnt_date')
            // ->first();
            // if ($qnt_sum->total_jumbo == 0 ) $qnt_sum->total_jumbo='';
            // else $qnt_sum->total_jumbo= ' ('. $qnt_sum->total_jumbo.')';

            $direct_moves = DB::table('move')
            ->select( DB::raw('count(*) as moves_count'))
            ->where([['vessel_id', $vessel->vessel_id] ,[ 'arrival' , '1'],[ 'move_type' , 'direct']])
            ->where('is_delete', '=', 0)
            ->first();

            $arrival_moves = DB::table('move')
            ->select( DB::raw('count(*) as moves_count'))
            ->where([['vessel_id', $vessel->vessel_id] ,[ 'arrival' , '1'],[ 'move_type' , 'normal']])
            ->where('is_delete', '=', 0)
            ->first();
            $car_count = DB::table('cars')
            ->select('*')
            ->where([['vessel_id', $vessel->vessel_id],['done', 0]])
            ->count();

            // $vessel->quantity = '('.number_format((float)$qnt_sum->total_qnt, 3, '.', '').')';
            // $vessel->archive = '('. $qnt_sum->count.')';
            // // $vessel->phones = $qnt_sum->total_jumbo;
            $vessel->direct_moves = $direct_moves->moves_count;
            $vessel->arrival_moves = $arrival_moves->moves_count;
            $vessel->car_count = $car_count;


    $direct_sum = DB::table('direct')
            ->select(DB::raw('SUM(jumbo) as jumbo'), DB::raw('count(*) as count'), DB::raw('SUM(qnt) as qnt'))
            ->where([['vessel_id', $vessel->vessel_id]])
            ->where('is_delete', '=', 0)
            ->whereNotNull('qnt_date')
            ->first();
    
    $normal_sum = DB::table('move')
        ->select(DB::raw('SUM(jumbo) as jumbo'), DB::raw('count(*) as count'), DB::raw('SUM(qnt) as qnt'))
        ->where([['vessel_id', $vessel->vessel_id] ,[ 'arrival' , '1'],[ 'move_type' , 'normal']])
            ->where('is_delete', '=', 0)
        ->first();

    

 $all_count = $direct_sum->count +   $normal_sum->count; 
 $all_qnt = $direct_sum->qnt +   $normal_sum->qnt;
 $all_jumbo = $direct_sum->jumbo +   $normal_sum->jumbo;

 $vessel->all_count = $all_count;
 $vessel->all_qnt = number_format($all_qnt, 3, '.', ''); ;
 $vessel->all_jumbo = $all_jumbo;
 
 //times

        if ($vessel->done == 0) {
            $now = Carbon::now();
        } else {
            $now = $vessel->end_date;
        }
$arrival = DB::table('move')
            ->select('*')
            ->where([['vessel_id', $vessel->vessel_id],['arrival', 1]])
            ->where('is_delete', '=', 0)
            ->orderby('arrival_date', 'asc')
            ->get()->first();
            
 if(isset($arrival->arrival_date)){
        $normal_date =strtotime($arrival->arrival_date);

        if($vessel->done == 1){
            $arrivals2 =   DB::table('move')
                ->select('*')
                ->where([['vessel_id', $vessel->vessel_id],['arrival', 1]])
            ->where('is_delete', '=', 0)
                ->orderby('arrival_date', 'desc')
                ->first();

            $now = Carbon::parse($arrivals2->arrival_date);

        }else{
            $now = Carbon::now();
        }

        $normal_date0 = Carbon::parse($normal_date);
        $diff = $normal_date0->diff($now);

        $vessel->days = $diff->d *24;
       
        $vessel->hours = $diff->h + $vessel->days;
        // if($vessel->hours  ==0)  $vessel->hours = $diff->m;

        }

       


        }

        return view('reports.live2', [
            'vessels' => $vessels,
        ]);
    }
    public function carsAnalysis($id)
    {
        $cars = DB::table('cars')
            ->select('*', 'limits as moves_count', 'vacant as qnts',  'car_no as avg', 'car_owner as status')
            ->where([['cars.vessel_id', $id]])
            ->orderby('id','asc')
            ->groupBy('car_no')
            ->get();
        $vessel = Vessel::where([['vessel_id', $id]])->first();

        if($vessel ){
        $total_cars = Car::where([['vessel_id', $id], ['type', 'normal']])->groupBy('car_no')->get()->count();
        $active_cars = Car::where([['vessel_id', $id], ['done', $vessel->done], ['type', 'normal']])->groupBy('car_no')->get()->count();
        $toktok_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة توكتوك'], ['done', $vessel->done], ['type', 'normal']])->groupBy('car_no')->get()->count();
        $qlab_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة قلاب'], ['done', $vessel->done], ['type', 'normal']])->groupBy('car_no')->get()->count();
        $company_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة الشركة'], ['done', $vessel->done], ['type', 'normal']])->groupBy('car_no')->get()->count();
        $grar_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة جرار'], ['done', $vessel->done], ['type', 'normal']])->groupBy('car_no')->get()->count();
    
        $owners = Car::where('vessel_id', $id)->groupBy('car_owner')->get();
        $total_owners = $owners->count();
        foreach ($owners as $owner) {

            $count = DB::table('cars')
                ->select('*')
                ->where([['vessel_id', $id], ['car_owner', $owner->car_owner]])
                ->groupBy('car_no')
                ->get()->count();

                $owner->vacant =  $count;                 
        }

            foreach ($cars as $car) {
                  $car->moves_count = 0 ;
                   $car->qnts = 0 ;
                   $car->avg = 0;
                   $car->sn = '';
                    $cars_no = Car::where([['vessel_id', $id], ['car_no',$car->car_no]])->get();
                        $num = $cars_no->count() - 1;
                    for($i=0;$i < $cars_no->count(); $i++){
                            $car->sn .=  '  '.$cars_no[$i]->sn.'  ';
                        $moves = DB::table('move')
                        ->select(DB::raw('count(*) as moves_count'),DB::raw('sum(qnt) as qnts'))
                        ->where([['vessel_id', $id], ['sn', $cars_no[$i]->sn],  ['arrival', 1]])
                        ->get();

                        $car->moves_count +=  $moves[0]->moves_count;
                        $car->qnts +=  $moves[0]->qnts; 
                        if ($moves[0]->moves_count != 0) {
                            $car->avg +=   $moves[0]->qnts /  $moves[0]->moves_count; 
                        }else {
                            $car->avg = 0;
                        }
                        }

                        $car->qnts =  number_format($car->qnts, 3, '.', ''); 
                        $car->avg =   number_format($car->avg, 3, '.', ' '); 
                          $last = DB::table('cars')
            ->select('*', 'limits as moves_count', 'vacant as qnts',  'car_no as avg')
            ->where([['cars.vessel_id', $id],['car_no', $car->car_no]])
            ->orderby('id','desc')
            ->get()->first();
                    if($last->exit_date == null){
                        $car->exit_date = '----------';
                        $car->status = 'ما زالت على الباخرة';
                    }else {
                        $car->exit_date =$last->exit_date;
                        $car->status = 'خرجت';
                    }
    
                    }
                

            $all_cost = DB::table('cars')
                ->select(DB::raw('sum(all_cost) as total_cost'))
                ->where([['vessel_id', $id]])
                ->get()->first();
                 $users = User::where('type', '1')->get();
            Notification::send($users, new SendNotifications(' تم الدخول على تقرير سيارات النقل '.$vessel->name, auth()->user()->id));
        return view('reports.carsAnalysis', [
            'cars' => $cars,
            'vessel' => $vessel,
            'total_cars' => $total_cars,
            'active_cars' => $active_cars,
            'toktok_cars' => $toktok_cars,
            'qlab_cars' => $qlab_cars,
            'company_cars' => $company_cars,
            'grar_cars' => $grar_cars,
            'total_owners' => $total_owners,
            'total_cost' => $all_cost->total_cost,
            'owners' => $owners,

        ]);
        }
             return redirect('/');
    }
   public function carAnalysis($car_no,$id)
    {
        $car_table = Car::where('id', $id)->get()->first();
        $vessel = Vessel::where([['vessel_id', $car_table->vessel_id]])->first();
            $cars = DB::table('cars')
                ->select('*', 'limits as moves_count', 'vacant as qnts',  'car_no2 as avg', 'car_owner as status')
                ->where([['car_no', $car_no], ['vessel_id', $vessel->vessel_id]])
                ->orderby('id','asc')
                ->get();
             $num = $cars->count() - 1;
        if($cars ){
            $count =0;
            $qnt =0;
            $minus_sum =0;
            $arr=[];
        foreach ($cars as $car) {
          array_push( $arr, $car->sn);
        $qnts = DB::table('move')
                        ->select(DB::raw('SUM(qnt) as qnt'))
                        ->where([['vessel_id',  $car->vessel_id], ['sn', $car->sn],  ['arrival', 1]])
                        ->get()->first();
        $qnt += $qnts->qnt;
        
        $move = DB::table('move')
                        ->select('*')
                        ->where([['vessel_id',  $car->vessel_id], ['sn', $car->sn],  ['arrival', 1]])
                        ->get()->count();

        $count +=  $move;


        $minus = DB::table('minus')
                        ->select(DB::raw('SUM(TIME_TO_SEC(minus_duration)) as minus_sum'))
                        ->where([['vessel_id',  $car->vessel_id], ['sn', $car->sn]])
                        ->get()->first();

            $minus_sum  +=  $minus->minus_sum;
            $minus_minutes = $minus_sum / 60 % 60;
            $minus_hours =  $minus_sum / 3600 % 24;
            $minus_days =  $minus_sum / 86400 % 7 ;

            if( $car->exit_date == null)  $car->exit_date = '----------';

                }
                $loading = DB::table('loading')
                        ->select('date')
                        ->where([['vessel_id',   $vessel->vessel_id], ['sn', $cars[0]->sn]])
                        ->orderby('id','asc')
                        ->get()->first();

            $arrival = DB::table('arrival')
                        ->select('date')
                        ->where([['vessel_id',   $vessel->vessel_id], ['sn', $cars[$num]->sn]])
                        ->orderby('id','desc')
                        ->get()->first();

            $cars = DB::table('cars')
                        ->select('start_date')
                        ->where([['vessel_id',   $vessel->vessel_id], ['sn', $cars[0]->sn]])
                        ->orderby('id','desc')
                        ->get()->first();

            $start_date  = 0;
            $end_date  = 0 ;
            if(isset($loading->date)) $start_date =strtotime($loading->date);
            if(isset($arrival->date)) $end_date  =strtotime($arrival->date);
            $wait_date = $start_date - strtotime($cars->start_date)  ;
            $all_time = abs($end_date -$start_date) ;

            $all_hours =$all_time / 3600 ;
    
        $wait_hours = $wait_date / 3600 ;
        
        $moves = DB::table('move')
            ->select('*','hobar as load_employee','crane as arrival_employee' , 'crane as duration')
            ->where('vessel_id',   $vessel->vessel_id)
            ->whereIn('sn',$arr)
            ->orderby('load_date', 'asc')
            ->get();



            foreach ($moves as $move) {
                if($move->move_type == 'direct'){
                     $direct = DB::table('direct')
                        ->select('*')
                        ->where([['vessel_id',   $vessel->vessel_id], ['move_id', $move->move_id]])
                        ->get()->first(); 
             
                    if($direct) $move->load_employee =  $direct->ename;
                    if($direct) $move->arrival_employee =  '';
                    $move->duration = '';
               
                } 


        }
            $users = User::where('type', '1')->get();
            Notification::send($users, new SendNotifications(' تم الدخول على تقرير سيارة نقل '.$vessel->name, auth()->user()->id));
        return view('reports.carAnalysis', [
            'car' => $car,
            'count' => $count,
            'qnt' => $qnt,
            'minus_minutes' => $minus_minutes,
            'minus_hours' => $minus_hours,
            'minus_days' => $minus_days,
            'all_hours' => number_format((float)$all_hours, 3, '.', ''),
            'wait_hours' => number_format((float)$wait_hours, 3, '.', ''),
            'moves' => $moves,
            'vessel' => $vessel
        ]);
        }
            return redirect('/');
    }

      public function carOwners($id)
    {
        $owners = DB::table('cars')
            ->select('*')
            ->where([['vessel_id', $id],['cars.type', 'normal']])
            ->groupBy('car_owner')
            ->get();
        $vessel = Vessel::where([['vessel_id', $id]])->first();
        $total_owners = $owners->count();
        foreach ($owners as $owner) {

            $count = DB::table('cars')
                ->select('*','car_no2 as moves','car_driver as qnt','type as count')
                ->where([['vessel_id', $id], ['car_owner', $owner->car_owner],['cars.type', 'normal']])
                ->groupBy('car_no')
                ->get()->count();

                $owner->vacant =  $count;                 
        }
        foreach ($owners as $car_owner) {

            $car_owner_cars = DB::table('cars')
                ->select('*')
                ->where([['vessel_id', $id], ['car_owner', $car_owner->car_owner],['cars.type', 'normal']])
                ->get();

                $moves_count = 0;
                $qnts = 0;

                foreach ($car_owner_cars as $car) {
                    $moves = DB::table('move')
                        ->select(DB::raw('count(*) as moves_count'),DB::raw('sum(qnt) as qnts'))
                        ->where([['vessel_id', $id], ['sn', $car->sn],  ['arrival', 1]])
                        ->get();

                        $moves_count +=  $moves[0]->moves_count;
                        $qnts +=  $moves[0]->qnts;
                }
          
                $count = DB::table('cars')
                ->select('*')
                ->where([['vessel_id', $id], ['car_owner', $car_owner->car_owner],['cars.type', 'normal']])
                ->groupBy('car_no')
                ->get()->count();

                $car_owner->count =  $count;
                $car_owner->moves =  $moves_count;
                $car_owner->qnt = $qnts;
                
        }
        if($vessel ){
            $direct_cars = Car::where([['vessel_id', $id],['cars.type', 'direct']])->groupBy('car_no')->get()->count();
            $total_cars = Car::where([['vessel_id', $id],['cars.type', 'normal']])->groupBy('car_no')->get()->count();
            $active_cars = Car::where([['vessel_id', $id], ['done', $vessel->done],['cars.type', 'normal']])->groupBy('car_no')->get()->count();
            $toktok_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة توكتوك'],['cars.type', 'normal']])->groupBy('car_no')->get()->count();
            $qlab_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة قلاب'],['cars.type', 'normal']])->groupBy('car_no')->get()->count();
            $company_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة الشركة'] ,['cars.type', 'normal']])->groupBy('car_no')->get()->count();
            $grar_cars = Car::where([['vessel_id', $id], ['car_type', 'سيارة جرار'],['cars.type', 'normal']])->groupBy('car_no')->get()->count();
            $all_cost = DB::table('cars')
                ->select(DB::raw('sum(all_cost) as total_cost'))
                ->where([['vessel_id', $id],['cars.type', 'normal']])
                ->get()->first();
            $users = User::where('type', '1')->get();
            Notification::send($users, new SendNotifications(' تم الدخول على حساب تكاليف النقل   '.$vessel->name, auth()->user()->id));
            return view('reports.carOwners', [
                'owners' => $owners,
                'vessel' => $vessel,
                'direct_cars' => $direct_cars,
                'total_cars' => $total_cars,
                'active_cars' => $active_cars,
                'toktok_cars' => $toktok_cars,
                'qlab_cars' => $qlab_cars,
                'company_cars' => $company_cars,
                'grar_cars' => $grar_cars,
                'total_owners' => $total_owners,
                'total_cost' => $all_cost->total_cost,
                
            ]);
        }
        return redirect('/');
    }

    public function carOwner($car_owner, $vessel_id)
    {
        $vessel = Vessel::where([['vessel_id', $vessel_id]])->first();
        $all_cost = DB::table('cars')
        ->select(DB::raw('sum(all_cost) as total_cost'))
        ->where([['vessel_id', $vessel_id],['car_owner', $car_owner]])
        ->get()->first();
        $cars = DB::table('cars')
                ->select('*', 'limits as moves_count', 'vacant as qnts')
                ->where([['car_owner', $car_owner], ['vessel_id', $vessel->vessel_id]])
                ->groupby('car_no')
                ->get();
        $car_owner = DB::table('cars')
                ->select('*', 'limits as count', 'vacant as qnt')
                ->where([['car_owner', $car_owner], ['vessel_id', $vessel->vessel_id]])
                ->groupby('car_no')
                ->get()->first();
        $moves_count =0;
        $qnts =0;
        foreach ($cars as $car) {
            $cars_no = Car::where([['vessel_id', $vessel_id], ['car_no',$car->car_no]])->get();
            $car->sn = '';
            $car->moves_count = 0 ;
            $car->qnts = 0 ;
            for($i=0;$i < $cars_no->count(); $i++){
                
                        $car->sn .=  '  '.$cars_no[$i]->sn.'  ';
                        $moves = DB::table('move')
                        ->select(DB::raw('count(*) as moves_count'),DB::raw('sum(qnt) as qnts'))
                        ->where([['vessel_id', $vessel_id], ['sn', $cars_no[$i]->sn],  ['arrival', 1]])
                        ->get();
                       
                        $car->moves_count +=  $moves[0]->moves_count; 
                        $car->qnts +=  $moves[0]->qnts; 
                        $moves_count += $moves[0]->moves_count; 
                        $qnts += $moves[0]->qnts; 
                    
            }
        }    
        $car_owner->count = $moves_count ;
        $car_owner->qnt =  $qnts ;
        $num = $cars->count() - 1;
        if($cars ){
       
        $users = User::where('type', '1')->get();
        Notification::send($users, new SendNotifications(' تم الدخول على تقرير مقاول  نقل '.$vessel->name, auth()->user()->id));
        return view('reports.carOwner', [
            'cars' => $cars,
            'car_owner' => $car_owner,
            'vessel' => $vessel,
            'total_cost' => $all_cost->total_cost
        ]);
        }
            return redirect('/');
    }
}   