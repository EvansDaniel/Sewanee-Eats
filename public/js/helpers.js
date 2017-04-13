function setWindowConfirmation(buttonIdentifier, confirmationText) {
  $(buttonIdentifier).on('click', function () {
    if (!window.confirm(confirmationText)) {
      return false;
    }
  });
}