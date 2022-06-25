<?php

namespace App\Http\Controllers;

use App\Models\Firebase;
use App\Models\User;
use Illuminate\Http\Request;

class WebNotificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function storeToken(Request $request)
    {
        $firebase = Firebase::create([
            'user_id' => auth()->user()->id,
            'name' => auth()->user()->name,
            'token' => $request->token,
        ]);
        if($firebase) return response()->json(['Token successfully stored.']);
        else return response('error', 422);

        
    }

    public function sendWebNotification($message)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = Firebase::where('user_id',auth()->user()->id)->whereNotNull('token')->pluck('token')->all();

        $serverKey = 'AAAA6O_X6RA:APA91bE3gi63HTKEIzujymFI75D--jm99qZXCqoAmVLSRelc793uT4heKwiFA7TlZmgm4qrfDDC3oOhE4x68z9qKPKwiJeDUBmNlwZIazlm5iDakqtK4hIA3dPUxtlUG3ZpsWhPoA_46';

        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => 'منظومة التشغيل',
                "body" => $message,
                "icon" => "https://marine-co.online/ops/shipping/public/style/index_style/images/favicon.png",
            ],
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        // Execute post
        $result = curl_exec($ch);

        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        // FCM response
    }
}