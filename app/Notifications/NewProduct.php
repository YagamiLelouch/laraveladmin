<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class NewProduct extends Notification implements ShouldQueue
{

    use Queueable;

    public $user;

    public function __construct(User $user)
    {
        // 注入回复实体，方便 toDatabase 方法中的使用
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

//        $notifiable->email = 'gaowei.song@geely.com';
//        $notifiable->email = 'wangjingxuan@aukeys.com';
//        return (new MailMessage)
//            ->line('Welcome to HKD Shaowen\'s world！')
////                ->line('11111111222')
//            ->action('Enter Shaowen\'s world！', 'https://www.google.com');
        return (new MailMessage())
            ->subject('Welcome to the the Portal')
            ->view('mails.product', ['user' => $this->user]);

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
