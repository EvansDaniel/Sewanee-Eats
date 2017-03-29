/** function to push the footer at the bottom of the page every time
*/

$(document).ready(function () {
  var windowHeight = $(window).height();
  var htmlHeight = $('html').height();
  var h = windowHeight - htmlHeight;
  if (h > 0) {
    $("#push-div").css("height", h);
  }
});