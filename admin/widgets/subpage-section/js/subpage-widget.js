/**
 * Tribe Image Widget Javascript
 * @author Modern Tribe, Inc.
 * Copyright 2013.
 */
jQuery(document).ready(function($){

	subpageWidgetImage = {

		// Call this from the upload button to initiate the upload frame.
		uploader : function( widget_id, widget_id_string ) {

			var frame = wp.media({
				title : SubpageWidget.frame_title,
				multiple : false,
				library : { type : 'image' },
				button : { text : SubpageWidget.button_title }
			});

			// Handle results from media manager.
			frame.on('close',function( ) {
				var attachments = frame.state().get('selection').toJSON();
				subpageWidgetImage.render( widget_id, widget_id_string, attachments[0] );
			});

			var removerButton = $("#" + widget_id_string + 'remover_button');
			removerButton.show();

			frame.open();
			return false;
		},

		// Output Image preview and populate widget form.
		render : function( widget_id, widget_id_string, attachment ) {

			$("#" + widget_id_string + 'preview').html(subpageWidgetImage.imgHTML( attachment ));
			$("#" + widget_id_string + 'fields').slideDown();
			$("#" + widget_id_string + 'organic_widgets_subpage_bg_image_id').val(attachment.id);
			$("#" + widget_id_string + 'organic_widgets_subpage_bg_image').val(attachment.url);
			$("#" + widget_id_string + 'size').val('organic_widgets-featured-large');


		},

		// Update input fields if it is empty
		updateInputIfEmpty : function( widget_id_string, name, value ) {
			var field = $("#" + widget_id_string + name);
			if ( field.val() == '' ) {
				field.val(value);
			}
		},

		// Render html for the image.
		imgHTML : function( attachment ) {
			if ( attachment ) {
				var img_html = '<img src="' + attachment.url + '" ';
				img_html += '/>';
			} else {
				var img_html = '';
			}
			return img_html;
		},

		// Call this from the upload button to initiate the upload frame.
		remover : function( widget_id, widget_id_string, field_name ) {

			subpageWidgetImage.render( widget_id, widget_id_string, false );

			var field = $("#" + widget_id_string + field_name);
			field.hide();

			return false;
		},

	};

});