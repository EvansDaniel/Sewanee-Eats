<!-- Messages sent from back end -->
@if (session('status_good'))
    <div align="center" class="alert alert-success">
        {{ session('status_good') }}
        @if(session('user_added_item'))
            <br>
            <a href="{{ route('checkout') }}">
                <button class="btn btn-primary" style="background-color: rebeccapurple">Proceed to Checkout</button>
            </a>
        @endif
    </div>
@endif
@if (session('status_bad'))
    <div align="center" class="alert alert-danger">
        {{ session('status_bad') }}
    </div>
@endif
@if(session('became_unavailable'))
    <div align="center" class="alert alert-danger">
        The following items were remove from your cart because of the restaurant's closing or
        the menu item became unavailable
        <ul>
            @foreach (session('became_unavailable') as $item)
                <li>{{ $item->getName() }} from {{ $item->getSellerEntity()->name }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (count($errors) > 0)
    <div align="center" class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<!-- End of messages sent from back end -->