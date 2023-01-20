<?php

namespace App\Mail;

//use Illuminate\Bus\Queueable;
//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class Contact extends Mailable
{
//    use Queueable, SerializesModels;

    protected $messageFromUser;
    protected $subjectFromUser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sub, $msg)
    {
        $this->subjectFromUser = $sub;
        $this->messageFromUser = $msg;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address(env('FCMS_CONTACT')),
            subject: $this->subjectFromUser,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.contact',
            text: 'emails.contact-text',
            with: [
                'messageFromUser' => $this->messageFromUser,
            ],
        );
    }

//    /**
//     * Build the message.
//     *
//     * @return $this
//     */
//    public function build()
//    {
//        return $this->view('view.name');
//    }
}
