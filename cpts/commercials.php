<?php
/*
Plugin Name: Commercial CPT
Plugin URI:  https://lezwatchcommercials.com
Description: Custom Post Type for commercials on LWCOM
Version: 1.0
Author: Mika Epstein
*/


/*
 * CPT Settings
 *
 */

add_action( 'init', 'lez_commercials_post_type', 0 );
function lez_commercials_post_type() {
	$labels = array(
		'name'					=> 'Commercials',
		'singular_name'			=> 'Commercial',
		'menu_name'				=> 'Commercials',
		'parent_item_colon'		=> 'Parent Commercial:',
		'all_items'				=> 'All Commercials',
		'view_item'				=> 'View Commercial',
		'add_new_item'			=> 'Add New Commercial',
		'add_new'				=> 'Add New',
		'edit_item'				=> 'Edit Commercial',
		'update_item'			=> 'Update Commercial',
		'search_items'			=> 'Search Commercials',
		'not_found'				=> 'No commercials found',
		'not_found_in_trash'	=> 'No commercials in the Trash',
	);
	$args = array(
		'label'					=> 'commercials',
		'description'			=> 'Commercials',
		'labels'				=> $labels,
		'supports'				=> array( 'title', 'editor', 'thumbnail', 'genesis-cpt-archives-settings', 'genesis-seo', 'revisions' ),
		'hierarchical'			=> false,
		'public'				=> true,
		'show_ui'				=> true,
		'show_in_menu'			=> true,
		'show_in_nav_menus' 	=> true,
		'show_in_admin_bar' 	=> true,
	 	'rewrite' 				=> array( 'slug' => 'commercial' ),
		'menu_icon'				=> 'dashicons-video-alt',
		'menu_position'			=> 7,
		'can_export'			=> true,
		'has_archive'			=> true,
		'exclude_from_search'	=> false,
		'publicly_queryable'	=> true,
		'capability_type'		=> 'page',
	);
	register_post_type( 'commercials', $args );
}


/*
 * Custom Taxonomies
 *
 */
add_action( 'init', 'lez_create_commercials_taxonomies', 0 );
function lez_create_commercials_taxonomies() {

	// FOCUS
	$names_focus = array(
		'name'							=> 'Focus',
		'singular_name'					=> 'Focus',
		'search_items'					=> 'Search Focuses',
		'popular_items'					=> 'Popular Focuses',
		'all_items'						=> 'All Focuses',
		'parent_item'					=> null,
		'parent_item_colon'				=> null,
		'edit_item'						=> 'Edit Focus',
		'update_item'					=> 'Update Focus',
		'add_new_item'					=> 'Add New Focus',
		'new_item_name'					=> 'New Focus Name',
		'separate_items_with_commas'	=> 'Separate Focuses with commas',
		'add_or_remove_items'			=> 'Add or remove Focuses' ,
		'choose_from_most_used'			=> 'Choose from the most used Focuses' ,
		'not_found'						=> 'No Focuses found.' ,
		'menu_name'						=> 'Focuses' ,
	);
	//paramters for the new taxonomy
	$args_focus = array(
		'hierarchical'			=> false,
		'labels'				=> $names_focus,
		'show_ui'				=> true,
		'show_admin_column'	 	=> true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'			 	=> true,
		'rewrite'				=> array( 'slug' => 'focus' ),
	);
	register_taxonomy( 'lez_focus', 'commercials', $args_focus );

	// COMPANY
	$names_company = array(
		'name'							=> 'Company',
		'singular_name'					=> 'Company Identity',
		'search_items'					=> 'Search Companies',
		'popular_items'					=> 'Popular Companies',
		'all_items'						=> 'All Companies',
		'parent_item'					=> null,
		'parent_item_colon'				=> null,
		'edit_item'						=> 'Edit Company',
		'update_item'					=> 'Update Company',
		'add_new_item'					=> 'Add New Company',
		'new_item_name'					=> 'New Company Name',
		'separate_items_with_commas'	=> 'Separate companies with commas',
		'add_or_remove_items'			=> 'Add or remove companies',
		'choose_from_most_used'			=> 'Choose from the most used companies',
		'not_found'						=> 'No companies found.',
		'menu_name'						=> 'Company',
	);
	$args_company = array(
		'hierarchical'			=> false,
		'labels'				=> $names_company,
		'public'				=> true,
		'show_ui'				=> true,
		'show_admin_column'		=> true,
		'show_in_nav_menus'		=> true,
		'show_in_quick_edit'	=> false,
		'show_tagcloud'			=> false,
		'rewrite'				=> array( 'slug' => 'company' ),
	);
	register_taxonomy( 'lez_company', 'commercials', $args_company );
}

/*
 * Custom Meta Box section
 *
 * This relies fully on CMB2.
 *
 */
add_filter( 'cmb2_admin_init', 'lez_commercials_metaboxes' );
function lez_commercials_metaboxes() {

	// prefix for all custom fields
	$prefix = 'lezcommercial_';

	// This is just an array of all years from 1930 on (1930 being the year TV dramas started)
	$year_array = array();
	foreach ( range(date('Y'), '1930' ) as $x) {
		$year_array[$x] = $x;
	}

	// MetaBox Group: Commercial Details
	$cmb_commercials = new_cmb2_box( array(
		'id'				=> 'chars_metabox',
		'title'				=> 'Details',
		'object_types'  	=> array( 'commercials', ), // Post type
		'context'			=> 'side',
		'priority'			=> 'high',
		'show_names'		=> true, // Show field names on the left
	) );
	// Field: Subject Focuses
	$cmb_commercials->add_field( array(
		'name'				=> 'Focus',
		'desc'				=> 'Primary subject of the commercial',
		'id'				=> $prefix . 'focus',
		'taxonomy'			=> 'lez_focus', //Enter Taxonomy Slug
		'type'				=> 'taxonomy_select',
		'default' 			=> 'lesbian',
		'show_option_none'	=> false,
	) );
	// Field: Year of Death (if applicable)
	$cmb_commercials->add_field( array(
		'name'				=> 'Year Aired',
		'desc'				=> 'What year did the commercial first air.',
		'id'				=> $prefix .'air_year',
		'type'				=> 'select',
		'show_option_none'	=> true,
		'default'			=> 'custom',
		'options'			=> $year_array,
	) );
	$cmb_commercials->add_field( array(
	    'name' 				=> 'Lezploitation?',
	    'desc' 				=> 'Is this one of those commercials made really for men?',
	    'id'   				=> $prefix . 'lezploitation',
	    'type'				=> 'checkbox'
	) );
}

/*
 * Meta Box Adjustments
 *
 */

// Remove Metaboxes we use elsewhere
add_action( 'admin_menu', 'lez_remove_commercials_metaboxes');
function lez_remove_commercials_metaboxes() {
	remove_meta_box( 'tagsdiv-lez_focus', 'commercials', 'side' );
}


/*
 * AMP
 */

add_action( 'amp_init', 'lez_amp_add_commercials_cpt' );
function lez_amp_add_commercials_cpt() {
    add_post_type_support( 'commercials', AMP_QUERY_VAR );
}
