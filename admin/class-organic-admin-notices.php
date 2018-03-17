<?php

/**
 * Admin Notices
 *
 * @link       https://organicthemes.com
 * @since      1.0.0
 *
 * @package    Organic_Widgets
 * @subpackage Organic_Widgets/admin
 */

/**
 * The admin notice functionality of the plugin.
 *
 * @package		Organic_Widgets
 * @subpackage Organic_Widgets/admin
 * @author		 Organic Themes <info@organicthemes.com>
 */
class Organic_Widgets_Admin_Notices {

	/** Function organic_widgets_admin_notice */
	function organic_widgets_admin_notice_active() {

		if ( ! PAnD::is_admin_notice_active( 'notice-organic-widgets-activated-forever' ) ) {
			return;
		}
		?>

			<div data-dismissible="notice-organic-widgets-activated-forever" class="notice updated is-dismissible">

				<h2 style="margin-bottom: 0px;"><?php printf( __( 'Thanks for using <a href="%1$s" target="_blank">Organic Customizer Widgets</a>! If you like the plugin, could you please take a moment to give us a <a href="%2$s" target="_blank"><strong>5-star rating</strong></a>?', ORGANIC_WIDGETS_18N ), 'https://organicthemes.com/', 'https://wordpress.org/support/plugin/organic-customizer-widgets/reviews/#new-post' ); ?></h2>
				<p><?php esc_html_e( 'A positive rating will keep us motivated to continue supporting and improving this free plugin, and will help spread its popularity. Your help is greatly appreciated!', ORGANIC_WIDGETS_18N ); ?></p>
				<p><?php printf( __( '<button class="button button-primary" href="%1$s" target="_blank">Leave 5-Star Rating!</button>', ORGANIC_WIDGETS_18N ), 'https://wordpress.org/support/plugin/organic-customizer-widgets/reviews/#new-post' ); ?></p>

			</div>

		<?php

	}

	/** Function organic_widgets_admin_notice */
	function organic_widgets_admin_notice_2_weeks() {

		$install_date = get_option( 'organic_widgets_install_date' );
		// $install_date = date( '2018-02-12 12:00:00' ); // Testing date.
		// $install_date = ''; // Testing date.
		$display_date = date( 'Y-m-d h:i:s' );
		$datetime1 = new DateTime( $install_date );
		$datetime2 = new DateTime( $display_date );
		$diff_intrval = round( ($datetime2->format( 'U' ) - $datetime1->format( 'U' )) / (60 * 60 * 24) );

		if ( ! PAnD::is_admin_notice_active( 'notice-organic-widgets-weeks-forever' ) ) {
			return;
		}
		?>

		<?php if ( $diff_intrval >= 14 ) { ?>

			<div data-dismissible="notice-organic-widgets-weeks-forever" class="notice updated is-dismissible">

				<h2 style="margin-bottom: 0px;"><?php printf( __( 'Sweet! You\'ve been using <a href="%1$s" target="_blank">Organic Customizer Widgets</a> for a couple weeks! Could you please take a moment to give us a <a href="%2$s" target="_blank"><strong>5-star rating</strong></a>?', ORGANIC_WIDGETS_18N ), 'https://organicthemes.com/', 'https://wordpress.org/support/plugin/organic-customizer-widgets/reviews/#new-post' ); ?></h2>
				<p><?php esc_html_e( 'A positive rating will keep us motivated to continue supporting and improving this free plugin, and will help spread its popularity. Your help is greatly appreciated!', ORGANIC_WIDGETS_18N ); ?></p>
				<p><?php printf( __( '<button class="button button-primary" href="%1$s" target="_blank">Leave 5-Star Rating!</button>', ORGANIC_WIDGETS_18N ), 'https://wordpress.org/support/plugin/organic-customizer-widgets/reviews/#new-post' ); ?></p>

			</div>

		<?php }

	}

	/** Function organic_widgets_admin_notice */
	function organic_widgets_admin_notice_1_month() {

		$install_date = get_option( 'organic_widgets_install_date' );
		// $install_date = date( '2018-02-12 12:00:00' ); // Testing date.
		// $install_date = ''; // Testing date.
		$display_date = date( 'Y-m-d h:i:s' );
		$datetime1 = new DateTime( $install_date );
		$datetime2 = new DateTime( $display_date );
		$diff_intrval = round( ($datetime2->format( 'U' ) - $datetime1->format( 'U' )) / (60 * 60 * 24) );

		if ( ! PAnD::is_admin_notice_active( 'notice-organic-widgets-month-forever' ) ) {
			return;
		}
		?>

		<?php if ( $diff_intrval >= 30 ) { ?>

			<div id="fb-root"></div>
			<script>(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.9&appId=246727095428680";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>

			<script>window.twttr = (function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0],
				t = window.twttr || {};
				if (d.getElementById(id)) return t;
				js = d.createElement(s);
				js.id = id;
				js.src = "https://platform.twitter.com/widgets.js";
				fjs.parentNode.insertBefore(js, fjs);

				t._e = [];
				t.ready = function(f) {
					t._e.push(f);
				};

				return t;
			}(document, "script", "twitter-wjs"));</script>

			<div data-dismissible="notice-organic-widgets-month-forever" class="notice updated is-dismissible">

				<h2 style="margin-bottom: 0px;"><?php printf( __( 'Whoa! You\'ve been using <a href="%1$s" target="_blank">Organic Customizer Widgets</a> for a whole month!', ORGANIC_WIDGETS_18N ), 'https://organicthemes.com/' ); ?></h2>
				<p><?php esc_html_e( 'We\'re just a couple dudes trying to make the web a better place. Please take a moment to show your appreciation by liking and subscribing! Your support is greatly appreciated!', ORGANIC_WIDGETS_18N ); ?></p>

				<div class="follows" style="overflow: hidden; margin-bottom: 12px;">

					<div id="mc_embed_signup" class="clear" style="float: left;">
						<form action="//organicthemes.us1.list-manage.com/subscribe/post?u=7cf6b005868eab70f031dc806&amp;id=c3cce2fac0" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
							<div id="mc_embed_signup_scroll">
								<div id="mce-responses" class="clear">
									<div class="response" id="mce-error-response" style="display:none"></div>
									<div class="response" id="mce-success-response" style="display:none"></div>
								</div>
								<div class="mc-field-group" style="float: left;">
									<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="Email Address">
								</div>
								<div style="float: left; margin-left: 6px;"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
								<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_7cf6b005868eab70f031dc806_c3cce2fac0" tabindex="-1" value=""></div>
							</div>
						</form>
					</div>

					<div class="social-links" style="float: left; margin-left: 24px; margin-top: 4px;">
						<div class="fb-like" style="float: left;" data-href="https://www.facebook.com/OrganicThemes/" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
						<div class="twitter-follow" style="float: left; margin-left: 6px;"><a class="twitter-follow-button" href="https://twitter.com/OrganicThemes" data-show-count="false">Follow @OrganicThemes</a></div>
					</div>

					<a class="button button-primary" style="float: right; margin-left: 12px;" href="<?php echo esc_url( 'https://organicthemes.com/themes/' ); ?>" target="_blank"><?php _e( 'Get 36+ Premium Themes! | $99/yr', ORGANIC_WIDGETS_18N ); ?></a>

				</div>

			</div>

			<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
			<!--End mc_embed_signup-->

		<?php } ?>

		<?php

	}

}
