<!-- Daniel Evans, Tariro Kandemiri, and Blaise Iradukunda -->

<html>
<head>
    @include('global_config')
    <link rel="icon" href="{{asset('images/mtneats.png')}}">
    <link rel="stylesheet" type="text/css" href=" {{ asset('css/home.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,900,900i" rel="stylesheet">
    @yield('head')
    {{--<script type="text/javascript" src="https://js.stripe.com/v2/"></script>--}}
</head>
<body>
<div id="main-container" class="container-fluid">
    @include('partials.nav_bar')
    <!-- Status messages to user about what they are doing -->
        <br><br><br><br>
    @include('partials.backend_messages')
        <strong><h1>HELLO WORLD THIS IS ME</h1></strong>
    @yield('body')
    <div id="push-div"></div>
    <footer id="footer" class="col-xs-offset-1 col-md-offset-1 col-sm-offset-1 col-lg-offset-1">
        <br>
        <p>COPYRIGHT (C) SEWANEE EATS</p>
        <br>
    </footer>
</div>
</body>
</html>
