<?php
/**
 * The Custom Welcome Page Content
 *
 * @package GivingPress Custom Admin
 * @since GivingPress Custom Admin 1.0
 */
?>

<?php $current_user = wp_get_current_user(); ?>

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

<div class="wrap welcome-screen">

  <h2></h2>

	<div class="intro clearfix">

		<div class="logo-wrap">

			<div class="organic-themes-logo">
				<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					 width="280px" height="60px" viewBox="0 0.75 280 60" enable-background="new 0 0.75 280 60" xml:space="preserve">
				<g>
					<path class="logo-mark" fill="#9ECE42" d="M19.687,15.264c-6.377,0-11.095,1.504-14.021,4.471c-6.71,6.804-4.461,14.111-3.612,16.19
						c0.748,1.828,1.667,2.979,2.371,3.587l2.438-2.152L6.634,26.532l2.473,8.847l3.607-3.184l0.36-8.804l1.562,7.105l8.682-6.954
						l-6.838,8.873l7.068,1.594l-8.738,0.366l-3.113,3.647l8.753,2.514l-10.699-0.23l-2.08,2.438c0.076,0.091,0.16,0.187,0.26,0.284
						c1.567,1.597,4.863,3.207,8.691,3.207c2.686,0,6.689-0.818,10.535-4.717c6.662-6.754,4.025-21.151,3.167-25.018
						C28.501,16.089,24.299,15.264,19.687,15.264L19.687,15.264L19.687,15.264z"/>
					<g class="logo-text">
						<path fill="#333333" d="M39.769,31.591c0-6.071,4.439-10.422,10.541-10.422c6.071,0,10.512,4.351,10.512,10.422
							s-4.44,10.42-10.512,10.42C44.209,42.01,39.769,37.661,39.769,31.591z M56.412,31.591c0-3.776-2.386-6.614-6.103-6.614
							c-3.744,0-6.131,2.838-6.131,6.614c0,3.743,2.387,6.614,6.131,6.614C54.024,38.204,56.412,35.334,56.412,31.591z"/>
						<path fill="#333333" d="M74.022,41.646l-3.957-7.158h-3.143v7.158h-4.289V21.501h9.424c4.198,0,6.797,2.749,6.797,6.494
							c0,3.532-2.267,5.467-4.44,5.979l4.562,7.672H74.022z M74.444,27.965c0-1.66-1.297-2.688-2.989-2.688h-4.531v5.438h4.531
							C73.147,30.714,74.444,29.688,74.444,27.965z"/>
						<path fill="#333333" d="M79.883,31.591c0-6.375,4.833-10.422,10.753-10.422c4.168,0,6.797,2.114,8.307,4.5l-3.533,1.935
							c-0.938-1.42-2.658-2.629-4.771-2.629c-3.686,0-6.344,2.811-6.344,6.615s2.658,6.615,6.344,6.615c1.781,0,3.442-0.785,4.258-1.541
							v-2.416H89.58v-3.746h9.604v7.732c-2.054,2.295-4.924,3.774-8.547,3.774C84.715,42.01,79.883,37.934,79.883,31.591z"/>
						<path fill="#333333" d="M115.313,41.646l-1.269-3.412h-8.638l-1.27,3.412h-4.863l7.763-20.146h5.376l7.763,20.146H115.313z
							 M109.726,25.79l-3.141,8.669h6.282L109.726,25.79z"/>
						<path fill="#333333" d="M135.341,41.646l-9.605-13.14v13.14h-4.289V21.501h4.41l9.334,12.655V21.501h4.289v20.146H135.341
							L135.341,41.646z"/>
						<path fill="#333333" d="M142.259,41.646V21.501h4.289v20.146H142.259L142.259,41.646z"/>
						<path fill="#333333" d="M148.965,31.591c0-6.224,4.684-10.422,10.753-10.422c4.409,0,6.979,2.388,8.396,4.923l-3.686,1.812
							c-0.846-1.631-2.658-2.93-4.712-2.93c-3.685,0-6.344,2.811-6.344,6.614c0,3.807,2.657,6.615,6.344,6.615
							c2.054,0,3.865-1.301,4.712-2.932l3.686,1.781c-1.418,2.508-3.986,4.953-8.396,4.953C153.646,42.01,148.965,37.781,148.965,31.591
							z"/>
						<path fill="#333333" d="M177.54,41.646V23.069h-6.585v-1.568h14.922v1.568h-6.585v18.577H177.54z"/>
						<path fill="#333333" d="M203.938,41.646v-9.574h-12.773v9.574h-1.723V21.501h1.723v9.001h12.773v-9.001h1.724v20.146H203.938
							L203.938,41.646z"/>
						<path fill="#333333" d="M210.072,41.646V21.501h12.775v1.568h-11.056v7.463h10.845v1.569h-10.845v7.976h11.056v1.569H210.072z"/>
						<path fill="#333333" d="M243.602,41.646V23.676l-7.34,17.973h-0.666l-7.369-17.973v17.973h-1.722V21.501h2.568l6.854,16.765
							l6.825-16.765h2.597v20.146H243.602L243.602,41.646z"/>
						<path fill="#333333" d="M249.159,41.646V21.501h12.776v1.568H250.88v7.463h10.845v1.569H250.88v7.976h11.056v1.569H249.159z"/>
						<path fill="#333333" d="M264.475,38.809l1.117-1.299c1.299,1.479,3.412,2.93,6.312,2.93c4.106,0,5.283-2.297,5.283-4.019
							c0-5.92-12.08-2.84-12.08-9.877c0-3.292,2.961-5.376,6.613-5.376c2.989,0,5.285,1.059,6.825,2.839l-1.146,1.269
							c-1.449-1.752-3.506-2.536-5.771-2.536c-2.688,0-4.712,1.54-4.712,3.717c0,5.165,12.081,2.323,12.081,9.847
							c0,2.598-1.722,5.708-7.157,5.708C268.521,42.01,266.016,40.65,264.475,38.809z"/>
					</g>
				</g>
				</svg>
			</div>

			<div class="social-links">
				<div class="fb-like" data-href="https://www.facebook.com/OrganicThemes/" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
				<div class="twitter-follow"><a class="twitter-follow-button" href="https://twitter.com/OrganicThemes" data-show-count="false">Follow @OrganicThemes</a></div>
			</div>

		</div>

		<h2 class="admin-headline"><?php _e( 'Organic Builder Widgets', ORGANIC_WIDGETS_18N ); ?></h2>

		<p class="admin-tagline"><?php _e( 'Aloha ' ) ?><b><?php global $userdata, $current_user, $user_identity; echo $user_identity ?></b><?php printf( __( ', you\'re moments away from creating awesome dynamic content on any page of your website! If this is your first time using the plugin, simply <a href="%1$s">add a new page</a> and apply the <b>Organic Custom</b> page template. Then, enter the WordPress <a href="%2$s">Customizer</a> to begin adding widgets to the page.', ORGANIC_WIDGETS_18N ), esc_url( admin_url( 'post-new.php?post_type=page' ) ), esc_url( admin_url( 'customize.php' ) ) ); ?></p>

		<p><?php printf( __( 'Enter your email to receive important updates and information from <a href="%1$s" target="_blank">Organic Themes</a>.', ORGANIC_WIDGETS_18N ), 'https://organicthemes.com' ); ?></p>

		<div id="mc_embed_signup" class="clear" style="overflow: hidden; margin-bottom: 12px;">
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

		<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>

		<!-- BEGIN .feature-links -->
		<div class="feature-links">

      <a class="feature-link-upgrade" href="<?php echo esc_url('https://organicthemes.com/builder/') ?>" target="_blank">
				<span class="icon">
          <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
          	 width="20px" height="20px" viewBox="0 0 20 20" enable-background="new 0 0 20 20" xml:space="preserve">
          <path fill="#99CC33" d="M2.782,3.782C0.988,5.576,0,7.962,0,10.5s0.988,4.923,2.782,6.718C4.576,19.014,6.962,20,9.5,20
          	s4.923-0.988,6.718-2.782C18.014,15.424,19,13.038,19,10.501s-0.988-4.923-2.782-6.718c-1.794-1.795-4.18-2.782-6.718-2.782
          	S4.577,1.989,2.782,3.782L2.782,3.782z"/>
          <path fill="#ffffff" d="M2.782,3.782C0.988,5.576,0,7.962,0,10.5s0.988,4.923,2.782,6.718C4.576,19.014,6.962,20,9.5,20
          	s4.923-0.988,6.718-2.782C18.014,15.424,19,13.038,19,10.501s-0.988-4.923-2.782-6.718c-1.794-1.795-4.18-2.782-6.718-2.782
          	S4.577,1.989,2.782,3.782L2.782,3.782z M18,10.5c0,4.688-3.812,8.5-8.5,8.5C4.813,19,1,15.188,1,10.5C1,5.813,4.813,2,9.5,2
          	C14.188,2,18,5.813,18,10.5z"/>
          <path fill="#ffffff" d="M9.147,4.647l-4,4c-0.195,0.195-0.195,0.512,0,0.707s0.512,0.195,0.707,0L9,6.207V16.5
          	C9,16.775,9.224,17,9.5,17s0.5-0.225,0.5-0.5V6.207l3.146,3.146c0.195,0.195,0.513,0.195,0.707,0C13.952,9.255,14,9.127,14,9
          	s-0.049-0.256-0.146-0.353l-4-4C9.658,4.452,9.341,4.452,9.147,4.647L9.147,4.647z"/>
          </svg>
				</span>
				<span class="info">
					<h4><?php esc_html_e( 'Upgrade To Pro', ORGANIC_WIDGETS_18N ); ?></h4>
					<p><?php esc_html_e( 'Buy Organic Builder Widgets Pro for additional widgets and options!', ORGANIC_WIDGETS_18N ); ?></p>
				</span>
			</a>

			<a href="<?php echo esc_url('https://organicthemes.com/tutorial/how-to-use-organic-customizer-widgets/') ?>" target="_blank">
				<span class="icon">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
					<path d="M16.5 20h-14c-0.827 0-1.5-0.673-1.5-1.5v-14c0-0.827 0.673-1.5 1.5-1.5h1c0.276 0 0.5 0.224 0.5 0.5s-0.224 0.5-0.5 0.5h-1c-0.276 0-0.5 0.224-0.5 0.5v14c0 0.276 0.224 0.5 0.5 0.5h14c0.276 0 0.5-0.224 0.5-0.5v-14c0-0.276-0.224-0.5-0.5-0.5h-1c-0.276 0-0.5-0.224-0.5-0.5s0.224-0.5 0.5-0.5h1c0.827 0 1.5 0.673 1.5 1.5v14c0 0.827-0.673 1.5-1.5 1.5z"></path>
					<path d="M13.501 5c-0 0-0 0-0.001 0h-8c-0.276 0-0.5-0.224-0.5-0.5 0-1.005 0.453-1.786 1.276-2.197 0.275-0.138 0.547-0.213 0.764-0.254 0.213-1.164 1.235-2.049 2.459-2.049s2.246 0.885 2.459 2.049c0.218 0.041 0.489 0.116 0.764 0.254 0.816 0.408 1.268 1.178 1.276 2.17 0.001 0.009 0.001 0.018 0.001 0.027 0 0.276-0.224 0.5-0.5 0.5zM6.060 4h6.88c-0.096-0.356-0.307-0.617-0.638-0.79-0.389-0.203-0.8-0.21-0.805-0.21-0.276 0-0.497-0.224-0.497-0.5 0-0.827-0.673-1.5-1.5-1.5s-1.5 0.673-1.5 1.5c0 0.276-0.224 0.5-0.5 0.5-0.001 0-0.413 0.007-0.802 0.21-0.331 0.173-0.542 0.433-0.638 0.79z"></path>
					<path d="M9.5 3c-0.132 0-0.261-0.053-0.353-0.147s-0.147-0.222-0.147-0.353 0.053-0.261 0.147-0.353c0.093-0.093 0.222-0.147 0.353-0.147s0.261 0.053 0.353 0.147c0.093 0.093 0.147 0.222 0.147 0.353s-0.053 0.26-0.147 0.353c-0.093 0.093-0.222 0.147-0.353 0.147z"></path>
					<path d="M8 14c-0.128 0-0.256-0.049-0.354-0.146l-1.5-1.5c-0.195-0.195-0.195-0.512 0-0.707s0.512-0.195 0.707 0l1.146 1.146 4.146-4.146c0.195-0.195 0.512-0.195 0.707 0s0.195 0.512 0 0.707l-4.5 4.5c-0.098 0.098-0.226 0.146-0.354 0.146z"></path>
					</svg>
				</span>
				<span class="info">
					<h4><?php esc_html_e( 'Getting Started', ORGANIC_WIDGETS_18N ); ?></h4>
					<p><?php esc_html_e( 'Review our guide to using the simple Organic Builder Widgets plugin.', ORGANIC_WIDGETS_18N ); ?></p>
				</span>
			</a>

			<a href="<?php echo esc_url('https://organicthemes.com/support/') ?>" target="_blank">
				<span class="icon">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
					<path d="M17.071 2.929c-1.889-1.889-4.4-2.929-7.071-2.929s-5.182 1.040-7.071 2.929c-1.889 1.889-2.929 4.4-2.929 7.071s1.040 5.182 2.929 7.071c1.889 1.889 4.4 2.929 7.071 2.929s5.182-1.040 7.071-2.929c1.889-1.889 2.929-4.4 2.929-7.071s-1.040-5.182-2.929-7.071zM10 15c-2.757 0-5-2.243-5-5s2.243-5 5-5c2.757 0 5 2.243 5 5s-2.243 5-5 5zM1 10c0-0.338 0.019-0.672 0.056-1h3.028c-0.055 0.325-0.084 0.659-0.084 1s0.029 0.675 0.084 1l-3.028-0c-0.036-0.328-0.056-0.662-0.056-1zM15.916 9h3.028c0.036 0.328 0.056 0.662 0.056 1s-0.019 0.672-0.056 1h-3.028c0.055-0.325 0.084-0.659 0.084-1s-0.029-0.675-0.084-1zM18.776 8h-3.119c-0.604-1.702-1.955-3.053-3.657-3.657l0-3.119c3.36 0.765 6.010 3.416 6.776 6.776zM11 1.056l-0 3.028c-0.325-0.055-0.659-0.084-1-0.084s-0.675 0.029-1 0.084v-3.028c0.328-0.036 0.662-0.056 1-0.056s0.672 0.019 1 0.056zM8 1.224v3.119c-1.702 0.604-3.053 1.955-3.657 3.657h-3.119c0.765-3.36 3.416-6.010 6.776-6.776zM1.224 12l3.119 0c0.604 1.702 1.955 3.053 3.657 3.657v3.119c-3.36-0.765-6.010-3.416-6.776-6.776zM9 18.944v-3.028c0.325 0.055 0.659 0.084 1 0.084s0.675-0.029 1-0.084v3.028c-0.328 0.037-0.662 0.056-1 0.056s-0.672-0.019-1-0.056zM12 18.776v-3.119c1.702-0.604 3.053-1.955 3.657-3.657h3.119c-0.765 3.36-3.416 6.010-6.776 6.776z"></path>
					</svg>
				</span>
				<span class="info">
					<h4><?php esc_html_e( 'Support Questions', ORGANIC_WIDGETS_18N ); ?></h4>
					<p><?php esc_html_e( 'Have a question or need some help? Get support from Organic Themes.', ORGANIC_WIDGETS_18N ); ?></p>
				</span>
			</a>

      <a href="<?php echo esc_url('https://wordpress.org/support/plugin/organic-customizer-widgets/reviews/?filter=5#new-post') ?>" target="_blank">
				<span class="icon">
          <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20">
          <path d="M15.5 19c-0.082 0-0.164-0.020-0.239-0.061l-5.261-2.869-5.261 2.869c-0.168 0.092-0.373 0.079-0.529-0.032s-0.235-0.301-0.203-0.49l0.958-5.746-3.818-3.818c-0.132-0.132-0.18-0.328-0.123-0.506s0.209-0.31 0.394-0.341l5.749-0.958 2.386-4.772c0.085-0.169 0.258-0.276 0.447-0.276s0.363 0.107 0.447 0.276l2.386 4.772 5.749 0.958c0.185 0.031 0.337 0.162 0.394 0.341s0.010 0.374-0.123 0.506l-3.818 3.818 0.958 5.746c0.031 0.189-0.048 0.379-0.203 0.49-0.086 0.061-0.188 0.093-0.29 0.093zM10 15c0.082 0 0.165 0.020 0.239 0.061l4.599 2.508-0.831-4.987c-0.027-0.159 0.025-0.322 0.14-0.436l3.313-3.313-5.042-0.84c-0.158-0.026-0.293-0.127-0.365-0.27l-2.053-4.106-2.053 4.106c-0.072 0.143-0.207 0.243-0.365 0.27l-5.042 0.84 3.313 3.313c0.114 0.114 0.166 0.276 0.14 0.436l-0.831 4.987 4.599-2.508c0.075-0.041 0.157-0.061 0.239-0.061z"></path>
          </svg>
				</span>
				<span class="info">
					<h4><?php esc_html_e( 'Leave A Review', ORGANIC_WIDGETS_18N ); ?></h4>
					<p><?php esc_html_e( 'Love the plugin? Take a moment to leave us a review. It really helps.', ORGANIC_WIDGETS_18N ); ?></p>
				</span>
			</a>

		<!-- END .feature-links -->
		</div>

	</div>

	<!-- BEGIN .options -->
	<div class="options clearfix">

    <div class="theme-header">
      <div class="information">
		    <h3><?php esc_html_e( 'Recommended Themes', ORGANIC_WIDGETS_18N ); ?></h3>
		    <p><?php esc_html_e( 'These themes are guaranteed to work seamlessly with Organic Builder Widgets.', ORGANIC_WIDGETS_18N ); ?></p>
      </div>
      <!-- <a class="purchase-button" href="<?php // echo esc_url( 'https://organicthemes.com/checkout?edd_action=add_to_cart&download_id=283005/' ); ?>" target="_blank"><?php // _e( 'Get 36+ Premium Themes! | $99/yr', ORGANIC_WIDGETS_18N ); ?></a> -->
    </div>

		<br />

		<div class="featured-themes">

			<div class="theme-browser">

				<div class="themes wp-clearfix">

          <div class="theme">

						<div class="theme-screenshot">

							<img src="<?php echo esc_url( ORGANIC_WIDGETS_ADMIN_IMG_DIR . 'startup-screenshot.png' ); ?>" alt="" />

						</div>

            <div class="theme-id-container">

  						<h2 class="theme-name"><?php _e( 'StartUp Theme', ORGANIC_WIDGETS_18N ); ?></h2>

  						<div class="theme-actions">

  							<a class="button button-primary theme-install" href="<?php echo esc_url( 'https://organicthemes.com/theme/startup-theme/' ); ?>" target="_blank"><?php _e( 'Details' ); ?></a>
  							<a class="button" href="<?php echo esc_url( 'https://organicthemes.com/demo/?demo=startup-theme' ); ?>" target="_blank"><?php _e( 'View Demo' ); ?></a>

  						</div>

            </div>

					</div>

          <div class="theme">

						<div class="theme-screenshot">

							<img src="<?php echo esc_url( ORGANIC_WIDGETS_ADMIN_IMG_DIR . 'purpose-screenshot.png' ); ?>" alt="" />

						</div>

            <div class="theme-id-container">

  						<h2 class="theme-name"><?php _e( 'Purpose Theme', ORGANIC_WIDGETS_18N ); ?></h2>

  						<div class="theme-actions">

  							<a class="button button-primary theme-install" href="<?php echo esc_url( 'https://organicthemes.com/theme/purpose-theme/' ); ?>" target="_blank"><?php _e( 'Details' ); ?></a>
  							<a class="button" href="<?php echo esc_url( 'https://organicthemes.com/demo/?demo=purpose-theme' ); ?>" target="_blank"><?php _e( 'View Demo' ); ?></a>

  						</div>

            </div>

					</div>

          <div class="theme">

						<div class="theme-screenshot">

							<img src="<?php echo esc_url( ORGANIC_WIDGETS_ADMIN_IMG_DIR . 'min-screenshot.png' ); ?>" alt="" />

						</div>

            <div class="theme-id-container">

  						<h2 class="theme-name"><?php _e( 'Min Theme', ORGANIC_WIDGETS_18N ); ?></h2>

  						<div class="theme-actions">

  							<a class="button button-primary theme-install" href="<?php echo esc_url( 'https://organicthemes.com/theme/min-theme/' ); ?>" target="_blank"><?php _e( 'Details' ); ?></a>
  							<a class="button" href="<?php echo esc_url( 'https://organicthemes.com/demo/?demo=min-theme' ); ?>" target="_blank"><?php _e( 'View Demo' ); ?></a>

  						</div>

            </div>

					</div>

					<div class="theme">

						<div class="theme-screenshot">

							<img src="<?php echo esc_url( ORGANIC_WIDGETS_ADMIN_IMG_DIR . 'swell-lite-screenshot.png' ); ?>" alt="" />

						</div>

            <div class="theme-id-container">

  						<h2 class="theme-name"><?php _e( 'Swell Lite', ORGANIC_WIDGETS_18N ); ?></h2>

  						<div class="theme-actions">

  							<a class="button button-primary theme-install" href="<?php echo esc_url( admin_url( 'theme-install.php?theme=swell-lite' ) ); ?>" target="_blank"><?php _e( 'Details' ); ?></a>
  							<a class="button" href="<?php echo esc_url( 'https://organicthemes.com/demo/swell/' ); ?>" target="_blank"><?php _e( 'View Demo' ); ?></a>

  						</div>

            </div>

					</div>

					<div class="theme">

						<div class="theme-screenshot">

							<img src="<?php echo esc_url( ORGANIC_WIDGETS_ADMIN_IMG_DIR . 'structure-lite-screenshot.png' ); ?>" alt="" />

						</div>

            <div class="theme-id-container">

  						<h2 class="theme-name"><?php _e( 'Structure Lite', ORGANIC_WIDGETS_18N ); ?></h2>

  						<div class="theme-actions">

  							<a class="button button-primary theme-install" href="<?php echo esc_url( admin_url( 'theme-install.php?theme=structure-lite' ) ); ?>" target="_blank"><?php _e( 'Details' ); ?></a>
  							<a class="button" href="<?php echo esc_url( 'https://organicthemes.com/demo/structure-lite/' ); ?>" target="_blank"><?php _e( 'View Demo' ); ?></a>

  						</div>

            </div>

					</div>

					<div class="theme">

						<div class="theme-screenshot">

							<img src="<?php echo esc_url( ORGANIC_WIDGETS_ADMIN_IMG_DIR . 'natural-lite-screenshot.png' ); ?>" alt="" />

						</div>

            <div class="theme-id-container">

  						<h2 class="theme-name"><?php _e( 'Natural Lite', ORGANIC_WIDGETS_18N ); ?></h2>

  						<div class="theme-actions">

  							<a class="button button-primary theme-install" href="<?php echo esc_url( admin_url( 'theme-install.php?theme=natural-lite' ) ); ?>" target="_blank"><?php _e( 'Details' ); ?></a>
  							<a class="button" href="<?php echo esc_url( 'https://organicthemes.com/demo/natural-lite/' ); ?>" target="_blank"><?php _e( 'View Demo' ); ?></a>

  						</div>

            </div>

					</div>

				</div>

			</div>

		<!-- END .options -->
		</div>

	</div>

</div>
