<?php
/*
 * Custom Post Type for commercials on LWCOM
 *
 * @since 1.0
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
		'supports'				=> array( 'title', 'editor', 'thumbnail', 'revisions', 'post-formats' ),
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
		'show_in_quick_edit'	=> true,
		'show_tagcloud'			=> false,
		'rewrite'				=> array( 'slug' => 'company' ),
	);
	register_taxonomy( 'lez_company', 'commercials', $args_company );

	// COUNTRY OF ORIGIN
	$names_country = array(
		'name'							=> 'Country',
		'singular_name'					=> 'Country of Origin',
		'search_items'					=> 'Search Countries',
		'popular_items'					=> 'Popular Countries',
		'all_items'						=> 'All Countries',
		'parent_item'					=> null,
		'parent_item_colon'				=> null,
		'edit_item'						=> 'Edit country',
		'update_item'					=> 'Update country',
		'add_new_item'					=> 'Add New country',
		'new_item_name'					=> 'New country Name',
		'separate_items_with_commas'	=> 'Separate countries with commas',
		'add_or_remove_items'			=> 'Add or remove countries',
		'choose_from_most_used'			=> 'Choose from the most used countries',
		'not_found'						=> 'No countries found.',
		'menu_name'						=> 'Country',
	);
	$args_country = array(
		'hierarchical'			=> false,
		'labels'				=> $names_country,
		'public'				=> true,
		'show_ui'				=> true,
		'show_admin_column'		=> true,
		'show_in_nav_menus'		=> true,
		'show_in_quick_edit'	=> true,
		'show_tagcloud'			=> false,
		'rewrite'				=> array( 'slug' => 'country' ),
	);
	register_taxonomy( 'lez_country', 'commercials', $args_country );
}

/* Post Formats
 *
 * Set the default to videos.
 */
add_filter( 'option_default_post_format', 'lez_default_post_format' );
function lez_default_post_format( $format ) {
    global $post_type;

	if ( $post_type == 'commercials' ) $format = 'video';

    return $format;
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
		'context'			=> 'normal',
		'priority'			=> 'high',
		'show_names'		=> true, // Show field names on the left
	) );
	// Field: Year of Airing (if applicable)
	$cmb_commercials->add_field( array(
		'name'				=> 'Year Aired',
		'desc'				=> 'What year did the commercial first air.',
		'id'				=> $prefix .'air_year',
		'type'				=> 'select',
		'show_option_none'	=> true,
		'default'			=> 'custom',
		'options'			=> $year_array,
	) );
	// Field: Video URL
	$cmb_commercials->add_field( array(
	    'name'				=> 'Video URL',
	    'id'				=> $prefix .'video_url',
	    'type'				=> 'text_url',
	    'protocols'			=> array( 'http', 'https' ),
	) );
	// Field: Lezploitation
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

// function to initiate metaboxes to remove
add_action( 'init', 'lez_remove_meta_boxes_from_commercials');
function lez_remove_meta_boxes_from_commercials() {
	function the_meta_boxes_to_remove() {
		remove_meta_box( 'formatdiv', 'commercials', 'side' ); // Hide Post Formats
	}
	add_action( 'admin_menu' , 'the_meta_boxes_to_remove' );
}

/*
 * Add AMP Support
 */

add_action( 'amp_init', 'lez_amp_add_commercials_cpt' );
function lez_amp_add_commercials_cpt() {
    add_post_type_support( 'commercials', AMP_QUERY_VAR );
}