@extends('main.main_layout')

@section('head')
    <title>Sewanee Eats</title>
    <link rel="stylesheet" href={{ asset('css/home_new.css', env('APP_ENV') !== 'local')  }}>
    <link href="https://fonts.googleapis.com/css?family=Anton" rel="stylesheet">
@stop
@section('body')
    <div id="push-fig"></div>
    @include('partials.home_static')
    <section id="hw-it-wrks" class="hw-it-wrks">
        <div class="container">
            <div class="col-lg-3 col-md-3 col-sm-5 col-xs-8 pick" id="pick">
                <h6 class="feature-desc-heading">Delivery from your favorite restaurants</h6>
                <img class="img-responsive center-block" id="pick-logo"
                     src="{{ asset('images/select.png',env('APP_ENV') !== 'local') }}">
                <ul>
                    <li>Effortless ordering</li>
                    <li>No markups on any local restaurants</li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-5 col-sm-offset-3 col-xs-8 col-xs-offset-2 delivery" id="delivery">
                <h6 class="feature-desc-heading">Delivery in 45 minutes or less</h6>
                <img class="img-responsive center-block" id="delivery-logo"
                     src="{{ asset('images/branding/purple-fast-delivery-truck.png',env('APP_ENV') !== 'local') }}">
                <ul>
                    <li>Delivery to any location on the mountain</li>
                    <li>Check out our <a href="{{ route('list_restaurants') }}#special-rests">special restaurant</a>
                        deliveries for this week
                    </li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-5 col-xs-8 pay" id="pay">
                <h6 class="feature-desc-heading">
                    Secure Payment Options
                </h6>
                <img class="img-responsive img-rounded center-block" id="pay-logo"
                     src="{{ asset('images/pay.png',env('APP_ENV') !== 'local') }}">
                <ul>
                    <li>We take <strong>Venmo</strong> payment at checkout</li>
                    <li>All payments via card secured with SSL and Stripe</li>
                </ul>

            </div>
        </div>
    </section>

    <section id="promo-section" class="container-fluid">
        <div class="container-fluid promo-wrap-1">
            <div class="container-fluid promo-wrap-2">
                <div class="container promo-wrap-3">

                    <p class="row" id="promo-p1">
                        Buying food on the mountain made easy
                    </p>
                    <p class="row" id="promo-p2">
                        We offer express food delivery everywhere
                    </p>
                    <br> <br>
                    <div class="row" id="promo-places">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-sm-offset-1 col-xs-offset-1 col-xs-10"
                             id="promo-place1">
                            <p class="col-md-12 col-lg-12">Order from your house <span> Or </span> residential room</p>
                            <img src="{{ asset('images/locations/humphreys.jpg',env('APP_ENV') !== 'local') }}"
                                 class="img-responsive img-thumbnail img-circle " alt="Smiley face" id="place-1">
                        </div>
                        <div class="col-lg-3 col-md-3 col-lg-offset-1 col-md-offset-1 col-sm-3 col-sm-offset-1 col-xs-offset-1 col-xs-10"
                             id="promo-place2">
                            <p class="col-md-12 col-lg-12">Your favorite place to study</p>
                            <img src="{{ asset('images/locations/dupont.jpg',env('APP_ENV') !== 'local') }}"
                                 class="img-responsive img-thumbnail img-circle" alt="Smiley face" id="place-2">
                        </div>
                        <div class="col-lg-3 col-md-3 col-lg-offset-1 col-md-offset-1 col-sm-3 col-sm-offset-1 col-xs-offset-1 col-xs-10"
                             id="promo-place3">
                            <p>your sorority/fraternity house</p>
                            <img src="{{ asset('images/locations/kappasig.jpg',env('APP_ENV') !== 'local') }}"
                                 class="img-responsive img-thumbnail img-circle" alt="Smiley face" id="place-3">
                        </div>
                    </div>
                    <div class="row" id="promo-btn-div">
                        <a href="{{ route('list_restaurants') }}" id="promo-order-button">
                            ORDER NOW
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="services" class="container">
        <p class="row" id="services-title">
            What does <span>SewaneeEats</span> Offer?
        </p>
        <div class="row services-wrapper">
            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-10 col-xs-offset-1 ondemand">
                <div id="demand-wrap" class="row">
                    <img src="{{ asset('images/branding/demand-logo0.png',env('APP_ENV') !== 'local') }}"
                         class="img-responsive center-block" alt="Smiley face" id="demand-logo">
                    <p id="demand-description"> On-Demand</p>
                </div>
                <p id="demand-long-description">
                    On-Demand food deliveries is a daily express food delivery. It covers all the listed <a
                            href="{{ route('list_restaurants') }}"> restaurants</a>
                    that are located on the mountain. After you have ordered the food, the food is delivered at your
                    door in less than 45 minutes.<br>
                    <strong>The On-Demand service is AVAILABLE WEEKDAYS FROM 12PM - 3PM and 5PM - 11PM. During weekends,
                        it is AVAILABLE FROM 12PM - 2AM.</strong>
                </p>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6 col-xs-10 col-xs-offset-1 weekly-specials">
                <div id="specials-wrap" class="row">
                    <img src="{{ asset('images/branding/specials-logo0.png',env('APP_ENV') !== 'local') }}"
                         class="img-responsive center-block" alt="Smiley face" id="special-logo">
                    <p id="specials-description"> Weekly Specials</p>
                </div>
                <p id="specials-long-description">
                    Each week, SewaneeEats will have food specials from establishments that might be a little farther
                    from campus than our usuals!
                    For example, we'll deliver Chick-fil-a, Zaxby's, Chicken Itza, ...!
                    Every Monday morning, we will post what the weekly special restaurant is for the week! You can then
                    place your order for
                    that restaurant, and we'll send you an email to confirm.
                    Then, we will deliver your order Sunday at the BC or Tuesday at the BC if the restaurant is not open
                    on weekends.</p>
            </div>
        </div>

    </section>
    <script src="{{ asset('js/home_animations.js',env('APP_ENV') !== 'local') }}"></script>

@stop