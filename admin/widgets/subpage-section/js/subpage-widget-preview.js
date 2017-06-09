// customize-controls-example.js
(function( api, $ ) {
  console.log('preview');
    api.bind( 'ready', function() {

        // Wait for the site title control to be added.
        api.control( 'blogname', function( siteTitleControl ) {
            api.previewer.bind( 'site-title-mousedover', function( message ) {
                console.info( 'Message sent from preview:', message );
                console.info( 'The jQuery container element for the site title control:', siteTitleControl.container );
            } );
        } );
    } );
}) ( wp.customize, jQuery );
