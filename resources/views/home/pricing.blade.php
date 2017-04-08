@extends('main.main_layout')
@section('head')
    <title>Sewanee Eats| Pricing</title>
@stop

@section('body')
    <link rel="stylesheet" href={{ asset('css/pricing.css', env('APP_ENV') !== 'local')  }}>

    <div align="center" class="center">

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
                    restaurant would charge</b> plus a <b>base delivery fee from Sewanee Eats, with tax).</b> Below are
                the
                base delivery prices. Prices are <b>per order</b>.
            </p>
            <p class="on-demand">
                1. ON DEMAND DELIVERY PRICES
            </p>

            <div class="prices">
                <ul>
                    <div class="central">
                        <hr>
                        <h2>Central campus restaurant - $3</h2>
                        <hr>
                    </div>
                    <p class="include">*on campus restaurants include</p>
                    <li class="rests">Stirlings</li>
                    <li class="rests">Pub</li>
                    <br>
                    <hr>
                    <h2>Others - $4</h2>
                    <hr>
                    <li class="rests">Sewanee Market</li>
                    <li class="rests">Shenanigans</li>
                    <li class="rests">Blue Chair or Tavern</li>
                    <li class="rests">Pizza Hut</li>
                    <li class="rests">Waffle House</li>
                    <li class="rests">Sonic</li>
                    <li class="rests">McDonalds</li>
                    <li class="rests">Mountain Goat Market</li>
                    <li class="rests">Wendys</li>
                    <p class="note"><br><span>Note: </span><br> - for any item added to your cart after the second item
                        an extra<strong> $ 0.30</strong> is added to your delivery fee
                        <br> - this only applies to On-Demand delivery services
                    </p>
                    <hr>
                </ul>
                <h2 class="special">2. Weekly Special Prices - $3</h2>
                <hr>
                <ul>

                <p class="more"> The more you add to your cart, the more the delivery prices reduces on Weekly specials
                    services</p>
                <p> restaurants include:</p>
                <li class="rests">
                    All restaurants that are outside Monteagle
                </li>
                <li class="rests">Please let us know which restaurants you would like us to deliver from <a
                            href="{{ route('support') }}">here</a></li>
                <p class="note"><br><span>Note: </span><br> - for any item added to your cart after the first item and
                    extra<strong>20%</strong> is reduced to your delivery fee.<br> This discount applies up to 4 items in your weekly special cart which means that you can save up to <strong>60%</strong>
                </p>
                </ul>

            </div>
            <h6 class="row"><a href="{{ route('list_restaurants') }}" id="promo-order-button"
                               class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
                    Order Now
                </a>
            </h6>

        </div>
    </div>
@stop