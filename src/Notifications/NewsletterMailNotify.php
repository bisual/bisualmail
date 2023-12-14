<?php

namespace bisual\bisualMail\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use bisual\bisualMail\Mail\NewsletterMail;

class NewsletterMailNotify extends Notification
{
    use Queueable;

    protected $args;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($args)
    {
        $this->args = $args;
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
        return (new NewsletterMail($this->args))
                    ->to($notifiable->email, $notifiable->name)
                    ->subject($this->args['title'])
                    ->from(config('bisualmail.default_mail'), config('bisualmail.default_name'));
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
