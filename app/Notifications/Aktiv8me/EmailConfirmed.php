<?php

namespace App\Notifications\Aktiv8me;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailConfirmed extends Notification
{
    use Queueable;

    /** @var \App\User */
    public $user;


    /**
     * Create a new notification instance.
     *
     * @param \App\User $user
     */
    public function __construct($user)
    {
        $this->user = $user;
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
            ->subject(trans('aktiv8me.notifications.welcome.subject'))
            ->line(
                trans(
                    'aktiv8me.notifications.welcome.line1',
                    ['appname' => config('app.name'), 'username' => $this->user->name]
                )
            )
            ->line(trans('aktiv8me.notifications.welcome.line2'));
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
