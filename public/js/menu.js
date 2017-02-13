function loadModal(div) {
    var name = $.trim($($(div).children().children()[1]).text());
    var price = $.trim($($(div).children().children()[2]).text());
    var description = $.trim($($(div).children()[1]).text());
    var item_id = $.trim($($(div).children()[2]).text());
    p(name);
    p(price);
    p(description);
    p(item_id); // for debugging

    // Set the divs to show item details to user
    $("#show-item-price").text("Price: " + price);
    $("#show-item-name").text(name);
    $("#show-item-description").text(description);

    // fill the hidden inputs to send to server
    $('#to-cart-item-id').val(item_id);
    $('#quantity').val(1);
}
function startAnim(i) {
    var str = "#item-extra" + i;
    // var anm_div =  document.getElementById(str);
    var anm_div = $(str);
    p(anm_div);
    // var bool =  anm_div.style.display == 'none';
    var bool = anm_div.is(":visible");
    p(bool);

    if(bool) {
        anm_div.hide(300);
    }
    else {
        anm_div.show(300);
    }


}

$(document).ready(function () {
    function getVal(item) {
        return item.val();
    }

    function setVal(item, num) {
        item.val(num);
    }

    function add_p(i) {
        var content = westside.html();
        var div = '<div class="west_div" id="west_div'+ i +' "> <div onclick="startAnim('+ i +')" id="item-extras-manipulation'+ i +'" class="row item-extras-manipulation"> <p class="add-extra" id="add-extra'+ i +'"> click to add toppings and extras on order number '+ i +'</p></div><div class="item-extra" style="display: none" id="item-extra'+ i +'"> <div class="checkbox row topings" id="topings'+ i +'"> <div class="row col-lg-3 free_toppings_d" id="free_toppings_d'+ i +'"> Free Toppings </div><div id="free_toppings'+ i +'" class="col-lg-8 free_toppings"> <div id="f_item '+ i +'" class="f-item row"> <label class="col-lg-9 col-md-9 col-sm-8 col-xs-8"> <input type="checkbox" value="">guac </label> </div><div id="f_item '+ i +'" class="f-item row"> <label class="col-lg-9 col-md-9 col-sm-8 col-xs-8"> <input type="checkbox" value="">guac </label> </div></div></div><div class="checkbox row o_topings" id="o_topings'+ i +'"> <div class="row col-lg-4 n_free_toppings_d" id="n_free_toppings_d'+ i +'"> Other($) </div><div id="n_free_toppings'+ i +'" class="n_free_toppings col-lg-8"> <div id="nf_item '+ i +'" class="nf-item row"> <label class="col-lg-9 col-md-9 col-sm-8 col-xs-8"> <input type="checkbox" value="">guac </label> <p class="f_price col-lg-3 col-md-3 col-sm-4 col-xs-4"> $ .45</p></div></div><p class="row col-lg-12 col-md-12 col-sm-12 col-xs-12 special_instr_p" id="special_instr_p'+ i +'"> Any special instructions? Make sure to write instructions for each item you purchase if you order more than one: </p><textarea class="form-control message-text message-text" name="special_instructions" placeholder="Any special instructions? Make sure to write instructions for each item you purchase if you order more than one" id="message-text'+ i +'"> </textarea> </div></div>';
        westside.html(content + div);
    }

    function remove_p(i) {
        var c =  document.getElementById("westside").childNodes;
        p(c.length)
        var west_rm = $(c[i]);
        p(west_rm);
        west_rm.remove();
    }

    var westside = $("#westside");
    var plus = $("#plus");
    var minus = $("#minus");
    var qty = $("#quantity");
    var qty_jvcrpt = document.getElementById("quantity");
    qty_jvcrpt.readOnly = true;
    qty_jvcrpt.min = 1;

    var i;
    setVal(qty, 1);
    i = getVal(qty);
    i = parseInt(i);
    p(i + "i" + getVal(qty) + "gty");
    add_p(i);

    plus.click(function () {
        i = getVal(qty);
        i = parseInt(i);
        i ++;
        setVal(qty, i);
        p("plus , i ->" + i);
        if(i > 1) {
            add_p(i);
        }


    });

    minus.click(function () {
        i = getVal(qty);
        i = parseInt(i);
        p(i);
        if (i <= 1) {
            setVal(qty, i);
        }
        else {
            if( i > 1){
                p("here");
                remove_p(i)
            }
            i--;
            setVal(qty, i);
        }


    });





});


/*$(document).ready(function () {

    var extra = $("#item-extra");
    var topings = $("#topings");
    var o_topings = $("#o-topings")
    var free_topings_items = $("#free_toppings");
    var non_free_topings_items = $("#n_free_toppings");
    var item_manip = $('#item-extras-manipulation');

//---------------new vars

    var west = $("#west_div");
    p(west + "west");
    var i=1;
    var qty = $("#quantity");
    var div = ' <div class="item-extra"  id="item-extra"> <div class="checkbox row" id="topings"> <div class="row col-lg-3" id="free_toppings_d"> Free Toppings </div><div id="free_toppings" class="col-lg-8"> <label><input type="checkbox" value=""> guac</label><br><label><input type="checkbox" value=""> guac</label><br><label><input type="checkbox" value=""> guac</label> </div></div><div class="checkbox row" id="o_topings"> <div class="row col-lg-4" id="n_free_toppings_d"> Other($) </div><div id="n_free_toppings" class="col-lg-8"> <label><input type="checkbox" value=""> guac</label> </div></div><p class="row" id="special_instr_p"> Any special instructions? Make sure to write instructions for each item you purchase if you order more than one: </p><textarea class="form-control" name="special_instructions'+ i + '" placeholder="Any special instructions? Make sure to write instructions for each item you purchase if you order more than one" id="message-text"> </textarea> </div>';

    var b_p = free_topings_items.is(":visible");
    var b_n = non_free_topings_items.is(":visible");
    var b = extra.is(":visible");

    //-------------------------code inject

    function inject_divs(west) {
        p("here");
        i = getVal(qty);
        i = parseInt(i);
        p(i + "i value");

        for (var j = 1 ; j<= i; j++){
            if(i >1) {
                var inj = getVal(west);
                p("inj -> \n" + inj);
                west.val(inj + div);
            }
            else{
                p("in else");
                west.append(div);
            }
            p(div);
        }
    }

    //--------------------


    item_manip.click(function () {
       inject_divs(west);
        if (!b) {
            extra.show(300);
            topings.show(300);
            o_topings.show(300);
            free_topings_items.hide(0);
            non_free_topings_items.hide(0)
            b = !b;
        }
        else {
            extra.hide(500);
            b = !b;
        }

        p("i=" + $("#quantity").val());
    });
    $("#n_free_toppings_d").click(function () {
        if (b_p) {
            non_free_topings_items.hide(300);
            b_p = !b_p;
        }
        else {
            non_free_topings_items.show(300);
            b_p = !b_p;
        }
    });

    $("#free_toppings_d").click(function () {
        if (b_n) {
            free_topings_items.hide(300);
            b_n = !b_n;
        }
        else {
            free_topings_items.show(300);
            b_n = !b_n;
        }
    });

    //new code
    // new code
    qty.val(i);
    p(i + "-")
    function getVal(item) {
        return item.val();
    }

    function setVal(item, num) {
        item.val(num);
    }

    $("#minus").click(function () {
        i = getVal(qty);
        i = parseInt(i);
        p(i);
        if (i <= 1) {
            setVal(qty, i);
        }
        else {
            i--;
            setVal(qty, i);
        }
    });

    $("#plus").click(function () {
        i = getVal(qty);
        i = parseInt(i);
        p(i);
        i++;
        setVal(qty, i);
    });
});


*/