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

		//Listen for delete
    $('.organic-widget-feature-delete-button').click(function(){
      organicWidgetsDeleteFeature(this);
    });

		// Listen for click to choose feature
		$('.organic-widgets-feature-select-item').click(function(){
			organicWidgetsChooseFeature(this);
		});

		// Listen for changes on inputs
		$('.organic-widgets-feature-list-summary-input').change(function(){
			var formItem = $(this).parent().parent('.organic-widgets-feature-list-item-form-item');
			organicWidgetsFeatureUpdateMainArray(this);
		});
		$('.organic-widgets-feature-list-title-input').change(function(){
			var formItem = $(this).parent().parent('.organic-widgets-feature-list-item-form-item');
			organicWidgetsFeatureUpdateMainArray(this);
		});

		// Prep hidden input value
		$('.organic-widgets-feature-list-widget-admin').each(function(){
			var formItem = $(this).find('.organic-widgets-feature-list-item-form-item').first();
			// organicWidgetsFeatureUpdateMainArray( formItem );
		});
  }

	function organicWidgetsDeleteFeature(feature) {
		var formItem = $(feature).closest('.organic-widgets-feature-list-item-form-item');
		var theForm = $(formItem).closest('.organic-widgets-feature-list-widget-admin');
		if ( confirm("Are you sure you want to delete this?") ){
			console.log(formItem);
			formItem.remove();

			organicWidgetsFeatureUpdateMainArray(theForm);
    }
		return false;
	}

	/*--------- Open Feature Selector ----------*/
	function organicWidgetsOpenDropdown(dropdown) {

	  var thisDropdown = $(dropdown).parent('.organic-widgets-feature-list-select');
		var thisIcon = thisDropdown.data('val');
    var thisID = thisDropdown.data('feature-id');

		$(dropdown).addClass('organic-widgets-open');
		thisDropdown.find('.organic-widgets-feature-list-select-dropdown').addClass('organic-widgets-show');

  }

	/*---------- Choose Feature -------------*/
	function organicWidgetsChooseFeature( feature ) {

		var thisItem = $(feature);
		var thisVal = thisItem.data('val');
		var thisFeature = thisItem.parent().parent('.organic-widgets-feature-list-select');
		var thisFormItem = thisFeature.parent('.organic-widgets-feature-list-item-form-item');
		var thisButton = thisItem.parent().siblings('.organic-widget-dropdown-button');
		var thisPreview = thisButton.find('.organic-widget-feature-icon-preview');

		// Update HTML Values
		thisPreview.html('<i class="fa ' + thisVal + '"></i>');
		thisFeature.attr( 'data-val', thisVal );

		// Update Main Data Array
		organicWidgetsFeatureUpdateMainArray(thisFormItem);

		// Set active classes
		thisItem.siblings().removeClass('organic-widgets-feature-active');
		thisItem.addClass('organic-widgets-feature-active');

		// Close Selector
		thisButton.removeClass('organic-widgets-open');
		thisItem.parent('.organic-widgets-feature-list-select-dropdown').removeClass('organic-widgets-show');

	}

	function organicWidgetsFeatureUpdateMainArray(item) {

		if ( $(item).hasClass('organic-widgets-feature-list-widget-admin') ) {
			var thisFormItem = $(item);
		} else {
			var thisFormItem = $(item).closest('.organic-widgets-feature-list-widget-admin');
		}


		var allFormItems = thisFormItem.find('.organic-widgets-feature-list-item-form-item');

		var thisItemData = {};
		allFormItems.each(function(key,el){

			var icon = $(el).find('.organic-widgets-feature-list-select').attr('data-val');
			var title = $(el).find('.organic-widgets-feature-list-title-input').val();
			var summary = $(el).find('.organic-widgets-feature-list-summary-input').val();

			if ( icon != '' || title != '' || summary != '' ) {
				var theID = $(el).data('feature-id');
				thisItemData[theID] = {
					'icon': icon,
					'title': title,
					'summary': summary
				}
			}
		});

		var mainInput = thisFormItem.siblings('.organic-widgets-feature-list-hidden-input');
		mainInput.val(JSON.stringify(thisItemData));

	}


$( document )
.ready( organicWidgetsCustomDropdown )
.ajaxComplete( organicWidgetsCustomDropdown );

})( jQuery );
