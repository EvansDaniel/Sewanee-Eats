<!-- Daniel Evans, Tariro Kandemiri, and Blaise Iradukunda -->

<html>
<head>
    @include('global_config')
    <link rel="icon" href="{{ asset('images/mtneats.png',env('APP_ENV') !== 'local') }} ">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,900,900i" rel="stylesheet">
    @yield('head')
</head>
<body>
<!-- Built by Daniel Evans -->
<div id="main-container" class="container-fluid">
    @include('courier.nav_bar')
    <br><br><br><br><br>
    <!-- Status messages to user about what they are doing -->
    @include('partials.backend_messages')
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
