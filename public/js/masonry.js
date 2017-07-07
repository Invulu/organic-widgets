( function( $ ) {
	'use strict';

	/* Masonry ---------------------*/
	function masonrySetup() {
		var $container = $('.organic-widgets-masonry-container');
    $container.masonry({
			itemSelector : '.organic-widgets-masonry-wrapper'
		});
	}

  $( window ).load( masonrySetup );

})( jQuery );
