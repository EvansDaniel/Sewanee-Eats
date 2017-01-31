<html>
<head>
    @include('global_config')
    <link rel="icon" href="{{ asset('images/mtneats.png') }}">
    <title> Mountain Eats </title>
    <link rel="stylesheet" type="text/css" href="home.css">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,900,900i" rel="stylesheet">

</head>
<body>

<nav class="navbar navbar-default navbar-custom navbar-fixed-top row">
    <div class="collapse navbar-collapse" id="navDiv">
        <ul id="navV" class="nav navbar-nav">
            <li><a class="active" href="#home">MOUNTAIN EATS</a></li>
            <li><a href="{{url ('food')}}">ORDER ONLINE</a></li>
            <li><a href="#about">ABOUT</a></li>
        </ul>
    </div>
</nav>

<section id="places" class="container">
    <ul>
        <li><a href="http://www.w3schools.com/html/">Papa Rons</a></li>
    </ul>
</section>


</body>
