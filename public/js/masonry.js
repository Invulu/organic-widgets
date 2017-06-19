( function( $ ) {
	'use strict';

	/* Masonry ---------------------*/
	function masonrySetup() {
		var $container = $('.organic-widgets-post-holder');
    $container.masonry({
			itemSelector : '.organic-widget-masonry-wrapper'
		});
	}

  $( window ).load( masonrySetup );

})( jQuery );
