/**
 * Subpage Section Widget JS
 */
(function( $ ) {
	'use strict';

	// Bind all click events and prep dropdown
  function organicWidgetsCustomDropdown() {

		//Listen for click to open/close dropdown
    $('.organic-widgets-dropdown-button').unbind('click').on('click', function(){

			if ( $(this).closest('.organic-widgets-repeatable-form-item').hasClass('organic-widgets-show') ) {
				organicWidgetsCloseDropdown(this);
			} else {
				organicWidgetsOpenDropdown(this);
			}

		});

		//Listen for delete
    $('.organic-widgets-repeatable-delete-button').unbind('click').on('click', function(){
      organicWidgetsDeleteRepeatableFormItem(this);
    });

		//Listen to add
    $('.organic-widgets-repeatable-add-item').unbind('click').on('click', function(){
      organicWidgetsAddRepeatableFormItem(this);
    });

		//Listen for move up
    $('.organic-widgets-move-up').unbind('click').on('click', function(){
      organicWidgetsReorderRepeatableFormItems(this, 'up');
    });

		//Listen for move up
    $('.organic-widgets-move-down').unbind('click').on('click', function(){
      organicWidgetsReorderRepeatableFormItems(this, 'down');
    });

		// Listen for click to choose feature
		$('.organic-widgets-feature-select-item').unbind('click').on('click', function(){
			organicWidgetsChooseFeature(this);
		});

		// Listen for changes on inputs
		$('.organic-widgets-feature-list-text-input').on('change', function(){
			var formItem = $(this).parent().parent('.organic-widgets-repeatable-form-item');
			organicWidgetsRepeatableFormItemUpdateMainArray(this);
		});
		// Listen for changes on inputs
		$('.organic-widgets-feature-list-link-url-input').on('change', function(){
			var formItem = $(this).parent().parent('.organic-widgets-repeatable-form-item');
			organicWidgetsRepeatableFormItemUpdateMainArray(this);
		});
		$('.organic-widgets-feature-list-title-input').on('change', function(){
			var formItem = $(this).parent().parent('.organic-widgets-repeatable-form-item');
			organicWidgetsRepeatableFormItemUpdateMainArray(this);
		});

		// Prep hidden input value
		$('.organic-widgets-repeatable-form-item-widget-admin').each(function(){
			var formItem = $(this).find('.organic-widgets-repeatable-form-item').first();
		});
  }

	/*--------- Delete Feature ----------*/
	function organicWidgetsDeleteRepeatableFormItem(feature) {
		var formItem = $(feature).closest('.organic-widgets-repeatable-form-item');
		var theForm = $(formItem).closest('.organic-widgets-repeatable-form-item-widget-admin');
		if ( confirm("Are you sure you want to delete this?") ){
			formItem.remove();
			organicWidgetsRepeatableFormItemUpdateMainArray(theForm);
    }
	}

	/*--------- Delete Feature ----------*/
	function organicWidgetsAddRepeatableFormItem(addButton) {

		// The Form
		var theForm = $(addButton).parents('.organic-widgets-repeatable-form-item-widget-admin');

		// Get Last Item
		var allItems = theForm.find('.organic-widgets-repeatable-form-item');
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
			newItem.find('.organic-widgets-feature-list-select-icon').html('<i class="fa fa-angle-down"></i>');
		}
		newItem.find('.organic-widgets-feature-list-select').attr('data-val', '');
		newItem.find('.organic-widgets-feature-list-select').attr('data-feature-id', newID);
		newItem.find('.organic-widgets-feature-list-icon-preview').html('');
		newItem.find('.organic-widgets-feature-list-title-input').val('');
		newItem.find('.organic-widgets-feature-list-link-url-input').val('');
		newItem.find('.organic-widgets-feature-list-text-input').html('');
		newItem.find('.organic-widgets-feature-list-text-input').val('');

		// Append New Item
		$(newItem).insertAfter(lastItem);

		// Rebind click events
		organicWidgetsCustomDropdown();

	}

	/*--------- Move Feature Up ----------*/
	function organicWidgetsReorderRepeatableFormItems( feature, direction ) {

		if ( $(feature).hasClass('organic-widgets-repeatable-form-item') ) {
			var formItem = $(feature);
		} else {
			var formItem = $(feature).closest('.organic-widgets-repeatable-form-item');
		}
		var form = $(formItem).parents('.organic-widgets-repeatable-form-item-widget-admin');
		var allFormItems = form.find('.organic-widgets-repeatable-form-item');

		// Move Up
		if ( direction == 'up' && allFormItems.first().data('feature-id') != formItem.data('feature-id') ) {

			// Get previous item
			var prevItem = formItem.prev('.organic-widgets-repeatable-form-item');

			// Insert before previous
			formItem.insertBefore(prevItem);

			// Update main input
			var theForm = $(formItem).closest('.organic-widgets-repeatable-form-item-widget-admin');
			organicWidgetsRepeatableFormItemUpdateMainArray(theForm);

		}
		// Move Down
		else if ( direction == 'down' && allFormItems.last().data('feature-id') != formItem.data('feature-id') ) {

			// Get next item
			var nextItem = formItem.next('.organic-widgets-repeatable-form-item');

			// Insert after next
			formItem.insertAfter(nextItem);

			// Update main input
			var theForm = $(formItem).closest('.organic-widgets-repeatable-form-item-widget-admin');
			organicWidgetsRepeatableFormItemUpdateMainArray(theForm);

		}

	}

	/*--------- Open Feature Selector ----------*/
	function organicWidgetsOpenDropdown(dropdown) {

	  var thisDropdown = $(dropdown).parent('.organic-widgets-feature-list-select');
		var thisIcon = thisDropdown.data('val');
    var thisID = thisDropdown.data('feature-id');
		var thisFormItem = $(dropdown).parents('.organic-widgets-repeatable-form-item');

		thisFormItem.find('.organic-widgets-feature-list-select-icon').html('<i class="fa fa-angle-up"></i>');

		thisFormItem.addClass('organic-widgets-show');
  }

	/*--------- Open Feature Selector ----------*/
	function organicWidgetsCloseDropdown(dropdown) {

		var thisFormItem = $(dropdown).parents('.organic-widgets-repeatable-form-item');

		thisFormItem.find('.organic-widgets-feature-list-select-icon').html('<i class="fa fa-angle-down"></i>');

		thisFormItem.removeClass('organic-widgets-show');
  }

	/*---------- Choose Feature -------------*/
	function organicWidgetsChooseFeature( feature ) {

		var thisItem = $(feature);
		var thisVal = thisItem.data('val');
		var thisFeature = thisItem.parents('.organic-widgets-feature-list-select');
		var thisFormItem = thisFeature.parent('.organic-widgets-repeatable-form-item');
		var thisButton = thisItem.parent().siblings('.organic-widgets-dropdown-button');
		var thisPreview = thisFormItem.find('.organic-widgets-feature-list-icon-preview');

		// Update HTML Values
		thisPreview.html('<i class="fa ' + thisVal + '"></i>');
		thisFeature.attr( 'data-val', thisVal );

		// Update Main Data Array
		organicWidgetsRepeatableFormItemUpdateMainArray(thisFormItem);

		// Set active classes
		thisItem.siblings().removeClass('organic-widgets-feature-active');
		thisItem.addClass('organic-widgets-feature-active');

		// HTML Changes
		thisFormItem.find('.organic-widgets-feature-list-select-icon').html('<i class="fa fa-angle-down"></i>');

		// Close Selector
		organicWidgetsCloseDropdown(thisItem);

	}

	/*---------- Update Hidden Input Array -------------*/
	function organicWidgetsRepeatableFormItemUpdateMainArray(item) {

		// Get thisFormAdmin
		if ( $(item).hasClass('organic-widgets-repeatable-form-item-widget-admin') ) {
			var thisFormAdmin = $(item);
		} else {
			var thisFormAdmin = $(item).closest('.organic-widgets-repeatable-form-item-widget-admin');
		}
		var allFormItems = thisFormAdmin.find('.organic-widgets-repeatable-form-item');
		var thisItemData = {};

		var orderNumber = 0;
		allFormItems.each(function(key,el){

			// Find form items and collect values into array
			var data = {};
			var inputs = $(el).find('.organic-widgets-repeatable-form-item-input');
			var activators = [];
			inputs.each(function(){
				// Get input name and values and add to data array
				var inputName = $(this).attr('data-input-name');
				var inputVal = $(this).attr('data-val') ? $(this).attr('data-val') : $(this).val();
				data[inputName] = inputVal;
				// Add to activator array if item is activator
				if ( $(this).attr('data-activator') ) {
					activators.push(inputName);
				}
			});

			var active = false;
			// Check if any activators have values
			for ( var i = 0; i < activators.length; i++ ) {
				if ( data[ activators[i] ] != '' ) {
					active = true;
					break;
				}
			}

			data['order'] = orderNumber;
			data['id'] = $(el).data('feature-id');

			if ( active ) {
				thisItemData[orderNumber] = data;
			}
			orderNumber++;

		});

		var mainInput = thisFormAdmin.find('.organic-widgets-repeatable-hidden-input');
		mainInput.trigger('change');
		mainInput.val(JSON.stringify(thisItemData));

	}

	// Binding main function
	$( document )
	.ajaxComplete( organicWidgetsCustomDropdown );

	$(window).on("load", function() {
		organicWidgetsCustomDropdown();
		if ( typeof wp.customize !== "undefined" ) {
			wp.customize.state.bind('change', function() {
				organicWidgetsCustomDropdown();
			});
			$('.customize-control-widget_form').on('click',function(){
				organicWidgetsCustomDropdown();
			});
		}
	});

})( jQuery );
