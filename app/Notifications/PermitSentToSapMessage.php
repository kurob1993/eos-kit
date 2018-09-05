<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use App\Models\Attendance;

class PermitSentToSapMessage extends Notification implements ShouldQueue
{
    use Queueable;
    public $attendance;

    public function __construct(Attendance $attendance)
    {
        $this->attendance = $attendance;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // judul email
        $subject = sprintf('%s: Pengajuan izin Anda telah berhasil masuk ke SAP',
            config('emss.modules.permits.text') );
            
        // kalimat pembuka
        $greeting = sprintf('Selamat %s!', $notifiable->name);

        // catatan persetujuan
        $sendToSapTimestamp = sprintf('Masuk SAP pada : %s', 
            $this->attendance->updated_at);

        // link untuk melihat izin
        $url = route('permits.index');
        
        // mengirim email
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line('Pengajuan izin Anda telah berhasil masuk ke SAP.')
                    ->line($sendToSapTimestamp)
                    ->action('Lihat Izin', $url);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
