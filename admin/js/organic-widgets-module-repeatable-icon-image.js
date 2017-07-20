/**
 * Feature List Icon Images
 */
jQuery(document).ready(function($){

	// Image Uploader Widget
	organicWidgetFeatureIconImage = {

		// Call this from the upload button to initiate the upload frame.
		uploader : function( widget_id, widget_id_string, repeatable_id_string ) {

			var frame = wp.media({
				title : RepeatableIcon.frame_title,
				multiple : false,
				library : { type : 'image' },
				button : { text : RepeatableIcon.button_title }
			});

			// Handle results from media manager.
			frame.on('close',function( ) {
				var attachments = frame.state().get('selection').toJSON();
				organicWidgetBackgroundImage.render( widget_id, icon_id_string, organicWidgetFeatureIconImage, attachments[0] );
			});

			var removerButton = $("#" + icon_id_string + 'remover_button');
			removerButton.show();

			frame.open();

			return false;
		},

		// Output Image preview and populate widget form.
		render : function( widget_id, icon_id_string, attachment ) {

			// Change inputs
			$("#" + icon_id_string + 'preview').html(organicWidgetBackgroundImage.imgHTML( attachment ));
			$("#" + icon_id_string + 'fields').slideDown();
			$("#" + icon_id_string + 'bg_image_id').val(attachment.id);
			$("#" + icon_id_string + 'bg_image').val(attachment.url);
			$("#" + icon_id_string + 'size').val('organic-widgets-featured-large');

			$("#" + icon_id_string + 'uploader_button').val('Change Image');

			// Trigger change in preview window
			$("#" + icon_id_string + 'bg_image').trigger('change');


			var section = $('#' + widget_id).find('.organic-widgets-section');

			section.css('background-image', attachment);

		},

		// Update input fields if it is empty
		updateInputIfEmpty : function( icon_id_string, name, value ) {
			var field = $("#" + icon_id_string + name);
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
		remover : function( widget_id, icon_id_string, field_name ) {

			organicWidgetBackgroundImage.render( widget_id, icon_id_string, false );

			$("#" + icon_id_string + 'uploader_button').val('Select an Image');

			var field = $("#" + icon_id_string + field_name);
			field.hide();

			// Trigger change in preview window
			$("#" + icon_id_string + 'bg_image').trigger('change');

			return false;
		},

	};

});
