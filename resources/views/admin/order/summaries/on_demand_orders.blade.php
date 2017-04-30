@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Orders</title>
@stop

@section('body')
    <link rel="stylesheet" href="{{ assetUrl('css/admin/orders/on_demand_orders.css') }}">
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
                <div class="col-lg-small">
                    Received: {{ $on_demand_order->created_at }}
                </div>
                <div class="col-lg-1 medium">Customer: {{$on_demand_order->c_name}}</div>
                @if(!$on_demand_order->is_delivered && !$on_demand_order->is_cancelled && !$on_demand_order->was_refunded)
                    <div class="col-lg-1 medium">Order received {{ $on_demand_order->timeSinceOrdering() }} mins ago
                    </div>
                @endif
                <div class="col-lg-1 medium">
                    <form action="{{ formUrl('changeCourierForOrder') }}" method="post">
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

                    <span class="od-order-status is-paid" data-is-paid="{{ $on_demand_order->is_paid_for }}">
                        @if($on_demand_order->is_paid_for) Paid @else Not Paid @endif
                    </span>
                    @if(!$on_demand_order->is_delivered)
                        <span class="od-order-status is-being-processed"
                              data-is-being-processed="{{ $on_demand_order->is_being_processed }}">
                            @if($on_demand_order->is_being_processed) Processing @else No Assigned Courier @endif
                        </span>
                    @endif
                    <span class="od-order-status is-delivered" data-is-delivered="{{ $on_demand_order->is_delivered }}">
                        @if($on_demand_order->is_delivered) Delivered @else Not Delivered @endif
                    </span>
                    <span class="od-order-status is-refunded" data-is-refunded="{{ !$on_demand_order->was_refunded }}">
                        @if($on_demand_order->was_refunded) Refunded @else Not Refunded @endif
                    </span>
                    <span class="od-order-status is-cancelled"
                          data-is-cancelled="{{ !$on_demand_order->is_cancelled }}">
                        @if($on_demand_order->is_cancelled) Cancelled @else Not Cancelled @endif
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
                                @if($on_demand_order->is_paid_for) Undo Payment Confirmation @else Confirm
                                Payment @endif
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach

    </section>

    <script src="{{ assetUrl('js/helpers.js')  }}"></script>
    <script src="{{ assetUrl('js/Misc/backend_msg_attach.js') }}"></script>
    <script src="{{ assetUrl('js/admin/orders/on_demand_orders.js') }}"></script>
@stop