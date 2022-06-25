<?php

namespace App\Notifications;

use Ably\Laravel\AblyService;

class AblyNotifications
{

    public static function notify($msg)
    {

        $ably = new AblyService;

        $ably->channel('notify')->publish('greeting', $msg, 'testClientId');

    }

}