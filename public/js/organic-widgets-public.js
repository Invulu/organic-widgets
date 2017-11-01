(function( $ ) {

	'use strict';

	/* Check The Background Brightness ---------------------*/
	function checkBackgroundBrightness(){
		$('.organic-widgets-section').not('.organic-widgets-content-slideshow-section').backgroundBrightness();
	}

	/* Add Section Highlighting When Hovering Over Edit Shortcuts ---------------------*/
	function editShortcutHoverBorder() {
		setTimeout(function(){
			$('.organic-widget .customize-partial-edit-shortcut').hover(function(){
				$('<div class="organic-widgets-preview-highlighter"></div>').insertAfter( $(this).siblings('.organic-widgets-section') );
			},function(){
				$('.organic-widgets-preview-highlighter').remove();
			});
		},2000);

	}

	/* Group Groupable Widgets ---------------------*/
	function groupGroupableWidgets() {

		// Loop through all groups
		$('.organic-widgets-groupable-first').each(function(){

			var firstGroupItem = $(this);
			var parentContainer = firstGroupItem.closest('.organic-widget');
			var groupID = $(this).data('group-id');
			var group = $('*[data-group-id="'+groupID+'"]');
			var numItems = group.length;
			if (numItems == 1 || numItems == 0 ) {
				var itemClass = 'organic-widgets-single';
			} else if (numItems == 2 || numItems == 4) {
				var itemClass = 'organic-widgets-half';
			} else {
				var itemClass = 'organic-widgets-third';
			}

			parentContainer.before( '<div class="organic-widgets-section organic-widgets-group"><div class="organic-widgets-group-container" data-group-id="' + groupID + '"></div></div>');

			// Get container
			var container = $('.organic-widgets-group-container[data-group-id="' + groupID + '"]');
			var containerWrapperSection = container.closest('.organic-widgets-section');

			// Loop through all elements of that group
			group.each(function(){

				var groupableWidget = $(this).closest('.organic-widget');

				if ( $(this).hasClass('organic-widgets-groupable-first') ) {
					var bgColor = $(this).css('background-color');
					var bgImage = $(this).css('background-image');
					$(this).css('background-color','');
					$(this).css('background-image','');
					containerWrapperSection.css('background-color', bgColor);
					containerWrapperSection.css('background-image', bgImage);
				} else {
					$(this).css('background-color','');
				}
				if ( ! $(this).hasClass('organic-widgets-groupable-widget') ) {
					$(this).addClass('organic-widgets-groupable-widget');
				}

				groupableWidget.addClass(itemClass);
				container.append(groupableWidget);

			});

			$('.organic-widgets-group .organic-widgets-featured-content-section .information').addClass('organic-widgets-card');

		});

		checkBackgroundBrightness();

	}

	function editShortcutHoverBorderReady() {

		// WP Customizer
		if ( typeof wp.customize != "undefined" ) {
			wp.customize.bind( 'preview-ready', _.defer( function() { editShortcutHoverBorder(); }));
		}

	}

	$( document )
	.ready( checkBackgroundBrightness )
	.ready( groupGroupableWidgets )
	.ready( editShortcutHoverBorderReady )
	.ajaxComplete( checkBackgroundBrightness );

})( jQuery );
