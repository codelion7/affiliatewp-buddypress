<?php
/**
 * Plugin Name: AffiliateWP - BuddyPress Integration
 * Plugin URI: http://affiliatewp.com
 * Description: Displays the Affiliate Area on each Affiliate's BuddyPress Profile
 * Author: Christian Freeman
 * Author URI: http://affiliatewp.com
 * Version: 1.0
 */


/**
 * Force the frontend scripts to load on affiliate area profile tabs
 * 
 * @since  1.0
 */
if ( ! function_exists( 'affwp_aas_force_frontend_scripts' ) ){
	function affwp_bp_force_frontend_scripts() {
	
		if ( function_exists( 'affiliate_area_bp_nav_adder' ) ){
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
add_action( 'bp_setup_nav', 'affiliate_area_bp_nav_adder' );
function affiliate_area_bp_nav_adder() {
	
	// Add the Affiliate Area (Dashboard) Tab
	bp_core_new_nav_item( array(
		'name' => __('Affiliate Area', 'affiliatewp-bp'),
		'slug' => 'affiliate-area',
		'position' => 3,
		'show_for_displayed_user' => false,
		'screen_function' => 'affwp_bp_affiliate_urls',
		'item_css_id' => 'affiliate-area',
		'default_subnav_slug' => 'affiliate-area'
	) );
	
	// Add the Affiliate Area (URLs) Sub Tab
	bp_core_new_subnav_item( array( 
		'name' => __('Affiliate URLs', 'affiliatewp-bp'),
		'slug' => 'affiliate-area',
		'show_for_displayed_user' => false, 
		'parent_url' => trailingslashit( bp_displayed_user_domain() . 'affiliate-area'),
		'parent_slug' => 'affiliate-area',
		'position' => 10,
		'screen_function' => 'affwp_bp_affiliate_urls',
		'item_css_id' => 'affiliate-area',
		'user_has_access' => bp_is_my_profile()
	) ); 
	
	// Add the Stats Sub Tab
	bp_core_new_subnav_item( array( 
		'name' => __('Stats', 'affiliatewp-bp'),
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
		'name' => __('Graphs', 'affiliatewp-bp'),
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
		'name' => __('Referrals', 'affiliatewp-bp'),
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
		'name' => __('Visits', 'affiliatewp-bp'),
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
		'name' => __('Creatives', 'affiliatewp-bp'),
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
		'name' => __('Settings', 'affiliatewp-bp'),
		'slug' => 'affiliate-settings',
		'show_for_displayed_user' => false, 
		'parent_url' => trailingslashit( bp_displayed_user_domain() . 'affiliate-area'),
		'parent_slug' => 'affiliate-area',
		'position' => 70,
		'screen_function' => 'affwp_bp_affiliate_settings',
		'item_css_id' => 'affiliate-settings',
		'user_has_access' => bp_is_my_profile()
	) );
}

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
* Load Affiliate URLs
*
* @since  1.0
*/
function affwp_bp_affiliate_urls() {
    add_action( 'bp_template_content', 'affwp_show_bp_affiliate_urls' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Get Affiliate URLs Page for BuddyPress Profile Tab
function affwp_show_bp_affiliate_urls() {

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
    add_action( 'bp_template_content', 'affwp_show_bp_affiliate_stats' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Get Affiliate Stats Page for BuddyPress Profile Tab
function affwp_show_bp_affiliate_stats() {

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
    add_action( 'bp_template_content', 'affwp_show_bp_affiliate_graphs' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Get Affiliate Graphs Page for BuddyPress Profile Tab
function affwp_show_bp_affiliate_graphs() {

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
    add_action( 'bp_template_content', 'affwp_show_bp_affiliate_referrals' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Get Affiliate Referrals Page for BuddyPress Profile Tab
function affwp_show_bp_affiliate_referrals() {

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
    add_action( 'bp_template_content', 'affwp_show_bp_affiliate_visits' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Get Affiliate Visits Page for BuddyPress Profile Tab
function affwp_show_bp_affiliate_visits() {

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
    add_action( 'bp_template_content', 'affwp_show_bp_affiliate_creatives' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Get Affiliate Creatives Page for BuddyPress Profile Tab
function affwp_show_bp_affiliate_creatives() {

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
    add_action( 'bp_template_content', 'affwp_show_bp_affiliate_settings' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

// Get Affiliate Settings Page for BuddyPress Profile Tab
function affwp_show_bp_affiliate_settings() {

	affwp_bp_page( affwp_bp_access_check() );
	
	if( affwp_bp_access_check() == '' ) {
	
		affwp_bp_affiliate_area_notices();
		
		echo '<div id="affwp-affiliate-dashboard">';
	
		affiliate_wp()->templates->get_template_part( 'dashboard-tab', 'settings' );
	
		echo '</div>';
	}
}

/**
* Create AffiliateWP BP Admin Settings (Integrations Tab)
*
* @since  1.0
*/
function affiliatewp_bp_admin_settings( $settings = array() ) {
	$settings[ 'affwp_bp_disable_msgs' ] = array(
		'name' => __( 'Disable BuddyPress Messages', 'affiliatewp-bp' ),
		'desc' => __( 'Disable buddypress message notifications?', 'affiliatewp-bp' ),
		'type' => 'checkbox'
	);
	return $settings;
}
add_filter( 'affwp_settings_integrations', 'affiliatewp_bp_admin_settings' );

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

/**
* Send AffiliateWP Messages
*
* @since  1.0
*/

/**
 * Send a message to admin when a user completes affiliate registration
 *
 * @since 1.0
 */
function affiliatewp_bp_msg_on_registration( $affiliate_id = 0, $status = '', $args = array() ) {
    if ( ! bp_is_active( 'messages' ) )
        return;
	
	// Make sure it's enabled in settings - NEW
	if ( affiliate_wp()->settings->get( 'affwp_bp_disable_msgs' ))
		return;

	affiliatewp_bp_message( 'registration', array( 'affiliate_id' => $affiliate_id ) );

}
add_action( 'affwp_register_user', 'affiliatewp_bp_msg_on_registration', 11, 3 );


/**
 * Send a message to the affiliate when their application has been approved
 *
 * @since 1.0
 */
function affiliatewp_bp_msg_on_approval( $affiliate_id = 0, $status = '', $old_status = '' ) {
    if ( ! bp_is_active( 'messages' ) )
        return;

	// Make sure it's enabled in settings - NEW
	if ( affiliate_wp()->settings->get( 'affwp_bp_disable_msgs' ))
		return;

	// Make sure affiliate has been approved
	if ( 'active' != $status || 'pending' != $old_status )
		return;

	affiliatewp_bp_message( 'application_accepted', array( 'affiliate_id' => $affiliate_id ) );
}
add_action( 'affwp_set_affiliate_status', 'affiliatewp_bp_msg_on_approval', 10, 3 );

/**
 * Send a message to the affiliate when their application has been rejected
 *
 * @since 1.0
 */
function affiliatewp_bp_msg_on_rejection( $affiliate_id = 0, $status = '', $old_status = '' ) {
    if ( ! bp_is_active( 'messages' ) )
        return;

	// Make sure it's enabled in settings - NEW
	if ( affiliate_wp()->settings->get( 'affwp_bp_disable_msgs' ))
		return;
		
	// Make sure affiliate has been rejected	
	if ( 'rejected' != $status || 'pending' != $old_status )
		return;
	
	affiliatewp_bp_message( 'application_rejected', array( 'affiliate_id' => $affiliate_id ) );
}
add_action( 'affwp_set_affiliate_status', 'affiliatewp_bp_msg_on_rejection', 10, 3 );

/**
 * Send a message to the affiliate when they generate a new referral
 *
 * @since 1.0
 */
function affiliatewp_bp_msg_for_new_referral( $affiliate_id = 0, $referral ) {
    if ( ! bp_is_active( 'messages' ) )
        return;

	$user_id = affwp_get_affiliate_user_id( $affiliate_id );
	
	// Make sure it's enabled in settings - NEW
	if ( affiliate_wp()->settings->get( 'affwp_bp_disable_msgs' ))
		return;

	/* Make sure it's enabled in settings - USER EMAILS
	if ( ! get_user_meta( $user_id, 'affwp_referral_notifications', true ) )
		return;
	*/
	
	affiliatewp_bp_message( 'new_referral', array( 'affiliate_id' => $affiliate_id, 'referral_id' => $referral->referral_id, 'amount' => $referral->amount ) );
}
add_action( 'affwp_referral_accepted', 'affiliatewp_bp_msg_for_new_referral', 10, 2 );

/**
 * Send a message to the affiliate when their referral has been paid
 *
 * @since 1.0
 */
function affiliatewp_bp_msg_for_paid_referral( $referral_id, $new_status, $old_status ) {
    if ( ! bp_is_active( 'messages' ) )
        return;

	if ( 'paid' != $new_status || 'unpaid' != $old_status )
		return;	

	$user_id = affwp_get_affiliate_user_id( $affiliate_id );
	
	// Make sure it's enabled in settings - NEW
	if ( affiliate_wp()->settings->get( 'affwp_bp_disable_msgs' ))
		return;

	/* Make sure it's enabled in settings - USER EMAILS
	if ( ! get_user_meta( $user_id, 'affwp_referral_notifications', true ) )
		return;
	*/

	$referral = affwp_get_referral( $referral_id );
	
	affiliatewp_bp_message( 'new_payout', array( 'affiliate_id' => $referral->affiliate_id, 'referral_id' => $referral_id, 'amount' => $referral->amount ) );
}
add_action( 'affwp_set_referral_status', 'affiliatewp_bp_msg_for_paid_referral', 10, 3 );

// Allow new message types
do_action( 'affiliatewp_bp_new_message_type' );

/**
 * Format and send messages
 *
 * @since 1.0
 */
function affiliatewp_bp_message( $type = '', $args = array() ) {
	global $blog_id;
	
	if ( empty( $type ) ) {
		return false;
	}

	$admin_id			= 1;
	$affiliate_id 		= $args['affiliate_id'];
	$affiliate_name 	= affiliate_wp()->affiliates->get_affiliate_name( $affiliate_id );	
	$user_info 			= get_userdata( affwp_get_affiliate_user_id( $affiliate_id ) );
	$user_id			= $user_info->ID;
	$user_name			= $args['name'];
	$user_fname			= $user_info->first_name;
	$user_link			= bp_core_get_user_domain( $user_id );
	$blog_details 		= get_blog_details( $blog_id );
	$blog_name 			= $blog_details->blogname;

	switch( $type ) {
		case 'registration':

			$user_url = $user_info->user_url;
			$promotion_method = get_user_meta( $user_id, 'affwp_promotion_method', true );
			$pending_url  = admin_url( 'admin.php?page=affiliate-wp-affiliates&status=pending' );
			
			$msg_content  = sprintf( __( "A new affiliate has registered on %s,\n\n", "affiliatewp-bp" ), $blog_name );
			$msg_content .= sprintf( __( "Name: %s\n\n", "affiliatewp-bp" ), $user_name );
			
			if ( $user_url ) {
				$msg_content .= sprintf( __( "Website URL: %s\n\n", "affiliatewp-bp" ), esc_url( $user_url ) );
			}
			
			if ( $promotion_method ) {
				$msg_content .= sprintf( __( "Promotion Method: %s\n\n", "affiliatewp-bp" ), esc_attr( $promotion_method ) );
			}
			
			if ( affiliate_wp()->settings->get( 'require_approval' ) ) {
				$msg_content  .= sprintf( __( "Review pending applications: %s\n\n", "affiliatewp-bp" ), $pending_url );
			}

			$msg_recipients = array( apply_filters( 'affwp_bp_registration_admin_id', $admin_id ) );
			
			// Load message data
			$sender_id = apply_filters( 'affwp_bp_registration_admin_id', $admin_id );
			$recipients = $msg_recipients;
			$subject = __( 'New Affiliate Registration', 'affiliatewp-bp' );
			$content = $msg_content;
			$date_sent = bp_core_current_time();

			// Enable message customization
			$recipients = apply_filters( 'affwp_bp_registration_recipients', $recipients, $args );
			$subject = apply_filters( 'affwp_bp_registration_subject', $subject, $args, $blog_name );
			$content = apply_filters( 'affwp_bp_registration_content', $content, $args, $blog_name );
			
		break;
		
		case 'application_accepted':

			$msg_content  = sprintf( __( "Congratulations %s!\n\n", "affiliatewp-bp" ), $user_fname );
			$msg_content .= sprintf( __( "Your affiliate application on %s has been accepted!\n\n", "affiliatewp-bp" ), $blog_name );
			$msg_content .= sprintf( __( "Access your affiliate area at %s\n\n", "affiliatewp-bp" ), trailingslashit( bp_core_get_user_domain( $user_id ) . 'affiliate-area' ) );
			
			$msg_recipients = array( $user_id );
			
			// Load message data 
			$sender_id = apply_filters( 'affwp_bp_registration_admin_id', $admin_id );
			$recipients = $msg_recipients;
			$subject = __( 'Affiliate Application Accepted', 'affiliatewp-bp' );
			$content = $msg_content;
			$date_sent = bp_core_current_time();

			// Enable message customization
			$subject = apply_filters( 'affwp_bp_application_accepted_subject', $subject, $args, $blog_name );
			$content = apply_filters( 'affwp_bp_application_accepted_content', $content, $args, $blog_name );

		break;
		
		case 'application_rejected':
		
			$msg_content  = sprintf( __( "Hi %s,\n\n", "affiliatewp-bp" ), $user_fname );
			$msg_content .= sprintf( __( "We regret to inform you that your application to become an affiliate on %s was not approved.\n\n", "affiliatewp-bp" ), $blog_name );
			
			$msg_recipients = array( $user_id );

			// Load message data
			$sender_id = apply_filters( 'affwp_bp_registration_admin_id', $admin_id );
			$recipients = $msg_recipients;
			$subject = __( 'Affiliate Application Denied', 'affiliatewp-bp' );
			$content = $msg_content;
			$date_sent = bp_core_current_time();

			// Enable message customization
			$subject = apply_filters( 'affwp_bp_application_rejected_subject', $subject, $args, $blog_name );
			$content = apply_filters( 'affwp_bp_application_rejected_content', $content, $args, $blog_name );
			
		break;
		
		case 'new_referral': 
		
			$affiliate_id = $args['affiliate_id'];
			$user_id = affwp_get_affiliate_user_id( $affiliate_id );
			$referral = affwp_get_referral( $args['referral_id'] );
			$amount = affwp_currency_filter( $referral->amount ); 
			$referrals_page = trailingslashit( bp_core_get_user_domain( $user_id ) . 'affiliate-referrals' );
			$referral_date = date_i18n( get_option( 'date_format' ), strtotime( $referral->date ) );
			
			$msg_content  = sprintf( __( "Congratulations %s!\n\n", "affiliatewp-bp" ), $user_fname );
			$msg_content .= sprintf( __( "You have been awarded a new referral of %s for %s on %s!\n\n", "affiliatewp-bp" ), $amount, $referral_date, $blog_name );
			$msg_content .= sprintf( __( "You can view your referrals at any time from your affiliate area: %s!\n\n", "affiliatewp-bp" ), $referrals_page );
			$msg_content .= __( "Keep it up!\n\n", "affiliatewp-bp" );

			$msg_recipients = array( $user_id );

			// Load message data
			$sender_id = apply_filters( 'affwp_bp_registration_admin_id', $admin_id );
			$recipients = $msg_recipients;
			$subject = __( 'Referral Awarded!', 'affiliatewp-bp' );
			$content = $msg_content;
			$date_sent = bp_core_current_time();

			// Enable message customization
			$subject = apply_filters( 'affwp_bp_new_referral_subject', $subject, $args, $blog_name );
			$content = apply_filters( 'affwp_bp_new_referral_content', $content, $args, $blog_name );
			
		break;

		case 'new_payout': 
		
			$referral = affwp_get_referral( $args['referral_id'] );
			$affiliate_id = $referral->affiliate_id;
			$user_id = affwp_get_affiliate_user_id( $affiliate_id );
			$user_info = get_userdata( affwp_get_affiliate_user_id( $affiliate_id  ) );
			$user_fname	= $user_info->first_name;
			$referrals_page = trailingslashit( bp_core_get_user_domain( $user_id ) . 'affiliate-referrals' );
			$amount = affwp_currency_filter( $referral->amount ); 
			$referral_date = date_i18n( get_option( 'date_format' ), strtotime( $referral->date ) );

			$msg_content  = sprintf( __( "Congratulations %s!\n\n", "affiliatewp-bp" ), $user_fname );
			$msg_content .= sprintf( __( "Your referral for %s from %s on %s, has just been paid!\n\n", "affiliatewp-bp" ), $amount, $referral_date, $blog_name );
			$msg_content .= sprintf( __( "You can view your referrals at any time from your affiliate area: %s!\n\n", "affiliatewp-bp" ), $referrals_page );
			$msg_content .= __( "Keep it up!\n\n", "affiliatewp-bp" );

			$msg_recipients = array( $user_id );

			// Load message data
			$sender_id = apply_filters( 'affwp_bp_registration_admin_id', $admin_id );

			$recipients = $msg_recipients;
			$subject = __( 'Referral Paid!', 'affiliatewp-bp' );
			$content = $msg_content;
			$date_sent = bp_core_current_time();

			// Enable message customization
			$subject = apply_filters( 'affwp_bp_new_payout_subject', $subject, $args, $blog_name );
			$content = apply_filters( 'affwp_bp_new_payout_content', $content, $args, $blog_name );

		break;
		
		// Allow formatting for new message types
		do_action( 'affiliatewp_bp_new_message_format', $type, $args );
	}

	// Load all message data
	ob_start();
	$msg_args = array(
		'sender_id' => $sender_id,
		'thread_id' => false,
		'recipients' => $recipients,
		'subject' => $subject,
		'content' => $content,
		'date_sent' => $date_sent
	);
	ob_clean();
	
	// Send Message
	$result = messages_new_message( $msg_args );
	
	/*
	* Note: $result will be either the ID of the new message thread, 
	* if the message was sent successfully, or it’ll be false on failure.
	*/
	
	/* DEBUG */
	if( $result == false ){
		echo '<p>'. $type .' Message Failed</p>';
		echo '<p>Admin ID: '. $admin_id .'</p>';
		echo '<p>Pending Apps URL: '. $pending_url .'</p>';
		echo '<p>Affiliate ID: '. $affiliate_id .'</p>';
		echo '<p>Affiliate Name: '. $affiliate_name .'</p>';
		//echo 'User Info: '. $user_info;
		echo '<p>User ID: '. $user_id .'</p>';
		echo '<p>User First Name: '. $user_fname .'</p>';
		echo '<p>User URL: '. $user_url .'</p>';
		echo '<p>User Link: '. $user_link .'</p>';
		echo '<p>Promotion Method: '. $promotion_method .'</p>';
		//echo 'Blog Details: '.$blog_details;
		echo '<p>Blog Name: '. $blog_name .'</p>';		
			
		echo '<p>Sender ID: '. $sender_id .'</p>';
		echo '<p>Thread ID: '. $thread_id .'</p>';
		echo '<p>Recipients: '. $recipients .'</p>';
		echo '<p>Subject: '.$subject .'</p>';
		echo '<p>Content: '. $content .'</p>';
		echo '<p>Date: '.$date_sent .'</p>';
	}
}

// Need to add message deletion functions
