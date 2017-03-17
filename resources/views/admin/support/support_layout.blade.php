<!-- Daniel Evans, Tariro Kandemiri, and Blaise Iradukunda -->

<html>
<head>
    @include('main.global_config')
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,900,900i" rel="stylesheet">
    @yield('head')
</head>
<body>
<!-- Built by Daniel Evans -->
<div id="main-container" class="container-fluid">

    @include('admin.support.support_nav_bar')
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
