<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendingToSapMail extends Mailable
{
    use Queueable, SerializesModels;

    public $demo;
 
    public function __construct($demo)
    {
        $this->demo = $demo;
    }

    public function build()
    {
        return $this->from('sender@example.com')
                    ->view('emails.demo')
                    ->text('emails.demo_plain')
                    ->with(
                      [
                            'testVarOne' => '1',
                            'testVarTwo' => '2',
                      ])
                      ->attach(public_path('/images').'/11725.jpg', [
                              'as' => '11725.jpg',
                              'mime' => 'image/jpeg',
                      ]);
    }
}
