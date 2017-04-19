@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Orders</title>
@stop

@section('body')
    <link href={{ asset('css/admin_on_demand.css',env('APP_ENV') !== 'local')  }}>
    <link rel="stylesheet" href="{{ asset('css/admin/order_listing.css',env('APP_ENV') !== 'local')  }}">
    <link rel="stylesheet" href="{{ asset('css/admin/general_admin.css',env('APP_ENV') !== 'local')  }}">
    <section class="container-fluid demand_container">
        @foreach($on_demand_open_orders as $on_demand_order)
            <div class="row" id="{{ $on_demand_order->id }}">
                <div class="col-lg-1 small">
                    <a href="{{ route('orderSummaryForAdmin',['order_id' => $on_demand_order->id]) }}">
                        ID: {{$on_demand_order->id}}
                    </a>
                    {{ generateScrollTo($scroll_to_item_id) }}
                </div>
                <div class="col-lg-1 medium">Customer: {{$on_demand_order->c_name}}</div>
                @if(!$on_demand_order->is_delivered && !$on_demand_order->is_cancelled && !$on_demand_order->was_refunded)
                    <div class="col-lg-1 medium">Order received {{ $on_demand_order->timeSinceOrdering() }} mins ago
                    </div>
                @endif
                <div class="col-lg-1 medium">
                    <form action="{{ route('changeCourierForOrder') }}" method="post">
                        Courier:
                        @if($on_demand_order->hasCourier()) {{$on_demand_order->getCourier()->name}} @else No
                        courier assigned yet @endif
                        {{ csrf_field() }}
                        <input name="order_id" type="hidden" value="{{ $on_demand_order->id }}">
                        <select name="courier_id" id="courier-select">
                            @foreach($couriers as $courier)
                                @if($on_demand_order->hasCourier() && $on_demand_order->getCourier()->id == $courier->id)
                                    <option selected value="{{ $courier->id }}">{{ $courier->name }}</option>
                                @else
                                    <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <button class="btn btn-dark courier-button" type="submit">Change Courier</button>
                    </form>
                </div>
                <div class="col-lg-1 medium">Deliver to Location: {{$on_demand_order->delivery_location}}</div>
                <div class="col-lg-1 medium">Customer Email: {{$on_demand_order->email_of_customer}}</div>
                <div class="col-lg-1 medium">Customer Phone Number: {{$on_demand_order->phone_number}}</div>
                <div class="col-lg-1 medium">Payment type:
                    @if($on_demand_order->payment_type == $venmo_payment_type) Venmo @else Card @endif
                </div>
                <div class="col-lg-1 large">
                    statuses:
                    <span style="background: darkgreen; color: white; display: inline-block; margin: 2px 0;">
                        @if($on_demand_order->is_paid_for) Paid @else Not paid @endif
                    </span>
                    <span style="background: yellow; color: black; display: inline-block; margin: 2px 0;">
                        @if($on_demand_order->is_being_processed) Processing @else No Assigned Courier @endif
                    </span>
                    <span style="background: darkgreen; color: white; display: inline-block; margin: 2px 0;">
                        @if($on_demand_order->is_delivered) Delivered @else Not delivered @endif
                    </span>
                    <span style="background: crimson; color: white; display: inline-block; margin: 2px 0;">
                        @if($on_demand_order->was_refunded) Refunded @else Not refunded @endif
                    </span>
                    <span style="background: crimson; color: white; display: inline-block; margin: 2px 0;">
                        @if($on_demand_order->is_cancelled) Canceled @else Not Canceled @endif
                    </span>
                </div>

            </div>
            <div class="row buttons-wrapper">
                <div class="buttons">
                    <form class="manage-order-form" action="{{ formUrl('toggleOrderIsDelivered')  }}"
                          method="post">
                        {{ csrf_field() }}
                        <input id="toggle-delivered-{{$on_demand_order->id}}" type="text" name="order_id"
                               value="{{ $on_demand_order->id}}" style="display: none">
                        @if($on_demand_order->is_delivered)
                            <button onclick="" class="btn btn-primary toggle-delivered">Undo
                                Mark Order as Delivered
                            </button>
                        @else
                            <button onclick="" class="btn btn-primary toggle-delivered">Mark
                                Order as Delivered
                            </button>
                        @endif
                    </form>
                    <form class="cancel manage-order-form"
                          action="{{ formUrl('toggleOrderCancellation') }}"
                          method="post">
                        {{ csrf_field() }}
                        <input id="cancel-order-{{$on_demand_order->id}}" type="hidden" name="order_id"
                               value="{{ $on_demand_order->id}}">
                        <button onclick="" class="btn btn-primary cancel-order">
                            @if($on_demand_order->is_cancelled) Undo Order Cancellation @else Cancel Order @endif
                        </button>
                    </form>
                @if(!$on_demand_order->is_cancelled) <!-- Can't refund if it is cancelled -->
                    <form class="manage-order-form"
                          action="{{ formUrl('toggleRefundOrder') }}"
                          method="post">
                        {{ csrf_field() }}
                        <input id="refund-order-{{$on_demand_order->id}}" type="hidden" name="order_id"
                               value="{{ $on_demand_order->id}}">
                        <button class="btn btn-primary refund">
                            @if($on_demand_order->was_refunded) Undo Order Refund @else Refund Order @endif
                        </button>
                    </form>
                    @endif
                    @if($on_demand_order->payment_type == $venmo_payment_type)
                        <form class="cancel change-status manage-order-form"
                              action="{{ formUrl('togglePaymentConfirmationForVenmo') }}"
                              method="post">
                            {{ csrf_field() }}
                            <input id="confirm-payment-{{$on_demand_order->id}}" type="text" name="order_id"
                                   value="{{ $on_demand_order->id}}" style="display: none">
                            <button class="btn btn-primary confirm-payment">
                                @if($on_demand_order->is_paid_for) Undo Payment Confirmation @else ConfirmPayment @endif
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach

    </section>

    <script src="{{ assetUrl('js/helpers.js')  }}"></script>
    <script>
      // scrolls to the item given by the generateScrollTo php view function
      scrollToItem(1000);

      // window checks for manipulating orders
      $(document).ready(function () {
        $(".confirm-payment").click(changeStatus(".confirm-payment", "Are you sure you want to change this order's payment status?"));
        $(".cancel-order").click(changeStatus(".cancel-order", "Are you sure you want to change the cancellation status of this order?"));
        $(".refund").click(changeStatus(".refund", ".Are you sure you want to change the refund status?"));
        $(".toggle-delivered").click(changeStatus(".toggle-delivered", ".Are you sure you want to change the delivery status?"));
      });
      // function to change the status of an order
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
    <script src="{{ assetUrl('js/Misc/backend_msg_attach.js') }}"></script>
    <script>
      msgTimeout(7500);
    </script>
@stop