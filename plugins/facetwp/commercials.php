<?php
/*
Description: FacetWP Customizations for Commercials
Version: 1.0
Author: Mika Epstein
*/

if ( ! defined('WPINC' ) ) die;

/**
 * class LWComm_FacetWP_Commercials
 *
 * @since 1.0
 */
class LWComm_FacetWP_Commercials {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Filter data before saving it
		add_filter( 'facetwp_index_row', array( $this, 'facetwp_index_row' ), 10, 2 );
	}

	/**
	 * Filter Data before it's saved
	 * Useful for serialized data but also capitalizing stars
	 *
	 * @since 1.1
	 */
	function facetwp_index_row( $params, $class ) {

		// Lezploitation
		// Change ON to YES
		if ( 'video_lezploit' == $params['facet_name'] ) {
			$params['facet_value'] = $params['facet_value'];
			$params['facet_display_value'] = "Yes";
			$class->insert( $params );
			return false; // skip default indexing
	    }

	    return $params;
	}
}

new LWComm_FacetWP_Commercials();