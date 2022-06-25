<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendNotifications extends Notification
{
    use Queueable;

    public $message;
    public $user_id;
    public function __construct($message, $user_id)
    {
        $this->message = $message ;
        $this->user_id = $user_id ;
    }

    public function via()
    {
        return ['database'];
    }

public function toDatabase($notifiable)
{
    return [
        'message' => $this->message,
        'user_id' => $this->user_id
    ];
}
}