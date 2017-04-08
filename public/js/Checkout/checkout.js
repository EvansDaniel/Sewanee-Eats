var AJAX_DONE = true;
var SUBMIT_FORM = false;
// we need a timer for each text area so that
// we don't overwrite the timer in case the user
// quickly writes something in two different text areas
// timer identifier
var timers = [];
for (var i = 0; i < $('.si').length; i++) {
  timers[i] = null;
}
// time spent waiting after user is "done" typing before we save changes
var DONE_TYPING_INTERVAL = 500;

var docInit = function () {
  var sis = $('.si');
  // initialize events on text areas
  sis.each(function (i) {
    var si = $('#' + this.id);

    // on keyup, start the countdown
    si.on('keyup', function () {
      AJAX_DONE = false;

      clearTimeout(timers[i]);
      timers[i] = setTimeout(function () {
        return doneTyping(si);
      }, DONE_TYPING_INTERVAL);
    });

    // on keydown, clear the countdown
    si.on('keydown', function () {
      AJAX_DONE = false;
      clearTimeout(timers[i]);
    });
  });

  // Init events on checkboxes
  $('.acc-check').each(function () {
    // TODO: provide this same functionality when the user clicks on the entire label
    //var check = $('#' + this.id);
    var check = $(this);
    check.on('change', function () {
      var url = API_URL + 'cart/updateExtras/' +
      check.data('cart-item-id');
      var data = {accessory: parseInt(check.val())};
      doneEditingForm(url, data);
    });
  });
}();

// user is "finished typing," do something
function doneTyping(si) {
  var data = {special_instructions: si.val()};
  // Submit an ajax request and reload the data
  var url = API_URL + 'cart/updateInstructions/'
  + si.data('cart-item-id');
  doneEditingForm(url, data);
}

function doneEditingForm(url, data) {
  $.ajax({
    type: "POST",
    url: url,
    headers: {
      'X-CSRF-TOKEN': $('#x').attr('content')
    },
    data: data,
    context: document.body,
    error: function () {
      AJAX_DONE = true;
      // should i enable this though???
      // TODO: retry the ajax request
    },
    complete: function () {
      AJAX_DONE = true;
      if (SUBMIT_FORM) {
        // TODO: fill in logic of validatePayForm()
        // TODO: test pretend card numbers, for now leave this as message = null
        message = null;
        if (message == null) {
          $('#payment-form').submit();
        } else {
          $('#pay-now-button').prop('disabled', false);
          $('#payment-errors').show().text(message);
        }
      }
    }
  }).done(function (res) {
    updatePrices();
  });
}

function updatePrices() {
  $.ajax({
    url: API_URL + 'billing/priceSummary',
    type: "GET",
    context: document.body,
    dataType: "json"
  }).done(function (res) {
    $('#subtotal').text(res.subtotal);
    $('#total-price').text(res.total_price);
    $('#delivery-fee').text(res.delivery_fee);
    $('#cost-of-food').text(res.cost_of_food);
    $('#delivery-fee-percentage').text(res.discount);
  });
}


function deleteItemFromCart(button) {
  var delButton = $('#' + button.id);
  var url = API_URL + 'cart/deleteFromCart/' + delButton.data('cart-item-id');
  $.ajax({
    type: "POST",
    url: url,
    headers: {
      'X-CSRF-TOKEN': $('#x').attr('content')
    },
    error: function () {

    }
  }).done(function (res) {
    delButton.parent().parent().hide(350, function () {
      updateUIAfterDeleteItem(delButton, res);
    });
  });
}

function updateUIAfterDeleteItem(delButton, res) {
  delButton.remove();

  // update the counter
  var counter = $('#num-items-in-cart');
  var currentCount = parseInt(counter.text());
  counter.text(--currentCount);

  p('here')
  // give a message to the user if cart empty and hide payment form???
  if (currentCount == 0) {
    $('#main-payment-form').hide(350, function () {
      $(this).remove();
    });
    setTimeout(function () {
      $('#main-container').append(
      '<div id="cart-title" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
      '<div class="c-t-i">' +
      '<h1>You don\'t have any items in your cart!</h1>' +
      '</div>' +
      '<div class="row">' +
      '<a id="cart-order-again" href="/restaurants"">Start your order here</a>' +
      '</div>' +
      '</div>'
      )
    }, 250);
    $('#checkout-link').hide();
    $('#cart-container').each(function () {
      $(this).hide(350);
    })
  } else { // cart isn't empty
    p('her eeia am')
    // update the cost
    updatePrices();
  }
}

function checkPayNow(event) {
  if (!AJAX_DONE) {
    SUBMIT_FORM = true;
    // disable button til ajax request for special instructions/extras is done saving
    $('#pay-now-button').prop('disabled', true);
    event.preventDefault();
  }
}

function showInstruction(showSIbutton) {
  // hide the button the user pressed
  $(showSIbutton).hide();
  // show the special instruction text area
  $($(showSIbutton).parent().children()[1]).show();
}

function showExtras(button) {
  $(button).hide();
  $($(button).parent().children()[1]).show();
  $('.cart-review').hide();
  $('.cart-line').each(function () {
    $(this).hide();
  });
}

// need the restaurant locations
var address = $('#location');
var timeEstimation = new TimeEstimation(address.val(), '521 W Main St Monteagle, TN 37356');

address.on('keyup', function () {
  setDeliveryTime(address);
});

function setDeliveryTime() {
  timeEstimation.timeEstimation(address, function (res, status) {
    if (status == 'OK') {
      $('#on-demand-delivery-time').text(res);
    }
  });
}

// Create the search box and link it to the UI element.
var deliveryLocationInput = document.getElementById('location');
var searchBox = new google.maps.places.SearchBox(deliveryLocationInput);


// delivery location validation
var uLocDiv = $('#university-wrap');
var aLocDiv = $('#location-wrap');

$('input:radio').on('change', function () {
  var uLocInputs = uLocDiv.find('input');
  var aLocInputs = aLocDiv.find('input');
  p(uLocInputs);
  if (uLocDiv.is(':visible')) {
    setLocationInputsRequired(false, uLocInputs, aLocInputs);
  } else {
    setLocationInputsRequired(true, uLocInputs, aLocInputs);
  }
});

function setLocationInputsRequired(setALocsRequired, uLocInputs, aLocInputs) {
  var i = 0;
  for (i = 0; i < uLocInputs.length; i++) {
    p(!setALocsRequired);
    $(uLocInputs[i]).prop('required', !setALocsRequired);
    if (setALocsRequired) {
      $(uLocInputs[i]).val("");
    }
  }
  for (i = 0; i < aLocInputs.length; i++) {
    $(aLocInputs[i]).prop('required', setALocsRequired);
    if (!setALocsRequired) {
      $(aLocInputs[i]).val("");
    }
  }

}

$('.pay-input').on('mousedown', function () {
  $(this).focus();
});