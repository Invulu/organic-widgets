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

	/* Add Section Highlighting When Hovering Over Edit Shortcuts ---------------------*/
	function groupGroupableWidgets() {

		//Loop through all groups
		$('.organic-widgets-groupable-first').each(function(){

			var firstGroupItem = $(this);
			var parentContainer = firstGroupItem.parents('.organic-widgets-section');
			// if ( ! parentContainer.hasClass('organic-widgets-masonry-container') ) {
			// 	parentContainer.addClass('organic-widgets-masonry-container');
			// }
			parentContainer.wrapInner( '<div class="organic-widgets-profile-section-masonry-buffer organic-widgets-masonry-container"></div>');


			//Loop through all elements of that group
			var groupID = $(this).data('group-id');
			var group = $('*[data-group-id="'+groupID+'"]');
			var numItems = group.length;
			if (numItems == 1) {
				var itemClass = 'organic-widgets-single';
			} else if (numItems == 2) {
				var itemClass = 'organic-widgets-half';
			} else if (numItems == 3) {
				var itemClass = 'organic-widgets-third';
			} else if ( numItems == 4 ) {
				var itemClass = 'organic-widgets-half';
			} else {
				var itemClass = 'organic-widgets-third';
			}

			group.each(function(){

				if ( ! $(this).hasClass('organic-widgets-groupable-first') ) {
					$(this).unwrap();
					$(this).insertAfter(firstGroupItem);
				}

				if ( ! $(this).hasClass('organic-widgets-groupable-widget') ) {
					$(this).addClass('organic-widgets-groupable-widget');
				}
				if ( ! $(this).hasClass('organic-widget-masonry-wrapper') ) {
					$(this).addClass('organic-widget-masonry-wrapper');
				}
				$(this).addClass(itemClass);

				if ( $(this).hasClass('organic-widgets-groupable-last') ) {

					$('<div class="organic-widgets-clear"></div>').insertAfter(this);
				}

			});



		});

		masonrySetup();

	}

	/* Masonry ---------------------*/
	function masonrySetup() {
		var $container = $('.organic-widgets-masonry-container');
    $container.masonry({
			itemSelector : '.organic-widget-masonry-wrapper'
		});
	}


	$( document )
	.ready( checkBackgroundBrightness )
	.ready( groupGroupableWidgets )
	.ajaxComplete( checkBackgroundBrightness )
	.ajaxComplete( groupGroupableWidgets );
	// WP Customizer
	if ( typeof wp != "undefined" ) {
		wp.customize.bind( 'preview-ready', _.defer( function() { editShortcutHoverBorder(); }));
	}

})( jQuery );
