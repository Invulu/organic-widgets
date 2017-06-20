(function( $ ) {
	'use strict';

	/* Check The Background Image Brightness ---------------------*/
	function checkBackgroundImageBrightness(){
		console.log('checkBackgroundImageBrightness()');
		$('.organic-widgets-section').backgroundImageBrightness();
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

	$( document )
	.ready( checkBackgroundImageBrightness )
	.ajaxComplete( checkBackgroundImageBrightness );
	// WP Customizer
	if ( typeof wp != "undefined" ) {
		wp.customize.bind( 'preview-ready', _.defer( function() { editShortcutHoverBorder(); }));
	}

})( jQuery );
