<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- Bootstrap -->
    <link href="{{ asset('vendors/bootstrap/dist/css/bootstrap.min.css',env('APP_ENV') !== 'local')  }}"
          rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('vendors/font-awesome/css/font-awesome.min.css',env('APP_ENV') !== 'local')  }}"
          rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('vendors/nprogress/nprogress.css',env('APP_ENV') !== 'local')  }}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{ asset('vendors/iCheck/skins/flat/green.css',env('APP_ENV') !== 'local')  }}" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="{{ asset('vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css',env('APP_ENV') !== 'local')  }}"
          rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{ asset('vendors/jqvmap/dist/jqvmap.min.css',env('APP_ENV') !== 'local')  }}" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="{{ asset('vendors/bootstrap-daterangepicker/daterangepicker.css',env('APP_ENV') !== 'local')  }}"
          rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{ asset('css/admin/gentella_custom.min.css',env('APP_ENV') !== 'local')  }}" rel="stylesheet">
    <!-- jQuery -->
    <script src="{{ asset('vendors/jquery/dist/jquery.min.js',env('APP_ENV') !== 'local')  }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('vendors/bootstrap/dist/js/bootstrap.min.js',env('APP_ENV') !== 'local')  }}"></script>
    <link rel="stylesheet" href="{{ assetUrl('css/lib/lib.css') }}">
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
    </script>
    @yield('head')
</head>

<body class="nav-md" style="color: black; background: white;">
<!-- Status messages to admin about what they are doing -->
<div class="container body">
    <div class="main_container">
        <!-- Sidebar -->
    @include('employee.partials.sidebar')
    @include('employee.partials.nav_bar')
    @include('partials.backend_messages')
    <!-- /top tiles -->
        <div class="x_panel right_col" role="main" style="width: 83%; margin-left: 16%;">
            <div class="clearfix"></div>
            @yield('body')
        </div>
    </div>
</div>
@include('partials.dashboard_scripts')
</body>
</html>
