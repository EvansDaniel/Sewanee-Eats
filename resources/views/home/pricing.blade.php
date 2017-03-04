@extends('layout')


@section('body')

    <style type="text/css">
        .prices {
            text-align: center
        }

        .prices ul {
            display: inline-block;
            text-align: left;
        }

        * html .test ul {
            display: inline
        }

        /* ie6 inline-block fix */
        * + html .test ul {
            display: inline
        }

        /* ie7 inline-block fix */
    </style>
    <div align="center">

        <h1>Pricing</h1>
        <h4><a href="{{ route('list_restaurants') }}">Place your order here!</a></h4>

        <h2>How much will my order cost?</h2>
        <p style="width:50%;">The total to get your food delivered is the <b>cost of your food (the
                amount the
                restaurant would charge</b> plus a <b>base delivery fee from Sewanee Eats, with tax.</b> Below are the
            base delivery prices. Prices are <b>per order</b>.

            <br>
            <br>

            If you purchase 5 or more items from inside the gates, we will add $1 per item to your order total.
            <br>
            If you purchase 5 or more items from outside the gates, we will add $2 per item to your delivery charge.
        </p>

        <h2>Restaurants Inside the Gates:</h2>
        <hr>
        <h3>
            <div class="prices">
                {{--<div class="container">
                    <div align="center">
                        <table class="table" style="padding: 10px">
                            <thead>
                            <tr>
                                <th>Restaurant</th>
                                <th>Price ($)</th>
                            </tr>
                            </thead>
                            <tr>
                                <td>Pizza Hut</td>
                                <td>4.50</td>
                            </tr>
                            <tr>
                                <td>Waffle House</td>
                                <td>6.00</td>
                            </tr>
                            <tr>
                                <td>Sonic</td>
                                <td>4.50</td>
                            </tr>
                            <tr>
                                <td>McDonalds</td>
                                <td>4.50</td>
                            </tr>
                            <tr>
                                <td>Mountain Goat Market</td>
                                <td>5.00</td>
                            </tr>
                            <tr>
                                <td>Wendy's</td>
                                <td>5.00</td>
                            </tr>
                        </table>
                    </div>
                </div>--}}
                <ul>
                    <li>Stirlings - $2</li>
                    <li>Pub - $2</li>
                    <br>

                    <li>Sewanee Market - $4</li>
                    <li>Shenanigans - $4</li>
                    <li>Blue Chair or Tavern - $4</li>
                </ul>
                <hr>
                <h2>Restaurants Outside the Gates:</h2>
                <hr>
                <ul>
                    <li>Pizza Hut - $4.50</li>
                    <li>Waffle House - $6</li>
                    <li>Sonic - $4.50</li>
                    <li>McDonalds - $4.50</li>
                    <br>
                    <li>Mountain Goat Market - $4.50</li>
                    <li>Wendys - $5</li>
                </ul>

                <hr>
                <h2>Weekly Special Prices:</h2>
                <hr>
                <ul>
                    <li>Chick-fil-a - $3</li>
                    <li>Zaxbys - $3</li>
                </ul>
            </div>
        </h3>
    </div>


@stop