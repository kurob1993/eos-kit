<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Absence;

class AbsenceDeletedMessage extends Notification implements ShouldQueue
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
        $subjectText = 'Pengajuan Absence Anda telah dihapus oleh Admin';
        $subject = sprintf('%s: %s',
            config('emss.modules.absences.text'), $subjectText );
        
        // kalimat pembuka
        $greetingText = 'Informasi';
        $greeting = sprintf('%s %s!', $greetingText, $notifiable->name);

        // mengirim email
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line($this->absence->start_date)
                    ->line($this->absence->end_date);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
