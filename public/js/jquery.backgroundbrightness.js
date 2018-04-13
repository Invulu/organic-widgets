/*
 *  backgroundBrightness.js
 *
 *  Copyright 2016, Jesse Lee & Organic Themes w/ snippets from ColourBrightness.js
 *  Released under the WTFPL license
 *  https://organicthemes.com
 *
 *  Version: 1.0
 */

(function ($) {

    "use strict";

    $.fn.extend({

      backgroundBrightness: function (contentSelectors, threshold) {

      // RGB Brightness Threshold
      var threshold = 160;

      // Background Object
      var el = $(this);

      // Content Targets
      if ( ! contentSelectors ) {
        contentSelectors = false;
      } else if ( !( contentSelectors.constructor === Array) ) {
        contentSelectors = [contentSelectors];
      }

      //Loop Through all Items
      $.each(el, function(key, value){

        //Initialize
        var r,g,b,brightness;

        //Get Background Styles for element
        var bgStyles = getBackgroundStyles( $(value) );
        var colour = bgStyles.bgColor;
        var image = bgStyles.bgImage;
        var targetedElement = bgStyles.targetedElement;

        // If bg image found, calculate luminosity of image
        if ( image ) {
          getBackgroundImageLuminosity( targetedElement, contentSelectors, threshold);
        }
        // If no bg image, calculate bg color brightness
        else if ( colour ) {
          if (colour.match(/^rgb/)) {
            colour = colour.match(/rgba?\(([^)]+)\)/)[1];
            colour = colour.split(/ *, */).map(Number);
            r = colour[0];
            g = colour[1];
            b = colour[2];
          } else if ("#" === colour[0] && 7 === colour.length) {
            r = parseInt(colour.slice(1, 3), 16);
            g = parseInt(colour.slice(3, 5), 16);
            b = parseInt(colour.slice(5, 7), 16);
          } else if ("#" === colour[0] && 4 === colour.length) {
            r = parseInt(colour[1] + colour[1], 16);
            g = parseInt(colour[2] + colour[2], 16);
            b = parseInt(colour[3] + colour[3], 16);
          }

          brightness = (r * 299 + g * 587 + b * 114) / 1000;

          if (brightness < threshold) {
            // white text
            targetedElement.removeClass("ocw-bg-light").addClass("ocw-bg-dark");
          } else {
            // black text
            targetedElement.removeClass("ocw-bg-dark").addClass("ocw-bg-light");
          }

        }

      });

      function getBackgroundImageLuminosity(el, contentSelectors, threshold ) {

        var bg = el.css("backgroundImage");
        bg = bg.replace(/^url\(['"]?/, "").replace(/['"]?\)$/, "");

        getImageLightness( bg, el ,function(brightness,el){

          // Set classes to add and to remove
          if (brightness < threshold) {
            var addClass = "ocw-bg-dark";
            var removeClass = "ocw-bg-light";
          } else {
            var addClass = "ocw-bg-light";
            var removeClass = "ocw-bg-dark";
          }

          // Add and remove classes
          // If applying to self
          if (!contentSelectors ){
            if ( !el.hasClass("ocw-bg-img") ) el.addClass("ocw-bg-img");
            el.removeClass(removeClass);
            el.addClass(addClass);
          }
          // If applying to custom selectors
          else {
            contentSelectors.forEach(function(contentSelector){
              if ( !$(contentSelector).hasClass("ocw-bg-img") ) $(contentSelector).addClass("ocw-bg-img");
              $(contentSelector).removeClass(removeClass);
              $(contentSelector).addClass(addClass);
            });
          }

          }); // End getImageLightness(){}

      } // End getBackgroundImageLuminosity(){}

      function getImageLightness(imageSrc, imgObject, callback) {
        var img = document.createElement("img");
        img.crossOrigin = "Anonymous";
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

          for ( var x = 0, len = data.length; x < len; x += 4 ) {
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

      /* ======== ColourBrightness Addons =========*/

      function getBackgroundStyles(el) {
        var bgColor = false;
        var bgImage = false;
        while(el[0].tagName.toLowerCase() !== "html") {
          bgColor = ( ! el.css("backgroundColor") || el.css("backgroundColor") === "rgba(0, 0, 0, 0)" || el.css("backgroundColor") === "transparent" ) ? false : el.css("backgroundColor");
          bgImage = ( ! el.css("backgroundImage") || el.css("backgroundImage") === "" || el.css("backgroundImage") === "none" ) ? false : el.css("backgroundImage");
          if ( bgColor || bgImage ) {
            break;
          }
          el = el.parent();
        }
        return { bgColor: bgColor, bgImage: bgImage, targetedElement: el };
      }

    } // End backgroundBrightness(){}

  })

})( jQuery );
