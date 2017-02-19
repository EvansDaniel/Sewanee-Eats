var AJAX_DONE = false;
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
    //var check = $('#' + this.id);
    var check = $(this);
    check.on('change', function () {
      var url = API_URL + 'checkout/updateExtras/' +
      check.data('model-id') + '/' + check.data('index');
      var data = {accessory: parseInt(check.val())};
      doneEditingForm(url, data);
    });
  });
}();

// user is "finished typing," do something
function doneTyping(si) {
  var data = {special_instructions: si.val()};
  // Submit an ajax request and reload the data
  var url = API_URL + 'checkout/updateInstructions/'
  + si.data('model-id') + '/' + si.data('index');
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
        p('inside submit form');
        // TODO: fill in logic of validatePayForm()
        if (validatePayForm()) {
          $('#payment-form').submit();
        } else {
          // TODO: show error message
        }
      }
    }
  }).done(function (result) {

  });
}

function deleteItemFromCart(button) {
  var delButton = $('#' + button.id);
  var url = API_URL + 'checkout/deleteItem/' +
  delButton.data('model-id') + '/' + delButton.data('item-index');
  $.ajax({
    type: "POST",
    url: url,
    context: document.body,
    headers: {
      'X-CSRF-TOKEN': $('#x').attr('content')
    },
    error: function () {
      // TODO: error deleting item from the cart
    }
  }).done(function (res) {
    delButton.parent().parent().hide('slow', function () {
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

  // give a message to the user if cart empty and hide payment form???
  if (currentCount == 0) {
    $('#main-payment-form').hide('slow');
    setTimeout(function () {
      $('#main-container').append(
      '<div align="center"><h1>You don\'t have any items in your cart!</h1>' +
      '<a href="' + getBaseUrl() + '/restaurants">' +
      'Start your order here</a></div>'
      )
    }, 250);
  } else { // cart isn't empty
    // update the cost
    $('#subtotal').text(res.subtotal);
    $('#total-price').text(res.totalPrice);
  }
}

function validatePayForm() {
  return false;
}

function checkPayNow(event) {
  if (!AJAX_DONE) {
    SUBMIT_FORM = true;
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
}