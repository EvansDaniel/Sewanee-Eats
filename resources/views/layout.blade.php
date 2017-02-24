<!-- Built by Daniel Evans (evansdb0@sewanee.edu), Tariro Kandemiri, and Blaise Iradukunda (iradub0@sewanee.edu)-->

<html>
<head>
    @include('global_config')
    @if(env('APP_ENV') === 'local')
        <link rel="stylesheet" href={{ asset('images/branding/mtneats.png') }}>
    @else
        <link rel="stylesheet" href={{ secure_asset('images/branding/mtneats.png') }}>
    @endif
    @if(env('APP_ENV') === 'local')
        <link rel="stylesheet" href={{ asset('css/home.css') }}>
    @else
        <link rel="stylesheet" href={{ secure_asset('css/home.css') }}>
    @endif
    @if(env('APP_ENV') === 'local')
        <link rel="stylesheet" href={{ asset('css/footer.css') }}>
    @else
        <link rel="stylesheet" href={{ secure_asset('css/footer.css') }}>
    @endif
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
