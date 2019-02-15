<?php
namespace Elementor_Skins_Posts_MS;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function widget_scripts() {
		wp_register_script( 'elementor-skins-posts-ms', plugins_url( '/assets/js/hello-world.js', __FILE__ ), [ 'jquery' ], false, true );
	}

	/**
	 * Include Widgets skins
	 *
	 * Load Widgets skins
	 *
	 * @since 1.2.0
	 * @access private
	 */
	private function include_skins_files() {
	    require_once( __DIR__ . '/skins/posts/skin-base-ms.php' );
	    require_once( __DIR__ . '/skins/posts/skin-cards-ms.php' );
	    require_once( __DIR__ . '/skins/posts/skin-classic-ms.php' );
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_widgets() {
		// Its is now safe to include Widgets files
		$this->include_skins_files();
		// Register skin
		add_action( 'elementor/widget/posts/skins_init', function( $widget ) {
			//$widget->add_skin( new Posts\Skin_Base_MS($widget) );
			$widget->add_skin( new Posts\Skin_Cards_MS($widget) );
			$widget->add_skin( new Posts\Skin_Classic_MS($widget) );
		} );
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {

		// Register widget scripts
		//add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
	}
}

// Instantiate Plugin Class
Plugin::instance();
