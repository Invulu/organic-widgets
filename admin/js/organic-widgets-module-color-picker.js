/**
 * JS for Initializing Color Pickers
 */
 ( function( $ ){

  function initColorPicker( widget ) {
    widget.find( '.organic-widgets-color-picker' ).wpColorPicker( {
      change: _.debounce( function() { // For Customizer
        $(this).trigger( 'change' );
      }, 200 )
    });
  }

  function onFormUpdate( event, widget ) {
    initColorPicker( widget );
  }

  $( document ).on( 'widget-added widget-updated', onFormUpdate );

  $( document ).ready( function() {
    $( '#widgets-right .widget:has(.organic-widgets-color-picker)' ).each( function () {
      initColorPicker( $( this ) );
    } );
  } );

}( jQuery ) );
