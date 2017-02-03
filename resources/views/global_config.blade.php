{{-- JQuery --}}
<script src= {{ asset('js/app.js') }}></script>
{{-- Bootstrap --}}
<link rel="stylesheet" href= {{ asset('css/app.css') }}>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<script>
  // debugging helper function
  function p($obj) {
    console.log($obj);
  }
  $(document).ready(function () {
      var bd = $("body");
      var h = bd.height();
      p(h);
      h = h - $("#main-container").height();
      p(h);
      if( h >0) {
          $("#push-div").css("height", h);
      }

  })
</script>


{{--Angular JS Scripts --}}
<!--<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.1/jquery.validate.min.js"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular-resource.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
