<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VendorBulkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $messageBody;

    public function __construct($subject, $messageBody)
    {
        $this->subject = $subject;
        $this->messageBody = $messageBody;
    }

    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.bulk')
                    ->with(['content' => $this->messageBody]);
    }
}