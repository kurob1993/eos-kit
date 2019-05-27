<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use App\Models\TimeEventApproval;

class TimeEventApprovalMessage extends Notification implements ShouldQueue
{
    use Queueable;
    public $approved;
    public $fromUser;
    public $timeEventApproval;
    public $tries = 5;
    
    public function __construct(User $user, TimeEventApproval $timeEventApproval)
    {
        $this->fromUser = $user;
        $this->approved = $timeEventApproval->isApproved;
        // lazy eager loading
        $this->timeEventApproval = $timeEventApproval->load('timeEvent.stage');
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // judul email
        $subjectText = ($this->approved) ? 'Pengajuan tidak slash Anda telah disetujui' :
            'Pengajuan tidak slash Anda telah ditolak';
        $subject = sprintf('%s: %s oleh %s!',
            config('emss.modules.time_events.text'), $subjectText, $this->fromUser->name );
        
        // kalimat pembuka
        $greetingText = ($this->approved) ? 'Selamat' : 'Mohon maaf';
        $greeting = sprintf('%s %s!', $greetingText, $notifiable->name);

        // catatan persetujuan
        $approvalNotes = sprintf('Catatan: %s', 
            $this->timeEventApproval->text);

        // catatan tahapan persetujuan
        $currentStageText = sprintf('Tahapan pengajuan: %s',
            $this->timeEventApproval->timeEvent->stage->description);

        // link untuk melihat tidak slash
        $url = route('time_events.index');
        
        // mengirim email
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line($subjectText)
                    ->line($approvalNotes)
                    ->line($currentStageText)
                    ->action('Lihat Tidak Slash', $url);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
