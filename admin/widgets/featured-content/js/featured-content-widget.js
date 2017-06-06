/**
 * Featured Section Widget JS
 */
jQuery(document).ready(function($){

	featuredContentWidgetImage = {

		// Call this from the upload button to initiate the upload frame.
		uploader : function( widget_id, widget_id_string ) {

			var frame = wp.media({
				title : FeaturedContentWidget.frame_title,
				multiple : false,
				library : { type : 'image' },
				button : { text : FeaturedContentWidget.button_title }
			});


			// Handle results from media manager.
			frame.on('close',function( ) {

				var attachments = frame.state().get('selection').toJSON();
				featuredContentWidgetImage.render( widget_id, widget_id_string, attachments[0] );
			});

			var removerButton = $("#" + widget_id_string + 'remover_button');
			removerButton.show();

			frame.open();
			return false;
		},

		// Output Image preview and populate widget form.
		render : function( widget_id, widget_id_string, attachment ) {

			$("#" + widget_id_string + 'preview').html(featuredContentWidgetImage.imgHTML( attachment ));
			$("#" + widget_id_string + 'fields').slideDown();
			$("#" + widget_id_string + 'organic_widgets_featured_content_bg_image_id').val(attachment.id);
			$("#" + widget_id_string + 'organic_widgets_featured_content_bg_image').val(attachment.url);
			$("#" + widget_id_string + 'size').val('organic_widgets-featured-medium');


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

			featuredContentWidgetImage.render( widget_id, widget_id_string, false );

			var field = $("#" + widget_id_string + field_name);
			field.hide();

			return false;
		},

	};

});
