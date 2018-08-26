<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use App\Models\Absence;

class LeaveSentToSapMessage extends Notification implements ShouldQueue
{
    use Queueable;
    public $absence;

    public function __construct(Absence $absence)
    {
        $this->absence = $absence;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // judul email
        $subject = sprintf('%s: Pengajuan cuti Anda telah berhasil masuk ke SAP',
            config('emss.modules.leaves.text') );
            
        // kalimat pembuka
        $greeting = sprintf('Selamat %s!', $notifiable->name);

        // catatan persetujuan
        $sendToSapTimestamp = sprintf('Masuk SAP pada : %s', 
            $this->absence->updated_at);

        // link untuk melihat cuti
        $url = route('leaves.index');
        
        // mengirim email
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line('Pengajuan cuti Anda telah berhasil masuk ke SAP.')
                    ->line($sendToSapTimestamp)
                    ->action('Lihat Cuti', $url);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
