<!-- Tariro Kandemiri - 2017 -->

<html>
<head>
    @include('global_config')
    <link rel="icon" href="{{asset('images/mtneats.png')}}">
    <title> Mountain Eats </title>
    <link rel="stylesheet" type="text/css" href=" {{ asset('css/home.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,900,900i" rel="stylesheet">
</head>
<body>
@include('nav_bar')
@yield('body')
</body>
</html>
