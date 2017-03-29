/**
 * Created by blaise o
 * n 3/28/17.
 */


$(function () {
    var $form = $('#payment-form');
    $form.submit(function (event) {
        $('#pay-now-button').prop('disabled', true);
        if (!$('#pay-with-venmo').is(':checked')) {
            // Disable the submit button to prevent repeated clicks:

            // TODO: see where this fits into the current set up
            /*var message = validPayForm(true);
             if (message !== null) { // an error message was returned
             $('#payment-errors').show().text(message);
             event.preventDefault();
             $form.find('.submit').prop('disabled', false);
             return false;
             }*/

            // Request a token from Stripe:
            Stripe.card.createToken($form, stripeResponseHandler);

            // Prevent the form from being submitted:
            /*$form.find('.submit').prop('disabled', false);*/
            event.preventDefault();
            return false;
        }

    });
});

function stripeResponseHandler(status, response) {
    // Grab the form:
    var $form = $('#payment-form');

    if (response.error) { // Problem!

        // Show the errors on the form:
        $('#payment-errors-div').show();
        $('#payment-errors').text(response.error.message);
        p('here');
        $('#pay-now-button').prop('disabled', false); // Re-enable submission

    } else { // Token was created!

        // Get the token ID:
        var token = response.id;

        // Insert the token ID into the form so it gets submitted to the server:
        $form.append($('<input type="hidden" name="stripeToken">').val(token));

        // Submit the form:
        $form.get(0).submit();
    }
    return response
}