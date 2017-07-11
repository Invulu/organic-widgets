(function( $ ) {
	'use strict';

	function checkSelectedTemplate() {

		var selectedTemplate = $('#page_template').find(':selected').val();

		if ( selectedTemplate.indexOf( 'organic-custom-template.php' ) !== -1 ) {
			hideEditor();
		} else {

		}

	}

	function hideEditor() {
		var wpContentEditorDiv = $('#postdivrich');
		var organicCustomEditDiv = $(document.createElement('div'));
		organicCustomEditDiv.html('<div id="organic-widgets-post-editor"><div class="organic-widgets-post-editor-content"><a href="'+organicWidgets.customizeURL+'" class="button organic-widgets-customize-page-button">Customize Page</a></div></div>');
		wpContentEditorDiv.before(organicCustomEditDiv);
		wpContentEditorDiv.hide();

	}


	$( document )
	.ready( checkSelectedTemplate );


})( jQuery );
