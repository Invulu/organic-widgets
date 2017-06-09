(function( api, $ ) {
	console.log('asdf');
	api.bind( 'preview-ready', function() {
    $( '.site-title' ).on( 'mouseover', function() {
			console.log('mousover');
			api.preview.send( 'site-title-mousedover', 'some optional message value' );
    } );

  } );
}) ( wp.customize, jQuery );
