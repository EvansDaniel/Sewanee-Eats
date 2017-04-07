<link rel="stylesheet" href={{ asset('css/home_static.css',env('APP_ENV') !== 'local')  }}>
<link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i,900,900i" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Vollkorn:400,700" rel="stylesheet">


<header class="home-header">
    <div class="row my-wrap0">
        <div class="container-fluid my-wrap">
            <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-12 col-xs-12 col-sm-offset-0 col-xs-12">
                <div class="site-heading">
                    <p class="subheading"><span>Sewanee Eats and PMO</span><span id="lower_case_stuff"> brings a new Weekly Special</span></p>
                    <div class="container-fluid" id="btn-wrap">
                    <a href="{{ route('list_restaurants') }}" class="col-lg-2 col-lg-offset-5 col-md-3 col-sm-4 col-sm-offset-4 col-xs-6 col-xs-offset-3" id="btn">ORDER NOW</a>
                    {{--<a href="#" id="more" class="col-lg-2 col-md-3 col-sm-4 col-xs-4">Read More </a>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<style>
    #lower_case_stuff{
        text-transform: none;
    }
</style>