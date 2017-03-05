<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderRequestEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     * @param $order Order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $managers = [
            'evansdb0@sewanee.edu', 'kandeta0@sewanee.edu', 'iradub0@sewanee.edu'
        ];
        $m_name = [
            'Daniel Evans', 'Tariro Kandemiri', 'Blaise Iradukunda'
        ];
        // send to manager
        if ($this->order->paid_with_venmo) {
            for ($i = 0; $i < count($managers); $i++) {
                $mailer->send('emails.new_order_manager', ['order' => $this->order], function ($message) use ($managers, $m_name, $i) {
                    $message->from('sewaneeeats@gmail.com');
                    $message->to($managers[$i], $m_name[$i])->subject('New Venmo Order Request!');
                });
            }
        } else {
            for ($i = 0; $i < count($managers); $i++) {
                $mailer->send('emails.new_order', ['order' => $this->order], function ($message) use ($managers, $m_name, $i) {
                    $message->from('sewaneeeats@gmail.com');
                    $message->to($managers[$i], $m_name[$i])->subject('New Order Request!');
                });
            }
        }
        // send to customer
        $mailer->send('emails.new_order', ['order' => $this->order], function ($message) {
            $message->from('sewaneeeats@gmail.com');
            $message->to($this->order->email_of_customer)->subject('SewaneeEats Order Confirmation');
        });
    }
}
