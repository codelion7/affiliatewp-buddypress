<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class AffiliateWP_BP_Settings {
	
	public function __construct() {

		add_filter( 'affwp_settings_tabs', array( $this, 'settings_tab' ) );
		add_filter( 'affwp_settings', array( $this, 'settings' ), 10, 1 );

	}

	/**
	 * Register the BuddyPress Settings Tab
	 *
	 * @since 1.0
	 */
	public function settings_tab( $tabs ) {
		$tabs['buddypress'] = __( 'BuddyPress', 'affiliatewp-buddypress' );
		return $tabs;
	}
	
	/**
	 * Register BuddyPress Settings
	 *
	 * @since 1.0
	 */
	public function settings( $settings ) {
	
		// BuddyPress Settings
		$buddypress_settings = array(
			
			'buddypress' => apply_filters( 'affwp_settings_bp',
				array(
					'affwp_bp_aff_area_header' => array(
						'name' => '<strong>' . __( 'Profile Settings', 'affiliatewp-buddypress' ) . '</strong>',
						'type' => 'header',
					),				
					'affwp_bp_aff_area_hide_tabs' => array(
						'name' => __( 'Hide Tabs', 'affiliatewp-multi-level-marketing' ),
						'desc' => '<p class="description">' . __( 'Choose the affiliate area tabs that you wish to hide from the buddypress profile.', 'affiliatewp-multi-level-marketing' ) . '</p>',
						'type' => 'multicheck',
						'options' => apply_filters( 'affwp_bp_aff_area_hide_tabs', array(
							'urls'      => __( 'Affiliate URLs', 'affiliatewp-buddypress' ),
							'stats'     => __( 'Statistics', 'affiliatewp-buddypress' ),
							'graphs'    => __( 'Graphs', 'affiliatewp-buddypress' ),
							'referrals' => __( 'Referrals', 'affiliatewp-buddypress' ),
							'payouts' 	=> __( 'Payouts', 'affiliatewp-buddypress' ),
							'visits'    => __( 'Visits', 'affiliatewp-buddypress' ),
							'creatives' => __( 'Creatives', 'affiliatewp-buddypress' ),
							'settings'  => __( 'Settings', 'affiliatewp-buddypress' ),							
						) )
					),
					'affwp_bp_aff_area_tab_location' => array(
						'name' => __( 'Tab Order', 'affiliatewp-buddypress' ),
						'desc' => '<p class="description">' . __( 'Choose where the affiliate area tab will be displayed on the buddypress profile.', 'affiliatewp-buddypress' ) . '</p>',
						'type' => 'select',
						'options' => array(
							'beginning' 	=> 'The Beginning',
							'end'   		=> 'The End',
							'custom'   => 'Custom',
						)
					),
					'affwp_bp_aff_area_tab_order' => array(
						'name' => __( 'Custom Tab Order', 'affiliatewp-buddypress' ),
						'desc' => '<p class="description">' . __( 'Set a custom order/priority for the affiliate area tab.', 'affiliatewp-buddypress' ) . '</p>'. $this->get_bp_nav_positions(),
						'type' => 'number',
						'size' => 'small',
					),
					'affwp_bp_aff_area_tab_label' => array(
						'name' => __( 'Tab Label', 'affiliatewp-buddypress' ),
						'desc' => '<p class="description">' . __( 'Add a custom label for the affiliate area buddypress profile tab.', 'affiliatewp-buddypress' ) . '</p>',
						'type' => 'text',
						'size' => 'regular',
					),


				)
			)
		);

		$settings = array_merge( $settings, $buddypress_settings );
		
		return $settings;
	}

	/**
	 * Sort Current Tabs by Position
	 *
	 * @since 1.0
	 */
	// public function sort_bp_nav_positions( $data, $field ) {
	public function sort_bp_nav_positions( $a, $b ) {

		/*
		$code = "return strnatcasecmp(\$a['$field'], \$b['$field']);";
		usort( $data, create_function( '$a,$b', $code ) );
		
		return $data;  
		*/
		
		/*
		if ( $a->position == $b->position ) {
			return 0;
		}
		return ($a->position < $b->position ) ? -1 : 1;
		*/
		
		return strcmp( $a->position, $b->position );	
	}

	/**
	 * Get the Current BuddyPress Tab Positions
	 *
	 * @since 1.0
	 */
	public function get_bp_nav_positions() {
		global $bp;
		
		$bp_nav_positions = array();
		$position = 0;
		
		if ( isset( $bp->bp_nav ) ) {
			foreach ( $bp->bp_nav as $pos => $data ) {
				if ( ! isset( $data['slug'] ) ) continue; 
				$position = $data['position'];
				$bp_nav_positions[] = '<strong>' . ucwords( $data['slug'] ) . '</strong> = ' . $position;
			}
		}
		
		// Sort by position
		// $sorted_bp_nav_positions = $this->sort_bp_nav_positions( $bp_nav_positions, 'position' );
		usort( $bp_nav_positions, array( $this, 'sort_bp_nav_positions' ) );
		
		// DEBUG - return var_dump( $sorted_bp_nav_positions );
		
		return '<br><span class="description"><h4>' . __( 'Current Menu Positions:', 'affiliatewp-buddypress' ) . '</h4>  ' . implode( ', ', $bp_nav_positions ) . '</span>';
	}
	
}
