<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 *  Load the frontend styles
 *  
 *  @since 1.2
 *  @return void
 */
function affwp_bp_frontend_styles() {
	global $post;
		
	if ( affiliatewp_buddypress()->is_dashboard_tab() 
		 || has_shortcode( $post->post_content, 'affiliate_area' )
		 || ( function_exists('buddypress') && bp_is_user() ) ) {
	
		wp_enqueue_style( 'affwp-bp-frontend', AFFWP_BP_PLUGIN_URL . 'assets/css/buddypress.css', AFFWP_BP_VERSION );

	}
}
add_action( 'wp_enqueue_scripts', 'affwp_bp_frontend_styles' );