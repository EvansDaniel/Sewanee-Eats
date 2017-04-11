function changeDisplayForRole() {
  var role_types = $('#role-type option:selected');
  var has_courier_type = false;
  role_types.each(function () {
    if($(this).text() == 'courier') {
      has_courier_type = true;
    }
  });
  if(has_courier_type) {
    $('#courier-phone-number-wrapper').show();
    $('#courier-phone-number').prop('required',true);
  } else {
    $('#courier-phone-number-wrapper').hide();
    $('#courier-phone-number').prop('required',false);
  }
}