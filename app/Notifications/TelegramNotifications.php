<?php

namespace App\Notifications;


class TelegramNotifications
{
      public function send($message, $receiver)
    {
        $apiToken = '5573963807:AAFLiZ39CvpwXvRDYq06GcWAzSaDPyj1UsA';
        
        if($receiver == 'unloading') $chat_id =   "-1001799028920";
        if($receiver == 'ceo') $chat_id =   "-1001530156095";

       

       $data = [
	            "chat_id" => $chat_id,
                'text' => $message, 
                'parse_mode' => 'markdown'
       ];


       file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data) );
    }
}
