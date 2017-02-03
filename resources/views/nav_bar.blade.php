<link rel="stylesheet" href="{{asset('css/nav_bar.css')}}">
<nav class="navbar navbar-default navbar-custom navbar-fixed-top row">
    <button type="button" id="collapse-button" class="navbar-toggle" data-toggle="collapse"
            data-target="#navDiv">
        <span class="sr-only">Toggle navigation</span>
        Menu </button>
    <div class="collapse navbar-collapse" id="navDiv">

        <ul id="navV" class="nav navbar-nav">
            <li><a class="active" href="{{ route('home') }}">MOUNTAIN EATS</a></li>
            <li><a href="{{ route('list_restaurants') }}">ORDER ONLINE</a></li>
            <li><a href="{{ route('about') }}">ABOUT</a></li>
            <li><a href="{{ route('showShoppingCart') }}">CART</a></li>
            @if(!empty(Session::get('cart')))
                <li><a href="{{ route('checkout') }}">CHECKOUT</a></li>
            @endif
        </ul>
    </div>
</nav>x