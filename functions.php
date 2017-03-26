<?php
/*
Plugin Name: LezWatch Commercials
Plugin URI:  https://lezwatchcommercials.com
Description: All the base functions for running LezWatch Commercials, which aren't dependant on the theme.
Version: 1.0
Author: Mika Epstein
*/

// Symbolicons
define( 'LWTV_SYMBOLICONS_URL', plugins_url( 'symbolicons/images', __FILE__ ) );
define( 'LWTV_SYMBOLICONS_PATH', plugin_dir_path( __FILE__ ).'/symbolicons/images' );
include_once( 'symbolicons/symbolicons.php' );

// Include CPTs
include( 'cpts/commercials.php' );

