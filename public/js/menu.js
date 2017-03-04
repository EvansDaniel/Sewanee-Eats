var accessories = null;
var itemPrice = -1;
var ADDED_TO_CART = false;
p($('#num-items-in-cart').text());
var CART_QUANTITY = 0;
getCartQuantity();

function saveAjaxResult(result) {
  accessories = result;
}

function getCartQuantity() {
  $.ajax({
    url: API_URL + 'cart/totalQuantity',
    context: document.body,
    dataType: 'json'
  }).done(function (result) {
    saveCartQuantity(result.num_items);
  });
}

function saveCartQuantity(num_items) {
  CART_QUANTITY = num_items;
}

function showOptions(i) {

  var ANIMATION_TIME = 300;
  var item_extra_id = "#item-extra" + i;
  var anm_div = $(item_extra_id);
  var bool = anm_div.is(":visible");

  var pricyDiv = $('#n_free_toppings' + i);
  var freeDiv = $('#free_toppings' + i);
  if (bool) {
    anm_div.hide(ANIMATION_TIME);
    pricyDiv.empty();
    freeDiv.empty();
  }
  else {
    var q = $('#quantity').val();
    // HIDE ALL OTHER OPEN ITEM EXTRAS, and empty them
    for (var k = 1; k <= q; k++) {
      $('#item-extra' + k).hide(ANIMATION_TIME);
      $('#n_free_toppings' + k).empty();
      $('#free_toppings' + k).empty();
    }

    anm_div.show(ANIMATION_TIME);
    // list out accessories
    var pricy = accessories.accs.pricy;
    var free = accessories.accs.free;

    // fill the pricy div
    // only show the pricy toppings header if there are pricy toppings to show
    if (pricy.length == 0) {
      $('#n_free_toppings_d' + i).hide();
    }
    for (var j = 0; j < pricy.length; j++) {
      pricyDiv.append
      (
      '<label class="col-lg-9 col-md-9 col-sm-8 col-xs-8">' +
      '<input type="checkbox" id="p_ex-' + i + pricy[j].id + '" onclick="saveCheck(this,' + i + ',' + pricy[j].id + ',true)" value="' + pricy[j].id + '">' +
      pricy[j].name + '</label><p id="check-price-' + i + '" class="f_price col-lg-3 col-md-3 col-sm-4 col-xs-4">' + pricy[j].price + '</p>'
      );
    }

    // fill the free div
    // only show the free toppings header if there are free toppings to show
    if (free.length == 0) {
      $('#free_toppings_d' + i).hide();
    }
    for (j = 0; j < free.length; j++) {
      freeDiv.html
      (
      '<label class="col-lg-9 col-md-9 col-sm-8 col-xs-8">' +
      '<input type="checkbox" id="f_ex-' + i + free[j].id + '" onclick="saveCheck(this,' + i + ',' + free[j].id + ',false)" name="extras' + i + '[]" value="' + free[j].id + '">' +
      'free[j].name </label>'
      );
    }
  }

  // load up any stored info about checkboxes or special instructions
  // if there is any
  loadCheckBoxAndSIInfo();
}

function loadCheckBoxAndSIInfo() {
  var extrasInputs = $('#extras-inputs').children();
  var si = $('#special-instructions-inputs').children();
  // load info from hidden extras inputs
  extrasInputs.each(function () {
    var otherId = getOtherId(this.id);
    $('#' + otherId).prop('checked', true);
  });
  // load info from hidden special instruction inputs
  si.each(function () {
    var otherId = getOtherId(this.id);
    $('#' + otherId).val($(this).val());
  });
}

// reverse the given unique id to get the other element's id
function getOtherId(id) {
  return id.split("").reverse().join("");
}

function saveSpecialInstructions(textarea, i) {
  var special_instructions_div = $('#special-instructions-inputs');
  var otherId = getOtherId(textarea.id);
  var input = $('#' + otherId);
  var text = $(textarea).val();
  if (!input.length) { //make the input
    special_instructions_div.append(
    '<input name="special_instructions' + i + '" value="' + text + '" id="' + otherId + '" data-instructions-value="' + i + '" type="hidden">'
    );
  } else { // adjust the text
    input.val(text);
  }
}

function saveCheck(checkbox_input, i, acc_id, pricy) {
  var priceObj = $('#show-item-price');
  var checkbox = $(checkbox_input);
  // either finds and returns the input with the acc_id or returns null
  var otherId = getOtherId(checkbox_input.id);
  var input = $('#' + otherId);
  // get current price and price of toppings
  var price = parseFloat(priceObj.text());
  var checkPrice = parseFloat($('#check-price-' + i).text());

  if (checkbox.prop('checked')) { // checkbox was originally not checked
    if (pricy) {
      // add price of extra
      priceObj.text(Math.round((price + checkPrice) * 100) / 100);
    }
    $('#extras-inputs').append
    (
    '<input type="hidden" id="' + otherId + '" name="extras' + i + '[]" value="' + acc_id + '">'
    );
  } else {
    // checkbox was originally checked
    // set the value to the negative, this WILL be unique to this input
    // and we can than check each inputs abs(acc_id) in getInput func to find this input
    // all the server needs to do is discard any negative acc_id b/c they are not wanted by user
    input.remove();
    if (pricy) {
      // subtract price of extra
      priceObj.text(Math.round((price - checkPrice) * 100) / 100);
    }
  }
}

function loadModal(div) {
  var name = $.trim($($(div).children().children()[0]).text());
  var price = $.trim($($(div).children().children()[2]).text());
  var description = $.trim($($(div).children()[2]).text());
  var item_id = $.trim($($(div).children()[3]).text());
  // p(name); p(price); p(description); p(item_id); // for debugging

  // Set the divs to show item details to user
  itemPrice = parseFloat(price);
  $("#show-item-price").text(price);
  $("#show-item-name").text(name);
  $("#show-item-description").text(description);

  // fill the hidden inputs to send to server
  $('#to-cart-item-id').val(item_id);
  $('#quantity').val(1);

  // ajax request to get the current menu item's accessories
  // p(API_URL + "menuItems/" + item_id + "/freeAndPricyAccessories"); // for debugging
  retreiveAccessories(item_id);
}

function retreiveAccessories(item_id) {
  $.ajax({
    url: API_URL + "menuItems/" + item_id + "/freeAndPricyAccessories",
    context: document.body,
    dataType: 'json'
  }).done(function (result) {
    // AJAX SUCCESS
    // save the current item's accessories
    saveAjaxResult(result);
  });
}

function retFalse() {
  return false;
}

function disableClicks(class_id) {
  var obj = $(class_id);
  obj.each(function () {
    $(this).on('click', retFalse);
  })
}

// Doc Ready ---------------------------------------------------------------------------------------------
$(function () {
  var westside = $("#westside");
  var plus = $("#plus");
  var minus = $("#minus");
  var qty = $("#quantity");
  var MIN_ITEMS = 1, MAX_ITEMS = 10;
  docReadyInit();

  function docReadyInit() {
    initPopUpView();
    var error_msg = $('#max-items-exceeded-error');
    var addToCartButton = $('#add-to-cart-button');
    if (CART_QUANTITY == MAX_ITEMS) {
      addToCartButton.attr('disabled', true);
      error_msg.show();
    }

    addToCartButton.on('click', function () {
      // send message to popstate
      ADDED_TO_CART = true;
    });

    // load text for a potential error message to user about the max items in the cart
    error_msg.text('The max allowable items in the cart is ' + MAX_ITEMS + ". You have " + CART_QUANTITY + " items right now");


    // disable/lock click events on each menu item until
    // page completely loads. This is so ajax will fire properly
    // TODO: Is there any way to make this better
    disableClicks('.menu-li');
  }

  $('#add-to-cart-modal').on('hidden.bs.modal', function () {
    var q = $('#quantity').val();
    for (var i = 1; i <= q; i++) {
      $('#item-extra' + i).hide();
    }
    resetModal();
  });

  function resetModal() {
    // destroy any extra inputs that were added by user
    $('#extras-inputs').empty();
    $('#special-instructions-inputs').empty();

    var i = 0;
    $('.west_div').each(function () {
      if (i != 0) { // skip the first button b/c we always want the first one in the modal
        $(this).remove();
      }
      i++;
    });
    $('#max-items-exceeded-error').hide();
    // reset quantity of items to 1
    setVal(qty, 1);
  }

  function initPopUpView() {
    // INIT the view
    var i;
    setVal(qty, 1);
    i = getVal(qty);
    i = parseInt(i);
    addAnotherOrderButton(i);
    // make the quantity input read only
    $('#quantity').prop('readonly', true);
    /*if (DISABLE_ADD_BUTTON) { // this would be defined in showMenu.blade.php by a php script
     $('#add-to-cart-button').prop('disabled', true);
     }*/
  }


  plus.click(function () {
    var i = getVal(qty);
    i = parseInt(i);
    if (i >= MIN_ITEMS && (CART_QUANTITY + i) < MAX_ITEMS) {
      p(CART_QUANTITY);
      ++i;
      setVal(qty, i); // set value of the new quantity
      $('#max-items-exceeded-error').hide();
      addAnotherOrderButton(i);
    } else {
      // show the span
      $('#max-items-exceeded-error').show();
    }
    // maintain the current checkbox/textarea info during quantity changes
    loadCheckBoxAndSIInfo();
  });

  function getVal(item) {
    return item.val();
  }

  function setVal(item, num) {
    item.val(num);
  }

  function addAnotherOrderButton(i) {
    var content = westside.html();
    var htmlArray = [
      '<div class="west_div" id="west_div' + i + ' ">',
      '<div onclick="showOptions(' + i + ')" id="item-extras-manipulation' + i + '" class="row item-extras-manipulation">',
      '<p class="add-extra" id="add-extra' + i + '"> Customize order number ' + i + '</p>',
      '</div>',
      '<div class="item-extra" style="display: none" id="item-extra' + i + '">',
      '<div class="checkbox row topings" id="topings' + i + '">',
      '<div class="row col-lg-4 col-md-4 col-sm-12 col-xs-12 free_toppings_d" id="free_toppings_d' + i + '"> Free Extras </div>',
      '<div id="free_toppings' + i + '" class="col-lg-8 col-md-8 col-sm-12 col-xs-12 free_toppings">',

      '</div>',
      '</div>',
      '<div class="checkbox row o_topings" id="o_topings' + i + '">',
      '<div class="row col-lg-4 col-md-4 col-sm-12 col-xs-12  n_free_toppings_d" id="n_free_toppings_d' + i + '"> Non-free Extras ($) </div>',
      '<div id="n_free_toppings' + i + '" class="n_free_toppings col-lg-8 col-md-8 col-sm-12 col-xs-12 ">',

      '<div id="nf_item ' + i + '" class="nf-item row">',
      '</div>',
      '</div>',
      '</div>',
      '<div>',
      '<p class="row col-lg-12 col-md-12 col-sm-12 col-xs-12 special_instr_p" id="special_instr_p' + i + '"> Any special instructions? Make sure to write instructions for each item you purchase if you order more than one: </p>',
      '<textarea id="spe-' + i + '" class="form-control message-text message-text" onkeyup="saveSpecialInstructions(this,' + i + ')" ' +
      'placeholder="Any special instructions? Make sure to write instructions for each item you purchase if you order more than one" id="message-text' + i + '"> </textarea>',

      '</div>',
      '</div>'
    ];
    westside.html(content + htmlArray.join(""));
    // add the price of the item b/c they want another one
    updateTotalForItem('+');
  }

  // str must be either '-' or '+' to subtract/add to the total
  // otherwise this function does nothing
  function updateTotalForItem(str) {
    var price = $('#show-item-price');
    //p(itemPrice);
    //p(parseFloat(price.text()) + itemPrice);
    if (price.text()) {
      if (str === '-') {
        price.text(Math.round((parseFloat(price.text()) - itemPrice) * 100) / 100);
      } else if (str === '+') {
        price.text(Math.round((parseFloat(price.text()) + itemPrice) * 100) / 100);
      }
    }
  }

  function removeAnotherButton(i) {
    var c = document.getElementById("westside").childNodes;
    var west_rm = c[i];
    west_rm.remove();
    // subtract the price of the item b/c they got rid of one
    updateTotalForItem('-');
  }

  minus.click(function () {
    var i = getVal(qty);
    if ((parseInt(CART_QUANTITY) + parseInt(i)) == MAX_ITEMS) { // hide b/c we are subtracting one when it is at max items
      $('#max-items-exceeded-error').hide();
    }
    i = parseInt(i);
    if (i <= 1) {
      setVal(qty, i);
    }
    else {
      if (i > 1) {
        removeAnotherButton(i);
        i--;
        setVal(qty, i);
      }
    }
    // maintain the current checkbox/textarea info during quantity changes
    loadCheckBoxAndSIInfo();
  });


  // KEEP THIS AT THE BOTTOM OF DOCUMENT ON READY
  // THIS CODE SHOULD RUN LAST
  $(document).on('click', '.menu-li', function () {
    loadModal($(this).find('.menu-item'));
  });
  $('.menu-li').each(function () {
    $(this).attr('data-toggle', 'modal');
    $(this).attr('data-target', '#add-to-cart-modal');
    // enable clicking
    $(this).off('click', retFalse);
  });

}); // end document ready