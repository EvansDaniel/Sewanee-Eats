// pre-hide the create-category input
$('#create-category-parent').hide();
function handleCategory() {
  var createCatPar = $('#select-category-parent');
  var selectCatPar = $('#create-category-parent')
  var createCat = $('#create-category');
  var selectCat = $('#select-category');
  // change the text on the button
  if (createCatPar.is(":visible")) {
    createCat.prop('required', true);
    selectCat.prop('required', false);
    $('#toggle-category').text('Select existing category');
  }
  else {
    createCat.attr('value', '');
    createCat.val('');
    createCat.prop('required', false);
    selectCat.prop('required', true);
    $('#toggle-category').text('Or create a new category');
  }
  // toggle the hiddenness of the input/select
  createCatPar.toggle();
  selectCatPar.toggle();
}