$(document).ready(function () {
//      var container = $('.main-main-container').height();
//      p(container + "cnt")
    var footer = $('#footer').height();
    p(footer + "ft");
    var wn= $( window ).height();
    p(wn + "wn");
    var doc = $('html').height();
    p(doc + "document");
    var h = wn - doc;
    p(h + "after math");
    if( h > 0) {
        var diff = h;
        $("#push-div").css("height", diff);
    }
});