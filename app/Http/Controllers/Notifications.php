<?php

namespace App\Http\Controllers;

use App\Notifications\AblyNotifications;
use App\Notifications\EmailNotifications;
use App\Notifications\SendNotifications;
use Illuminate\Http\Request;

class Notifications extends Controller
{
    public function send($message){
        
        $preference = explode(', ',auth()->user()->notification_preference);

        if(in_array('database', $preference)) auth()->user()->notify(new SendNotifications($message));
        if(in_array('mail', $preference)) auth()->user()->notify(new EmailNotifications($message));

        AblyNotifications::notify($message);
    }
}