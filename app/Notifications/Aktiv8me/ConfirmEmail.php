<?php

namespace App\Notifications\Aktiv8me;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmEmail extends Notification
{
    use Queueable;

    /** @var \App\RegistrationToken */
    public $registration_token;


    /**
     * Create a new notification instance.
     *
     * @param \App\RegistrationToken $registration_token
     */
    public function __construct($registration_token)
    {
        $this->token = $registration_token->token;
        $this->username = $registration_token->user->name;
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
        return (new MailMessage)
            ->subject(trans('aktiv8me.notifications.confirm.subject'))
            ->line(trans('aktiv8me.notifications.confirm.line1', ['username' => $this->username]))
            ->action(trans('aktiv8me.notifications.confirm.action'), route('register.verify', $this->token))
            ->line(trans('aktiv8me.notifications.confirm.line2', ['appname' => config('app.name')]));
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
