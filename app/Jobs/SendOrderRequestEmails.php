<?php

namespace App\Jobs;

use App\CustomClasses\Schedule\Shift;
use App\CustomClasses\ShoppingCart\PaymentType;
use App\CustomClasses\ShoppingCart\RestaurantOrderCategory;
use App\Mail\NewOrderToDriver;
use App\Mail\NewOrderToManager;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class   SendOrderRequestEmails implements ShouldQueue
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
        // SEND TO MANAGER
        $on_demand_order_type = RestaurantOrderCategory::ON_DEMAND;
        $weekly_order_type = RestaurantOrderCategory::WEEKLY_SPECIAL;
        $venmo_payment_type = PaymentType::VENMO_PAYMENT;
        $order = $this->order;
        $subject = "New Order Request!";
        if ($this->order->payment_type == PaymentType::VENMO_PAYMENT) {
            $subject = "New Venmo Order Request!";
        }
        // ONLY RUNS IN PRODUCTION/STAGING
        // SEND TO SewaneeEats
        if (env('APP_ENV') === "production" || env('APP_ENV') == "staging") {
            \Log::info('sending email to sewaneeeats@gmail.com');
            \Mail::to('sewaneeeats@gmail.com')->sendNow(new NewOrderToManager($order));
        } else {
            \Log::info('would send email to manager to sewaneeeats@gmail.com but we are not in production mode so sending to seatstest17@gmail.com');
            \Mail::to('seatstest17@gmail.com')->sendNow(new NewOrderToManager($order));
        }
        // send to all couriers
        // Having a shift now MUST imply that ther is couriers on that shift
        $couriers_online = $this->getCouriersForOrder($order);
        if ($order->hasOrderType(RestaurantOrderCategory::ON_DEMAND)) {
            \Log::info('on demand order rolling out');
            if (!empty($couriers_online)) {
                foreach ($couriers_online as $courier_online) {
                    \Log::info('sending email to ' . $courier_online->name . ' email ' . $courier_online->email . ' courier');
                    \Mail::to($courier_online->email)->sendNow(new NewOrderToDriver($order));
                }
            } else {
                \Log::info('no courier available');
                // TODO: send email to manager saying there is no one online to service order
            }
        } else {
            \Log::info('only special order');
        }


        // SEND TO CUSTOMER
        $mailer->send('emails.new_order_to_customer', [
            'order' => $this->order,
            'on_demand_order_type' => $on_demand_order_type,
            'weekly_order_type' => $weekly_order_type,
            'venmo_payment_type' => $venmo_payment_type
        ],
            function ($message) {
                \Log::info('sending email to customer ' . $this->order->email_of_customer);
                $message->from('sewaneeeats@gmail.com');
                $message->to($this->order->email_of_customer)->subject('SewaneeEats Order Confirmation');
            });
    }

    private function getCouriersForOrder(Order $order)
    {
        $shift = Shift::now();
        if (empty($shift)) {
            return null;
        }
        $couriers_online = $shift->users;
        $couriers_with_courier_type = [];
        foreach ($couriers_online as $courier_online) {
            if ($order->hasCourierType($courier_online->pivot->courier_type)) {
                $couriers_with_courier_type[] = $courier_online;
            }
        }
        return $couriers_with_courier_type;
    }
}
