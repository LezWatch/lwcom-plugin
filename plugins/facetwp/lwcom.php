<?php
/*
Library: FacetWP Add Ons
Description: Addons for FacetWP that make life worth living
Author: Mika Epstein
*/

// Bail if the plugin is missing.
if ( ! class_exists( 'FacetWP' ) ) {
	return;
}

/**
 * class LWComm_FacetWP_Addons
 *
 * Customize FacetWP
 *
 * @since 1.0
 */
class LWComm_FacetWP_Addons {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Filter data before saving it
		add_filter( 'facetwp_index_row', array( $this, 'facetwp_index_row' ), 10, 2 );

		// Filter paged output
		add_filter( 'facetwp_pager_html', array( $this, 'facetwp_pager_html' ), 10, 2 );

		// Javascript
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

		// Reset Shortcode
		add_shortcode( 'facetwp-reset', array( $this, 'reset_shortcode' ) );

		if ( is_admin() ) {
			// Don't output <!--fwp-loop--> on admin pages
			add_filter( 'facetwp_is_main_query', function( $is_main_query, $query ) {
				return false;
			}, 10, 2 );
		} else {
			// DO output on pages where the main-query is set to true anyway. Asshols
			add_filter( 'facetwp_is_main_query', array( $this, 'facetwp_is_main_query' ), 10, 2 );
		}
	}

	public function wp_enqueue_scripts() {
		wp_enqueue_script( 'facetwp-pagination', plugins_url( 'facetwp/pagination.js', __FILE__ ), array(), '1.0', true );
	}

	/**
	 * Force Facet to show sometimes
	 */
	public function facetwp_is_main_query( $is_main_query, $query ) {
		if ( isset( $query->query_vars['facetwp'] ) ) {
			$is_main_query = true;
		}
		return $is_main_query;
	}

	/**
	 * Filter Data before it's saved
	 * Useful for serialized data but also capitalizing stars
	 *
	 * @since 1.1
	 */
	public function facetwp_index_row( $params, $class ) {

		// Lezploitation
		// Change ON to YES
		if ( 'video_lezploit' === $params['facet_name'] ) {
			$params['facet_value']         = ( 'on' === $params['facet_value'] ) ? 'yes' : 'no';
			$params['facet_display_value'] = ( 'on' === $params['facet_display_value'] ) ? 'Yes' : 'No';
			$class->insert( $params );
			// skip default indexing
			$params['facet_value'] = '';
			return $params;
		}

		// Some extra weird things...
		// Becuase you can't store data for EMPTY fields so there's a 'fake'
		// facet called 'all_the_missing' and we use it to pass through data
		if ( 'all_the_missing' === $params['facet_name'] ) {
			// If we do not love the show...
			$lezploit = get_post_meta( $params['post_id'], 'lezcommercial_lezploitation', true );
			if ( empty( $lezploit ) ) {
				$params_lezploit                        = $params;
				$params_lezploit['facet_name']          = 'video_lezploit';
				$params_lezploit['facet_source']        = 'cf/lezcommercial_lezploitation';
				$params_lezploit['facet_value']         = 'no';
				$params_lezploit['facet_display_value'] = 'No';
				$class->insert( $params_lezploit );
			}
			// skip default indexing
			$params['facet_value'] = '';
			return $params;
		}

		return $params;
	}

	/**
	 * Only show pagination if there's more than one page
	 * Credit: https://gist.github.com/mgibbs189/69176ef41fa4e26d1419
	 */
	public function facetwp_pager_html( $output, $params ) {

		$output      = '';
		$page        = (int) $params['page'];
		$total_pages = (int) $params['total_pages'];

		// Only show pagination when > 1 page
		if ( 1 < $total_pages ) {

			if ( 1 < $page ) {
				$output .= '<a class="facetwp-page" data-page="' . ( $page - 1 ) . '">&laquo; Previous</a>';
			}
			if ( 3 < $page ) {
				$output .= '<a class="facetwp-page first-page" data-page="1">1</a>';
				$output .= ' <span class="dots">…</span> ';
			}
			for ( $i = 2; $i > 0; $i-- ) {
				if ( 0 < ( $page - $i ) ) {
					$output .= '<a class="facetwp-page" data-page="' . ( $page - $i ) . '">' . ( $page - $i ) . '</a>';
				}
			}

			// Current page
			$output .= '<a class="facetwp-page active" data-page="' . $page . '">' . $page . '</a>';

			for ( $i = 1; $i <= 2; $i++ ) {
				if ( $total_pages >= ( $page + $i ) ) {
					$output .= '<a class="facetwp-page" data-page="' . ( $page + $i ) . '">' . ( $page + $i ) . '</a>';
				}
			}
			if ( $total_pages > ( $page + 2 ) ) {
				$output .= ' <span class="dots">…</span> ';
				$output .= '<a class="facetwp-page last-page" data-page="' . $total_pages . '">' . $total_pages . '</a>';
			}
			if ( $page < $total_pages ) {
				$output .= '<a class="facetwp-page" data-page="' . ( $page + 1 ) . '">Next &raquo;</a>';
			}
		}

		return $output;
	}

	/*
	 * Reset Shortcode
	 *
	 * Echo reset button
	 *
	 * @since 1.1.0
	 */
	public function reset_shortcode( $atts ) {
		$reset = '<center><button class="facetwp-reset" onclick="FWP.reset()">Reset All Parameters</button></center>';
		return $reset;
	}

}
new LWComm_FacetWP_Addons();
