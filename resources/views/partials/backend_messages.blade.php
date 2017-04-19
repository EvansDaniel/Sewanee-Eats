<style>
    .b-msg {
        z-index: 1000;
    }
</style>
<!-- Messages sent from back end -->
@if (session('status_good'))
    <div align="center" class="alert alert-success b-msg" id="backend-msg" style="background-color: #dff0d8;">
        <p style="color: rebeccapurple;">{!! session('status_good') !!}</p>
    </div>
@endif
@if (session('status_bad'))
    <div align="center" class="alert alert-danger b-msg" id="backend-msg">
        {{ session('status_bad') }}
    </div>
@endif
@if(session('became_unavailable'))
    <div align="center" class="alert alert-danger b-msg" id="backend-msg">
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
    <div align="center" class="alert alert-danger b-msg" id="backend-msg">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<!-- End of messages sent from back end -->