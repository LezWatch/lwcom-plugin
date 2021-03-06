<?php
/*
 * Custom Post Type for commercials on LWCOM
 *
 * @since 1.0
 */

/**
 * class LWTV_CPT_Characters
 */
class LWComm_CPT_Commercials {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		add_action( 'init', array( $this, 'create_post_type' ), 0 );
		add_action( 'init', array( $this, 'create_taxonomies' ), 0 );

		add_action( 'cmb2_init', array( $this, 'cmb2_metaboxes' ) );

		add_filter( 'option_default_post_format', array( $this, 'default_post_format' ) );
		add_action( 'amp_init', array( $this, 'amp_init' ) );
		add_action( 'admin_menu', array( $this, 'remove_metaboxes' ) );
	}

	/**
	 * Admin Init
	 */
	public function admin_init() {
		add_action( 'admin_head', array( $this, 'admin_css' ) );
		add_action( 'dashboard_glance_items', array( $this, 'dashboard_glance_items' ) );
	}

	/*
	 * CPT Settings
	 *
	 */
	public function create_post_type() {
		$labels = array(
			'name'               => 'Commercials',
			'singular_name'      => 'Commercial',
			'menu_name'          => 'Commercials',
			'parent_item_colon'  => 'Parent Commercial:',
			'all_items'          => 'All Commercials',
			'view_item'          => 'View Commercial',
			'add_new_item'       => 'Add New Commercial',
			'add_new'            => 'Add New',
			'edit_item'          => 'Edit Commercial',
			'update_item'        => 'Update Commercial',
			'search_items'       => 'Search Commercials',
			'not_found'          => 'No commercials found',
			'not_found_in_trash' => 'No commercials in the Trash',
		);
		$args   = array(
			'label'               => 'commercials',
			'description'         => 'Commercials',
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', 'post-formats', 'genesis-cpt-archives-settings', 'genesis-seo' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_in_rest'        => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'rest_base'           => 'commercials',
			'rewrite'             => array( 'slug' => 'commercial' ),
			'menu_icon'           => 'dashicons-video-alt',
			'menu_position'       => 7,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);
		register_post_type( 'commercials', $args );
	}

	/*
	 * Custom Taxonomies
	 */
	public function create_taxonomies() {

		// FOCUS
		$names_focus = array(
			'name'                       => 'Focus',
			'singular_name'              => 'Focus',
			'search_items'               => 'Search Focuses',
			'popular_items'              => 'Popular Focuses',
			'all_items'                  => 'All Focuses',
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => 'Edit Focus',
			'update_item'                => 'Update Focus',
			'add_new_item'               => 'Add New Focus',
			'new_item_name'              => 'New Focus Name',
			'separate_items_with_commas' => 'Separate Focuses with commas',
			'add_or_remove_items'        => 'Add or remove Focuses',
			'choose_from_most_used'      => 'Choose from the most used Focuses',
			'not_found'                  => 'No Focuses found.',
			'menu_name'                  => 'Focuses',
		);
		//parameters for the new taxonomy
		$args_focus = array(
			'hierarchical'          => false,
			'labels'                => $names_focus,
			'show_ui'               => true,
			'show_in_rest'          => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'focus' ),
		);
		register_taxonomy( 'lez_focus', 'commercials', $args_focus );

		// COMPANY
		$names_company = array(
			'name'                       => 'Company',
			'singular_name'              => 'Company Identity',
			'search_items'               => 'Search Companies',
			'popular_items'              => 'Popular Companies',
			'all_items'                  => 'All Companies',
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => 'Edit Company',
			'update_item'                => 'Update Company',
			'add_new_item'               => 'Add New Company',
			'new_item_name'              => 'New Company Name',
			'separate_items_with_commas' => 'Separate companies with commas',
			'add_or_remove_items'        => 'Add or remove companies',
			'choose_from_most_used'      => 'Choose from the most used companies',
			'not_found'                  => 'No companies found.',
			'menu_name'                  => 'Company',
		);
		$args_company  = array(
			'hierarchical'       => false,
			'labels'             => $names_company,
			'public'             => true,
			'show_ui'            => true,
			'show_in_rest'       => true,
			'show_admin_column'  => true,
			'show_in_nav_menus'  => true,
			'show_in_quick_edit' => true,
			'show_tagcloud'      => false,
			'rewrite'            => array( 'slug' => 'company' ),
		);
		register_taxonomy( 'lez_company', 'commercials', $args_company );

		// COUNTRY OF ORIGIN
		$names_country = array(
			'name'                       => 'Country',
			'singular_name'              => 'Country of Origin',
			'search_items'               => 'Search Countries',
			'popular_items'              => 'Popular Countries',
			'all_items'                  => 'All Countries',
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => 'Edit country',
			'update_item'                => 'Update country',
			'add_new_item'               => 'Add New country',
			'new_item_name'              => 'New country Name',
			'separate_items_with_commas' => 'Separate countries with commas',
			'add_or_remove_items'        => 'Add or remove countries',
			'choose_from_most_used'      => 'Choose from the most used countries',
			'not_found'                  => 'No countries found.',
			'menu_name'                  => 'Country',
		);
		$args_country  = array(
			'hierarchical'       => false,
			'labels'             => $names_country,
			'public'             => true,
			'show_ui'            => true,
			'show_in_rest'       => true,
			'show_admin_column'  => true,
			'show_in_nav_menus'  => true,
			'show_in_quick_edit' => true,
			'show_tagcloud'      => false,
			'rewrite'            => array( 'slug' => 'country' ),
		);
		register_taxonomy( 'lez_country', 'commercials', $args_country );
	}

	/*
	 * Custom Meta Box section
	 *
	 * This relies fully on CMB2.
	 *
	 */
	public function cmb2_metaboxes() {

		// prefix for all custom fields
		$prefix = 'lezcommercial_';

		// This is just an array of all years from 1930 on (1930 being the year TV dramas started)
		// This could probably be smaller since I doubt we had lesbian ads before 1970
		$year_array = array();
		foreach ( range( date( 'Y' ), '1930' ) as $x ) {
			$year_array[ $x ] = $x;
		}

		// MetaBox Group: Commercial Details
		$cmb_commercials = new_cmb2_box(
			array(
				'id'           => 'chars_metabox',
				'title'        => 'Details',
				'object_types' => array( 'commercials' ), // Post type
				'context'      => 'normal',
				'priority'     => 'high',
				'show_in_rest' => true,
				'show_names'   => true, // Show field names on the left
			)
		);
		// Field: Year of Airing (if applicable)
		$cmb_commercials->add_field(
			array(
				'name'             => 'Year Aired',
				'desc'             => 'What year did the commercial first air.',
				'id'               => $prefix . 'air_year',
				'type'             => 'select',
				'show_option_none' => true,
				'default'          => 'custom',
				'options'          => $year_array,
			)
		);
		// Field: Video URL
		$cmb_commercials->add_field(
			array(
				'name'      => 'Video URL',
				'id'        => $prefix . 'video_url',
				'type'      => 'text_url',
				'protocols' => array( 'http', 'https' ),
			)
		);
		// Field: Lezploitation
		$cmb_commercials->add_field(
			array(
				'name' => 'Lezploitation?',
				'desc' => 'Is this one of those commercials made really for men?',
				'id'   => $prefix . 'lezploitation',
				'type' => 'checkbox',
			)
		);
	}

	/* Post Formats
	 *
	 * Set the default to videos.
	 */
	public function default_post_format( $format ) {
		global $post_type;
		if ( 'commercials' === $post_type ) {
			$format = 'video';
		}
		return $format;
	}

	/*
	 * Add AMP Support
	 */
	public function amp_init() {
		add_post_type_support( 'commercials', AMP_QUERY_VAR );
	}

	/*
	 * Remove Meta Boxes
	 */
	public function remove_metaboxes() {
		remove_meta_box( 'formatdiv', 'commercials', 'side' ); // Hide Post Formats
	}

	/*
	 * Style Admin CSS
	 */
	public function admin_css() {
		echo "<style type='text/css'>
			#adminmenu #menu-posts-commercials div.wp-menu-image:before, #dashboard_right_now li.commercials-count a:before {
					content: '\\f234';
					margin-left: -1px;
				}
			 </style>";
	}

	/*
	 * Add Commercials to dashboard glance
	 */
	public function dashboard_glance_items() {
		foreach ( array( 'commercials' ) as $post_type ) {
			$num_posts = wp_count_posts( $post_type );
			if ( $num_posts && $num_posts->publish ) {
				if ( 'commercials' === $post_type ) {
					// Translators: Number of commercials
					$text = _n( '%s Commercial', '%s Commercials', $num_posts->publish );
				}
				$text = sprintf( $text, number_format_i18n( $num_posts->publish ) );
				printf( '<li class="%1$s-count"><a href="edit.php?post_type=%1$s">%2$s</a></li>', lwcom_sanitized( $post_type ), lwcom_sanitized( $text ) );
			}
		}
	}

}

new LWComm_CPT_Commercials();
