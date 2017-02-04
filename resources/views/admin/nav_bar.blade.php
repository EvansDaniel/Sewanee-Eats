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
            <li><a href="{{ route('adminListRestaurants') }}">VIEW RESTAURANTS</a></li>
            <li><a href="{{ route('showCreateRestaurantForm') }}">ADD NEW RESTAURANT</a></li>
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