function fixDiv() {
  var $div = $("#backend-msg");
  if ($(window).scrollTop() > $div.data("top")) {
    $div.css({'position': 'fixed', 'top': '0', 'width': '100%'});
  }
  else {
    $div.css({'position': 'static', 'top': 'auto', 'width': '100%'});
  }
}
$div = $("#backend-msg");
// extra offset of 20 to stop scrolling problem for smaller menus
$div.data("top", $div.offset().top + 20); // set original position on load
$(window).scroll(fixDiv);

function msgTimeout(time) {
  setTimeout(function () {
    $('#backend-msg').hide(1000);
  }, time);
}