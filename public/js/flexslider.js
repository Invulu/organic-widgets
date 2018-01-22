( function( $ ) {
	'use strict';

	/* Flexslider ---------------------*/
	function flexSliderSetup() {

		if ( ($).flexslider) {
			var content_slider = $('.organic-widgets-content-slideshow-section .organic-widgets-flexslider');
			var testimonial_slider = $('.organic-widgets-testimonial-section .organic-widgets-flexslider');
			content_slider.flexslider({
				slideshowSpeed		: content_slider.attr('data-speed'),
				animationDuration	: 800,
				animation					: content_slider.attr('data-transition'),
				video							: false,
				useCSS						: false,
				prevText					: '<i class="fa fa-angle-left"></i>',
				nextText					: '<i class="fa fa-angle-right"></i>',
				touch							: false,
				controlNav				: false,
				animationLoop			: true,
				smoothHeight			: content_slider.attr('data-height'),
				pauseOnAction			: true,
				pauseOnHover			: true,

				start: function(content_slider) {
					content_slider.removeClass('loading');
					$( ".preloader" ).hide();
				}
			});
			testimonial_slider.flexslider({
				slideshowSpeed		: 18000,
				animationDuration	: 800,
				animation					: 'slide',
				video							: false,
				useCSS						: false,
				touch							: true,
				prevText					: '<i class="fa fa-angle-left"></i>',
				nextText					: '<i class="fa fa-angle-right"></i>',
				controlNav				: true,
				animationLoop			: false,
				smoothHeight			: false,
				pauseOnAction			: true,
				pauseOnHover			: true,
				itemWidth 				: 320,
				itemMargin				: 24,
				maxItems					: testimonial_slider.attr('data-per-slide'),
				minItems					: 1,

				start: function(testimonial_slider) {
					testimonial_slider.removeClass('loading');
					$( ".preloader" ).hide();
				}
			});
		}

	}

  $( window ).load( flexSliderSetup );
	$( window ).resize( flexSliderSetup );
	$( document ).ajaxComplete( flexSliderSetup );

})( jQuery );
