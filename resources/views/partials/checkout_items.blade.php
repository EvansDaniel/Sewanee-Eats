<div class="menu-item row" id="mid-{{ $order->getCartItemId() }}">

    <div class="col-lg-2 col-md-2 order-name"><p>{{ $order->getName() }}</p>
    </div>
    <div class="col-lg-2 col-md-2 order-price">
        $ {{ toTwoDecimals($order->getPrice()) }}</div>
    <div class="col-lg-2 col-md-2 order-descr">{{ $order->getDesc() }}</div>

    <input type="hidden" name="cart_item_id" value="{{ $order->getCartItemId() }}">
    <div class="col-lg-3 col-md-3 order-special">
        @if(empty($order->getSi()))
            <button type="button" class="checkout-btn"
                    onclick="showInstruction(this)">
                Add Special Instructions
            </button>
    @endif <!-- Make the div hidden or not -->
        <div style="display: {{ empty($order->getSi()) ? "none" : "block"}};">
            <label for="si-id" id="special-btn">Special
                instructions</label>
            <div class="container row">
                <textarea id="si-id-{{$order->getCartItemId()}}" class="si"
                          data-cart-item-id="{{$order->getCartItemId() }}"
                          name="special_instructions">
                    {{ $order->getSi() }}
                </textarea>
            </div>
            <input name="special_instructions" type="hidden"
                   value="{{ $order->getSi() }}">
        </div>
    </div>

    @if(!empty($order->itemExtras()))
        <div class="col-lg-2 col-md-2 order-extra">
            @if(empty($order->getExtras()) && !empty($order->itemExtras()))
                <button type="button"
                        onclick="showExtras(this)"
                        class="btn btn-primary show-extra">
                    Add extras
                </button>
            @endif
            <div class="row"
                 style="display: {{ empty($order->getExtras()) ? "none" : "block"}};">
                <label for="extras">Select items accessories</label>
            @foreach($order->itemExtras() as $acc)
                <!-- if the order has some extras and this acc has already been checked -->
                    @if(!empty($order->getExtras()) && in_array($acc->id,$order->getExtras()))
                        <div class="checkbox container">
                            <label for="acc">
                                <input id="acc-{{$order->getCartItemId()}}-{{$acc->id}}"
                                       name="extras{{$order->getCartItemId()}}[]"
                                       type="checkbox"
                                       data-cart-item-id="{{$order->getCartItemId()}}"
                                       checked class="acc-check"
                                       value="{{ $acc->id }}">
                                <span class="display-left">{{ $acc->name }}</span> - <span
                                        class="display-right">$ {{ toTwoDecimals($acc->price) }}</span>
                            </label>
                        </div>
                    @else <!-- acc has not been checked yet -->
                    <div class="checkbox container">
                        <label for="acc">
                            <input id="acc-{{$order->getCartItemId()}}-{{$acc->id}}"
                                   name="extras{{$order->getCartItemId()}}[]"
                                   type="checkbox"
                                   data-cart-item-id="{{$order->getCartItemId()}}"
                                   class="acc-check"
                                   value="{{ $acc->id }}">
                            <span class="display-left">{{ $acc->name }}</span> - <span
                                    class="display-right">$ {{ toTwoDecimals($acc->price) }}</span>
                        </label>
                    </div>
                    @endif
                @endforeach
            </div>

        </div>
    @endif
    <div class="col-lg-1 col-md-1 remove-btn">
        <button class="ckbtn btn btn-primary"
                id="dfc-{{ $order->getCartItemId() }}"
                data-cart-item-id="{{ $order->getCartItemId() }}"
                onclick="deleteItemFromCart(this)"
                type="button">X
        </button>
    </div>
</div>