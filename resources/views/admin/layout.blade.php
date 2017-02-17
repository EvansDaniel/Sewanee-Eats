<!-- Daniel Evans, Tariro Kandemiri, and Blaise Iradukunda -->

<html>
<head>
    @include('global_config')
    <link rel="icon" href="{{asset('images/mtneats.png')}}">
    {{--<link rel="stylesheet" type="text/css" href=" {{ asset('css/home.css') }}">--}}
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,900,900i" rel="stylesheet">
    @yield('head')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
</head>
<body>
<!-- Built by Daniel Evans -->
<div id="main-container" class="container-fluid">

    @include('admin.nav_bar')
    <br><br><br>
    <!-- Status messages to admin about what they are doing -->
    @include('partials.backend_messages')
    @yield('body')
    <div id="push-div"></div>
    <footer id="footer" class="row" align="center">
        <p>COPYRIGHT (C) SEWANEE EATS</p>
    </footer>
</div>
</body>
</html>
