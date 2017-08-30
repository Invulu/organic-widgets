/**
 * JS for Image Backgrounds
 */
jQuery(document).ready(function($){

	// Image Uploader Widget
	organicWidgetFeaturedImage = {

		// Call this from the upload button to initiate the upload frame.
		uploader : function( widget_id, widget_id_string ) {

			var frame = wp.media({
				title : OrganicWidgetIMG.frame_title,
				multiple : false,
				library : { type : 'image' },
				button : { text : OrganicWidgetIMG.button_title }
			});

			// Handle results from media manager.
			frame.on('close',function( ) {
				var attachments = frame.state().get('selection').toJSON();
				organicWidgetFeaturedImage.render( widget_id, widget_id_string, attachments[0] );
			});

			var removerButton = $("#" + widget_id_string + 'featured_image_remover_button');
			removerButton.show();

			frame.open();

			return false;
		},

		// Output Image preview and populate widget form.
		render : function( widget_id, widget_id_string, attachment ) {

			// Change inputs
			$("#" + widget_id_string + 'featured_image_preview').html(organicWidgetFeaturedImage.imgHTML( attachment ));
			$("#" + widget_id_string + 'featured_image_fields').slideDown();
			$("#" + widget_id_string + 'featured_image_id').val(attachment.id);
			$("#" + widget_id_string + 'featured_image').val(attachment.url);
			$("#" + widget_id_string + 'featured_image_uploader_button').val('Change Image');

			// Trigger change in preview window
      $("#" + widget_id_string + 'featured_image').trigger('change');
			var section = $('#' + widget_id).find('.organic-widgets-section');

			section.css('background-image', attachment);

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

			organicWidgetFeaturedImage.render( widget_id, widget_id_string, false );

			$("#" + widget_id_string + 'featured_image_uploader_button').val('Select an Image');

			var field = $("#" + widget_id_string + field_name);
			field.hide();

      // Trigger change in preview window
      $("#" + widget_id_string + 'featured_image').trigger('change');

			return false;
		},

	};

});
