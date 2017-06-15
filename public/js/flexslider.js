( function( $ ) {
	'use strict';

	/* Flexslider ---------------------*/
	function flexSliderSetup() {
		if( ($).flexslider) {
			var slider = $('.organic-widgets-flexslider');
			slider.flexslider({
				slideshowSpeed		: slider.attr('data-speed'),
				animationDuration	: 800,
				animation			: slider.attr('data-transition'),
				video				: false,
				useCSS				: false,
				prevText			: '<svg style="" width="100%" height="100%" viewBox="0 0 50 80" xml:space="preserve"><polyline fill="none" stroke="#000000" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" points="45.63,75.8 0.375,38.087 45.63,0.375 "/></svg><svg style="position:absolute;left:-2px;" width="100%" height="100%" viewBox="0 0 50 80" xml:space="preserve"><polyline fill="none" stroke="#FFFFFF" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" points="45.63,75.8 0.375,38.087 45.63,0.375 "/></svg>',
				nextText			: '<svg style="position:absolute;right:2px;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="100%" viewBox="0 0 50 80" xml:space="preserve"><polyline fill="none" stroke="#000000" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" points="0.375,0.375 45.63,38.087 0.375,75.8 "/></svg><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="100%" viewBox="0 0 50 80" xml:space="preserve"><polyline fill="none" stroke="#FFFFFF" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" points="0.375,0.375 45.63,38.087 0.375,75.8 "/></svg>',
				touch				: false,
				controlNav			: false,
				animationLoop		: true,
				smoothHeight		: true,
				pauseOnAction		: true,
				pauseOnHover		: true,

				start: function(slider) {
					slider.removeClass('loading');
					$( ".preloader" ).hide();
				}
			});
		}
	}

  $( window ).load( flexSliderSetup );

})( jQuery );
