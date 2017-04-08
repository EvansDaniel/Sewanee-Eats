function PromoCode(promoCodeText) {
  this.promoCode = promoCodeText;

  this.checkCodeValidity = function () {
    var codeValidityUrl = API_URL + 'codes/checkValidCode';
    $.ajax({
      type: "POST",
      url: codeValidityUrl,
      headers: {
        'X-CSRF-TOKEN': $('#x').attr('content')
      },
      data: {promo_code: this.promoCode},
      context: document.body
    }).done(function (res) {
      if (res.status == "OK:") {
        // valid code let user, let user know that it was successful
      } else {
        // invalid code
      }
    });
  }
}
