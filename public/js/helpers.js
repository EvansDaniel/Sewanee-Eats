function setWindowConfirmation(buttonIdentifier, confirmationText) {
  // if the user declines, don't perform the default action
  $(buttonIdentifier).on('click', function () {
    if (!window.confirm(confirmationText)) {
      return false;
    }
  });
}

function scrollToItem(scrollTime) {
  var itemId = $('#scroll-to-id').data('scroll-to');
  if (itemId == null) {
    return null;
  }
  var item = $('#' + itemId);
  $('html, body').animate({
    scrollTop: item.offset().top - 300
  }, scrollTime);
}