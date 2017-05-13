<?php
/*
Plugin Name: LezWatch Commercials
Plugin URI:  https://lezwatchcommercials.com
Description: All the base functions for running LezWatch Commercials, which aren't dependant on the theme.
Version: 1.0
Author: Mika Epstein
*/

// Include CPTs
include( 'cpts/commercials.php' );

// Category Icons
include( 'category-icons/category-icons.php' );

// If Facet WP is active, call customizations
if ( class_exists( 'FacetWP' ) ) {
	require_once( 'plugins/facetwp.php' );
}