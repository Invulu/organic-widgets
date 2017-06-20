/*
 *  backgroundimagebrightness.js
 *
 *  Copyright 2016, Jesse Lee & Organic Themes
 *  Released under the WTFPL license
 *  https://organicthemes.com
 *
 *  Version: 1.0
 */
( function( $ ) {

"use strict";

	$.fn.backgroundImageBrightness = function(contentSelectors, threshold = 160){

		// Background Object
		var $el = this;

		// Content Targets
		if ( ! contentSelectors ) {
			contentSelectors = false;
		} else if ( !( contentSelectors.constructor === Array) ) {
			contentSelectors = [contentSelectors];
		}

		function getBackgroundColor($el, contentSelectors, threshold ) {

			var bg = $el.css('background-image');
			bg = bg.replace(/^url\(['"]?/,'').replace(/['"]?\)$/,'');

			getImageLightness( bg, $el ,function(brightness,$el){

					if (brightness < threshold) {
						// If applying to self
						if (!contentSelectors ){
							$el.removeClass('bg-img-light');
							$el.addClass('bg-img-dark');
						}
						// If applying to custom selectors
						else {
							contentSelectors.forEach(function(contentSelector){
								$(contentSelector).removeClass('bg-img-light');
								$(contentSelector).addClass('bg-img-dark');
							});
						}
					} else {
						//If applying to self
						if (! contentSelectors ){
							$el.removeClass('bg-img-dark');
							$el.addClass('bg-img-light');
						}
						// If applying to custom selectors
						else {
							contentSelectors.forEach(function(contentSelector){
								$(contentSelector).removeClass('bg-img-dark');
								$(contentSelector).addClass('bg-img-light');
							});
						}
					}
				}); // End getImageLightness(){}

		} // End getBackgroundColor(){}

		function getImageLightness(imageSrc, imgObject, callback) {
			var img = document.createElement("img");
			img.src = imageSrc;
			img.style.display = "none";
			document.body.appendChild(img);

			var colorSum = 0;

			img.onload = function() {
				// create canvas
				var canvas = document.createElement( "canvas" );
				canvas.width = this.width;
				canvas.height = this.height;

				var ctx = canvas.getContext( "2d" );
				ctx.drawImage(this,0,0);

				var imageData = ctx.getImageData( 0, 0, canvas.width, canvas.height );
				var data = imageData.data;
				var r,g,b,avg;

				for( var x = 0, len = data.length; x < len; x += 4 ) {
					r = data[x];
					g = data[x + 1];
					b = data[x + 2];

					avg = Math.floor( (r + g + b) / 3 );
					colorSum += avg;
				}

				var brightness = Math.floor(colorSum / (this.width * this.height));
				callback(brightness, imgObject);
			}
		} // End getImageLightness(){}

		$el.each( function(key, value){
			getBackgroundColor( $(value), contentSelectors, threshold);
		});

	} // End backgroundImageBrightness(){}

})( jQuery );
