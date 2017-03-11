@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>List weekly orders</title>
@stop

@section('body')

    <div class="right_col" role="main">
        <!-- top tiles -->
        <div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i>Gross Sales</span>
                <div class="count">2500</div>
                <span class="count_bottom"><i class="green">TODO: percentage up/down </i> From last Week</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-clock-o"></i> Average Time</span>
                <div class="count">123.50</div>
                <span class="count_bottom"><i class="green"><i
                                class="fa fa-sort-asc"></i>TODO: percentage up/down </i> From last Week</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i>Profit</span>
                <div class="count green">2,500</div>
                <span class="count_bottom"><i class="green"><i
                                class="fa fa-sort-asc"></i>TODO: percentage up/down </i> From last Week</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i>Stripe Costs</span>
                <div class="count">4,567</div>
                <span class="count_bottom"><i class="red"><i
                                class="fa fa-sort-desc"></i>TODO: percentage up/down </i> From last Week</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i>Tax Fees</span>
                <div class="count">2,315</div>
                <span class="count_bottom"><i class="green"><i
                                class="fa fa-sort-asc"></i>TODO: percentage up/down </i> From last Week</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                <span class="count_top"><i class="fa fa-user"></i>Food Sales</span>
                <div class="count">7,325</div>
                <span class="count_bottom"><i class="green"><i
                                class="fa fa-sort-asc"></i>TODO: percentage up/down </i> From last Week</span>
            </div>
        </div>

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
    </div>

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
        $('#total-cost-before-fees').text(totalBeforeFees - numItems);
        var totalForAllOrders = ((profit - numItems) + (totalBeforeFees)) * tax;

        var totalForTax = totalForAllOrders * (1 - tax);
        $('#tax').text(totalForTax);
        var stripeCost = .3 + (.029 * totalForAllOrders);

        $('#stripe-cost').text(stripeCost);

        profit -= stripeCost;
        $('#profit').text(profit);
      })
    </script>
@stop
