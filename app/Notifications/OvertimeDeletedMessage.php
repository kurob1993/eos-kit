<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\AttendanceQuota;

class OvertimeDeletedMessage extends Notification implements ShouldQueue
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
        $subjectText = 'Pengajuan lembur Anda telah dihapus oleh Admin';
        $subject = sprintf('%s: %s',
            config('emss.modules.overtimes.text'), $subjectText );
        
        // kalimat pembuka
        $greetingText = 'Informasi';
        $greeting = sprintf('%s %s!', $greetingText, $notifiable->name);

        // mengirim email
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line($this->overtime->start_date . ' ' . $this->overtime->from)
                    ->line($this->overtime->end_date . ' ' . $this->overtime->to)
                    ->line($this->overtime->overtimeReason->text);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
