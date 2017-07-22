/**
 * JS for Repeatable Feature Icon Images
 */
jQuery(document).ready(function($){

	// Image Uploader Widget
	organicWidgetFeatureIconImage = {

		// Call this from the upload button to initiate the upload frame.
		uploader : function( widget_id, widget_id_string, icon_id_string ) {

			var frame = wp.media({
				title : RepeatableIcon.frame_title,
				multiple : false,
				library : { type : 'image' },
				button : { text : RepeatableIcon.button_title }
			});

			// Handle results from media manager.
			frame.on('close',function( ) {
				var attachments = frame.state().get('selection').toJSON();
				organicWidgetFeatureIconImage.render( widget_id, widget_id_string, icon_id_string, attachments[0] );
			});

			var removerButton = $("#" + widget_id_string + 'remover_button' + icon_id_string);
			removerButton.show();

			frame.open();

			return false;
		},

		// Output Image preview and populate widget form.
		render : function( widget_id, widget_id_string, icon_id_string, attachment ) {

			// Change inputs
			$("#" + widget_id_string + 'image_preview' + icon_id_string ).html(organicWidgetFeatureIconImage.imgHTML( attachment ));
			$("#" + widget_id_string + 'icon' + icon_id_string).val(attachment.id);
			$('.organic-widgets-feature-list-select.organic-widgets-repeatable-form-item-input')
			$("#" + widget_id_string + 'uploader_button' + icon_id_string).html('Change Image');

			// Trigger change in preview window
			$("#" + widget_id_string + 'icon' + icon_id_string).trigger('change');

		},

		// Update input fields if it is empty
		updateInputIfEmpty : function( widget_id_string, icon_id_string, name, value ) {
			var field = $("#" + widget_id_string + name + icon_id_string );
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
		remover : function( widget_id, widget_id_string, icon_id_string, field_name ) {

			organicWidgetFeatureIconImage.render( widget_id, widget_id_string, icon_id_string, false );

			$("#" + widget_id_string + 'uploader_button' + icon_id_string ).html('Select an Image');

			var field = $("#" + widget_id_string + field_name + icon_id_string );
			field.hide();

			// Trigger change in preview window
			$("#" + widget_id_string + 'icon' + icon_id_string ).trigger('change');

			return false;
		},

	};

});
