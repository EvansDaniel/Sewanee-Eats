<link href="https://fonts.googleapis.com/css?family=Lato:900" rel="stylesheet">
<link rel="stylesheet" href={{ asset('css/carousel.css',env('APP_ENV') !== 'local')  }}>
<div class="carousel slide" id="home-carousel">
    <ol class="carousel-indicators">
        <li data-target="#home-carousel" data-slide-to="0" class="active"></li>
        <li data-target="#home-carousel" data-slide-to="1"></li>
        <li data-target="#home-carousel" data-slide-to="2"></li>

    </ol>
    {{-- add uses-main-image for any lighter image--}}
    <div class="carousel-inner">
        <div class="item active ">
            <img src="{{ asset('images/sushi2.jpg',env('APP_ENV') === 'production') }}" class="img-responsive hidden-sm hidden-xs"  alt="Food is the fuel for the body">
            <img src="{{ asset('images/sushi.jpg',env('APP_ENV') === 'production') }}" class="img-responsive hidden-md hidden-lg"  alt="Food is the fuel for the body">
            <div class="carousel-caption">
                <h3><span> SewaneeEats</span> brings <span id="carousel-restaurant">YAMOTO</span> Tuesday for dinner. Are you ready?</h3><br>
                <a href="{{ route('list_restaurants') }}" class="btn hidden-xs" id="btn">ORDER NOW</a>
            </div>
        </div>
        <div class="item dark-overlay">
            <img src="{{ asset('images/pie2.jpg',env('APP_ENV') === 'production') }}" class="img-responsive hidden-sm hidden-xs" alt="Food is the fuel for the body">
            <img src="{{ asset('images/pie.jpg',env('APP_ENV') === 'production') }}" class="img-responsive hidden-lg hidden-md" alt="Food is the fuel for the body">
            <div class="carousel-caption" id="car-charity">
                <h3><span> AΔΠ</span> presents <span id="carousel-restaurant">"Pie for Charity"</span> Tuesday.</h3>
                <p>Buy a pie to support AΔΠ fundraising towards Ronald McDonald house in chattanooga.</p>
                <a href="{{ route('list_restaurants') }}" class="btn hidden-xs" id="btn2">DONATE NOW</a>
            </div>
        </div>
        <div class="item dark-overlay" id="car-promo">
            <img src="{{ asset('images/homefront2.jpg',env('APP_ENV') === 'production') }}" class="img-responsive hidden-xs hidden-sm" alt="Food is the fuel for the body">
            <img src="{{ asset('images/homefront.jpg',env('APP_ENV') === 'production') }}" class="img-responsive hidden-lg hidden-md" alt="Food is the fuel for the body">
            <div class="carousel-caption">
                <h3>BUY MORE SAVE MORE</h3>
                <p>Get 20% off delivery fee on anything added to the cart after the first item - UP TO 4 ITEMS.</p>
            </div>
        </div>
    </div>
    <a class="carousel-control left" href="#home-carousel" data-slide="prev">
        <span class="icon-prev"></span>
    </a>
    <a class="carousel-control right" href="#home-carousel" data-slide="next">
        <span class="icon-next"></span>
    </a>


</div>
<div class="hidden-lg hidden-md hidden-sm" style="display: none" id="btn-div-smallscreen">
    <a href="{{ route('list_restaurants') }}" class="btn" id="btn3">ORDER NOW</a>
</div>
<script>
    function btnShow() {
        var btn = $('#btn-div-smallscreen');
        var wn = $(document).width();
        var charity = $('#car-charity').is(":visible");
        if(wn < 768){
            if(charity){
                p(charity + 'charity');
                $('#btn3').text('DONATE NOW');
            }
            else {
                p(charity + 'charity');
                $('#btn3').text('ORDER NOW');
            }
            btn.show();
        }
        else{
            btn.hide(0);
        }
    }
    var carsl = $('.carousel');
    $(document).ready(function () {
        p(carsl.carousel()) ;
        carsl.carousel({
            interval: 4000
        });
        btnShow();
        carsl.on('slid.bs.carousel', function () {
            btnShow();
        });
    });

</script>
