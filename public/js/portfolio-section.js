( function( $ ) {
	'use strict';

	/* Masonry ---------------------*/
	function masonrySetup() {
		var $container = $('.organic_widgets-portfolio-section .organic-widgets-portfolio-holder');
    console.log($container);
    $container.masonry({
			itemSelector : '.organic-widget-masonry-wrapper'
		});
	}

  $( window ).load( masonrySetup );

})( jQuery );
