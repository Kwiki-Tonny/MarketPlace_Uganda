<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VendorApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Welcome to MarketPlace Uganda!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your vendor account has been approved.')
            ->line('You can now log in and start selling.')
            ->action('Go to Dashboard', route('vendor.dashboard'))
            ->line('Thank you for joining us!');
    }
}