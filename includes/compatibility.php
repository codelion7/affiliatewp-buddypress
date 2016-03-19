<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Check to see if a supported integration is enabled for affiliate coupons
 *
 * @since 1.1
 *
 * @return void
 */
function affwp_sac_integration_supported() {
	$supported_integrations = AffiliateWP_Show_Affiliate_Coupons::supported_integrations();
	$enabled_integrations = affiliate_wp()->integrations->get_enabled_integrations();
	foreach ( $enabled_integrations as $integration_key => $integration ) {
		// integration supported
		if ( in_array( $integration_key, $supported_integrations ) ) {
			return true;
		}
	}
	return false;
}