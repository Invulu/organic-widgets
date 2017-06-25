/**
 * Subpage Section Widget JS
 */
(function( $ ) {
	'use strict';



  function organicWidgetsCustomDropdown() {

		//Listen for click to open/close dropdown
    $('.organic-widget-dropdown-button').unbind('click').click(function(){

			if ( $(this).closest('.organic-widgets-feature-list-item-form-item').hasClass('organic-widgets-show') ) {
				organicWidgetsCloseDropdown(this);
			} else {
				organicWidgetsOpenDropdown(this);
			}

		});

		//Listen for delete
    $('.organic-widget-feature-delete-button').unbind('click').click(function(){
      organicWidgetsDeleteFeature(this);
    });

		//Listen for move up
    $('.organic-widget-move-up').unbind('click').click(function(){
      organicWidgetsReorderFeatures(this, 'up');
    });

		//Listen for move up
    $('.organic-widget-move-down').unbind('click').click(function(){
      organicWidgetsReorderFeatures(this, 'down');
    });

		// Listen for click to choose feature
		$('.organic-widgets-feature-select-item').unbind('click').click(function(){
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

	/*--------- Delete Feature ----------*/
	function organicWidgetsDeleteFeature(feature) {
		var formItem = $(feature).closest('.organic-widgets-feature-list-item-form-item');
		var theForm = $(formItem).closest('.organic-widgets-feature-list-widget-admin');
		if ( confirm("Are you sure you want to delete this?") ){

			formItem.remove();

			organicWidgetsFeatureUpdateMainArray(theForm);
    }
	}

	/*--------- Move Feature Up ----------*/
	function organicWidgetsReorderFeatures( feature, direction ) {

		if ( $(feature).hasClass('organic-widgets-feature-list-item-form-item') ) {
			var formItem = $(feature);
		} else {
			var formItem = $(feature).closest('.organic-widgets-feature-list-item-form-item');
		}
		var form = $(formItem).parents('.organic-widgets-feature-list-widget-admin');
		var allFormItems = form.find('.organic-widgets-feature-list-item-form-item');

		// Move Up
		if ( direction == 'up' && allFormItems.first().data('feature-id') != formItem.data('feature-id') ) {

			// Get previous item
			var prevItem = formItem.prev('.organic-widgets-feature-list-item-form-item');

			// Insert before previous
			formItem.insertBefore(prevItem);

			// Update main input
			var theForm = $(formItem).closest('.organic-widgets-feature-list-widget-admin');
			organicWidgetsFeatureUpdateMainArray(theForm);

		}
		// Move Down
		else if ( direction == 'down' && allFormItems.last().data('feature-id') != formItem.data('feature-id') ) {

			// Get next item
			var nextItem = formItem.next('.organic-widgets-feature-list-item-form-item');

			// Insert after next
			formItem.insertAfter(nextItem);

			// Update main input
			var theForm = $(formItem).closest('.organic-widgets-feature-list-widget-admin');
			organicWidgetsFeatureUpdateMainArray(theForm);

		}

	}

	/*--------- Open Feature Selector ----------*/
	function organicWidgetsOpenDropdown(dropdown) {

	  var thisDropdown = $(dropdown).parent('.organic-widgets-feature-list-select');
		var thisIcon = thisDropdown.data('val');
    var thisID = thisDropdown.data('feature-id');
		var thisFormItem = $(dropdown).parents('.organic-widgets-feature-list-item-form-item');

		thisFormItem.find('.organic-widget-feature-list-select-icon').html('<i class="fa fa-angle-up"></i>');

		thisFormItem.addClass('organic-widgets-show');
  }

	/*--------- Open Feature Selector ----------*/
	function organicWidgetsCloseDropdown(dropdown) {

		var thisFormItem = $(dropdown).parents('.organic-widgets-feature-list-item-form-item');

		thisFormItem.find('.organic-widget-feature-list-select-icon').html('<i class="fa fa-angle-down"></i>');

		thisFormItem.removeClass('organic-widgets-show');
  }

	/*---------- Choose Feature -------------*/
	function organicWidgetsChooseFeature( feature ) {

		var thisItem = $(feature);
		var thisVal = thisItem.data('val');
		var thisFeature = thisItem.parents('.organic-widgets-feature-list-select');
		var thisFormItem = thisFeature.parent('.organic-widgets-feature-list-item-form-item');
		var thisButton = thisItem.parent().siblings('.organic-widget-dropdown-button');
		var thisPreview = thisFormItem.find('.organic-widgets-feature-list-icon-preview');

		// Update HTML Values
		thisPreview.html('<i class="fa ' + thisVal + '"></i>');
		thisFeature.attr( 'data-val', thisVal );

		// Update Main Data Array
		organicWidgetsFeatureUpdateMainArray(thisFormItem);

		// Set active classes
		thisItem.siblings().removeClass('organic-widgets-feature-active');
		thisItem.addClass('organic-widgets-feature-active');

		// HTML Changes
		thisFormItem.find('.organic-widget-feature-list-select-icon').html('<i class="fa fa-angle-down"></i>');

		// Close Selector
		organicWidgetsCloseDropdown(thisItem);

	}

	function organicWidgetsFeatureUpdateMainArray(item) {
		console.log('organicWidgetsFeatureUpdateMainArray');

		// Get thisFormAdmin
		if ( $(item).hasClass('organic-widgets-feature-list-widget-admin') ) {
			var thisFormAdmin = $(item);
		} else {
			var thisFormAdmin = $(item).closest('.organic-widgets-feature-list-widget-admin');
		}
		var allFormItems = thisFormAdmin.find('.organic-widgets-feature-list-item-form-item');
		var thisItemData = {};

		allFormItems.each(function(key,el){
			var icon = $(el).find('.organic-widgets-feature-list-select').attr('data-val');
			var title = $(el).find('.organic-widgets-feature-list-title-input').val();
			var summary = $(el).find('.organic-widgets-feature-list-summary-input').val();

			if ( icon != '' || title != '' || summary != '' ) {
				var theID = $(el).data('feature-id');
				console.log(theID);
				thisItemData[theID] = {
					'icon': icon,
					'title': title,
					'summary': summary
				}
			}
		});
		console.log(JSON.stringify(thisItemData));
		var mainInput = thisFormAdmin.find('.organic-widgets-feature-list-hidden-input');
		mainInput.val(JSON.stringify(thisItemData));

	}


$( document )
.ready( organicWidgetsCustomDropdown )
.ajaxComplete( organicWidgetsCustomDropdown );

})( jQuery );
