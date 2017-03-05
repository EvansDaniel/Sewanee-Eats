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
            var url = API_URL + 'checkout/updateExtras/' +
                check.data('model-id') + '/' + check.data('index');
            var data = {accessory: parseInt(check.val())};
            doneEditingForm(url, data);
        });
    });
    // Validation handlers
    var payInputs = $('.pay-input');
  /*payInputs.on('change', function () {
        var message = validPayForm(false);
        if (message != null) {
            $('#payment-errors').show().text(message);
   } else {
   $('#payment-errors').hide();
        }
    });
    payInputs.on('keyup', function () {
        var message = validPayForm(false);
        if (message != null) {
            $('#payment-errors').show().text(message);
   } else {
   $('#payment-errors').hide();
        }
   });*/
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
        p('called and finished doneEditing');
        // update price shown to user
        if (res != null && res.subtotal && res.totalPrice) {
            $('#subtotal').text(res.subtotal);
            $('#total-price').text(res.totalPrice);
        }
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
                '</div>'+
                '</div>'
            )
        }, 250);
        $('#checkout-link').hide();
      $('#cart-container').each(function () {
        $(this).hide(350);
      })
    } else { // cart isn't empty
        // update the cost
        if (res != null) { // server error otherwise
            $('#subtotal').text(res.subtotal);
            $('#total-price').text(res.totalPrice);
        }
    }
}

function validPayForm(focus) {
    var payFormError = $('#payment-form-error');
    var expMonth = $('#exp-month');
    var expYear = $('#exp-year');
    var cvc = $('#cvc');
    var cardNumber = $('#card-number');
    var location = $('#location');
    var phoneNumber = $('#phone-number');

    // Card validation start
    if (!Stripe.card.validateCardNumber(cardNumber.val())) {
        p('dsljfghsd here');
        if (focus) cardNumber.focus();
        return 'The card number provided is invalid';
    } else {
        payFormError.hide()
    }
    if (!Stripe.card.validateExpiry(expMonth.val(), expYear.val())) {
        if (focus) expMonth.focus();
        return 'The expiry fields are in correct. Make sure it is of the form MM/YYYY';
    } else {
        payFormError.hide()
    }
    if (!Stripe.card.validateCVC(cvc.val())) {
        if (focus) cvc.focus();
        return 'The CVC field is incorrect. Make sure it is a 3 or 4 digit number';
    } else {
        payFormError.hide()
    } // end of card validation

    if (location.val() == "") {
        payFormError.show().text('The location field is required');
        if (focus) location.focus();
        return 'The location field is required';
    } else {
        payFormError.hide()
    }
    if (phoneNumber.val() == "" ||
        phoneNumber.val().length != 10 ||
        isNaN(parseInt(phoneNumber.val()))) {
        if (focus) phoneNumber.focus();
        return 'The phone number field is required and should be a 10 number with the area code';
    } else {
        payFormError.hide()
    }
    return "Would be null here";
}

function checkPayNow(event) {
    if (!AJAX_DONE) {
        SUBMIT_FORM = true;
      p('in check pay now');
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
  p($($(button).parent().children()[1]));
    $($(button).parent().children()[1]).show();
    $('.cart-review').hide();
    $('.cart-line').each(function () {
        this.hide();
    });
}