<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VoterApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Determine channels based on user preferences or other logic
        $channels = ['database'];

        if (config('mail.enabled')) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Voter Registration Approved - '.config('app.name'))
                    ->greeting('Congratulations '.$notifiable->name.'!')
                    ->line('We are pleased to inform you that your voter registration has been successfully approved.')
                    ->line('You can now:')
                    ->line('✓ Access all voter features')
                    ->line('✓ Participate in upcoming elections')
                    ->line('✓ Update your voter profile')
                    ->action('Go to Your Dashboard', route('voter.dashboard'))
                    ->line('If you have any questions, please contact our support team.')
                    ->salutation('Regards, '.PHP_EOL.config('app.name').' Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Registration Approved',
            'message' => 'Your voter registration has been approved by the administration.',
            'action' => 'Access Dashboard',
            'link' => route('voter.dashboard'),
            'icon' => 'check-circle', // For font-awesome or other icon sets
            'type' => 'voter_approval',
            'timestamp' => now()->toDateTimeString(),
            'data' => [
                'approval_date' => now()->format('Y-m-d H:i:s'),
                'reference_id' => 'VOT-'.strtoupper(substr(md5($notifiable->id.now()), 0, 8))
            ]
        ];
    }

    /**
     * Determine which queues should be used for each notification channel.
     */
    public function viaQueues(): array
    {
        return [
            'mail' => 'mail-queue',
            'database' => 'database-queue',
        ];
    }
}
