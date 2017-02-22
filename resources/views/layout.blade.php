<!-- Built by Daniel Evans (evansdb0@sewanee.edu), Tariro Kandemiri, and Blaise Iradukunda (iradub0@sewanee.edu)-->

<html>
<head>
    @include('global_config')
    <link rel="icon" href="{{asset('images/mtneats.png')}}">
    <link rel="stylesheet" type="text/css" href=" {{ asset('css/home.css') }}">
    <link rel="stylesheet" type="text/css" href=" {{ asset('css/footer.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,900,900i" rel="stylesheet">
    @yield('head')
</head>
<body>
<div id="main-container" class="container-fluid">

    @include('countdown')
    @include('partials.nav_bar')
    <!-- Status messages to user about what they are doing -->
    @include('partials.backend_messages')
    @yield('body')
    <div id="push-div"></div>

</div>
<footer id="footer">
    <hr>
    <br>
    <p>COPYRIGHT (C) SEWANEE EATS</p>
    <br>
</footer>
</body>
</html>
