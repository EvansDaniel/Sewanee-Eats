<link rel="stylesheet" href={{ asset('css/home_static.css',env('APP_ENV') !== 'local')  }}>
<link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i,900,900i" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Vollkorn:400,700" rel="stylesheet">


<header class="home-header">
    <div class="row my-wrap0">
        <div class="container-fluid my-wrap">
            <div class="site-heading">
                <p class="subheading">Finish Finals Strong with a Delivery from Sewanee Eats!</p>
                <div class="container-fluid" id="btn-wrap">
                    <a href="{{ route('list_restaurants') }}"
                       class="col-lg-2 col-lg-offset-5 col-md-4 col-md-offset-4 col-sm-offset-4 col-sm-4 col-xs-6 col-xs-offset-3"
                       id="btn">
                        ORDER NOW
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
