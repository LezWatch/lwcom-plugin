<?php
/*
Plugin Name: LezWatch Commercials
Plugin URI:  https://lezwatchcommercials.com
Description: All the base functions for running LezWatch Commercials, which aren't dependant on the theme.
Version: 1.0
Author: Mika Epstein
*/

// First make sure this only runs on the right sites
$site_url    = parse_url( get_site_url() );
$valid_sites = array( 'lezwatchcommercials.com', 'com.lezpress.local', 'lezwatchcommercials.dev', 'lezwatchcommercials.local' );

function lwcom_plugin_deactivation() {
	global $valid_sites;
    echo '<div id="message" class="error"><p>';
    echo 'This plugin can only be run on LezWatch Commercials. You\'re using <strong>' . get_site_url() . '</strong>. If you\'re trying to run this on a local site, tell Mika your URL or use one of these: ' . implode( ", ", $valid_sites ) .'.';
    echo '</p></div>';
}

if ( !in_array( $site_url['host'], $valid_sites ) ) {
	deactivate_plugins( plugin_basename( __FILE__ ) );
	add_action( 'admin_notices', 'lwcom_plugin_deactivation' );
    return;
}

// Include CPTs
include( 'cpts/commercials.php' );

// Category Icons
include( 'category-icons/category-icons.php' );

// If Facet WP is active, call customizations
if ( class_exists( 'FacetWP' ) ) {
	require_once( 'plugins/facetwp.php' );
}