<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use App\Models\TimeEvent;

class TimeEventSentToSapMessage extends Notification implements ShouldQueue
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
        $subject = sprintf('%s: Pengajuan tidak slash Anda telah berhasil masuk ke SAP',
            config('emss.modules.time_events.text') );
            
        // kalimat pembuka
        $greeting = sprintf('Selamat %s!', $notifiable->name);

        // catatan persetujuan
        $sendToSapTimestamp = sprintf('Masuk SAP pada : %s', 
            $this->timeEvent->updated_at);

        // link untuk melihat tidak slash
        $url = route('time_events.index');
        
        // mengirim email
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line('Pengajuan tidak slash Anda telah berhasil masuk ke SAP.')
                    ->line($sendToSapTimestamp)
                    ->action('Lihat Tidak Slash', $url);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
