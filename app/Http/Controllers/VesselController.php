<?php

namespace App\Http\Controllers;

use App\Events\addVessel;
use App\Jobs\sendMails;
use App\Models\Vessel;
use App\Models\VesselsType;
use App\Notifications\TelegramNotifications;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Notifications\SendNotifications;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class VesselController extends Controller
{

    public function __construct()
    {
        if(Route::current()){
              $id = Route::current()->parameter('vessel');
        $this->middleware("auth");
        $this->middleware("canView:$id", [
        'only' => [
            'show' 
            ]
    ]);
        }
      
    }
    //get  vessels
    public function index()
    {
        $types = VesselsType::all();

        return response([
            'types' => $types,
        ], 201);
    }

    //store new Vessel
    public function store(Request $request, WebNotificationController $firebase, TelegramNotifications  $telegram)
    {
        $result = Vessel::latest('id')->first();

        if (isset($result->vessel_id)) {
            $vessel_id = $result->vessel_id + 1;
        } else {
            $vessel_id = 1001;
        }
        $randomString = Str::random(4);
            $random = Vessel::where('token', '=',$randomString)->first();
            while ($random != null) {
                $randomString = Str::random(4);
                $random = Vessel::where('token', '=',$randomString)->first();
            }
        $fields = $request->validate([
            'name' => 'required',
            'type' => 'required',
            'quantity' => 'required',
            'client' => 'required',
            'quay' => 'required',
            'shipping_agency' => 'required',
        ]);
        if (!$request->notes) {
            $request->notes = '____';
        }
        try {
            $vessel = Vessel::create([
                'vessel_id' => $vessel_id,
                'name' => $fields['name'],
                'qnt' => $fields['quantity'],
                'type' => $fields['type'],
                'client' => $fields['client'],
                'quay' => $fields['quay'],
                'shipping_agency' => $fields['shipping_agency'],
                'notes' => $request->notes,
                'token' => $randomString,
                'start_date' => date("Y-m-d H:i:s"),
            ]);
            event(new addVessel('vessel added'));
            
                $users = User::where('type', '1')->get();
            Notification::send($users, new SendNotifications('  تم إضافة باخرة  '.$fields['name'], auth()->user()->id));
            $message='  تم إضافة  باخرة جديدة لمنظومة التفريغ ';
            $message.= "\r\n";
            $message.= '*اسم الباخرة*: *'.$fields['name'].'*';
            $message.= "\r\n";
            $message.= ' العميل : '.$fields['client']; 
            $message.= "\r\n";
            $message.= ' الكمية : '.$fields['quantity'];
            $message.= "\r\n";
            $message.= ' الصنف : '.$fields['type'];
            $telegram->send($message, 'ceo');
            $telegram->send($message, 'unloading');
            $firebase->sendWebNotification('تم إضافة الباخرة بنجاح');
        } catch (QueryException $e) {
            return response($e, 401);
        }

        $response = [
            'vessel' => $vessel,
            'message' => 'vessel created',
        ];

        return response($response, 201);
    }
    //end vessel
    public function show($id)
    {
        $vessel = Vessel::where('vessel_id', $id)->first();
          $users = User::where('type', '1')->get();
            Notification::send($users, new SendNotifications('تم الدخول على تقارير باخرة '.$vessel->name, auth()->user()->id));
        if($vessel ){
            return view('reports.index', [
            "vessel" => $vessel,
            ]);
        }
        return redirect('/');
    }

    //end vessel
    public function end($id, WebNotificationController $firebase)
    {
    
        $vessel = Vessel::where('id', $id)->first();
            $move = DB::table('move')
                        ->select('*')
                        ->where([['vessel_id', $vessel->vessel_id],['arrival', 1]])
                        ->orderby('arrival_date','desc')
                        ->first();
            $exit_date = $move->arrival_date;
        $end = Vessel::where('id', $id)->update(['done' => 1, 'end_date' => $exit_date]);
        if ($end) {
            event(new addVessel('vessel ended'));
            $users = User::where('type', '1')->get();
            Notification::send($users, new SendNotifications('  تم إنهاء باخرة '.$vessel->name, auth()->user()->id));
            //$firebase->sendWebNotification('تم إنهاء '+$name->name+' بنجاح');
            return response('success', 201);
        }
        return response($end, 422);
    }

    public function edit($id)
    {
        // get the vessel
        $vessel = Vessel::find($id);
        $types = VesselsType::all();
        $users = User::where('type', '1')->get();
        Notification::send($users, new SendNotifications(' تم الدخول على تعديل باخرة  '.$vessel->name, auth()->user()->id));
        return view('vessels.edit', [
            "vessel" => $vessel,
            'types' => $types,
        ]);
    }

    public function destroy($id, WebNotificationController $firebase)
    {
        $vessel = Vessel::find($id);
        if ($vessel->delete()) {
            //$firebase->sendWebNotification('تم حذف '+$vessel->name+' بنجاح');
            $users = User::where('type', '1')->get();
            Notification::send($users, new SendNotifications(' تم حذف باخرة  '.$vessel->name, auth()->user()->id));
            event(new addVessel('vessel deleted'));
            return response('success', 201);
        }
        return response('error', 422);
    }
    public function update(Request $request, $id)
    {
        $vessel = Vessel::where('id', $id)->first();
        if (!$vessel->update($request->all())) {
            return response($vessel, 422);
        }
        $users = User::where('type', '1')->get();
        Notification::send($users, new SendNotifications(' تم تعديل باخرة  '.$vessel->name, auth()->user()->id));
        event(new addVessel('vessel updated'));
        return response($vessel, 201);
    }
}