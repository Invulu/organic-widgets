( function( $ ) {
	'use strict';

	/* Masonry ---------------------*/
	function masonrySetup() {
		var $container = $('.organic-widgets-post-holder');
    console.log($container);
    $container.masonry({
			itemSelector : '.organic-widget-masonry-wrapper'
		});
	}

  $( window ).load( masonrySetup );

})( jQuery );
