<!-- Built by Daniel Evans (evansdb0@sewanee.edu), Tariro Kandemiri, and Blaise Iradukunda -->

<head>
    <link rel="stylesheet" href="{{ asset('images/mtneats.png',env('APP_ENV') !== 'local') }}">
    <link rel="stylesheet" href="{{ asset('css/timer.css',env('APP_ENV') !== 'local') }}">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,900,900i" rel="stylesheet">
</head>

<div id="main-container" class="container-fluid timer-div">
    <!-- Status messages to user about what they are doing -->
    <br><br>
    <div id="header" style="display: none">
        <h1 id="s-eat-h">SewaneeEats <br class="hidden-lg hidden-md"><span>launches this Friday at 12PM!</span></h1>
    </div>
    <div id="clockdiv">
        <div id="daysDiv" style="display: none">
            <span id="days"></span>
            <div class="smalltext">Days</div>
        </div>
        <div id="hoursDiv" style="display: none">
            <span id="hours"></span>
            <div class="smalltext">Hours</div>
        </div>
        <div id="minDiv" style="display: none">
            <span id="minutes"></span>
            <div class="smalltext">Minutes</div>
        </div>
        <div id="secDiv" style="display: none">
            <span id="seconds"></span>
            <div class="smalltext">Seconds</div>
        </div>
    </div>

    <script>

      getTime(); // set the start time
      // cool animations
      p("ref = " + document.referrer);
      loadCountdownView(wasReferred());
      // Set the date we're counting down to
      var countDownDate = new Date("Mar 3, 2017 12:00:00").getTime();

      // Update the count down every 1 second
      var x = setInterval(function () {
        EXTRA += 1000;
        setTime();
      }, 1000);

      function getTime() {
        $.ajax({
          url: getBaseUrl() + '/time'
        }).done(function (result) {
          // Get todays date and time
          setStartTime(result);
          setTime();
        });
      }
      var START_TIME = 0;
      var EXTRA = 0;
      function setStartTime(result) {
        START_TIME = result
      }

      function wasReferred() {
        return !(document.referrer.indexOf("localhost") > -1 || document.referrer.indexOf("sewaneeeats.com") > -1)
      }

      function loadCountdownView(fadeIn) {
        p("fade in " + fadeIn);
        if (fadeIn) {
          $('#header').fadeIn(1000);
          setTimeout(function () {
            $('#daysDiv').fadeIn(1000);
          }, 125);
          setTimeout(function () {
            $('#hoursDiv').fadeIn(1000);
          }, 250);
          setTimeout(function () {
            $('#minDiv').fadeIn(1000);
          }, 375);
          setTimeout(function () {
            $('#secDiv').fadeIn(1000);
          }, 500);
        } else {
          $('#header').show();
          $('#daysDiv').fadeIn(500);
          $('#hoursDiv').fadeIn(500);
          $('#minDiv').fadeIn(500);
          $('#secDiv').fadeIn(500);
        }
      }

      function setTime() {

        var now = new Date(START_TIME).getTime() + EXTRA;
        // Find the distance between now an the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        $('#days').text(days);
        $('#hours').text(hours);
        $('#minutes').text(minutes);
        $('#seconds').text(seconds);

        // If the count down is finished, write some text
        if (distance < 0) {
          clearInterval(x);
          $('#days').text(0);
          $('#hours').text(0);
          $('#minutes').text(0);
          $('#seconds').text(0);
        }
      }
    </script>
</div>

