var inputAppenderDiv = $('#item-input-appender-wrap');
var itemSelects = $('.time-range-add')
$('#create-multi-time-range-items-form').on('submit', function () {
  itemSelects.each(function () {
    var itemInput = $(this);
    if (itemInput.is(':checked')) {
      var itemInputData = itemInput.data('item-id');
      inputAppenderDiv.append(
      '<input type="hidden" name="items[]" value="' + itemInputData + '">'
      )
    }
  });
});

function selectAll(button) {
  if ($(button).text() == 'Select All Items') {
    $(button).text('Deselect All Items');
    itemSelects.each(function () {
      $(this).prop('checked', true);
    });
  } else {
    $(button).text('Select All Items');
    itemSelects.each(function () {
      $(this).prop('checked', false);
    });
  }
}
