function checkDays(event) {
  var days = $('.days');
  var isValid = true;
  days.each(function () {
    if (!validTableData(this)) {
      var span = $('#invalid-table-data');
      span.show();
      span.text("One of the cells contains invalid input. " +
      "Input form: hh:mm-hh:mm, where 0 <= hh < 24 and 0 <= mm < 60." +
      "Furthermore, hh and mm must be exactly two digits long");
      isValid = false;
      event.preventDefault();
      return false;
    }
  });
}

$('#invalid-table-data').hide();

// values need to be in this format hh:mm-hh:mm
function validTableData(input) {
  var formatLength = "hh:mm-hh:mm".length;
  var text = $(input).val();
  //p("text = " + text.length);
  // allow no shift or restaurant is closed
  if (!text || text.toLowerCase() === "closed")
    return true;

  // regex to replace all spaces with ""
  var res = text.replace(/ /g, "");
  // extra invalid characters
  // length of "hh:mm-hh:mm" === 11
  if (res.length != formatLength) return false;

  var vals = res.split("-"); // [hh:mm,hh:mm]
  if (vals.length != 2) {
    // user didn't split the two hh:mm's with "-"
    return false;
  }
  var start = vals[0].split(":"); // [hh,mm]
  if (start.length != 2) {
    // user didn't split the start hh and mm with ":"
    // or did something else weird
    return false
  }
  var end = vals[1].split(":"); // [hh,mm]
  if (end.length != 2) {
    // user didn't split the end hh and mm with ":"
    return false
  }

  // We will represent 12 AM as "00" so we reject any hh > 23
  // check start[0] -> hh
  if (!$.isNumeric(start[0]) || start[0] < 0 || start[0] > 23 || start[0].length != 2) {
    return false;
  }
  // check start[1] -> mm
  if (!$.isNumeric(start[1]) || start[1] < 0 || start[1] > 59 || start[1].length != 2) {
    return false;
  }

  // check end[0] -> hh
  if (!$.isNumeric(end[0]) || end[0] < 0 || end[0] > 23 || end[0].length != 2) {
    return false;
  }
  // check end[1] -> mm
  if (!$.isNumeric(end[1]) || end[1] < 0 || end[1] > 59 || end[1].length != 2) {
    return false;
  }
  return true;
}