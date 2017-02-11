var table_info = {
  color_map: [
    'white',
    'thistle',
    'plum',
    'orchid',
    'mediumorchid',
    'darkorchid',
    'darkviolet',
    'darkmagenta',
    'indigo'
  ],
  num_cols: $('th').length,
};
$('td').each(function (i) {
  var num_couriers_avail = $(this).data('num-couriers-available');
  // don't color the days of the week
  if (!(i == 0 || i % table_info.num_cols == 0)) {
    if (num_couriers_avail >= table_info.color_map.length) {
      $(this).css('background-color', '#4B0082');
    } else {
      $(this).css('background-color',
      table_info.color_map[num_couriers_avail]);
    }
  }
});
// called on click td
function getAvailableCouriers(obj) {
  // the cell that is the time slot the courier clicked on
  var td = $('#' + obj.id);
  var day = td.data('day-of-week');
  // time is directly in between the start and end times of the time-slot
  // i.e. for time-slot 6AM-8AM, time will equal 7
  var time = td.data('time-slot');
  // the id of the currently authenticated courier
  var courier_id = $('#courier-id').data('courier-id');
  // API_URL = base_url + api/v1/...
  $.ajax({
    url: API_URL + "couriers/getOnlineCouriers/" + day + '/' + time,
    context: document.body
  }).done(function (couriers) {
    // AJAX SUCCESS
    ajaxTimeSlotListing(couriers, courier_id, day, time);
  });
}

function ajaxTimeSlotListing(couriers, courier_id, day, time) {
  // list online couriers
  var courierHasThisTimeSlot = false
  var time_slot = $('#signed-couriers');
  for (var i = 0; i < couriers.length; i++) {
    // determine if the courier has already signed up for this time slot
    // or not. This will determine if we show the add or remove button
    if (couriers[i].id === courier_id) {
      courierHasThisTimeSlot = true;
    }
    var html_array = [
      '<dt>' + couriers[i].name + '</dt>',
      '<dd>' + couriers[i].email + '</dd>'
    ];
    // show the list of couriers who have already signed up for this time slot
    time_slot.append(html_array.join(""));
  }
  var modal_body_header = $('#courier-listing-header');
  if (couriers.length === 0) {
    modal_body_header.text('No couriers have signed up for this time slot');
  } else {
    modal_body_header.text('Couriers who have signed up for this time slot');
  }

  // fill the data values in the hidden fields that
  // correspond to the shift the user wants to sign up for
  $('#add-day').val(day);
  $('#add-time').val(time);
  $('#remove-day').val(day);
  $('#remove-time').val(time);

  // p("day " + day); //debugging
  // p("time " + time); //debugging
  // show remove
  if (courierHasThisTimeSlot) {
    $('#remove-courier-form').show();
    $('#add-courier-form').hide();
  }
  else { // show add
    $('#add-courier-form').show();
    $('#remove-courier-form').hide();
  }
}

// empty the time-slot-details-modal on hide that contains
// couriers who signed up for the time slot
$('#time-slot-details-modal').on('hidden.bs.modal', function () {
  $('#signed-couriers').empty();
});