<link rel="stylesheet" href="{{asset('css/nav_bar.css',env('APP_ENV') === 'production')}}">
<nav class="navbar navbar-default navbar-custom navbar-fixed-top row">
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
            <ul id="nav" class="nav navbar-nav navbar-right">
                <li><a class="active nav-links" href="{{ route('showAdminDashboard') }}">DASHBOARD</a></li>
                <li><a class="active nav-links" href="{{ route('orders') }}">ORDERS</a></li>
                <li><a href="{{ route('adminListRestaurants') }}">MANAGE RESTAURANTS INFO</a></li>
                <li><a class="nav-links" href="{{ route('adminShowSchedule') }}">SCHEDULE</a></li>
                @if(Auth::check() && Auth::user()->hasRole('employee'))
                    <li><a class="nav-links" href="{{ route('showCourierDashboard') }}">YOUR COURIER DASHBOARD</a></li>
                @endif
                <li id="logout-li">
                    <form action="{{ url()->to(parse_url(route('logout',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                          method="post" id="logout-form">
                        {{ csrf_field() }}
                        <a id="logout-a" class="nav-links" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                            LOGOUT
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>