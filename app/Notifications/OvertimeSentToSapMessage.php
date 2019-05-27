<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use App\Models\AttendanceQuota;

class OvertimeSentToSapMessage extends Notification implements ShouldQueue
{
    use Queueable;
    public $overtime;
    public $tries = 5;
    
    public function __construct(AttendanceQuota $overtime)
    {
        $this->overtime = $overtime;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // judul email
        $subject = sprintf('%s: Pengajuan lembur Anda telah berhasil masuk ke SAP',
            config('emss.modules.overtimes.text') );
            
        // kalimat pembuka
        $greeting = sprintf('Selamat %s!', $notifiable->name);

        // catatan persetujuan
        $sendToSapTimestamp = sprintf('Masuk SAP pada : %s', 
            $this->overtime->updated_at);

        // link untuk melihat lembur
        $url = route('overtimes.index');
        
        // mengirim email
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line('Pengajuan lembur Anda telah berhasil masuk ke SAP.')
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
