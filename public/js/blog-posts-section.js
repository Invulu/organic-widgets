( function( $ ) {
	'use strict';

	/* Masonry ---------------------*/
	function masonrySetup() {
		var $container = $('.organic_widgets-blog-posts-section .organic-widgets-blog-posts-holder');
    console.log($container);
    $container.masonry({
			itemSelector : '.organic-widget-masonry-wrapper'
		});
	}

  $( window ).load( masonrySetup );

})( jQuery );
