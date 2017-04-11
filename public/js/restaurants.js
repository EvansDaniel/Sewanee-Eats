/**
 * Created by blaise on 3/25/17.
 * set of function necessary for the restaurant listing page
 */

$(document).ready(function () {
    var rsnt = $(".restaurant");
    var interval = 250;
    rsnt.each(function (index, value) {
        rsnt.fadeIn(interval + index * 200);
    });

    // changing the height of the first images before calling the change height function.
    var node = $(".img-responsive")[0];
    node.height = "100%";
    change_heights();

    // deactivating closed places/
    $(".on-demand-links").each(function () {
        disable_link($(this));
    })
    $(".weekly-specials-link").each(function () {
        disable_link($(this));
    })
});

/**
 * makes the dimensions of all restaurants logos the same
 * if they are not equal check the images criteria: 40 x 33
 */

function change_heights() {
    var imgs = $(".img-responsive");
    var img_model = imgs[0];
    var li_h = img_model.width;
    imgs.each(function () {
        $(this).css("width", li_h);
    });

}

/**
 * disables a link
 * @param link: link in question
 */
function disable_link(link) {
    var status = $(link).data("open");
    if (!status) {
        change_rest_status_color(link);

        // $(link).click(function () {
        //     return false;
        // });

    }
}

function change_rest_status_color( link) {
    var restaurant_status = $(link).children('p');
    $(restaurant_status).css('background', 'crimson');
    $(restaurant_status).text('closed')
}