@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Orders</title>
@stop

@section('body')
    <link href={{ asset('css/admin_on_demand.css',env('APP_ENV') !== 'local')  }}>
    <style>
        .demand_container {
            background: white;
            font-family: "Lato", sans-serif;
            color: black;
        }

        .buttons-wrapper {
            margin-bottom: 30px;
            border-bottom: 2px solid rgba(0, 0, 128, .2);
        }

        .clearfix {
            background: white;
        }

        .profile {
            background: #5A738E;
        }

        span {
            padding: 2px;
            margin-right: 2px;
        }

        .small {
            width: 40px;
            display: block;
            float: left;
            margin-right: 5px;
        }

        .medium {
            width: 150px;
            float: left;
            margin-right: 5px;
        }

        .large {
            width: 300px;
            float: left;
        }

        .main-main-container, body {
            background: white;
        }

        .buttons {

        }

    </style>
    <section class="container-fluid demand_container">
    @foreach($on_demand_open_orders as $on_demand_order)

        <!--
         TODO: for Blaise
          Make it possible for a manager/admin to assert that a venmo order was paid for, cancel an order, refund an order, etc.
          Later on we will make it so that you can refund individual items, also link to an order summary page

        -->
            <div class="row">
                <div class="col-lg-1 small">
                    <a href="{{ route('orderSummaryForAdmin',['order_id' => $on_demand_order->id]) }}">
                        ID: {{$on_demand_order->id}}
                    </a>
                </div>
                <div class="col-lg-1 medium">customer: {{$on_demand_order->c_name}}</div>
                <div class="col-lg-1 medium">
                    Courier: @if(count($on_demand_order->couriers) > 1) {{$on_demand_order->couriers[0]->name}} @else No
                    courier assigned yet   @endif</div>
                <div class="col-lg-1 medium">Deliver to Location: {{$on_demand_order->delivery_location}}</div>
                <div class="col-lg-1 medium">Customer Email: {{$on_demand_order->email_of_customer}}</div>
                <div class="col-lg-1 medium">Customer Phone Number: {{$on_demand_order->phone_number}}</div>
                <div class="col-lg-1 medium">Payment type:
                    @if($on_demand_order->payment_type == $venmo_payment_type)
                        Venmo
                    @else
                        Card
                    @endif

                </div>
                <div class="col-lg-1 large">
                    statuses:
                    @if($on_demand_order-> is_paid_for)
                        <span style="background: darkgreen; color: white;">
                        Paid
                    </span>
                    @else
                        <span style="background: crimson; color: white;">
                        Not paid
                    </span>
                    @endif
                    @if($on_demand_order->is_being_processed)
                        <span style="background: yellow; color: white;">
                        Processing
                    </span>
                    @elseif($on_demand_order->is_delivered)
                        <span style="background: darkgreen; color: white;">
                        Processing
                    </span>
                    @else
                        <span style="background: crimson; color: white;">
                            No Assigned Courier
                    </span>
                    @endif
                    @if($on_demand_order->is_delivered)
                        <span style="background: darkgreen; color: white;">
                        Delivered
                    </span>
                    @else
                        <span style="background: crimson; color: white;">
                        Not delivered
                    </span>
                    @endif
                    @if($on_demand_order->was_refunded)
                        <span style="background: crimson; color: white;">
                        Refunded
                    </span>
                    @else
                        <span style="background: darkgreen; color: white;">
                        Not refunded
                    </span>
                    @endif
                    @if($on_demand_order->is_cancelled)
                        <span style="background: crimson; color: white;">Canceled
                        </span>
                    @else
                        <span style="background: darkgreen; color: white;">
                        Not Canceled
                    </span>
                    @endif
                </div>
                <div class="col-lg-1 medium">

                </div>

            </div>
            <div class="row buttons-wrapper">
                <div class="buttons">
                    <form action="{{ url()->to(parse_url(route('toggleOrderIsDelivered',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                          method="post" style="display: inline">
                        {{ csrf_field() }}
                        <input id="toggle-delivered-{{$on_demand_order->id}}" type="text" name="order_id"
                               value="{{ $on_demand_order->id}}" style="display: none">
                        @if($on_demand_order->is_delivered)
                            <button onclick="" class="btn btn-primary toggle-delivered" style="display: inline">Undo
                                Mark Order as Delivered
                            </button>
                        @else
                            <button onclick="" class="btn btn-primary toggle-delivered" style="display: inline">Mark
                                Order as Delivered
                            </button>
                        @endif
                    </form>
                    <form class="cancel"
                          action="{{ url()->to(parse_url(route('toggleOrderCancellation',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                          method="post" style="display: inline">
                        {{ csrf_field() }}
                        <input id="cancel-order-{{$on_demand_order->id}}" type="text" name="order_id"
                               value="{{ $on_demand_order->id}}" style="display: none">
                        @if($on_demand_order->is_cancelled)
                            <button onclick="" class="btn btn-primary cancel-order">Undo Order Cancellation</button>
                        @else
                            <button onclick="" class="btn btn-primary cancel-order">Cancel Order</button>
                        @endif
                    </form>
                @if(!$on_demand_order->is_cancelled) <!-- Can't refund if it is cancelled -->
                    <form class="refund"
                          action="{{ url()->to(parse_url(route('toggleRefundOrder',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                          method="post" style="display: inline">
                        {{ csrf_field() }}
                        <input id="refund-order-{{$on_demand_order->id}}" type="text" name="order_id"
                               value="{{ $on_demand_order->id}}" style="display: none">
                        @if($on_demand_order->was_refunded)
                            <button class="btn btn-primary refund">Undo Order Refund</button>
                        @else
                            <button class="btn btn-primary refund">Refund Order</button>
                        @endif
                    </form>
                    @endif
                    @if($on_demand_order->payment_type == $venmo_payment_type)
                        <form class="cancel change-status"
                              action="{{url()->to(parse_url(route('togglePaymentConfirmationForVenmo',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                              method="post" style="display: inline">
                            {{ csrf_field() }}
                            <input id="confirm-payment-{{$on_demand_order->id}}" type="text" name="order_id"
                                   value="{{ $on_demand_order->id}}" style="display: none">
                            @if($on_demand_order->is_paid_for)
                                <button class="btn btn-primary confirm-payment">Undo Payment Confirmation</button>
                            @else
                                <button class="btn btn-primary confirm-payment">Confirm Payment</button>
                            @endif
                        </form>
                    @endif
                </div>
            </div>
        @endforeach

    </section>

    <script>

      $(document).ready(function () {
        $(".confirm-payment").click(changeStatus(".confirm-payment", "Are you sure you want to change this order's payment status?"));
        $(".cancel-order").click(changeStatus(".cancel-order", "Are you sure you want to change the cancellation status of this order?"));
        $(".refund").click(changeStatus(".refund", ".Are you sure you want to change the refund status?"));
        $(".toggle-delivered").click(changeStatus(".toggle-delivered", ".Are you sure you want to change the delivery status?"));

      });
      function changeStatus(button, text) {
        $(button).each(function () {
          $(this).on('click', function () {
            if (window.confirm(text)) {
            }
            else {
              return false;
            }

          });
        });
      }
    </script>

@stop