function setWindowConfirmation(buttonIdentifier, confirmationText) {
  // if the user declines, don't perform the default action
  $(buttonIdentifier).on('click', function () {
    if (!window.confirm(confirmationText)) {
      return false;
    }
  });
}

// extend jquery to include and exists function for after selecting some elements
$.fn.exists = function () {
  return this.length != 0;
};

function scrollToItem(scrollTime) {
  var scrollItem = $('#scroll-to-id');
  if (scrollItem.length == 0) return false; // scroll item does not exist
  var itemId = scrollItem.data('scroll-to');
  if (!itemId) {
    return null;
  }
  var item = $('#' + itemId);
  $('html, body').animate({
    scrollTop: item.offset().top - 300
  }, scrollTime);
}