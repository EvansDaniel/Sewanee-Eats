<!-- Messages sent from back end -->
@if (session('status_good'))
    <div class="alert alert-success">
        {{ session('status_good') }}
    </div>
@endif
@if (session('status_bad'))
    <div class="alert alert-danger">
        {{ session('status_bad') }}
    </div>
@endif
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<!-- End of messages sent from back end -->