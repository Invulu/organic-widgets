/**
 * Subpage Section Widget JS
 */
(function( $ ) {
	'use strict';



  function organicWidgetsCustomDropdown() {
    //Listen for click to open dropdown
    $('.organic-widget-dropdown-button').click(function(){
      organicWidgetsOpenDropdown(this);
    })
  }



  function organicWidgetsOpenDropdown(dropdown) {
    var thisDropdown = $(dropdown).parent('.organic-widgets-feature-list-select');
    var thisIcon = thisDropdown.data('val');
    var thisID = thisDropdown.data('feature-id');
    console.log(thisIcon);
    thisDropdown.find('.organic-widgets-feature-list-select-dropdown').addClass('organic-widgets-show');

  }



$( document )
.ready( organicWidgetsCustomDropdown );

})( jQuery );
