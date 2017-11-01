<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://organicthemes.com
 * @since      1.0.0
 *
 * @package    Organic_Widgets
 * @subpackage Organic_Widgets/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Organic_Widgets
 * @subpackage Organic_Widgets/public
 * @author     Organic Themes <info@organicthemes.com>
 */
class Organic_Widgets_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/organic-widgets-public.css', array(), $this->version, 'all' );

		$organic_widgets_settings = get_option('organic_widgets_settings');

		if ( $organic_widgets_settings['additional_stylesheets'] ) {

			switch($organic_widgets_settings['additional_stylesheets']) {
				case 2:
					wp_enqueue_style( $this->plugin_name . '_additional_' .$organic_widgets_settings['additional_stylesheets'] , plugin_dir_url( __FILE__ ) . 'css/organic-widgets-public-additional-2.css', array(), $this->version, 'all' );
					break;
				case 3:
					wp_enqueue_style( $this->plugin_name . '_additional_' .$organic_widgets_settings['additional_stylesheets'], plugin_dir_url( __FILE__ ) . 'css/organic-widgets-public-additional-3.css', array(), $this->version, 'all' );
					break;
			}
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/organic-widgets-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
   * Echo video bg scripts to page
   *
   * @since    	1.0.0
   *
   */
  public function video_bg_script() {

		// Global Variable
		global $organic_widgets_video_bgs;

		if ( ! is_array( $organic_widgets_video_bgs ) || ! count( $organic_widgets_video_bgs ) ) {
			return false;
		}

		// Start outputting javascript to page.
		echo "<script>
		//determine if devices is small or is iOS, where autoplay isn't enabled
		if (typeof iOSOrSmall != 'function') {
			function iOSOrSmall() {
				if ( window.innerWidth < 691 ) { return true; }
				var iDevices = [
					'iPad Simulator',
					'iPhone Simulator',
					'iPod Simulator',
					'iPad',
					'iPhone',
					'iPod'
				];
				while (iDevices.length) {
					if (navigator.platform === iDevices.pop()){ return true; }
				}
				return false;
			}
		}
		//if device is not small or ios, load video background from youtube
		if (!iOSOrSmall()) {
			// This code loads the IFrame Player API code asynchronously
			var tag = document.createElement('script');
			tag.src = 'https://www.youtube.com/iframe_api';
			var firstScriptTag = document.getElementsByTagName('script')[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
			// This function creates an <iframe> (and YouTube player)
			// after the API code downloads.
			function onYouTubeIframeAPIReady() { ";

		//Loop through all widgets with video bg
		foreach( $organic_widgets_video_bgs as $widget ) {

  		if ( $widget['video_type'] == 'youtube' && $widget['video_id'] ) {
  			echo "var player".$widget['clean_widget_id'].";
					player".$widget['clean_widget_id']." = new YT.Player('".$widget['clean_widget_id']."', {
  					height: '1014',
  					width: '1920',
						// fitToBackground: true,
  					videoId: '".$widget['video_id']."',
						playerVars: {
  						'loop':  '1',
  						'modestbranding': '1',
  						'autoplay':  '1',
  						'showinfo':  '0',
  						'controls':  '0',
  						'start': '0',
  						'playlist': '".esc_html__( $widget['video_id'] )."',
  						'rel': '0',
							'enablejsapi': '1',
							'origin': '".get_site_url()."',
							'iv_load_policy': '3'
  					},
						events: {
  						'onReady': onPlayerReady".$widget['clean_widget_id'].",
  						'onStateChange': onPlayerStateChange".$widget['clean_widget_id']."
  					}
  				});";

			}//End if
		}//End foreach

			echo "}";//End onYouTubeIframeAPIReady()

		//Loop through all widgets with video bg
		foreach( $organic_widgets_video_bgs as $widget ) {

			if ( $widget['video_type'] == 'youtube' && $widget['video_id'] ) {
					echo "// Mute and start playing video when ready
	        function onPlayerReady".$widget['clean_widget_id']."(event) {
	          event.target.mute();
	  				event.target.playVideo();
						var width = jQuery('#".$widget['widget_id']."').outerWidth();
						var height = Math.round( width * (9/16) );
						if ( height < jQuery('#".$widget['widget_id']."').outerHeight() ) {
							height = jQuery('#".$widget['widget_id']."').outerHeight();
							width = Math.round( height * 1.78);
						}
						event.target.a.style.width = width + 'px';
						event.target.a.style.maxWidth = width + 'px';
						event.target.a.style.height = height + 'px';
	  			}
	  			// Fade out overlay image
	  			function onPlayerStateChange".$widget['clean_widget_id']."(event) {
						if (event.data == YT.PlayerState.PLAYING) {
							setTimeout( function(){
								var width = jQuery('#".$widget['widget_id']."').outerWidth();
								var height = Math.round( width * (9/16) );
								if ( height < jQuery('#".$widget['widget_id']."').outerHeight() ) {
									height = jQuery('#".$widget['widget_id']."').outerHeight();
									width = Math.round( height * 1.78);
								}
								event.target.a.style.width = width + 'px';
								event.target.a.style.maxWidth = width + 'px';
			  				event.target.a.style.height = height + 'px';
								jQuery('.organic-widgets-video-bg-wrapper').find('iframe').fadeTo('slow', 1);
								jQuery('.organic-widgets-video-bg-wrapper').find('video').fadeTo('slow', 1);
							}, 100);
	  				}
	  			}";
	  	} // End if
		}//End foreach

		echo '}// END if (!iOSOrSmall())
		</script>';

  }

	/**
	 * Add body class for pages using custom template
	 *
	 * @since    	1.0.0
	 *
	 */
	 function add_body_class( $classes ) {

		 global $post;

		 if ( is_object( $post ) ) {
			 $page_template_slug = get_page_template_slug( $post->ID );
				if ( strpos( $page_template_slug, 'organic-custom-template.php' ) !== false ) {
					$classes[] = 'organic-widgets-custom-template';
				}
		 }

     return $classes;

	 }


}
