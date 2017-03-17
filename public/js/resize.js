$(document).ready(function () {
  var footer = $('#footer').height();
  var wn = $(window).height();
  var doc = $('html').height();
  var h = wn - doc;
  if (h > 0) {
    var diff = h;
    $("#push-div").css("height", diff);
  }
});