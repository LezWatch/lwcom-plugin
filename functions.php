<?php
/*
Plugin Name: LezWatch Commercials
Plugin URI:  https://lezwatchcommercials.com
Description: All the base functions for running LezWatch Commercials, which aren't dependant on the theme.
Version: 1.0
Author: Mika Epstein
*/

// Include Library files
// Most things will still work without this
if ( file_exists( WP_CONTENT_DIR . '/library/functions.php' ) ) {
	require_once WP_CONTENT_DIR . '/library/functions.php';
}

// Include CPTs
require_once 'cpts/commercials.php';
require_once 'cpts/widgets.php';

// If Facet WP is active, call customizations
if ( class_exists( 'FacetWP' ) ) {
	require_once 'plugins/facetwp.php';
}
