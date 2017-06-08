/**
 * JS for Initializing Color Pickers
 */
jQuery(document).ready(function($){

	// Initialize Color Pickers
  // $('.organic-widgets-color-picker').wpColorPicker();

  console.log('.ready() ran');
  console.log($('.organic-widgets-color-picker'));
  $('.organic-widgets-color-picker').wpColorPicker({
		change: _.debounce( function() {
      console.log('.change() ran');
      $('.organic-widgets-color-picker').change();
		}, 200 )
	});
  $('.organic-widgets-color-picker').change();

});

// On AJAX Completion
jQuery(document).ajaxComplete(function() {

	// Initialize Color Pickers
	jQuery('.organic-widgets-color-picker').wpColorPicker();


});
