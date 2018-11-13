<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Attendance;

class AttendanceDeletedMessage extends Notification implements ShouldQueue
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
        $subjectText = 'Pengajuan Attendance Anda telah dihapus oleh Admin';
        $subject = sprintf('%s: %s',
            config('emss.modules.attendances.text'), $subjectText );
        
        // kalimat pembuka
        $greetingText = 'Informasi';
        $greeting = sprintf('%s %s!', $greetingText, $notifiable->name);

        // mengirim email
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line($this->attendance->start_date)
                    ->line($this->attendance->end_date);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
