<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\TimeEventApproval;

class TimeEventApprovalCreatedMessage extends Notification  implements ShouldQueue
{
    use Queueable;
    protected $timeEventApproval, $timeEvent;
    public $tries = 5;
    
    public function __construct(TimeEventApproval $timeEventApproval)
    {
        $this->timeEventApproval = $timeEventApproval;
        $this->timeEvent = $timeEventApproval->timeEvent;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $timeEventApprovalText = config('emss.modules.time_events.text');
        
        // judul email
        $subject = sprintf('Pengajuan %s oleh %s', 
            $timeEventApprovalText,
            $this->timeEvent->employee->personnelNoWithName
        );

        // kalimat pembuka
        $greeting = sprintf('Telah dibuat pengajuan %s dengan rincian:',
            $timeEventApprovalText);

        // mengirim email
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line('Karyawan: ' . $this->timeEvent->employee->personnelNoWithName)
                    ->line('Jenis: ' . $this->timeEvent->timeEventType->description)
                    ->line('Tanggal: ' . $this->timeEvent->formatted_check_date)
                    ->line('Jam: ' . $this->timeEvent->check_time)
                    ->action('Lihat pengajuan', route('time_events.index'));
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
