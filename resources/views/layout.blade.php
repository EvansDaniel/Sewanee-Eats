<!-- Built by Daniel Evans (evansdb0@sewanee.edu), Tariro Kandemiri, and Blaise Iradukunda (iradub0@sewanee.edu)-->

<html>
<head>
    @include('global_config')
    <link rel="stylesheet" href={{ asset('images/branding/mtneats.png',env('APP_ENV') !== 'local') }}>
    <link rel="stylesheet" href={{ asset('css/home.css',env('APP_ENV') !== 'local')  }}>
    <link rel="stylesheet" href={{ asset('css/footer.css',env('APP_ENV') !== 'local') }}>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
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
