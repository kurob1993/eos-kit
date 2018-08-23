<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use App\Models\AbsenceApproval;

class LeaveApprovalMessage extends Notification
{
    use Queueable;
    public $approved;
    public $fromUser;
    public $absenceApproval;

    public function __construct($approved = false, User $user, AbsenceApproval $absenceApproval)
    {
        $this->approved = $approved;
        $this->fromUser = $user;
        $this->absenceApproval = $absenceApproval;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // judul email
        $subjectText = ($this->approved) ? 'Pengajuan cuti Anda telah disetujui' :
            'Pengajuan cuti Anda telah ditolak';
        $subject = sprintf('%s: %s oleh %s!',
            config('emss.modules.leaves.text'), $subjectText, $this->fromUser->name );
        
        // kalimat pembuka
        $greetingText = ($this->approved) ? 'Selamat' : 'Mohon maaf';
        $greeting = sprintf('%s %s!', $greetingText, $notifiable->name);

        // catatan persetujuan
        $approvalNotes = sprintf('Catatan: %s', 
            $this->absenceApproval->text);

        // catatan tahapan persetujuan
        $currentStage = $this->absenceApproval->with('absence.stage')->first(); 
        $currentStageText = sprintf('Tahapan pengajuan: %s',
            $currentStage->absence->stage->description);

        // link untuk melihat cuti
        $url = route('leaves.index');
        
        // mengirim email
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line($subjectText)
                    ->line($approvalNotes)
                    ->line($currentStageText)
                    ->action('Lihat Cuti', $url);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
