@extends('admin.admin_dashboard_layout')

@section('head')
    <title>List weekly orders</title>
@stop

@section('body')

    <ul class="list-group">
        @foreach($orders as $order)

            <li class="list-group-item">
                Customer Name: <h3>{{ $order->c_name }}</h3>
                Customer Email: <h3>{{ $order->email_of_customer }}</h3>
                <span class="order-price-before-fees">{{ $order->sumPriceBeforeFees() }}</span>
                <ul>
                    @foreach($order->menuItemOrders as $menuItemOrder)
                        <li>
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

    <ul>
        <li>
            Total Cost before fees: <span id="total-cost-before-fees"></span>
        </li>
    </ul>

    <script>
      $(function () {
        var totalBeforeFees = 0;
        $('.order-price-before-fees').each(function () {
          p($(this).text());
          totalBeforeFees += parseFloat($(this).text())
        });
        $('#total-cost-before-fees').text(totalBeforeFees);
      })
    </script>
@stop
