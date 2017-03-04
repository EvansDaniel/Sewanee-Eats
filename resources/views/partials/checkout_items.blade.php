<div class="menu-item row" id="mid-{{$order['menu_item_model']->id}}">

    <div class="col-lg-2 col-md-2 order-name"><p>{{ $order['menu_item_model']->name }}</p>
    </div>
    <div class="col-lg-2 col-md-2 order-price">
        $ {{ $order['menu_item_model']->price }}</div>
    <div class="col-lg-2 col-md-2 order-descr">{{ $order['menu_item_model']->description }}</div>

    <input type="hidden" name="cart_item_id" value="{{ $order['menu_item_model']->id }}">
    <div class="col-lg-3 col-md-3 order-special">
        @if(empty($order['special_instructions'][$i]))
            <button type="button" class="checkout-btn"
                    onclick="showInstruction(this)">
                Add Special Instructions
            </button>
    @endif <!-- Make the div hidden or not -->
        <div style="display: {{ empty($order['special_instructions'][$i]) ? "none" : "block"}};">
            <label for="si-id" id="special-btn">Special
                instructions</label>
            <div class="container row">
                                               <textarea id="si-id-{{$order['menu_item_model']->id}}-{{$i}}"
                                                         class="si"
                                                         data-model-id="{{$order['menu_item_model']->id}}"
                                                         data-index="{{$i}}"
                                                         name="special_instructions">{{ $order['special_instructions'][$i] }}</textarea>
            </div>
            <input name="special_instructions" type="hidden"
                   value="{{ $order['special_instructions'][$i] }}">
        </div>
    </div>


    <div class="col-lg-2 col-md-2">
        @if(empty($order['extras'][$i]) && !(empty($order['menu_item_model']->accessories[0])))
            <button type="button"
                    onclick="showExtras(this)"
                    class="btn btn-primary show-extra">
                Add extras
            </button>
        @endif
        <div class="row"
             style="display: {{ empty($order['extras'][$i]) ? "none" : "block"}};">
            <label for="extras">Select items accessories</label>
            @foreach($order['menu_item_model']->accessories as $acc)
                <div class="checkbox">
                    <label for="acc">
                        @if(!(empty($order['extras'][$i])) && in_array($acc->id,$order['extras'][$i]))
                            <input id="acc-{{$i}}-{{$acc->id}}"
                                   name="extras{{$i}}[]"
                                   type="checkbox"
                                   data-model-id="{{$order['menu_item_model']->id}}"
                                   data-index="{{$i}}"
                                   checked class="acc-check"
                                   value="{{ $acc->id }}">
                            {{ $acc->name . "  $" . $acc->price }}
                        @else
                            <input id="acc-{{$i}}-{{$acc->id}}"
                                   name="extras{{$i}}[]"
                                   type="checkbox"
                                   class="acc-check"
                                   data-model-id="{{$order['menu_item_model']->id}}"
                                   data-index="{{$i}}"
                                   value="{{ $acc->id }}">
                            {{ $acc->name . "  $" . $acc->price }}
                        @endif
                    </label>
                </div>
            @endforeach
        </div>

    </div>
    <div class="col-lg-1 col-md-1">
        <button class="ckbtn btn btn-primary"
                id="dfc-{{ $order['menu_item_model']->id }}-{{ $i }}"
                data-model-id="{{ $order['menu_item_model']->id }}"
                data-item-index="{{ $i }}"
                onclick="deleteItemFromCart(this)"
                type="button">X
        </button>
    </div>
</div>