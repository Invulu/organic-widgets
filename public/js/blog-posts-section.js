( function( $ ) {
	'use strict';

	/* Masonry ---------------------*/
	function masonrySetup() {
		var $container = $('.organic_widgets-blog-posts-section .organic-widgets-blog-posts-holder');
    console.log($container);
    $container.masonry({
			itemSelector : '.single, .half, .third, .fourth'
		});
	}

  $( window ).load( masonrySetup );

})( jQuery );
