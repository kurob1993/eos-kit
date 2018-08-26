<?php

namespace App\Listeners;

use App\Events\LeaveSentToSap;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendLeaveToSap implements ShouldQueue
{
    public $queue = 'listeners';

    public function __construct()
    {
        //
    }

    public function handle(LeaveSentToSap $event)
    {
        $objDemo = new \stdClass();
        $objDemo->demo_one = 'Demo One Value';
        $objDemo->demo_two = 'Demo Two Value';
        $objDemo->sender = 'SenderUserName';
        $objDemo->receiver = 'ReceiverUserName';
 
        Mail::to("receiver@example.com")->send(new SendingToSapMail($objDemo));
    }

    public function failed(LeaveSentToSap $event, $exception)
    {
        //
    }    
}
