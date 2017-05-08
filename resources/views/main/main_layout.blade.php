<!-- Built by Daniel Evans (evansdb0@sewanee.edu)and Blaise Iradukunda (iradub0@sewanee.edu)-->
<!DOCTYPE html>
<html>
<head>
    @include('main.global_config')
    @if(strpos(url()->current(),"restaurants") !== false) <!-- for menu or list restaurants page -->
        <link rel="shortcut icon" href="{{ assetUrl('images/branding/purple_truck_title_logo.ico') }}"/>
        @else
            <link rel="shortcut icon" href="{{ assetUrl('images/branding/brand_title_logo.ico') }}"/>
        @endif
        <link rel="stylesheet" href={{ assetUrl('css/footer.css') }}>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i"
          rel="stylesheet">
    <style>
        body {
            background: white;
        }
    </style>
    @yield('head')
</head>
<body>
@include('main.main_nav_bar_new')
@include('partials.backend_messages')
<div id="main-container" class="container-fluid main-main-container">

    @if(url()->current() != route("home"))
        <script src={{ asset('js/resize.js',env('APP_ENV') !== 'local') }}></script>
    @endif

    @yield('body')

    <div id="push-div"></div>
</div>
<footer id="footer">
    <hr>
    <br>
    <p>COPYRIGHT © SEWANEE EATS - ALL PAYMENTS ARE PROCESSED SECURELY THROUGH VENMO AND <a
                href="https://www.stripe.com">STRIPE</a>.
        | <a href="{{ route('login') }}">Login</a>
    </p>
</footer>
</body>
</html>
