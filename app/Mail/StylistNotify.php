<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StylistNotify extends Mailable
{
    use Queueable, SerializesModels;

    protected $uuid = null;
    protected $status = null;
    protected $messageSubject = null;
    protected $type = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($uuid, $status, $messageSubject = 'Stylist Notify', $type = null)
    {
        $this->uuid = $uuid;
        $this->status = $status;
        $this->messageSubject = $messageSubject;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        switch ($this->status) {
            case 4:
                return $this->view('mail.admin.lead-stylist',[
                    'uuid' => $this->uuid
                ]);
            case 5:
                return $this->subject($this->messageSubject)->view($this->type == 'methodist' ? 'mail.admin.lead-methodist-trouble' : 'mail.admin.lead-stylist-trouble',[
                    'uuid' => $this->uuid
                ]);
        }
    }

}
