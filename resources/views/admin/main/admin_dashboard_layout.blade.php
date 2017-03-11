<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gentelella Alela! | </title>

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
</head>

<body class="nav-md">

@include('admin.main.admin_nav_bar')
<br><br><br>
<!-- Status messages to admin about what they are doing -->
@include('partials.backend_messages')
<div class="container body">
    <div class="main_container">
        <!-- Sidebar -->
    @include('admin.partials.admin_sidebar')
    <!-- /top tiles -->
        <div class="container" style="margin-left: 20%">
            @yield('body')

        </div>
    </div>
</div>
<!-- jQuery -->
<script src="{{ asset('vendors/jquery/dist/jquery.min.js',env('APP_ENV') !== 'local')  }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('vendors/bootstrap/dist/js/bootstrap.min.js',env('APP_ENV') !== 'local')  }}"></script>
<!-- FastClick -->
<script src="{{ asset('vendors/fastclick/lib/fastclick.js',env('APP_ENV') !== 'local')  }}"></script>
<!-- NProgress -->
<script src="{{ asset('vendors/nprogress/nprogress.js',env('APP_ENV') !== 'local')  }}"></script>
<!-- Chart.js -->
<script src="{{ asset('vendors/Chart.js/dist/Chart.min.js',env('APP_ENV') !== 'local')  }}"></script>
<!-- gauge.js -->
<script src="{{ asset('vendors/gauge.js/dist/gauge.min.js',env('APP_ENV') !== 'local')  }}"></script>
<!-- bootstrap-progressbar -->
<script src="{{ asset('vendors/bootstrap-progressbar/bootstrap-progressbar.min.js',env('APP_ENV') !== 'local')  }}"></script>
<!-- iCheck -->
<script src="{{ asset('vendors/iCheck/icheck.min.js',env('APP_ENV') !== 'local')  }}"></script>
<!-- Skycons -->
<script src="{{ asset('vendors/skycons/skycons.js',env('APP_ENV') !== 'local')  }}"></script>
<!-- Flot -->
<script src="{{ asset('vendors/Flot/jquery.flot.js',env('APP_ENV') !== 'local')  }}"></script>
<script src="{{ asset('vendors/Flot/jquery.flot.pie.js',env('APP_ENV') !== 'local')  }}"></script>
<script src="{{ asset('vendors/Flot/jquery.flot.time.js',env('APP_ENV') !== 'local')  }}"></script>
<script src="{{ asset('vendors/Flot/jquery.flot.stack.js',env('APP_ENV') !== 'local') }}"></script>
<script src="{{ asset('vendors/Flot/jquery.flot.resize.js',env('APP_ENV') !== 'local')  }}"></script>
<!-- Flot plugins -->
<script src="{{ asset('vendors/flot.orderbars/js/jquery.flot.orderBars.js',env('APP_ENV') !== 'local') }}"></script>
<script src="{{ asset('vendors/flot-spline/js/jquery.flot.spline.min.js',env('APP_ENV') !== 'local') }}"></script>
<script src="{{ asset('vendors/flot.curvedlines/curvedLines.js',env('APP_ENV') !== 'local')  }}"></script>
<!-- DateJS -->
<script src="{{ asset('vendors/DateJS/build/date.js',env('APP_ENV') !== 'local')  }}"></script>
<!-- JQVMap -->
<script src="{{ asset('vendors/jqvmap/dist/jquery.vmap.js',env('APP_ENV') !== 'local')  }}"></script>
<script src="{{ asset('vendors/jqvmap/dist/maps/jquery.vmap.world.js',env('APP_ENV') !== 'local')  }}"></script>
<script src="{{ asset('vendors/jqvmap/examples/js/jquery.vmap.sampledata.js',env('APP_ENV') !== 'local')  }}"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{ asset('vendors/moment/min/moment.min.js',env('APP_ENV') !== 'local')  }}"></script>
<script src="{{ asset('vendors/bootstrap-daterangepicker/daterangepicker.js',env('APP_ENV') !== 'local')  }}"></script>

<!-- Custom Theme Scripts -->
<script src="{{ asset('js/admin/gentella_custom.min.js',env('APP_ENV') !== 'local')  }}"></script>
</body>
</html>
