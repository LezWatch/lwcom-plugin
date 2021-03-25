<?php
/*
Plugin Name: LezWatch.Commercials
Plugin URI:  https://lezwatchcommercials.com
Description: All the base functions for running LezWatch.Commercials, which aren't dependant on the theme.
Version: 1.2.1
Author: Mika Epstein
*/

/*
 * Require the library code
 */
if ( file_exists( WP_CONTENT_DIR . '/library/functions.php' ) ) {
	require_once WP_CONTENT_DIR . '/library/functions.php';
	define( 'LWTV_LIBRARY', true );
}

/**
 * class LWCom_Functions
 *
 * The background functions for the site, independent of the theme.
 */
class LWCom_Functions {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_filter( 'http_request_args', array( $this, 'disable_wp_update' ), 10, 2 );
		add_action( 'pre_current_active_plugins', array( $this, 'hide_plugin' ) );
	}

	/**
	 * Hide the LWTV Plugin.
	 *
	 * @access public
	 * @return void
	 */
	public function hide_plugin() {
		global $wp_list_table;

		$hide_plugins = array(
			plugin_basename( __FILE__ ),
		);
		$curr_plugins = $wp_list_table->items;
		foreach ( $curr_plugins as $plugin => $data ) {
			if ( in_array( $plugin, $hide_plugins, true ) ) {
				unset( $wp_list_table->items[ $plugin ] );
			}
		}
	}

	/**
	 * Disable WP from updating this plugin..
	 *
	 * @access public
	 * @param mixed $return - array to return.
	 * @param mixed $url    - URL from which checks come and need to be blocked (i.e. wp.org)
	 * @return array        - $return
	 */
	public function disable_wp_update( $return, $url ) {
		if ( 0 === strpos( $url, 'https://api.wordpress.org/plugins/update-check/' ) ) {
			$my_plugin = plugin_basename( __FILE__ );
			$plugins   = json_decode( $return['body']['plugins'], true );
			unset( $plugins['plugins'][ $my_plugin ] );
			unset( $plugins['active'][ array_search( $my_plugin, $plugins['active'], true ) ] );
			$return['body']['plugins'] = wp_json_encode( $plugins );
		}
		return $return;
	}
}
new LWCom_Functions();

/**
 * This function does NOTHING, and if someone submitted it to the .org repo,
 * I would slap them with a fish. The problem is there isn't a good way to
 * sanitize an SVG, so it's this or having a million WPCS comments.
 * @param  mixed $input HTML content with an SVG
 * @return mixed        Content with an SVG
 */
function lwcom_sanitized( $input ) {
	return $input;
}

/*
 * Add-Ons.
 */
require_once 'cpts/_main.php';
require_once 'plugins/_main.php';
