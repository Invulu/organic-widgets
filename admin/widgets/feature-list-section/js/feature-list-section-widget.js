/**
 * Subpage Section Widget JS
 */
(function( $ ) {
	'use strict';



  function organicWidgetsCustomDropdown() {

		//Listen for click to open dropdown
    $('.organic-widget-dropdown-button').click(function(){
      organicWidgetsOpenDropdown(this);
    });

		// Listen for click to choose feature
		$('.organic-widgets-feature-select-item').click(function(){
			console.log('hey');
			organicWidgetsChooseFeature(this);
		});
  }

	/*--------- Open Feature Selector ----------*/
	function organicWidgetsOpenDropdown(dropdown) {

	  var thisDropdown = $(dropdown).parent('.organic-widgets-feature-list-select');
		var thisIcon = thisDropdown.data('val');
    var thisID = thisDropdown.data('feature-id');
    console.log(thisIcon);
		$(dropdown).addClass('organic-widgets-open');
		thisDropdown.find('.organic-widgets-feature-list-select-dropdown').addClass('organic-widgets-show');

  }

	/*---------- Choose Feature -------------*/
	function organicWidgetsChooseFeature( feature ) {

		var thisItem = $(feature);
		var thisVal = thisItem.data('val');
		var thisFeature = thisItem.parent().parent('.organic-widgets-feature-list-select');
		var thisButton = thisItem.parent().siblings('.organic-widget-dropdown-button');
		var thisPreview = thisButton.find('.organic-widget-feature-icon-preview');
		thisPreview.html('<i class="fa ' + thisVal + '"></i>');
		console.log(thisFeature);
		console.log(thisVal);
		thisFeature.attr( 'data-val', thisVal );
		console.log(thisFeature.attr( 'data-val'));
		// thisFeature.innerhtml('<i class="fa ' + thisVal + '"></i>');
		thisItem.siblings().removeClass('organic-widgets-feature-active');
		thisItem.addClass('organic-widgets-feature-active');

		//Close Selector
		thisButton.removeClass('organic-widgets-open');
		thisItem.parent('.organic-widgets-feature-list-select-dropdown').removeClass('organic-widgets-show');

	}


$( document )
.ready( organicWidgetsCustomDropdown );

})( jQuery );
