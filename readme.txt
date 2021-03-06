=== AffiliateWP BuddyPress ===
Contributors: codelion7
Tags: AffiliateWP, affiliate, Pippin Williamson, Andrew Munro, mordauk, Christian Freeman, pippinsplugins, sumobi, codelion7, ecommerce, e-commerce, e commerce, selling, referrals, 
Requires at least: 3.9
Tested up to: 4.2.2
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Integrate AffiliateWP with BuddyPress.

== Description ==

Once activated, this plugin creates a BuddyPress Profile Tab for each tab of the Affiliate Area:

* URLs
* Stats
* Graphs
* Referrals
* Payouts
* Visits
* Creatives
* Settings
* Sub Affiliates
* Bonuses
* Coupons
* Order Details

If a user is not an affiliate, it will display the Affiliate Registration page on their profile instead.


This plugin requires [AffiliateWP](http://affiliatewp.com/ "AffiliateWP") and [BuddyPress](http://buddypress.org/ "BuddyPress") in order to function.


Remember to set your Affiliate Area page in Affiliates &rarr; Settings to be your [login page](http://docs.affiliatewp.com/article/98-affiliatelogin "Affiliate Login Shortcode") or your affiliates will have no where to login from via emails etc.

**What is AffiliateWP?**

[AffiliateWP](http://affiliatewp.com/ "AffiliateWP") provides a complete affiliate management system for your WordPress website that seamlessly integrates with all major WordPress e-commerce and membership platforms. It aims to provide everything you need in a simple, clean, easy to use system that you will love to use.

== Installation ==

1. Unpack the entire contents of this plugin zip file into your `wp-content/plugins/` folder locally
1. Upload to your site
1. Navigate to `wp-admin/plugins.php` on your site (your WP Admin plugin page)
1. Activate this plugin

== Frequently Asked Questions ==

= Does this plugin work with multisite installations? =

Absolutely. This plugin is designed to work for BuddyPress & AffiliateWP, even when network activated.

== Changelog ==

= 1.4 =
* UPDATE - Added Payouts tab for AffiliateWP 1.9+
* FIX - Hide Affiliate Area Menu in Affiliate Dashboard Tab for AffiliateWP 1.9+

= 1.3 =
* NEW - Option to Hide Affiliate Area tabs from the BP profile.
* NEW - Option to add your own custom label on the Affiliate Area tab in BP profile.
* FIX - Error related to displaying current tab positions.
* FIX - Bug in Affiliate Area Tab due to AffiliateWP 1.8.1 update. 

= 1.2 =
* NEW - Option to choose where to display the Affiliate Area tab in BP profile.
* UPDATE - Display current menu positions for each tab.
* UPDATE - Display Affiliate Dashboard in URLs tab.
* FIX - Fatal error when deactivating AffiliateWP.

= 1.1 =
* NEW - Order Details Profile Tab.
* NEW - Coupons Profile Tab.
* UPDATE - Transferred Sub Affiliates BP profile tab code to BuddyPress add-on.
* UPDATE - Transferred Performances Bonuses BP profile tab code to BuddyPress add-on.

= 1.0 =
* Initial release