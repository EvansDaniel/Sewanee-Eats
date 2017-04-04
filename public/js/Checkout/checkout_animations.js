/**
 * Created by blaise on 3/26/17.
 */


var loc_address = $("input[type='radio'] [name='loc-address']");

$(document).ready(function () {
    var university_wrap = $('#university-wrap');
    var adrress_wrap= $('#location-wrap');
    var loc_university = $('#loc-university');
    var loc_address = $('#loc-address');

    $('#venmo-payment-div').hide();
    handle_venmo_options();
    initialize_locations();
    loc_university.click(function () {
        hideOrShow(this,university_wrap, adrress_wrap, loc_address);
    });
    loc_address.click(function () {
        hideOrShow(this, adrress_wrap, university_wrap, loc_university);
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

function hideOrShow(radio,target, to_hide, to_change){
    if($(radio).prop("checked")){
        $(to_hide).hide(0);
        $(target).show(200);
        $(radio).val(1);
        $(to_change).val(0);
        // ---if testing is needed
        // p( $(radio).attr('id') + $(radio).val());
        // p( $(to_change).attr('id') + $(to_change).val());
        // p($(radio).prop("checked"));
        $(radio).prop("checked", true);
    }
    else{
        $(to_hide).show(0);
        $(radio).val(0);
        p($(radio).val());
        $(target).hide(200);
        $(to_change).val(1);
        $(radio).prop("checked", false);
    }
}