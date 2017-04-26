function posFixDiv() {
  var $div = $("#backend-msg");
  if ($(window).scrollTop() > $div.data("top")) {
    $div.css({'position': 'fixed', 'top': '0', 'width': '100%'});
  }
  else {
    $div.css({'position': 'static', 'top': 'auto', 'width': '100%'});
  }
}

function setUpAttach() {
  var div = $("#backend-msg");
  if (div.length == 0) return false;
  div.data("top", div.offset().top + 20); // set original position on load
  $(window).scroll(posFixDiv);
}

// Handles the attachment
setUpAttach();

function msgTimeout(time) {
  setTimeout(function () {
    var backendMsg = $('#backend-msg');
    if (backendMsg.length == 0) return false;
    backendMsg.hide(1000);
  }, time);
}