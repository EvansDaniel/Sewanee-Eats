{{--
<link rel="stylesheet" href={{ asset('css/nav_bar.css',env('APP_ENV') != 'local') }}>
--}}
{{--<link rel="stylesheet" href="{{asset('css/nav_bar.css')}}">--}}{{--

<nav class="navbar navbar-default navbar-custom navbar-fixed-top row">
    <button type="button" id="collapse-button" class="navbar-toggle" data-toggle="collapse"
            data-target="#navDiv">
        <span class="sr-only">Toggle navigation</span>
        Menu
    </button>
    <div class="collapse navbar-collapse" id="navDiv">
        <ul id="navV" class="nav navbar-nav">
            <li><a class="active nav-links" href="{{ route('home') }}">MOUNTAIN EATS</a></li>
            <li><a class="nav-links" href="{{ route('courierShowSchedule') }}">SCHEDULE</a></li>
            <li><a class="nav-links" href="#">PAY PERIOD</a></li>
            @if(Auth::user()->isOnShift() && !Auth::user()->courierInfo->is_delivering_order)
                <li><a class="nav-links" href="{{ route('nextOrderInQueue') }}">Next order</a></li>
            @elseif(Auth::user()->isOnShift() && Auth::user()->courierInfo->is_delivering_order)
                <li><a class="nav-links" href="{{ route('currentOrder') }}">Current Order</a></li>
            @endif
            @if(!empty(Session::get('cart')))
                <li><a class="nav-links" href="{{ route('checkout') }}">CHECKOUT
                        <u>{{ count(Session::get('cart')) }}</u></a></li>
            @endif
            @if(Auth::user()->hasRole('admin'))
                <li><a class="nav-links" href="{{ route('showAdminDashboard') }}">ADMIN DASHBOARD</a></li>
            @endif
            <li>
                <form action="{{ url()->to(parse_url(route('logout',[]),PHP_URL_PATH),[],env('APP_ENV') !== 'local') }}"
                      method="post">
                    --}}
{{--<form id="logout-form" action="{{ route('logout') }}" method="POST">--}}{{--

                    {{ csrf_field() }}
                    <button type="submit">LOGOUT</button>
                </form>
            </li>
        </ul>
    </div>
</nav>--}}
<!-- top navigation -->
<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="false">
                        {{ Auth::user()->name }}
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li>
                            <form action="{{ formUrl('logout') }}"
                                  method="post">
                                {{ csrf_field() }}
                                <a>
                                    <button type="submit"><i class="fa fa-sign-out pull-right"></i> Log Out</button>
                                </a>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
<!-- /top navigation -->