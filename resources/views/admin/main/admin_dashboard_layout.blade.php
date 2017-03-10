<!-- Daniel Evans, Tariro Kandemiri, and Blaise Iradukunda -->

<html>
<head>
    @include('main.global_config')
    <link rel="icon" href="{{asset('images/mtneats.png',env('APP_ENV') === 'production')}}">
    {{--<link rel="stylesheet" type="text/css" href=" {{ asset('css/home.css') }}">--}}
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,900,900i" rel="stylesheet">

    <!-- Charts.js and Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>
    @yield('head')
</head>
<body>
<!-- Built by Daniel Evans -->
<div id="main-container" class="container-fluid">

    @include('admin.main.admin_nav_bar')
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
