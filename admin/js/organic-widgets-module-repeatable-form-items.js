/**
 * JS for Repeatable Form Items
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
		$('.organic-widgets-repeatable-form-item-input').on('change', function(){
			var formItem = $(this).closest('.organic-widgets-repeatable-form-item');
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
		var theForm = $(addButton).closest('.organic-widgets-repeatable-form-item-widget-admin');

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
    var templateItem = theForm.find('.organic-widgets-repeatable-form-item-template');
    var newItem = templateItem.clone();
		newItem.attr('data-feature-id', newID);
		newItem.attr('data-order', newOrder);
    newItem.removeClass('organic-widgets-repeatable-form-item-template');
    newItem.addClass('organic-widgets-repeatable-form-item');
    newItem.find('.organic-widgets-repeatable-item-number').html(newOrder+1);
		if ( newItem.hasClass('organic-widgets-show') ) {
			newItem.removeClass('organic-widgets-show');
			newItem.find('.organic-widgets-feature-list-icon-preview').html('');
			newItem.find('.organic-widgets-feature-list-select-icon').html('<i class="fa fa-angle-down"></i>');
		}
    giveNewItemID( newItem.find('.organic-widgets-image-uploader'), newID );
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

  /*--------- Give New IDs to image uploaders ----------*/
  function giveNewItemID( item, newID ) {

    var preview = item.closest('.organic-widgets-repeatable-form-item').find('.organic-widgets-feature-list-icon-preview');
		if (preview.length) {
			var existingIDString = preview.attr('id');
	    var newIDString = existingIDString.replace('__x__',newID);
	    preview.attr('id', newIDString);
	    item.find('div').each(function(){
	      var existingIDString = $(this).attr('id');
	      var newIDString = existingIDString.replace('__x__',newID);
	      $(this).attr('id', newIDString);
	      var existingOnClickString = $(this).attr('onclick');
	      var newOnClickString = existingOnClickString.replace('__x__',newID);
	      $(this).attr('onclick', newOnClickString);
	    });
	    item.find('input').each(function(){
	      var existingIDString = $(this).attr('id');
	      var newIDString = existingIDString.replace('__x__',newID);
	      $(this).attr('id', newIDString);
	      var existingNameString = $(this).attr('name');
	      var newNameString = existingNameString.replace('__x__',newID);
	      $(this).attr('name', newNameString);
	      $(this).attr('data-feature-id', newID);
	    });
		}


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
      formItem.css('z-index',100);
      prevItem.fadeTo(150,0);
      var moveHeight = formItem.height() + 20;

      // prevItem.css('opacity',0,400);
      formItem.animate({marginTop: '-'+moveHeight+'px' }, 400, function(){
        formItem.css('z-index','');
        formItem.css('margin-top', '');
        prevItem.hide();
        formItem.insertBefore(prevItem);
        prevItem.slideDown( 400, function(){
          prevItem.fadeTo(150,1);
          // Update main input
          var theForm = $(formItem).closest('.organic-widgets-repeatable-form-item-widget-admin');
          organicWidgetsRepeatableFormItemUpdateMainArray(theForm);
        });

      });

		}
		// Move Down
		else if ( direction == 'down' && allFormItems.last().data('feature-id') != formItem.data('feature-id') ) {

			// Get next item
			var nextItem = formItem.next('.organic-widgets-repeatable-form-item');

      // Insert before previous
      formItem.css('z-index',100);
      // nextItem.fadeTo(150,0);
      var moveHeight = formItem.height() + 20;

      nextItem.fadeTo(150,0 ,function(){
        // prevItem.css('opacity',0,400);
        formItem.animate({marginTop: moveHeight+'px' }, 400, function(){

          formItem.css('z-index','');
          formItem.css('margin-top', '');
          formItem.insertAfter(nextItem);
          nextItem.fadeTo(150,1);

          // Update main input
          var theForm = $(formItem).closest('.organic-widgets-repeatable-form-item-widget-admin');
          organicWidgetsRepeatableFormItemUpdateMainArray(theForm);

        });
      });

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
    var thisFormItem = thisItem.closest('.organic-widgets-repeatable-form-item');
    var thisFeature = thisFormItem.find('.organic-widgets-repeatable-form-item-input[data-input-name="icon"]');
		var thisButton = thisItem.parent().siblings('.organic-widgets-dropdown-button');
    var removerButton = thisFormItem.find('.organic-widgets-remove-image-button');
    var uploaderButton = thisFormItem.find('.organic-widgets-upload-image-button');
    var thisPreview = thisFormItem.find('.organic-widgets-feature-list-icon-preview');

		// Update HTML Values
		thisPreview.html('<i class="fa ' + thisVal + '"></i>');
    thisFeature.val( thisVal );

		// Update Main Data Array
		organicWidgetsRepeatableFormItemUpdateMainArray(thisFormItem);

		// Set active classes
		thisItem.siblings().removeClass('organic-widgets-feature-active');
		thisItem.addClass('organic-widgets-feature-active');

    // Hide Remove Image Button
    removerButton.hide();
    uploaderButton.val('Select Image');

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

			// Order Numbers
      data['order'] = orderNumber;
			$(el).attr('data-order', orderNumber);
      // Reset order numbers in title bars
      $(this).find('.organic-widgets-repeatable-item-number').html( orderNumber +  1 );

			var active = false;
			// Check if any activators have values
			for ( var i = 0; i < activators.length; i++ ) {
				if ( data[ activators[i] ] != '' ) {
					active = true;
					break;
				}
			}

      data['id'] = $(el).data('feature-id');

      // If active, push into main object
			if ( active ) {
        thisItemData[orderNumber] = data;
			}
			orderNumber++;

    });

		// Get Values and update main input
		var mainInput = thisFormAdmin.find('.organic-widgets-repeatable-hidden-input');
		var oldInputVal = mainInput.val();
		var newInputVal = JSON.stringify(thisItemData);
    mainInput.val(newInputVal);

		// If there has been a change, trigger updates
		if (oldInputVal !=  newInputVal ) {

			mainInput.trigger('change');

			// If customizer and there are new changes, force refresh
			if ( typeof wp.customize !== "undefined" ) {

				var saveButton = $(thisFormAdmin).closest('.form').find('[name=savewidget]');

				if ( saveButton.length ) {
					if ( saveButton.css('display') !== 'none' ) {
						saveButton.trigger('click');
					}
				}

			}

		}

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
