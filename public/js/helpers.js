function setWindowConfirmation(buttonId, confirmationText) {
  $('#' + buttonId).on('click', function () {
    if (!window.confirm(confirmationText)) {
      return false;
    }
  });
}