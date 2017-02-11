{{-- JQuery --}}
<script src= {{ asset('js/app.js') }}></script>
<!-- JQuery UI -->
<script
        src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"
        integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw="
        crossorigin="anonymous"></script>
<!-- Jquery cookie plugin -->
<script src="{{ asset('js/lib/jquery-cookie/jquery.cookie.js') }}"></script>
{{-- Bootstrap --}}
<link rel="stylesheet" href= {{ asset('css/app.css') }}>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<script>
  function getBaseUrl() {
    var pathArray = location.href.split('/');
    var protocol = pathArray[0];
    var host = pathArray[2];
    return protocol + '//' + host;
  }
  API_URL = getBaseUrl() + "/api/v1/";
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

<meta name="viewport" content="width=device-width, initial-scale=1">
