<?php
/**
 * Activation handler
 *
 * @package     AffiliateWP\ActivationHandler
 * @since       1.0
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Activation Handler Class
 *
 * @since       1.0
 */
class AffiliateWP_BuddyPress_Activation {

    public $plugin_name, $plugin_path, $plugin_file, $has_plugin;

    /**
     * Setup the activation class
     *
     * @access      public
     * @since       1.0
     * @return      void
     */
    public function __construct( $plugin_path, $plugin_file ) {
        // We need plugin.php!
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        $plugins = get_plugins();

        // Set plugin directory
        $plugin_path = array_filter( explode( '/', $plugin_path ) );
        $this->plugin_path = end( $plugin_path );

        // Set plugin file
        $this->plugin_file = $plugin_file;
        
        // Set plugin name
        if ( isset( $plugins[$this->plugin_path . '/' . $this->plugin_file]['Name'] ) ) {
            $this->plugin_name = $plugins[$this->plugin_path . '/' . $this->plugin_file]['Name'];

            //$this->plugin_name = 'hello';
        } else {
            $this->plugin_name = __( 'This plugin', 'affiliate-wp' );
        }

        // Is plugin installed?
        foreach ( $plugins as $plugin_path => $plugin ) {
            
            if ( $plugin['Name'] == 'BuddyPress' ) {
                $this->has_plugin = true;
                break;
            }
        }
    }

    /**
     * Show notice
     *
     * @access      public
     * @since       1.0
     * @return      void
     */
    public function run() {
        // Display notice
        add_action( 'admin_notices', array( $this, 'missing_plugin_notice' ) );
    }

    /**
     * Display notice if plugin isn't installed
     *
     * @access      public
     * @since       1.0
     * @return      string The notice to display
     */
    public function missing_plugin_notice() {

        if ( $this->has_plugin ) {
           echo '<div class="error"><p>' . sprintf( __( '%s requires %s. Please activate it to continue.', 'affiliatewp-buddypress' ), $this->plugin_name, '<a href="http://buddypress.org/" title="BuddyPress" target="_blank">BuddyPress</a>' ) . '</p></div>'; 

        } else {
            echo '<div class="error"><p>' . sprintf( __( '%s requires %s. Please install it to continue.', 'affiliatewp-buddypress' ), $this->plugin_name, '<a href="http://buddypress.org/" title="BuddyPress" target="_blank">BuddyPress</a>' ) . '</p></div>';
        }
    }
}
