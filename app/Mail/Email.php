<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Email extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'howcalm@sarapis.org';
        $subject = 'This is a demo!';
        $name = 'Devin Balkind';
        if ($this->data['attachment'] != '') {
            return $this->view('backEnd.emails.test')
                ->from($address, $name)
                ->replyTo($address, $name)
                ->subject($this->data['subject'])
                ->attach($this->data['attachment'])
                ->with(['message' => $this->data['message'],
                ]);
        } else {
            return $this->view('backEnd.emails.test')
                ->from($address, $name)
                ->replyTo($address, $name)
                ->subject($this->data['subject'])
                ->with(['message' => $this->data['message'],
                ]);

        }

    }
}
