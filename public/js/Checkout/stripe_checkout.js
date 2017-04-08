/**
 * Created by blaise o
 * n 3/28/17.
 */


$(function () {
    var $form = $('#payment-form');
    $form.submit(function (event) {
      // immediately disable the pay now button
        $('#pay-now-button').prop('disabled', true);
        if (!$('#pay-with-venmo').is(':checked')) {
            // Disable the submit button to prevent repeated clicks:

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