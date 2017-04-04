function changeDisplayForRole() {
  if ($('#role-type option:selected').text() == 'courier') {
    $('#courier-phone-number-wrapper').show();
  } else {
    $('#courier-phone-number-wrapper').hide();
  }
}