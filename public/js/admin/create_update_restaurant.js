function checkDays(event) {
  var days = $('.days');
  var isValid = true;
  days.each(function () {
    if (!validTableData(this)) {
      var span = $('#invalid-table-data');
      span.show();
      span.text("One of the cells contains invalid input. " +
      "Input form: num1-num2, where 0 <= num1 < num2 <= 24");
      isValid = false;
      event.preventDefault();
      return false;
    }
  });
}

$('#invalid-table-data').hide();

function validTableData(input) {
  var text = $(input).val();
  //p("text = " + text.length);
  // allow no shift or restaurant is closed
  if (!text || text.toLowerCase() === "closed")
    return true;
  // regex to replace all spaces with ""
  var res = text.replace(/ /g, "");
  // extra invalid characters
  if (res.length > 5) return false;
  var vals = res.split("-");
  //p(vals[0] + " " + vals[1]);
  if (!$.isNumeric(vals[0]) || vals[0] < 0 || vals[0] > 24) {
    return false;
  }
  if (!$.isNumeric(vals[1]) || vals[1] < 0 || vals[1] > 24) {
    return false;
  }
  return true;
}