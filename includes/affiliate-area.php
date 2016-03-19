<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Force the frontend scripts to load on affiliate area profile tabs
 * 
 * @since  1.0
 */
if ( ! function_exists( 'affwp_aas_force_frontend_scripts' ) ) {
	function affwp_bp_force_frontend_scripts( $ret ) {
	
		if ( function_exists( 'affwp_bp_add_affiliate_area_bp_nav' ) ) {
			$ret = true;
		}
		return $ret;
	}
	add_filter( 'affwp_force_frontend_scripts', 'affwp_bp_force_frontend_scripts' );
}

/**
* Create Affiliate Area Tabs in BuddyPress Profile
*
* @since  1.0
*/
function affwp_bp_add_affiliate_area_bp_nav() {
	
	$main_tab_location = affiliate_wp()->settings->get( 'affwp_bp_aff_area_tab_location' );
	
	if( $main_tab_location == 'beginning' ) {
		$main_tab_order = 3;
	} 
	
	if( $main_tab_location == 'end' ) {
		$main_tab_order = 100;
	}
	
	// Use custom tab order if set
	if( $main_tab_location == 'custom' ) {
		$custom_order = affiliate_wp()->settings->get( 'affwp_bp_aff_area_tab_order' );
		$main_tab_order = $custom_order ? $custom_order : 3;
	}
	
	// Add the Affiliate Area (Dashboard) Tab
	bp_core_new_nav_item( array(
		'name' => __('Affiliate Area', 'affiliatewp-buddypress'),
		'slug' => 'affiliate-area',
		'position' => $main_tab_order,
		'show_for_displayed_user' => false,
		'screen_function' => 'affwp_bp_affiliate_dashboard',
		'item_css_id' => 'affiliate-area',
		'default_subnav_slug' => 'affiliate-area'
	) );
	
	// Add the Affiliate Area (URLs) Sub Tab
	bp_core_new_subnav_item( array( 
		'name' => __('Affiliate URLs', 'affiliatewp-buddypress'),
		'slug' => 'affiliate-area',
		'show_for_displayed_user' => false, 
		'parent_url' => trailingslashit( bp_displayed_user_domain() . 'affiliate-area'),
		'parent_slug' => 'affiliate-area',
		'position' => 10,
		'screen_function' => 'affwp_bp_affiliate_dashboard',
		'item_css_id' => 'affiliate-area',
		'user_has_access' => bp_is_my_profile()
	) ); 
	
	// Add the Stats Sub Tab
	bp_core_new_subnav_item( array( 
		'name' => __('Stats', 'affiliatewp-buddypress'),
		'slug' => 'affiliate-stats', 
		'parent_url' => trailingslashit( bp_displayed_user_domain() . 'affiliate-area'),
		'parent_slug' => 'affiliate-area',
		'position' => 20,
		'screen_function' => 'affwp_bp_affiliate_stats',
		'item_css_id' => 'affiliate-stats',
		'user_has_access' => bp_is_my_profile()
	) ); 	
	
	// Add the Graphs Sub Tab
	bp_core_new_subnav_item( array( 
		'name' => __('Graphs', 'affiliatewp-buddypress'),
		'slug' => 'affiliate-graphs', 
		'parent_url' => trailingslashit( bp_displayed_user_domain() . 'affiliate-area'),
		'parent_slug' => 'affiliate-area',
		'position' => 30,
		'screen_function' => 'affwp_bp_affiliate_graphs',
		'item_css_id' => 'affiliate-graphs',
		'user_has_access' => bp_is_my_profile()
	) );
	
	// Add the Referrals Sub Tab
	bp_core_new_subnav_item( array( 
		'name' => __('Referrals', 'affiliatewp-buddypress'),
		'slug' => 'affiliate-referrals', 
		'parent_url' => trailingslashit( bp_displayed_user_domain() . 'affiliate-area'),
		'parent_slug' => 'affiliate-area',
		'position' => 40,
		'screen_function' => 'affwp_bp_affiliate_referrals',
		'item_css_id' => 'affiliate-referrals',
		'user_has_access' => bp_is_my_profile()
	) );
	
	// Add the Visits Sub Tab
	bp_core_new_subnav_item( array( 
		'name' => __('Visits', 'affiliatewp-buddypress'),
		'slug' => 'affiliate-visits', 
		'parent_url' => trailingslashit( bp_displayed_user_domain() . 'affiliate-area'),
		'parent_slug' => 'affiliate-area',
		'position' => 50,
		'screen_function' => 'affwp_bp_affiliate_visits',
		'item_css_id' => 'affiliate-visits',
		'user_has_access' => bp_is_my_profile()
	) );

	// Add the Creatives Sub Tab
	bp_core_new_subnav_item( array( 
		'name' => __('Creatives', 'affiliatewp-buddypress'),
		'slug' => 'affiliate-creatives', 
		'parent_url' => trailingslashit( bp_displayed_user_domain() . 'affiliate-area'),
		'parent_slug' => 'affiliate-area',
		'position' => 60,
		'screen_function' => 'affwp_bp_affiliate_creatives',
		'item_css_id' => 'affiliate-creatives',
		'user_has_access' => bp_is_my_profile()
	) );
	
	// Add the Settings Sub Tab
	bp_core_new_subnav_item( array( 
		'name' => __('Settings', 'affiliatewp-buddypress'),
		'slug' => 'affiliate-settings',
		'show_for_displayed_user' => false, 
		'parent_url' => trailingslashit( bp_displayed_user_domain() . 'affiliate-area'),
		'parent_slug' => 'affiliate-area',
		'position' => 100,
		'screen_function' => 'affwp_bp_affiliate_settings',
		'item_css_id' => 'affiliate-settings',
		'user_has_access' => bp_is_my_profile()
	) );
	
	do_action( 'affwp_bp_after_add_nav_items' );
}
add_action( 'bp_setup_nav', 'affwp_bp_add_affiliate_area_bp_nav' );

/**
* Check User Access 
*
* @since  1.0
*/
function affwp_bp_access_check( $page = '' ) {

	if ( ! affwp_is_affiliate() && affiliate_wp()->settings->get( 'allow_affiliate_registration' ) ){
		$page = 'register';
	} 
	
	elseif ( !affiliate_wp()->settings->get( 'allow_affiliate_registration' ) && !affwp_is_affiliate() ) {
		$page = 'no-access';
	}

	if ( ! is_user_logged_in() ) { 
		$page = 'login';
	} 
	
	return $page;
}

/**
* Display Appropriate Page Template
*
* @since  1.0
*/
function affwp_bp_page( $page = '' ) {

	if ( $page == 'login' ) {
		// Show login page for logged out users
		return affiliate_wp()->templates->get_template_part( 'login' );
	}

	if ( $page == 'register' ) {
		// Show affiliate registration page if enabled in settings
		return affiliate_wp()->templates->get_template_part( 'register' );
	}
	
	if ( $page == 'no-access' ) {
		// Show no access page if registration page disabled in settings
		return affiliate_wp()->templates->get_template_part( 'no', 'access' );
	}
}

/**
* Add Affiliate Dashboard Notices
*
* @since  1.0
*/
function affwp_bp_affiliate_area_notices() {

	if ( 'pending' == affwp_get_affiliate_status( affwp_get_affiliate_id() ) ) :

		echo '<p class="affwp-notice">' . __( "Your affiliate account is pending approval", "affiliate-wp" ) . '</p>';

	elseif ( 'inactive' == affwp_get_affiliate_status( affwp_get_affiliate_id() ) ) :

		echo '<p class="affwp-notice">' . __( "Your affiliate account is not active", "affiliate-wp" ) . '</p>';

	elseif ( 'rejected' == affwp_get_affiliate_status( affwp_get_affiliate_id() ) ) :

		echo '<p class="affwp-notice">' . __( "Your affiliate account request has been rejected", "affiliate-wp" ) . '</p>';

	endif;

	if ( ! empty( $_GET['affwp_notice'] ) && 'profile-updated' == $_GET['affwp_notice'] ) :

		echo '<p class="affwp-notice">' . __( "Your affiliate profile has been updated", "affiliate-wp" ) . '</p>';

	endif;

	do_action( 'affwp_affiliate_dashboard_notices', affwp_get_affiliate_id() ); 

}

/**
* Load Affiliate Dashboard
*
* @since  1.2
*/
function affwp_bp_affiliate_dashboard() {
    add_action( 'bp_template_content', 'affwp_bp_show_affiliate_dashboard' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Get Affiliate Area Template for Main BuddyPress Profile Tab
function affwp_bp_show_affiliate_dashboard() {

	affwp_bp_page( affwp_bp_access_check() );
	
	if( affwp_bp_access_check() == '' ) {
	
		affwp_bp_affiliate_area_notices();
		
		echo '<div id="affwp-affiliate-dashboard">';

		affiliate_wp()->templates->get_template_part( 'dashboard' );
	
		echo '</div>';
			
	}
}

/**
* Load Affiliate URLs
*
* @since  1.0
*/
function affwp_bp_affiliate_urls() {
    add_action( 'bp_template_content', 'affwp_bp_show_affiliate_urls' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Get Affiliate URLs Template for BuddyPress Profile Tab
function affwp_bp_show_affiliate_urls() {

	affwp_bp_page( affwp_bp_access_check() );
	
	if( affwp_bp_access_check() == '' ) {
	
		affwp_bp_affiliate_area_notices();
		
		echo '<div id="affwp-affiliate-dashboard">';
	
		affiliate_wp()->templates->get_template_part( 'dashboard-tab', 'urls' );
	
		echo '</div>';
			
	}
}

/**
* Load Affiliate Stats
*
* @since  1.0
*/
function affwp_bp_affiliate_stats() {
    add_action( 'bp_template_content', 'affwp_bp_show_affiliate_stats' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Get Affiliate Stats Template for BuddyPress Profile Tab
function affwp_bp_show_affiliate_stats() {

	affwp_bp_page( affwp_bp_access_check() );
	
	if( affwp_bp_access_check() == '' ) {
	
		affwp_bp_affiliate_area_notices();
		
		echo '<div id="affwp-affiliate-dashboard">';
	
		affiliate_wp()->templates->get_template_part( 'dashboard-tab', 'stats' );
	
		echo '</div>';
	}
}

/**
* Load Affiliate Graphs
*
* @since  1.0
*/
function affwp_bp_affiliate_graphs() {
    add_action( 'bp_template_content', 'affwp_bp_show_affiliate_graphs' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Get Affiliate Graphs Template for BuddyPress Profile Tab
function affwp_bp_show_affiliate_graphs() {

	affwp_bp_page( affwp_bp_access_check() );
	
	if( affwp_bp_access_check() == '' ) {
	
		affwp_bp_affiliate_area_notices();
		
		echo '<div id="affwp-affiliate-dashboard">';
	
		affiliate_wp()->templates->get_template_part( 'dashboard-tab', 'graphs' );
	
		echo '</div>';
	}
}

/**
* Load Affiliate Referrals
*
* @since  1.0
*/
function affwp_bp_affiliate_referrals() {
    add_action( 'bp_template_content', 'affwp_bp_show_affiliate_referrals' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Get Affiliate Referrals Template for BuddyPress Profile Tab
function affwp_bp_show_affiliate_referrals() {

	affwp_bp_page( affwp_bp_access_check() );
	
	if( affwp_bp_access_check() == '' ) {
	
		affwp_bp_affiliate_area_notices();
		
		echo '<div id="affwp-affiliate-dashboard">';
	
		affiliate_wp()->templates->get_template_part( 'dashboard-tab', 'referrals' );
	
		echo '</div>';
	}
}

/**
* Load Affiliate Visits 
*
* @since  1.0
*/
function affwp_bp_affiliate_visits() {
    add_action( 'bp_template_content', 'affwp_bp_show_affiliate_visits' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Get Affiliate Visits Template for BuddyPress Profile Tab
function affwp_bp_show_affiliate_visits() {

	affwp_bp_page( affwp_bp_access_check() );
	
	if( affwp_bp_access_check() == '' ) {
	
		affwp_bp_affiliate_area_notices();
		
		echo '<div id="affwp-affiliate-dashboard">';
	
		affiliate_wp()->templates->get_template_part( 'dashboard-tab', 'visits' );
	
		echo '</div>';
	}
}

/**
* Load Affiliate Creatives
*
* @since  1.0
*/
function affwp_bp_affiliate_creatives() {
    add_action( 'bp_template_content', 'affwp_bp_show_affiliate_creatives' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Get Affiliate Creatives Template for BuddyPress Profile Tab
function affwp_bp_show_affiliate_creatives() {

	affwp_bp_page( affwp_bp_access_check() );
	
	if( affwp_bp_access_check() == '' ) {
	
		affwp_bp_affiliate_area_notices();
		
		echo '<div id="affwp-affiliate-dashboard">';
	
		affiliate_wp()->templates->get_template_part( 'dashboard-tab', 'creatives' );
	
		echo '</div>';
	}
}

/**
* Load Affiliate Settings
*
* @since  1.0
*/
function affwp_bp_affiliate_settings() {
    add_action( 'bp_template_content', 'affwp_bp_show_affiliate_settings' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Get Affiliate Settings Template for BuddyPress Profile Tab
function affwp_bp_show_affiliate_settings() {

	affwp_bp_page( affwp_bp_access_check() );
	
	if( affwp_bp_access_check() == '' ) {
	
		affwp_bp_affiliate_area_notices();
		
		echo '<div id="affwp-affiliate-dashboard">';
	
		affiliate_wp()->templates->get_template_part( 'dashboard-tab', 'settings' );
	
		echo '</div>';
	}
}

/**
* Redirect affiliate to buddypress affiliate area after registration
* 
* @since  1.0
*/
function affiliatewp_bp_registration_redirect( $affiliate_id, $status, $args ) {
	// global $bp;
	// $redirect = trailingslashit( $bp->loggedin_user->domain . 'affiliate-area' );
	$redirect = trailingslashit( bp_loggedin_user_domain() . 'affiliate-area' );

	wp_redirect( $redirect ); exit;
}
add_action( 'affwp_register_user', 'affiliatewp_bp_registration_redirect', 10, 3 );

// Run if AffiliateWP MLM is Active
if ( class_exists( 'AffiliateWP_Multi_Level_Marketing' ) ) {
	
	if ( ! function_exists( 'affwp_bp_add_sub_affiliates_nav_item' ) ) {
		/**
		* Create Affiliate Area Tab for Sub Affiliates in BuddyPress Profile
		*
		* @since  1.1
		*/
		function affwp_bp_add_sub_affiliates_nav_item() {
		
			// Add the Sub Affiliates Tab
			bp_core_new_subnav_item( array(
				'name' => __('Sub Affiliates', 'affiliatewp-bp'),
				'slug' => 'affiliate-sub-affiliates',
				'show_for_displayed_user' => false, 
				'parent_url' => trailingslashit( bp_displayed_user_domain() . 'affiliate-area'),
				'parent_slug' => 'affiliate-area',
				'position' => 70,
				'screen_function' => 'affwp_bp_affiliate_sub_affiliates',
				'item_css_id' => 'affiliate-sub-affiliates',
				'user_has_access' => bp_is_my_profile()
			) );
		}
		add_action( 'affwp_bp_after_add_nav_items', 'affwp_bp_add_sub_affiliates_nav_item' );
	}

	if ( ! function_exists( 'affwp_bp_affiliate_sub_affiliates' ) ) {
		/**
		* Load Affiliate Sub Affiliates
		*
		* @since  1.1
		*/
		function affwp_bp_affiliate_sub_affiliates() {
			add_action( 'bp_template_content', 'affwp_bp_show_affiliate_sub_affiliates' );
			bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
		}
	}
	
	if ( ! function_exists( 'affwp_bp_show_affiliate_sub_affiliates' ) ) {	
		// Get Affiliate Sub Affiliates Template for BuddyPress Profile Tab
		function affwp_bp_show_affiliate_sub_affiliates() {
		
			affwp_bp_page( affwp_bp_access_check() );
			
			if( affwp_bp_access_check() == '' ) {
			
			affwp_bp_affiliate_area_notices();
				
			echo '<div id="affwp-affiliate-dashboard">';
		
			affiliate_wp()->templates->get_template_part( 'dashboard-tab', 'sub-affiliates' );
		
			echo '</div>';
					
			}
		}
	}
}

// Run if AffiliateWP Performance Bonuses is Active
if ( class_exists( 'AffiliateWP_Performance_Bonuses' ) ) {

	if ( ! function_exists( 'affwp_bp_add_bonuses_nav_item' ) ) {
		/**
		* Create Affiliate Area Tab for Bonuses in BuddyPress Profile
		*
		* @since  1.1
		*/
		function affwp_bp_add_bonuses_nav_item() {
		
			// Add the Bonuses Tab
			bp_core_new_subnav_item( array(
				'name' => __('Bonuses', 'affiliatewp-bp'),
				'slug' => 'affiliate-bonuses',
				'show_for_displayed_user' => false, 
				'parent_url' => trailingslashit( bp_displayed_user_domain() . 'affiliate-area'),
				'parent_slug' => 'affiliate-area',
				'position' => 80,
				'screen_function' => 'affwp_bp_affiliate_bonuses',
				'item_css_id' => 'affiliate-bonuses',
				'user_has_access' => bp_is_my_profile()
			) );
		}
		add_action( 'affwp_bp_after_add_nav_items', 'affwp_bp_add_bonuses_nav_item' );
	}
	
	if ( ! function_exists( 'affwp_bp_affiliate_bonuses' ) ) {
		/**
		* Load Affiliate Bonuses
		*
		* @since  1.0
		*/
		function affwp_bp_affiliate_bonuses() {
			add_action( 'bp_template_content', 'affwp_bp_show_affiliate_bonuses' );
			bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
		}
	}
	
	if ( ! function_exists( 'affwp_bp_show_affiliate_bonuses' ) ) {
		// Get Affiliate Bonuses Template for BuddyPress Profile Tab
		function affwp_bp_show_affiliate_bonuses() {
		
			affwp_bp_page( affwp_bp_access_check() );
			
			if( affwp_bp_access_check() == '' ) {
			
			affwp_bp_affiliate_area_notices();
				
			echo '<div id="affwp-affiliate-dashboard">';
		
			affiliate_wp()->templates->get_template_part( 'dashboard-tab', 'bonuses' );
		
			echo '</div>';
					
			}
		}
	}
}

// Run if AffiliateWP Order Details is Active
if ( class_exists( 'AffiliateWP_Order_Details_For_Affiliates' ) ) {

	/**
	* Create Affiliate Area Tab for Order Details in BuddyPress Profile
	*
	* @since  1.0
	*/
	function affwp_bp_add_order_details_nav_item() {
	
		// Add the Order Details Tab
		bp_core_new_subnav_item( array(
			'name' => __('Order Details', 'affiliatewp-bp'),
			'slug' => 'affiliate-order-details',
			'show_for_displayed_user' => false, 
			'parent_url' => trailingslashit( bp_displayed_user_domain() . 'affiliate-area'),
			'parent_slug' => 'affiliate-area',
			'position' => 90,
			'screen_function' => 'affwp_bp_affiliate_order_details',
			'item_css_id' => 'affiliate-order-details',
			'user_has_access' => bp_is_my_profile()
		) );
	}
	add_action( 'affwp_bp_after_add_nav_items', 'affwp_bp_add_order_details_nav_item' );
	
	/**
	* Load Affiliate Order Details
	*
	* @since  1.0
	*/
	function affwp_bp_affiliate_order_details() {
		add_action( 'bp_template_content', 'affwp_bp_show_affiliate_order_details' );
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}
	
	// Get Affiliate Order Details Template for BuddyPress Profile Tab
	function affwp_bp_show_affiliate_order_details() {
	
		affwp_bp_page( affwp_bp_access_check() );
		
		if( affwp_bp_access_check() == '' ) {
		
		affwp_bp_affiliate_area_notices();
		
		$affiliate_id = affwp_get_affiliate_id();
		
		if ( ! ( AffiliateWP_Order_Details_For_Affiliates::can_access_order_details( affwp_get_affiliate_user_id( $affiliate_id ) ) || AffiliateWP_Order_Details_For_Affiliates::global_order_details_access() ) ) {
			return;
		}
			
		echo '<div id="affwp-affiliate-dashboard">';
	
		affiliate_wp()->templates->get_template_part( 'dashboard-tab', 'order-details' );
	
		echo '</div>';
				
		}
	}
}

// Run if AffiliateWP Show Affiliate Coupons is Active
if ( class_exists( 'AffiliateWP_Show_Affiliate_Coupons' ) ) {

	/**
	* Create Affiliate Area Tab for Affiliate Coupons in BuddyPress Profile
	*
	* @since  1.0
	*/
	function affwp_bp_add_coupons_nav_item() {
	
		// Don't display tab if integration isn't supported or affiliate doesn't have any active coupons
		if ( ! affwp_sac_integration_supported() || ! AffiliateWP_Show_Affiliate_Coupons::get_coupons() ) {
			return;
		}
	
		// Add the Coupons Tab
		bp_core_new_subnav_item( array(
			'name' => __('Coupons', 'affiliatewp-bp'),
			'slug' => 'affiliate-coupons',
			'show_for_displayed_user' => false, 
			'parent_url' => trailingslashit( bp_displayed_user_domain() . 'affiliate-area'),
			'parent_slug' => 'affiliate-area',
			'position' => 80,
			'screen_function' => 'affwp_bp_affiliate_coupons',
			'item_css_id' => 'affiliate-coupons',
			'user_has_access' => bp_is_my_profile()
		) );
	}
	add_action( 'affwp_bp_after_add_nav_items', 'affwp_bp_add_coupons_nav_item' );
	
	/**
	* Load Affiliate Coupons
	*
	* @since  1.0
	*/
	function affwp_bp_affiliate_coupons() {
		add_action( 'bp_template_content', 'affwp_bp_show_affiliate_coupons' );
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	}
	
	// Get Affiliate Coupons Template for BuddyPress Profile Tab
	function affwp_bp_show_affiliate_coupons() {
	
		affwp_bp_page( affwp_bp_access_check() );
		
		if( affwp_bp_access_check() == '' ) {
		
		affwp_bp_affiliate_area_notices();
			
		echo '<div id="affwp-affiliate-dashboard">';
	
		affiliate_wp()->templates->get_template_part( 'dashboard-tab', 'coupons' );
	
		echo '</div>';
				
		}
	}
}
