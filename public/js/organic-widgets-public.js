(function( $ ) {
	'use strict';





	function editShortcutHoverBorder() {
		setTimeout(function(){
			$('.organic-widget .customize-partial-edit-shortcut').hover(function(){
				$('<div class="organic-widgets-preview-highlighter"></div>').insertAfter( $(this).siblings('.organic-widgets-section') );
			},function(){
				$('.organic-widgets-preview-highlighter').remove();
			});
		},2000);

	}

	// $( window ).load( editShortcutHoverBorder );
	// $( document ).ajaxComplete( editShortcutHoverBorder );
	wp.customize.bind( 'preview-ready', _.defer( function() { editShortcutHoverBorder(); }));
	// wp.customize.bind( 'ready', function () { editShortcutHoverBorder(); } );



})( jQuery );
