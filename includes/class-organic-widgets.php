<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://organicthemes.com
 * @since      1.0.0
 *
 * @package    Organic_Widgets
 * @subpackage Organic_Widgets/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Organic_Widgets
 * @subpackage Organic_Widgets/includes
 * @author     Organic Themes <info@organicthemes.com>
 */
class Organic_Widgets {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Organic_Widgets_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The prefix for widget areas
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The prefix for widget areas
	 */
	protected $widget_prefix;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'organic-widgets';
		$this->version = ORGANIC_WIDGETS_CURRENT_VERSION;
		$this->widget_prefix = 'organic-widgets_';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Organic_Widgets_Loader. Orchestrates the hooks of the plugin.
	 * - Organic_Widgets_i18n. Defines internationalization functionality.
	 * - Organic_Widgets_Admin. Defines all hooks for the admin area.
	 * - Organic_Widgets_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-organic-widgets-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-organic-widgets-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-organic-widgets-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-organic-widgets-public.php';

		/**
		 * The class responsible for adding the custom page templates for the site
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-organic-page-template.php';

		/**
		 * The class responsible for registering widget areas
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-organic-widget-areas.php';

		/**
		 * The class responsible for registering admin notices
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-organic-admin-notices.php';
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/persist-admin-notices-dismissal/persist-admin-notices-dismissal.php';

		/**
		 * The classes responsible for registering widgets
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/widgets/class-organic-widgets-custom-widget.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/widgets/blog-posts-section/blog-posts-section-widget.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/widgets/content-slideshow-section/content-slideshow-section-widget.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/widgets/feature-list-section/feature-list-section-widget.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/widgets/featured-content/featured-content-widget.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/widgets/featured-product-section/featured-product-section-widget.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/widgets/hero-section/hero-section-widget.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/widgets/portfolio-section/portfolio-section-widget.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/widgets/profile-section/profile-widget.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/widgets/pricing-table/pricing-table-widget.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/widgets/subpage-section/subpage-section-widget.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/widgets/team-section/team-section-widget.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/widgets/testimonial-section/testimonial-section-widget.php';

		// Register Organic Widgets.
		add_action( 'widgets_init', function() {
			// Register Widgets conditionally according to settings.
			$organic_widgets_settings = get_option( 'organic_widgets_settings' ) ? get_option( 'organic_widgets_settings' ) : array();
			$organic_widgets = organic_widgets_get_organic_widgets();
			foreach ( $organic_widgets as $organic_widget_name => $organic_widget ) {
				if ( ! array_key_exists( $organic_widget['settings-activate-slug'], $organic_widgets_settings ) || $organic_widgets_settings[ $organic_widget['settings-activate-slug'] ] ) {
					register_widget( $organic_widget_name );
				}
			}
		});

		/**
		 * The classes responsible for registering blocks
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/blocks/class-organic-widgets-blocks.php';

		$this->loader = new Organic_Widgets_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Organic_Widgets_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Organic_Widgets_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		// Admin Hooks
		$plugin_admin = new Organic_Widgets_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_editor_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_admin_scripts' );

		$organic_blocks = new Organic_Widgets_Blocks( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $organic_blocks, 'enqueue_scripts' );

		// Page Template Hooks
		$plugin_page_template = new Organic_Page_Template( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'plugins_loaded', $plugin_page_template, 'get_instance' );

		// Widget Area Hooks
		$plugin_widget_areas = new Organic_Widget_Areas( $this->get_plugin_name(), $this->get_version(), $this->get_widget_prefix() );
		$this->loader->add_action( 'widgets_init', $plugin_widget_areas, 'register_widget_areas' );

		// Admin Notices
		$plugin_admin_notices = new Organic_Widgets_Admin_Notices( $this->get_plugin_name(), $this->get_version() );
		add_action( 'admin_init', array( 'PAnD', 'init' ) );
		$this->loader->add_action( 'admin_notices', $plugin_admin_notices, 'organic_widgets_admin_notice_active', 10 );
		$this->loader->add_action( 'admin_notices', $plugin_admin_notices, 'organic_widgets_admin_notice_2_weeks', 10 );
		$this->loader->add_action( 'admin_notices', $plugin_admin_notices, 'organic_widgets_admin_notice_1_month', 10 );

		// Content Import Hooks
		// Before content import.
		$this->loader->add_action( 'pt-ocdi/before_widgets_import', $plugin_widget_areas, 'register_widget_areas', 4 );
		// $this->loader->add_action( 'pt-ocdi/before_widgets_import', $plugin_admin, 'before_widgets_import_action', 5, 3 );
		// After Content Import
		$this->loader->add_action( 'pt-ocdi/after_all_import_execution', $plugin_admin, 'after_all_import_action', 20, 3 );


		// Add filter for widget titles
		$this->loader->add_filter( 'organic_widget_title', $plugin_admin, 'title_filter' );




	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Organic_Widgets_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'video_bg_script' );
		$this->loader->add_filter( 'body_class', $plugin_public, 'add_body_class' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Organic_Widgets_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Get the widget prefix
	 *
	 * @since     1.0.0
	 * @return    string    The widget prefix
	 */
	public function get_widget_prefix() {
		return $this->widget_prefix;
	}

}
