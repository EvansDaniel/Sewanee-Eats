<!-- Built by Daniel Evans (evansdb0@sewanee.edu), Tariro Kandemiri (kandeta0@sewanee.edu), and Blaise Iradukunda (iradub0@sewanee.edu)-->
<!DOCTYPE html>
<html>
<head>
    @include('main.global_config')
    <link rel="stylesheet" href={{ asset('css/footer.css',env('APP_ENV') !== 'local') }}>
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
{{--preloader animation stuff
  --if you don't need the preloader, use display none and change the big animation function on the home_animations.js
  --}}
{{--<div class="container prld">--}}
    {{--<div class="crl-1"></div>--}}
    {{--<div class="crl-2"></div>--}}
    {{--<div class="crl-3"></div>--}}
    {{--<div class="crl-4"></div>--}}

{{--</div>--}}
@include('main.main_nav_bar_new')
<div id="main-container" class="container-fluid main-main-container">
    {{--@include('main.countdown')--}}

    @if(url()->current() != route("home"))
        {{--<div style="margin-top: 35px"></div>--}}
        <script src={{ asset('js/resize.js',env('APP_ENV') !== 'local') }}></script>
    @endif

<!-- Status messages to user about what they are doing -->
    @include('partials.backend_messages')

    @yield('body')

    <div id="push-div"></div>
</div>
<footer id="footer">
    <hr>
    <br>
    <p>COPYRIGHT © SEWANEE EATS - ALL PAYMENTS ARE PROCESSED SECURELY THROUGH VENMO AND <a
                href="https://www.stripe.com">STRIPE</a>. | <a href="{{ route('login') }}">Login</a></p>
    {{--<h3><a style="float: right" href="{{ route('login') }}">Login</a></h3>--}}
    {{--<a href="{{ route('findMyOrder') }}">Find My Order</a>--}}
</footer>
</body>
</html>
