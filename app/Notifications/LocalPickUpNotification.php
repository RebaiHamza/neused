<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\CustomDbChannel;

class LocalPickUpNotification extends Notification
{
    use Queueable;

    public $productname;
    public $var_main;
    public $order_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($productname,$var_main,$order_id)
    {
        $this->productname = $productname;
        $this->var_main    = $var_main;
        $this->order_id    = $order_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
       return [CustomDbChannel::class];
    }
 /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [

            'data' => 'For Item '.$this->productname.'( '.$this->var_main.' ) Local Pickup date is updated',
            'n_type' => 'order',
            'url' => $this->order_id

        ];
    }
}
