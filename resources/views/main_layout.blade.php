<!-- Built by Daniel Evans (evansdb0@sewanee.edu), Tariro Kandemiri (kandeta0@sewanee.edu), and Blaise Iradukunda (iradub0@sewanee.edu)-->
<!DOCTYPE html>
<html>
<head>
    @include('global_config')
    <link rel="stylesheet" href={{ asset('images/branding/mtneats.png',env('APP_ENV') !== 'local') }}>
    <link rel="stylesheet" href={{ asset('css/footer.css',env('APP_ENV') !== 'local') }}>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
    @yield('head')
</head>
<body>
<style>
    body {
        background: white;
    }
</style>
<div id="main-container" class="container-fluid main-main-container">
@include('countdown')
@include('partials.main_nav_bar')
<!-- TODO: Blaise -> temporary fix for messages not showing and tops of pages not showing -->
    @if(url()->current() != route("home"))
        <script src={{ asset('js/resize.js',env('APP_ENV') !== 'local') }}></script>
        <div style="margin-top: 100px"></div>
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
                href="https://www.stripe.com">STRIPE</a>.</p>
    {{--<a href="{{ route('findMyOrder') }}">Find My Order</a>--}}
    <br>
</footer>
</body>
</html>