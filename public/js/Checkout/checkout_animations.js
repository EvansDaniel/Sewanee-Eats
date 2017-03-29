/**
 * Created by blaise on 3/26/17.
 */


var loc_address = $("input[type='radio'] [name='loc-address']");


$(document).ready(function () {
    var university_wrap = $('#university-wrap');
    var adrress_wrap= $('#location-wrap');

    $('#venmo-payment-div').hide();
    handle_venmo_options();
    initialize_locations();
    $("#loc-university").click(function () {
        hideOrShow(this,university_wrap, adrress_wrap );
    });
    $("#loc-address").click(function () {
        hideOrShow(this, adrress_wrap, university_wrap);
    });
});

/**
 * animates the venmo section
 */
function handle_venmo_options() {
    $('#pay-with-venmo').on('change', function () {
        var box = $(this);
        if (box.is(':checked')) {
            // set pay with venmo to true
            $('#pay-with-venmo').val(1);
            $('#venmo-payment-div').show(350);
            $('#card-payment-div').hide(350);
            $('#pay-with-card').hide(350);
            $('.total').css('margin-top', '0');
        } else {
            // set pay with venmo to false
            $('#pay-with-venmo').val(0);
            $('#venmo-payment-div').hide(350);
            $('#card-payment-div').show(350);
            $('#pay-with-card').show(350);
            if ($(window).width() >= 1080)
                $('.total').css('margin-top', '159px');
        }
    })
}

/**
 * initiliazes the values of the radio button and displays the corresponding input
 */

function initialize_locations(){
    $("#loc-university").prop("checked", true);
    $("#location-wrap").hide();
    $("#university-wrap").show();

}

/**
 * reacts to the radio buttons click
 * @param radio
 * @param target
 * @param to_hide
 */

function hideOrShow(radio,target, to_hide){
    if($(radio).prop("checked")){
        p($(radio).prop("checked"));
        $(to_hide).hide(0);
        $(target).show(200);
        $(radio).prop("checked", true);
    }
    else{
        $(to_hide).show(0);
        $(target).hide(200);
        $(radio).prop("checked", false);
    }


}