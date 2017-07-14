/**
 * JS for Content Aligners
 */
 (function( $ ) {
 	'use strict';

  function contentAligner() {

    $('.organic-widgets-content-alignment-table td').on( 'click', function(){

      var selected = $(this).data('selected');
      var alignment = $(this).data('alignment');
      var aligner = $(this).closest('.organic-widgets-content-alignment');
      var alignmentInput = aligner.find('input');

      if ( ! selected ) {
        alignmentInput.val(alignment);
        aligner.find('td').attr('data-selected', false);
        $(this).attr('data-selected', true);

        // Trigger change in preview window
        alignmentInput.trigger('change');
      }

    });

  }

  $(window).on("load", function() {
		contentAligner();
		if ( typeof wp.customize !== "undefined" ) {
			wp.customize.state.bind('change', function() {
				contentAligner();
			});
			$('.customize-control-widget_form').on('click',function(){
				contentAligner();
			});
		}
	});

})( jQuery );
