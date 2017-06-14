( function( $ ) {
	'use strict';

	/* Masonry ---------------------*/
	function masonrySetup() {
		var $container = $('.organic_widgets-team-section .organic-widgets-team-holder');
    console.log($container);
    $container.masonry({
			itemSelector : '.organic-widget-masonry-wrapper'
		});
	}

  $( window ).load( masonrySetup );

})( jQuery );
