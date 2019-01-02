<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\AbsenceApproval;

class AbsenceApprovalCreatedMessage extends Notification implements ShouldQueue
{
    use Queueable;
    protected $absenceApproval, $absence;

    public function __construct(AbsenceApproval $absenceApproval)
    {
        $this->absenceApproval = $absenceApproval;
        $this->absence = $absenceApproval->absence;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $absenceApprovalText = ($this->absence->is_a_leave) ? 
            config('emss.modules.leaves.text') :
            config('emss.modules.permits.text');

        $url = ($this->absence->is_a_leave) ? 
            route('leaves.index') :
            route('permits.index');
        
        // judul email
        $subject = sprintf('Pengajuan %s oleh %s', 
            $absenceApprovalText,
            $this->absence->employee->personnelNoWithName
        );

        // kalimat pembuka
        $greeting = sprintf('Telah dibuat pengajuan %s dengan rincian:',
            $absenceApprovalText);

        // mengirim email
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line('Karyawan: ' . $this->absence->employee->personnelNoWithName)
                    ->line('Jenis: ' . $this->absence->absenceType->text)
                    ->line('Mulai: ' . $this->absence->formatted_start_date)
                    ->line('Berakhir: ' . $this->absence->formatted_end_date)
                    ->line('Durasi: ' . $this->absence->duration . ' hari')
                    ->action('Lihat pengajuan', $url);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
