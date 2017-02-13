<link rel="stylesheet" href="{{asset('css/nav_bar.css')}}">
<nav class="navbar navbar-default navbar-custom navbar-fixed-top row">
    <button type="button" id="collapse-button" class="navbar-toggle" data-toggle="collapse"
            data-target="#navDiv">
        <span class="sr-only">Toggle navigation</span>
        Menu
    </button>
    <div class="collapse navbar-collapse" id="navDiv">
        <ul id="nav" class="nav navbar-nav">
            <li><a class="active nav-links" href="{{ route('home') }}">MOUNTAIN EATS</a></li>
            <li><a href="{{ route('adminListRestaurants') }}">MANAGE RESTAURANTS INFO</a></li>
            <li><a class="nav-links" href="{{ route('adminShowSchedule') }}">SCHEDULE</a></li>
            @if(Auth::check() && Auth::user()->hasRole('courier'))
                <li><a class="nav-links" href="{{ route('showCourierDashboard') }}">YOUR COURIER DASHBOARD</a></li>
            @endif
            <li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    {{ csrf_field() }}
                    <a onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                        LOGOUT
                    </a>
                </form>
            </li>

        </ul>
    </div>
</nav>