@extends('admin.admin_dashboard_layout')

@section('head')
    <title>List weekly orders</title>
@stop

@section('body')

    <ul>
        <li>
            Total Cost before fees: <span id="total-cost-before-fees"></span>
        </li>
        <li>
            Profit <span id="profit"></span>
        </li>
        <li>
            Tax Total: <span id="tax"></span>
        </li>
        <li>
            Stripe Costs: <span id="stripe-cost"></span>
        </li>
    </ul>

    <ul class="list-group">
        @foreach($orders as $order)

            <li class="list-group-item order">
                Customer Name: <h3>{{ $order->c_name }}</h3>
                Customer Email: <h3>{{ $order->email_of_customer }}</h3>
                <span class="order-price-before-fees">{{ $order->sumPriceBeforeFees() }}</span>
                <ul>
                    @foreach($order->menuItemOrders as $menuItemOrder)
                        <li class="item">
                            Name: {{ $menuItemOrder->menuItem->name }}
                            <br>
                            Price: {{ $menuItemOrder->menuItem->price }}
                            <br>
                            Instructions: {{ $menuItemOrder->special_instructions }}
                            <ul>
                                @foreach($menuItemOrder->accessories() as $acc)
                                    Acc name: {{ $acc->name }}
                                    <br>
                                    Acc price: {{ $acc->price }}
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </li>

        @endforeach
    </ul>

    <script>
      $(function () {
        var totalBeforeFees = 0;
        $('.order-price-before-fees').each(function () {
          p($(this).text());
          totalBeforeFees += parseFloat($(this).text())
        });
        var profit = 0;
        var numOrders = 0;
        var numItems = 0;
        var deliveryFee = 3;
        var tax = 1.0925;
        $('.order').each(function () {
          numOrders++;
        });
        $('.item').each(function () {
          numItems++;
        });
        profit = numItems + (numOrders * deliveryFee);
        $('#total-cost-before-fees').text(totalBeforeFees);
        var totalForAllOrders = (profit + totalBeforeFees) * tax;

        var totalForTax = totalForAllOrders * (1 - tax);
        $('#tax').text(totalForTax);
        var stripeCost = .3 + (.029 * totalForAllOrders);

        $('#stripe-cost').text(stripeCost);

        profit -= stripeCost;
        $('#profit').text(profit);
      })
    </script>
@stop
