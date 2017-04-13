function setWindowConfirmation(buttonIdentifier, confirmationText) {
  // if the user declines, don't perform the default action
  $(buttonIdentifier).on('click', function () {
    if (!window.confirm(confirmationText)) {
      return false;
    }
  });
}