<link rel="stylesheet" href={{ asset('css/nav_bar.css',env('APP_ENV') !== 'local')  }}>

<nav class="navbar navbar-default navbar-custom navbar-fixed-top row" id="eats-navbar">
    <div class="container-fluid nav-container">
        <div class="navbar-header">
            <a class="navbar-brand col-lg-12 col-md-12 col-sm-6 col-xs-6" href="{{ route('home') }}">
                <img src="{{ asset('images/branding/mountain_logo.jpg',env('APP_ENV') !== 'local')  }}"
                     id="brand_img" class="img-responsive" alt="">
            </a>
            <button type="button" id="collapse-button" class="navbar-toggle"
                    data-toggle="collapse"
                    data-target="#navDiv">
                <span class="sr-only">Toggle navigation</span>
                Menu
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navDiv">
            <ul id="navV" class="nav navbar-nav navbar-right">
                {{--<li id="nav-divider" class="hidden-sm hidden-xs"></li>--}}
                <li><a class="nav-links" href="{{ route('list_restaurants') }}">ORDER NOW</a></li>
                <li><a class="nav-links" href="{{ route('howItWorks') }}">HOW IT WORKS</a></li>
                <li><a class="nav-links" href="{{ route('pricing') }}">PRICING</a></li>
                <li><a class="nav-links" href="{{ route('support') }}">CONTACT</a></li>
                @if(!empty(Session::get('cart')))
                    <script>
                        // load number of items in the cart
                        $(function () {
                            $.ajax({
                                url: API_URL + 'cart/totalQuantity',
                                context: document.body,
                                dataType: 'json'
                            }).done(function (result) {
                                $('#num-items-in-cart').text(result.num_items);
                            });
                        });
                    </script>
                    <li><a class="nav-links" id="checkout-link" href="{{ route('checkout') }}">CHECKOUT
                            <u><span id="num-items-in-cart"></span></u></a></li>
                @endif
                @if(Auth::check() && Auth::user()->hasRole('admin'))
                    <li><a class="nav-links" href="{{ route('showAdminDashboard') }}">ADMIN DASHBOARD</a></li>
                @endif
                @if(Auth::check() && Auth::user()->hasRole('courier'))
                    <li><a class="nav-links" href="{{ route('showCourierDashboard') }}">COURIER DASHBOARD</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>

