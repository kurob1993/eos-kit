<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use App\Models\TravelApproval;

class TravelApprovalMessage extends Notification implements ShouldQueue
{
    use Queueable;
    public $approved;
    public $fromUser;
    public $travelApproval;
    public $tries = 5;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, TravelApproval $travelApproval)
    {
        $this->fromUser = $user;
        $this->approved = $travelApproval->isApproved;
        // lazy eager loading
        $this->travelApproval = $travelApproval->load('travel.stage');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $idTravel = $this->travelApproval->travel->id;
        // judul email
        $subjectText = ($this->approved) ? 'Pengajuan SPD ('.$idTravel.') Anda telah disetujui' :
            'Pengajuan SPD Anda telah ditolak';
        $subject = sprintf('%s: %s oleh %s!',
            'SPD', $subjectText, $this->fromUser->name );
        
        // kalimat pembuka
        $greetingText = ($this->approved) ? 'Selamat' : 'Mohon maaf';
        $greeting = sprintf('%s %s!', $greetingText, $notifiable->name);

        // catatan persetujuan
        $approvalNotes = sprintf('Catatan: %s', 
            $this->travelApproval->text);

        // catatan tahapan persetujuan
        $currentStageText = sprintf('Tahapan pengajuan: %s',
            $this->travelApproval->travel->stage->description);

        // link untuk melihat lembur
        $url = route('travels.index');

        // mengirim email
        return (new MailMessage)
                    ->subject($subject)
                    ->greeting($greeting)
                    ->line($subjectText)
                    ->line($approvalNotes)
                    ->line($currentStageText)
                    ->action('Lihat SPD', $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
