<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use App\Models\AttendanceQuotaApproval;

class OvertimeApprovalMessage extends Notification implements ShouldQueue
{
    use Queueable;
    public $approved;
    public $fromUser;
    public $attendanceApproval;

    public function __construct(User $user, AttendanceQuotaApproval $attendanceApproval)
    {
        $this->fromUser = $user;
        $this->approved = $attendanceApproval->isApproved;
        // lazy eager loading
        $this->attendanceApproval = $attendanceApproval->load('attendanceQuota.stage');
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // judul email
        $subjectText = ($this->approved) ? 'Pengajuan lembur Anda telah disetujui' :
            'Pengajuan lembur Anda telah ditolak';
        $subject = sprintf('%s: %s oleh %s!',
            config('emss.modules.overtimes.text'), $subjectText, $this->fromUser->name );
        
        // kalimat pembuka
        $greetingText = ($this->approved) ? 'Selamat' : 'Mohon maaf';
        $greeting = sprintf('%s %s!', $greetingText, $notifiable->name);

        // catatan persetujuan
        $approvalNotes = sprintf('Catatan: %s', 
            $this->attendanceApproval->text);

        // catatan tahapan persetujuan
        $currentStageText = sprintf('Tahapan pengajuan: %s',
            $this->attendanceApproval->attendanceQuota->stage->description);

        // link untuk melihat lembur
        $url = route('overtimes.index');
        
        // mengirim email
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line($subjectText)
                    ->line($approvalNotes)
                    ->line($currentStageText)
                    ->action('Lihat Lembur', $url);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
