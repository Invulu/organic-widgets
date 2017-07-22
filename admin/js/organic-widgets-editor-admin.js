/**
 * JS for Edit Pages
 */
(function( $ ) {
	'use strict';

	function checkSelectedTemplate() {

		var selectedTemplate = $('#page_template').find(':selected').val();

		if ( selectedTemplate.indexOf( 'organic-custom-template.php' ) !== -1 ) {
			hideEditor();
		} else {
			showEditor();
		}

	}

	function hideEditor() {
		var wpContentEditorDiv = $('#postdivrich');
		var organicCustomEditDiv = $(document.createElement('div'));

		if ( ! organicWidgets.isCustomTemplate ) {
			var customizeDisabled = 'disabled';
			var submitDisabled = '';
			var customizeLink = '#';
			var updateButton = '<div id="organic-widgets-update-post" class="button button-primary button-large organic-button-large" '+submitDisabled+'>Update</div>';
			var setPageTemplate = '<div class="organic-widgets-post-editor-update-post"><p>Please update post to apply custom template</p>'+updateButton+'<p>And then...</p></div>';
			var buttonSize = '';

		} else {
			var setPageTemplate = '';
			var customizeDisabled = '';
			var submitDisabled = 'disabled';
			var customizeLink = organicWidgets.customizeURL;
			var buttonSize = 'organic-button-large';
		}

		var customizeButton = '<a href="'+customizeLink+'" class="button button-primary button-large organic-widgets-customize-page-button '+buttonSize+'" '+customizeDisabled+'>Customize Page</a>';
		organicCustomEditDiv.attr( 'id', 'organic-widgets-post-editor' );
		organicCustomEditDiv.addClass('postbox');
		organicCustomEditDiv.html('<h2 class="hndle ui-sortable-handle"><img src="'+organicWidgets.leafIcon+'"/><span>Organic Custom Widgets Page</span></h2><div class="organic-widgets-post-editor-content">' + setPageTemplate + customizeButton + '</div>');
		wpContentEditorDiv.before(organicCustomEditDiv);
		wpContentEditorDiv.hide();

		updatePostListener();

	}

	function showEditor(){
		$('#postdivrich').show();
		$('#organic-widgets-post-editor').remove();
	}

	function pageTemplateListener() {

		$('#page_template').on( 'change', function(){
			checkSelectedTemplate();
		});

	}

	function updatePostListener() {
		$('#organic-widgets-update-post').on( 'click', function(){
			$('#post').submit();
		});
	}

	$( document )
	.ready( checkSelectedTemplate )
	.ready( pageTemplateListener );

})( jQuery );
