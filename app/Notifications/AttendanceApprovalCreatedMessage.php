<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\AttendanceApproval;

class AttendanceApprovalCreatedMessage extends Notification implements ShouldQueue
{
    use Queueable;
    protected $attendanceApproval, $attendance;

    public function __construct(AttendanceApproval $attendanceApproval)
    {
        $this->attendanceApproval = $attendanceApproval;
        $this->attendance = $attendanceApproval->attendance;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $attendanceApprovalText = config('emss.modules.permits.text');
        
        // judul email
        $subject = sprintf('Pengajuan %s oleh %s', 
            $attendanceApprovalText,
            $this->attendance->employee->personnelNoWithName
        );

        // kalimat pembuka
        $greeting = sprintf('Telah dibuat pengajuan %s dengan rincian:',
            $attendanceApprovalText);

        // mengirim email
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line('Karyawan: ' . $this->attendance->employee->personnelNoWithName)
                    ->line('Jenis: ' . $this->attendance->attendanceType->text)
                    ->line('Mulai: ' . $this->attendance->formatted_start_date)
                    ->line('Berakhir: ' . $this->attendance->formatted_end_date)
                    ->line('Durasi: ' . $this->attendance->duration . ' hari')
                    ->action('Lihat pengajuan', route('permits.index'));
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
