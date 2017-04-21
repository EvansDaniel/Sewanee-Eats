// scrolls to the item given by the generateScrollTo php view function
scrollToItem(1000);
// amount of time before the backend message disappears
msgTimeout(7500);

changeAllStatusColor();

p('here ia m')

// window checks for manipulating orders
$(document).ready(function () {
  $(".confirm-payment").click(changeStatus(".confirm-payment", "Are you sure you want to change this order's payment status?"));
  $(".cancel-order").click(changeStatus(".cancel-order", "Are you sure you want to change the cancellation status of this order?"));
  $(".refund").click(changeStatus(".refund", ".Are you sure you want to change the refund status?"));
  $(".toggle-delivered").click(changeStatus(".toggle-delivered", ".Are you sure you want to change the delivery status?"));
});

// function to change the statuses colors

function changeAllStatusColor() {
  changeStatusColor('.is-paid', 'is-paid');
  changeStatusColor('.is-being-processed', 'is-being-processed');
  changeStatusColor('.is-delivered', 'is-delivered');
  changeStatusColor('.is-refunded', 'is-refunded');
  changeStatusColor('.is-cancelled', 'is-cancelled');
}

function changeStatusColor(elementClasses, dataClass) {
  var goodColor = 'darkgreen', badColor = 'crimson', okayColor = 'yellow';
  $(elementClasses).each(function () {
    var statusBool = $(this).data(dataClass);
    if (statusBool) {
      $(this).css('background-color', goodColor);
    } else {
      $(this).css('background-color', badColor);
    }
  })
}


// function to change the status of an order
function changeStatus(button, text) {
  $(button).each(function () {
    $(this).on('click', function () {
      if (window.confirm(text)) {
      }
      else {
        return false;
      }

    });
  });
  // there is a more efficient way to do this than
  // going through each status just to change one
  // but this works for now
  changeAllStatusColor();
}