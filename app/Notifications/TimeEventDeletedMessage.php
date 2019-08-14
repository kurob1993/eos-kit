<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\TimeEvent;

class TimeEventDeletedMessage extends Notification implements ShouldQueue
{
    use Queueable;
    public $timeEvent;
    public $tries = 5;
    
    public function __construct(TimeEvent $timeEvent)
    {
        $this->timeEvent = $timeEvent;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // judul email
        $subjectText = 'Pengajuan TimeEvent Anda telah dihapus oleh Admin';
        $subject = sprintf('%s: %s',
            config('emss.modules.timeEvents.text'), $subjectText );
        
        // kalimat pembuka
        $greetingText = 'Informasi';
        $greeting = sprintf('%s %s!', $greetingText, $notifiable->name);

        // mengirim email
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line($this->timeEvent->check_date)
                    ->line($this->timeEvent->check_time)
                    ->line($this->timeEvent->timeEventType->description);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
