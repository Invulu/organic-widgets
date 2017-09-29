<?php
/**
 * The Settings Page Content
 *
 * @package GivingPress Custom Admin
 * @since GivingPress Custom Admin 1.0
 */
?>

<?php $current_user = wp_get_current_user(); ?>

<div class="wrap organic-widgets-settings-screen">

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

		</div>

		<h2 class="admin-headline"><?php _e( 'Organic Widgets Settings', ORGANIC_WIDGETS_18N ); ?></h2>

  </div>

  <h1>Organic Widgets Settings</h1>

  <form method="post" action="options.php">
    <?php settings_fields( 'organic-widgets-settings-group' ); ?>
    <?php do_settings_sections( 'organic-widgets-settings-group' ); ?>

    <h3><?php  _e( 'Active Widgets', ORGANIC_WIDGETS_18N ); ?></h3>

    <table class="form-table">

      <?php $ocw_settings = array(
        array(
          'name' => 'organic_widgets_blog_posts_section_activate',
          'text' => 'Blog Posts Widget'
        ),array(
          'name' => 'organic_widgets_content_slideshow_section_activate',
          'text' => 'Content Slideshow Widget'
        ),array(
          'name' => 'organic_widgets_feature_list_section_activate',
          'text' => 'Feature List Widget'
        ),
        array(
          'name' => 'organic_widgets_featured_content_activate',
          'text' => 'Featured Content Widget'
        ),array(
          'name' => 'organic_widgets_featured_product_section_activate',
          'text' => 'Featured Product Widget'
        ),array(
          'name' => 'organic_widgets_hero_section_activate',
          'text' => 'Hero Section Widget'
        ),
        array(
          'name' => 'organic_widgets_portfolio_section_activate',
          'text' => 'Portfolio Widget'
        ),array(
          'name' => 'organic_widgets_pricing_table_activate',
          'text' => 'Pricing Table Widget'
        ),array(
          'name' => 'organic_widgets_profile_section_activate',
          'text' => 'Profile Widget'
        ),
        array(
          'name' => 'organic_widgets_subpage_section_activate',
          'text' => 'Subpage Widget'
        ),array(
          'name' => 'organic_widgets_team_section_activate',
          'text' => 'Team Widget'
        ),array(
          'name' => 'organic_widgets_testimonial_section_activate',
          'text' => 'Testimonials Widget'
        ),
      );?>

      <?php foreach( $ocw_settings as $ocw_setting ) { ?>

        <?php $name = $ocw_setting['name']; ?>
        <?php $checked = get_option($ocw_setting['name']); ?>
        <?php $text = $ocw_setting['text'];?>
        <?php error_log($text);?>
        <tr valign="top">
          <th scope="row"><?php esc_html_e( $text, ORGANIC_WIDGETS_18N ); ?></th>
          <td>
            <input type="checkbox" name="<?php echo esc_attr($name); ?>" <?php echo esc_attr($checked); ?> />
          </td>
        </tr>

      <?php } ?>

    </table>
    <?php submit_button(); ?>
  </form>

</div>
