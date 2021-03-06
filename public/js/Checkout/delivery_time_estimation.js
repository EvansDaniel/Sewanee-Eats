function TimeEstimation(customerDeliveryLocation, restaurantLocation) {
  this.customerDeliveryLocation = customerDeliveryLocation;
  this.restaurauntLocation = restaurantLocation;
  this.startingLocation = '735 University Avenue, Sewanee, TN';
  // hide the api key by making ajax request to server for the key???

  this.parseResponse = function (response) {
    var toRest = response.rows[0].elements[0];
    var toDeliveryLoc = response.rows[0].elements[1];
    var pessimisticTime = 18;
    return Math.ceil((toRest.duration.value + toDeliveryLoc.duration.value) * 2 / 60) + pessimisticTime;
  };

  this.timeEstimation = function (deliveryLoc, callback) {
    if (this.customerDeliveryLocation == "") {
      return callback("30 - 45", "OK");
    } else {
      var service = new google.maps.DistanceMatrixService;
      var estimationObject = this;
      service.getDistanceMatrix({
        origins: [this.startingLocation],
        destinations: [this.restaurauntLocation, deliveryLoc],
        travelMode: 'DRIVING',
        unitSystem: google.maps.UnitSystem.METRIC,
        avoidHighways: false,
        avoidTolls: false
      }, function (response, status) {
        if (status == "OK") {
          return callback(estimationObject.parseResponse(response), status);
        } else {
          return callback("30 - 45", "OK");
        }
      });
    }
  };
}
