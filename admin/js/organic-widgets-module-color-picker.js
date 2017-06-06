/**
 * JS for Initializing Color Pickers
 */
jQuery(document).ready(function($){

	// Initialize Color Pickers
  $('.organic-widgets-color-picker').wpColorPicker();

});

// On AJAX Completion
jQuery(document).ajaxComplete(function() {

	// Initialize Color Pickers
	jQuery('.organic-widgets-color-picker').wpColorPicker();

});
