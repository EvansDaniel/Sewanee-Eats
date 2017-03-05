@extends('layout')
@section('head')
    <title>Sewanee Eats| Pricing</title>
@stop

@section('body')

    <style type="text/css">


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

        .rests {
            font-family: "Lato", sans-serif;
            list-style: none;
        }

        #promo-order-button {
            background: linear-gradient(90deg, #240a54 10%, #7459a5 90%);
            color: white;
            border-radius: 0px;
            text-align: center;
            padding: 10px 18px;
            text-decoration: none;
            margin-bottom: 1em;
            margin-left: 40%;
            font-size: 14px;
            font-weight: bold;
            /*width: 20%;*/
            border-radius: 5px;
            margin-top: 10px;
            border: solid rebeccapurple 2px;
            vertical-align: middle;
        }

        #promo-order-button:hover {
            background: linear-gradient(90deg, #7459a5 10%, #240a54 90%);
            -webkit-box-shadow: 0px 1px 5px 0px rgba(0, 0, 0, 0.75);
            -moz-box-shadow: 0px 1px 5px 0px rgba(0, 0, 0, 0.75);
            box-shadow: 0px 1px 5px 0px rgba(0, 0, 0, 0.75);
        }

        .pr-page {
            font-family: "Lato", sans-serif;

        }

        #pricing-p {

        }

        body {
            background: white;
        }

        @media only screen and (max-width: 768px) {

        }

        p {
            font-size: 18px;
        }

        .rests {
            font-size: 18px;
        }
        #pricing-header{
            margin-left: -30px;
        }
        /* ie7 inline-block fix */
    </style>
    <div align="center">

        <div class="container pr-page">
            <h1 id="pricing-header">Pricing</h1>

            <h2>How much will my order cost?</h2>

            <h6 class="row"><a href="{{ route('list_restaurants') }}" id="promo-order-button"
                               class="col-lg-2 col-md-3 col-sm-5 col-xs-5">
                    Order Now
                </a>
            </h6>
            <p id="pricing-p">The total to get your food delivered is the <b>cost of your food (the
                    amount the
                    restaurant would charge</b> plus a <b>base delivery fee from Sewanee Eats, with tax.</b> Below are
                the
                base delivery prices. Prices are <b>per order</b>.

                <br>
                <br>

                If you purchase 5 or more items from inside the gates, we will add $1 per item to your order total.
                <br>
                If you purchase 5 or more items from outside the gates, we will add $2 per item to your delivery charge.
            </p>

            <h2>Restaurants Inside the Gates:</h2>
            <hr>

            <div class="prices">
                <ul>
                    <li class="rests">Stirlings - $3</li>
                    <li class="rests">Pub - $3</li>
                    <br>
                    <li class="rests">Sewanee Market - $4</li>
                    <li class="rests">Shenanigans - $3</li>
                    <li class="rests">Blue Chair or Tavern - $4</li>
                <hr>
                <h2>Restaurants Outside the Gates:</h2>
                <hr>
                    <li class="rests">Pizza Hut - $4.50</li>
                    <li class="rests">Waffle House - $6</li>
                    <li class="rests">Sonic - $4.50</li>
                    <li class="rests">McDonalds - $4.50</li>
                    <br>
                    <li class="rests">Mountain Goat Market - $6</li>
                    <li class="rests">Wendys - $5</li>

                <hr>
                <h2>Weekly Special Prices:</h2>
                <hr>
                    <li class="rests">Chick-fil-a - $3 for less than 5 items, 75 cents per item for more than 5 items.
                    </li>
                    <li class="rests">Zaxbys - $3</li>
                </ul>

            </div>
            <h6 class="row"><a href="{{ route('list_restaurants') }}" id="promo-order-button"
                               class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
                    Order Now
                </a>
            </h6>

        </div>

@stop