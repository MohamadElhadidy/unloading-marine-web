<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\SendNotifications;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    public function roles(Request $request)
    {
        if($request->role_id == ''){
            $fields = $request->validate([
            'user_id' => 'required',
            'report_or_vessel' => 'required',
            ]);
            
        $role = Role::create([
            'user_id' => $fields['user_id'],
            'report_or_vessel' => $fields['report_or_vessel']
        ]);
        if($role)  return response($role, 201);
        return response($role, 422);
        }elseif($request->role_id != ''){

        $data = $request->only(["user_id", "report_or_vessel"]);
        if (!Role::find($request->role_id)->update($data)) {
            return response($data, 422);
        }
        return response($data, 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    public function notifications()
    {
        $notifications = auth()->user()->notifications ;
    
        foreach ($notifications as $notification ) {
             if(gettype($notification->data['user_id']) == "integer"){
                $user = User::where('id', $notification->data['user_id'])->get()->first();
                $notification->notifiable_id =  $user->name;
             }else {
                 $notification->notifiable_id = $notification->data['user_id'];
             }
               
           
        }
        return view('auth.notifications',[
            'notifications' =>$notifications
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
           // get the vessel
        $user = User::find($id);
        $types = Auth::all();
        $roles = Auth::all();
        $roles = DB::table('roles') ->select('*')->where([['user_id', $user->id]])->first();
        $reports = DB::table('reports') ->select('*')->get();
        return view('auth.edit', [
            "user" => $user,
            'types' => $types,
            'roles' =>$roles,
            'reports' =>$reports
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
        public function update(Request $request, $id)
        {
        $userData = $request->only(["name", "username", "type", "email","hint"]);
        if(isset($request->password)){
            $userData['password'] = Hash::make($request->password);
        }

        if (!User::find($id)->update($userData)) {
            return response($userData, 422);
        }
        return response($userData, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
          $user = User::find($id);
        if ($user->delete()) {
            return response('success', 201);
        }
        return response('error', 422);
    }
}