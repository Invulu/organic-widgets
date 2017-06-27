/**
 * Subpage Section Widget JS
 */
(function( $ ) {
	'use strict';

	// Bind all click events and prep dropdown
  function organicWidgetsCustomDropdown() {

		//Listen for click to open/close dropdown
    $('.organic-widget-dropdown-button').unbind('click').on('click', function(){

			if ( $(this).closest('.organic-widgets-feature-list-item-form-item').hasClass('organic-widgets-show') ) {
				organicWidgetsCloseDropdown(this);
			} else {
				organicWidgetsOpenDropdown(this);
			}

		});

		//Listen for delete
    $('.organic-widget-feature-delete-button').unbind('click').on('click', function(){
      organicWidgetsDeleteFeature(this);
    });

		//Listen to add
    $('.organic-widgets-feature-list-add-item').unbind('click').on('click', function(){
      organicWidgetsAddFeature(this);
    });

		//Listen for move up
    $('.organic-widget-move-up').unbind('click').on('click', function(){
      organicWidgetsReorderFeatures(this, 'up');
    });

		//Listen for move up
    $('.organic-widget-move-down').unbind('click').on('click', function(){
      organicWidgetsReorderFeatures(this, 'down');
    });

		// Listen for click to choose feature
		$('.organic-widgets-feature-select-item').unbind('click').on('click', function(){
			organicWidgetsChooseFeature(this);
		});

		// Listen for changes on inputs
		$('.organic-widgets-feature-list-summary-input').on('change', function(){
			var formItem = $(this).parent().parent('.organic-widgets-feature-list-item-form-item');
			organicWidgetsFeatureUpdateMainArray(this);
		});
		$('.organic-widgets-feature-list-title-input').on('change', function(){
			var formItem = $(this).parent().parent('.organic-widgets-feature-list-item-form-item');
			organicWidgetsFeatureUpdateMainArray(this);
		});

		// Prep hidden input value
		$('.organic-widgets-feature-list-widget-admin').each(function(){
			var formItem = $(this).find('.organic-widgets-feature-list-item-form-item').first();
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

	/*--------- Delete Feature ----------*/
	function organicWidgetsAddFeature(addButton) {

		// The Form
		var theForm = $(addButton).parents('.organic-widgets-feature-list-widget-admin');

		// Get Last Item
		var allItems = theForm.find('.organic-widgets-feature-list-item-form-item');
		var lastItem = allItems.last();

		var maxID = 0;
		var maxOrder = 0;
		allItems.each(function(){

			if ( $(this).data('feature-id') > maxID ) {
				maxID = $(this).data('feature-id');
			}

			if ( $(this).data('order') > maxOrder ) {
				maxOrder = $(this).data('order');
			}

		});

		// Get New ID
		var newID = maxID + 1;
		// Get New Order
		var newOrder = maxOrder + 1;

		// Create New Item
		var newItem = lastItem.clone();
		newItem.attr('data-feature-id', newID);
		newItem.attr('data-order', newOrder);
		if ( newItem.hasClass('organic-widgets-show') ) {
			newItem.removeClass('organic-widgets-show');
			newItem.find('.organic-widgets-feature-list-icon-preview').html('');
			newItem.find('.organic-widget-feature-list-select-icon').html('<i class="fa fa-angle-down"></i>');
		}
		newItem.find('.organic-widgets-feature-list-select').attr('data-val', '');
		newItem.find('.organic-widgets-feature-list-select').attr('data-feature-id', newID);
		newItem.find('.organic-widgets-feature-list-icon-preview').html('');
		newItem.find('.organic-widgets-feature-list-title-input').val('');
		newItem.find('.organic-widgets-feature-list-summary-input').html('');
		newItem.find('.organic-widgets-feature-list-summary-input').val('');

		// Append New Item
		$(newItem).insertAfter(lastItem);

		// Rebind click events
		organicWidgetsCustomDropdown();

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

	/*---------- Update Hidden Input Array -------------*/
	function organicWidgetsFeatureUpdateMainArray(item) {

		// Get thisFormAdmin
		if ( $(item).hasClass('organic-widgets-feature-list-widget-admin') ) {
			var thisFormAdmin = $(item);
		} else {
			var thisFormAdmin = $(item).closest('.organic-widgets-feature-list-widget-admin');
		}
		var allFormItems = thisFormAdmin.find('.organic-widgets-feature-list-item-form-item');
		var thisItemData = {};

		var orderNumber = 0;
		allFormItems.each(function(key,el){
			var icon = $(el).find('.organic-widgets-feature-list-select').attr('data-val');
			var title = $(el).find('.organic-widgets-feature-list-title-input').val();
			var summary = $(el).find('.organic-widgets-feature-list-summary-input').val();
			var order = orderNumber;
			var theID = $(el).data('feature-id');

			if ( icon != '' || title != '' || summary != '' ) {
				thisItemData[order] = {
					'icon': icon,
					'id': theID,
					'title': title,
					'summary': summary,
					'order': order
				}
			}

			orderNumber++;

		});

		var mainInput = thisFormAdmin.find('.organic-widgets-feature-list-hidden-input');
		mainInput.trigger('change');
		mainInput.val(JSON.stringify(thisItemData));

	}

	// Binding main function
	$( document )
	.ajaxComplete( organicWidgetsCustomDropdown );

	$(window).on("load", function() {
		organicWidgetsCustomDropdown()
		if ( typeof wp != "undefined" ) {
			wp.customize.state.bind('change', function() {
				organicWidgetsCustomDropdown();
			});
			$('.customize-control-widget_form').on('click',function(){
				organicWidgetsCustomDropdown();
			});
		}
	});

})( jQuery );
