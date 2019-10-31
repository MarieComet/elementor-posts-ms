<?php
namespace Elementor_Posts_MS;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 0.0.1
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @since 0.0.1
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
	 * @since 0.0.1
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
	 * Add new controls to existing widget
	 *
	 * @since 0.0.1
	 * @access public
	 * @param Element_Base The edited element
	 * @param array $args that sent to $element->start_controls_section
	 */

	public function posts_register_additional_ms_query_control( $element, $args ) {

		$sites_select = array();
		$sites = get_sites( $args = array(
			'fields' => 'ids',
		) );

		if ( is_array( $sites ) && !empty( $sites ) ) {

			$sites_select[ 'default' ] = __( 'Current', 'elementor-posts-ms' );

		    foreach ( $sites as $site ) {
		        $sites_select[ $site ] = get_blog_details( $site )->blogname;
		    }

		    $element->add_control(
		    	'ms_site_id',
		    	[
		    		'label' => __( 'Site', 'elementor-posts-ms' ),
		    		'type' => \Elementor\Controls_Manager::SELECT2,
		    		'multiple' => true,
		    		'options' => $sites_select,
		    		'default' => 'default',
		    		'description' => __( 'Add "multisite" to "Query ID" field', 'elementor-posts-ms' ),
		    	]
		    );
		}
		
	}

	/**
	 * Elementor Pro posts widget Query args.
	 *
	 * It allows developers to alter individual posts widget queries.
	 *
	 * @since 0.0.1
	 *
	 * @param \WP_Query $wp_query
	 * @param Posts     $this
	 */

	public function add_ms_query( $query, $widget ) {

		$settings = $widget->get_settings( 'ms_site_id' );

		if ( !empty( $settings ) && $settings !== 'default' ) {
			$query->set( 'multisite', 1 );
			$query->set( 'sites__in', $settings );
		}
		
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 0.0.1
	 * @access public
	 */
	public function __construct() {

		if ( !class_exists( 'WP_Query_Multisite') ) {
			require_once( __DIR__ . '/lib/class-wp-query-multisite.php' );
		}

		add_action( 'elementor/element/posts/section_query/after_section_start', [ $this, 'posts_register_additional_ms_query_control' ], 10, 2 );

		add_action( 'elementor/element/portfolio/section_query/after_section_start', [ $this, 'posts_register_additional_ms_query_control' ], 10, 2 );

		add_action( 'elementor/query/multisite', [ $this, 'add_ms_query'], 10, 2 );

	}
}

// Instantiate Plugin Class
Plugin::instance();
