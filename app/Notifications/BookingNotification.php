<?php

namespace App\Notifications;

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;

class BookingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    // Determine the notification delivery channels
    public function via($notifiable)
    {
        return ['mail', 'nexmo', 'database'];
    }

    // Define email notification
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->details['subject'])
                    ->line($this->details['body'])
                    ->action($this->details['actionText'], $this->details['actionURL']);
    }

    // Define SMS notification
    public function toNexmo($notifiable)
    {
        return (new NexmoMessage)
                    ->content($this->details['sms']);
    }

    // Define in-app notification
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->details['body'],
            'action_url' => $this->details['actionURL'],
        ];
    }
}
