<link rel="stylesheet" href={{ asset('css/new_nav.css',env('APP_ENV') !== 'local')  }}>

<div>
    <div class="brand-wrap">

        <a class="navbar-logo" href="{{ route('home') }}"></a>
    </div>

    <nav class=" nav-upper">
        <div class="container-fluid">
            <ul class="nav navbar-upper">
                {{--TODO: add available drivers feature--}}
                {{--<li><a class="nav-links" href="#">AVAILABLE DRIVERS: </a></li>--}}
            </ul>
        </div>
    </nav>

    <nav class="navbar navbar-static-top navbar-lower">
        <div class="container-fluid">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false"><span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navbar">
                <ul class="nav navbar-nav navbar-right">
                    <li><a class="nav-links" href="{{ route('home') }}">HOME</a></li>
                    <li><a class="nav-links" href="{{ route('list_restaurants') }}">ORDER NOW</a></li>
                    <li><a class="nav-links" href="{{ route('howItWorks') }}">HOW IT WORKS</a></li>
                    <li><a class="nav-links" href="{{ route('pricing') }}">PRICING</a></li>
                    <li><a class="nav-links" href="{{ route('support') }}">CONTACT</a></li>
                    @if(!empty(Session::get('cart')))
                        <script>
                            // load number of items in the cart
                            $(function () {
                                $.ajax({
                                    url: API_URL + 'cart/quantity',
                                    context: document.body,
                                    dataType: 'json'
                                }).done(function (result) {
                                    $('#num-items-in-cart').text(result);
                                });
                            });
                        </script>
                        <li><a class="nav-links" id="checkout-link" href="{{ route('checkout') }}">CHECKOUT
                                <u><span id="num-items-in-cart"></span></u></a></li>
                    @endif
                <!-- User is logged in and an admin -->
                    @if(Auth::check() && Auth::user()->hasRole('admin'))
                        <li><a class="nav-links" href="{{ route('showAdminDashboard') }}">ADMIN DASHBOARD</a></li>
                    @endif
                <!-- User is logged in, a employee and not an admin -->
                    @if(Auth::check() && Auth::user()->hasRole('courier') && !Auth::user()->hasRole('admin'))
                        <li><a class="nav-links" href="{{ route('showCourierDashboard') }}">COURIER DASHBOARD</a></li>
                    @endif
                </ul>
            </div>
            <hr class="bottom-hr">
        </div>
    </nav>
</div>
