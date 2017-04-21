@extends('admin.main.admin_dashboard_layout')

@section('head')
    <title>Item Breakdown for {{ $rest->name }}</title>
@stop

@section('body')

    <table class="table table-condensed">
        <tr>
            <th>Estimated Cost</th>
            <td>View all orders</td>
        </tr>
        <tr>
            <td>{{ $order_items_container->getEstimatedCost() }}</td>
            <td>
                <a href="{{ route('viewSpecialOrders',['rest_id' => $rest->id]) }}">
                    <button class="btn btn-dark">Go to orders</button>
                </a>
            </td>
        </tr>
    </table>
    <table class="table table-condensed">
        <thead>
        <tr>
            <th>Item</th>
            <th>Total Amount</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order_items_container->getAllItems() as $item)
            <tr>
                <td>{{ $item->item->name }}</td>
                <td>{{ $order_items_container->getCount($item->menu_item_id) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>



@stop