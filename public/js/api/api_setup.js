function getBaseUrl() {

  var pathArray = location.href.split('/');
  var protocol = pathArray[0];
  var host = pathArray[2];
  return protocol + '//' + host;
}
API_URL = getBaseUrl() + "/api/v1/";

// debugging helper function
function p($obj) {
  console.log($obj);
}