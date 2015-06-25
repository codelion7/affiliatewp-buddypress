<?php
/**
 * Plugin Name: AffiliateWP BuddyPress
 * Plugin URI: http://theperfectplugin.com/downloads/affiliatewp-buddypress
 * Description: Integrates AffiliateWP with BuddyPress
 * Author: Christian Freeman and The Perfect Plugin Team
 * Author URI: http://theperfectplugin.com
 * Version: 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'AffiliateWP_BuddyPress' ) ) {

	final class AffiliateWP_BuddyPress {

		/**
		 * Plugin instance.
		 *
		 * @see instance()
		 * @type object
		 */
		private static $instance;

		/**
		 * URL to this plugin's directory.
		 *
		 * @type string
		 */
		public static  $plugin_dir;
		public static  $plugin_url;
		private static $version;

		/**
		 * Main AffiliateWP_BuddyPress Instance
		 *
		 * Insures that only one instance of AffiliateWP_BuddyPress exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 * @staticvar array $instance
		 * @return The one true AffiliateWP_BuddyPress
		 */
		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AffiliateWP_BuddyPress ) ) {
				
				self::$instance = new AffiliateWP_BuddyPress;
				self::$version  = '1.0';

				self::$instance->setup_constants();
				self::$instance->includes();
				self::$instance->hooks();

			}

			return self::$instance;
		}

		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since 1.0
		 * @access protected
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'affiliatewp-buddypress' ), '1.0' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @since 1.0
		 * @access protected
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'affiliatewp-buddypress' ), '1.0' );
		}

		/**
		 * Constructor Function
		 *
		 * @since 1.0
		 * @access private
		 */
		private function __construct() {
			self::$instance = $this;
		}

		/**
		 * Reset the instance of the class
		 *
		 * @since 1.0
		 * @access public
		 * @static
		 */
		public static function reset() {
			self::$instance = null;
		}
		
		/**
		 * Setup plugin constants
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		private function setup_constants() {
			// Plugin version
			if ( ! defined( 'AFFWP_BP_VERSION' ) ) {
				define( 'AFFWP_BP_VERSION', self::$version );
			}

			// Plugin Folder Path
			if ( ! defined( 'AFFWP_BP_PLUGIN_DIR' ) ) {
				define( 'AFFWP_BP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'AFFWP_BP_PLUGIN_URL' ) ) {
				define( 'AFFWP_BP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'AFFWP_BP_PLUGIN_FILE' ) ) {
				define( 'AFFWP_BP_PLUGIN_FILE', __FILE__ );
			}

		}

		/**
		 * Include necessary files
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 */
		private function includes() {

			require_once AFFWP_BP_PLUGIN_DIR . 'includes/affiliate-area.php';
			// require_once AFFWP_BP_PLUGIN_DIR . 'includes/compatibility.php';
			
		}

		/**
		 * Setup the default hooks and actions
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function hooks() {
		
			// plugin meta
			add_filter( 'plugin_row_meta', array( $this, 'plugin_meta' ), null, 2 );

		}
		
		/**
		 * Modify plugin metalinks
		 *
		 * @access      public
		 * @since       1.0
		 * @param       array $links The current links array
		 * @param       string $file A specific plugin table entry
		 * @return      array $links The modified links array
		 */
		public function plugin_meta( $links, $file ) {
		    if ( $file == plugin_basename( __FILE__ ) ) {
		        $plugins_link = array(
		            '<a title="' . __( 'Get more add-ons for AffiliateWP', 'affiliatewp-buddypress' ) . '" href="http://theperfectplugin.com/downloads/category/affiliatewp" target="_blank">' . __( 'Get add-ons', 'affiliatewp-buddypress' ) . '</a>'
		        );

		        $links = array_merge( $links, $plugins_link );
		    }

		    return $links;
		}
	}
	
	/**
	 * The main function responsible for returning the one true AffiliateWP_BuddyPress
	 * Instance to functions everywhere.
	 *
	 * Use this function like you would a global variable, except without needing
	 * to declare the global.
	 *
	 * Example: <?php $AffiliateWP_BuddyPress = AffiliateWP_BuddyPress(); ?>
	 *
	 * @since 1.0
	 * @return object The one true AffiliateWP_BuddyPress Instance
	 */
	function AffiliateWP_BuddyPress() {

	    if ( ! class_exists( 'Affiliate_WP' ) || ! class_exists( 'BuddyPress' ) ) {
	    	
	        if ( ! class_exists( 'AffiliateWP_Activation' ) || ! class_exists( 'AffiliateWP_BuddyPress_Activation' ) ) {
	            require_once 'includes/class-activation.php';
	        }

	        // AffiliateWP activation
			if ( ! class_exists( 'Affiliate_WP' ) ) {
				$activation = new AffiliateWP_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
				$activation = $activation->run();
			}
			
			if ( ! class_exists( 'BuddyPress' ) ) {
				$activation = new AffiliateWP_BuddyPress_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
				$activation = $activation->run();
			}
			
	    } else {
	        return AffiliateWP_BuddyPress::instance();
	    }
	}
	add_action( 'plugins_loaded', 'AffiliateWP_BuddyPress', 100 );

}