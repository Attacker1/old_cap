<?php

namespace App\Jobs;

use App\Mail\StylistNotify;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email = null;
    protected $uuid = null;
    protected $status = null;
    protected $messageSubject = null;
    protected $type = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $uuid, $status, $messageSubject = 'Stylist Notify', $type = null)
    {
        $this->email = $email;
        $this->uuid = $uuid;
        $this->status = $status;
        $this->messageSubject = $messageSubject;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        @Mail::to($this->email)->send(new StylistNotify($this->uuid, $this->status, $this->messageSubject, $this->type));
    }
}
