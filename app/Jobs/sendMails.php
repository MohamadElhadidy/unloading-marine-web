<?php

namespace App\Jobs;

use App\Mail\sendEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class sendMails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $message)
    {
        $this->data = $data;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->data as $data) {
            Mail::to($data->email)->send(new sendEmail($this->message));
        }
    }
}