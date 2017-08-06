<?php
/**
 * Taxonomy Icons
 *
 * Create icons for catgeories and Custom Taxonomies.
 *
 * Version:    0.1.0
 * Author:     Mika A. Epstein
 * Author URI: https://halfelf.org
 * License:    GPL-2.0+
 *
 */

// if this file is called directly abort
if ( ! defined('WPINC' ) ) {
	die;
}

class LWComm_TaxonomyIcons {

	private $settings;
	const SETTINGS_KEY = 'taxicons_settings';

	/*
	 * Construct
	 *
	 * Actions to happen immediately
	 */
    public function __construct() {

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'init', array( $this, 'init' ) );

		// Normally this array is all the default values
		// Since we can't set it here, it's a placeholder
		$this->plugin_vars = array();

		// Create the list of symbolicons
		$this->symbolicon_array = array();

		$symbol_list = fopen( $upload_dir['basedir'] . '/symboliconscolor.txt', 'r' );

		if ( $symbol_list ) {
			while ( ( $line = fgets( $symbol_list ) ) !== false ) {
				$this->symbolicon_array[ $line . '.svg' ] = $line;
			}
		}

		// Permissions needed to use this plugin
		$this->plugin_permission = 'edit_posts';

		// Menus and their titles
		$this->plugin_menus = array(
			'taxicons'    => array(
				'slug'         => 'taxicons',
				'submenu'      => 'themes.php',
				'display_name' => __ ( 'Taxonomy Icons', 'taxonomy-icons' ),
			),
		);
    }

	/**
	 * admin_init function.
	 *
	 * @access public
	 * @return void
	 * @since 0.1.0
	 */
	function admin_init() {
		// Since we couldn't set it in _construct, we do it here
		// Create a default (false) for all current taxonomies
		$taxonomies = get_taxonomies( array( 'public' => true, '_builtin' => false ), 'names', 'and' );
		if ( $taxonomies && empty( $this->plugin_vars ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				$this->plugin_vars[$taxonomy] = false;
			}
		}
	}

	/**
	 * init function.
	 *
	 * @access public
	 * @return void
	 * @since 0.1.0
	 */
	function init() {
		add_shortcode( 'taxonomy-icon', array( $this, 'shortcode' ) );
	}

	/**
	 * Get Settings
	 *
	 * @access public
	 * @param bool $force (default: false)
	 * @return settings array
	 * @since 0.1.0
	 */
	function get_settings( $force = false) {
		if ( is_null( $this->settings ) || $force ) {
			$this->settings = get_option( static::SETTINGS_KEY, $this->plugin_vars );
		}
		return $this->settings;
	}

	/**
	 * Get individual setting
	 *
	 * @access public
	 * @param mixed $key
	 * @return key value (if available)
	 * @since 0.1.0
	 */
	function get_setting( $key ) {
		$this->get_settings();
		if ( isset( $this->settings[$key] ) ) {
			return $this->settings[$key];
		} else {
			return false;
		}
	}

	/**
	 * Set setting from array
	 *
	 * @access public
	 * @param mixed $key
	 * @param mixed $value
	 * @return void
	 * @since 0.1.0
	 */
	function set_setting( $key, $value ) {
		$this->settings[$key] = $value;
	}

	/**
	 * Save individual setting
	 *
	 * @access public
	 * @return void
	 * @since 0.1.0
	 */
	function save_settings() {
		update_option( static::SETTINGS_KEY, $this->settings );
	}

	/**
	 * admin_menu function.
	 *
	 * @access public
	 * @return void
	 * @since 0.1.0
	 */
	function admin_menu() {

		foreach ( $this->plugin_menus as $menu ) {
			$hook_suffixes[ $menu['slug'] ] = add_submenu_page(
				$menu['submenu'],
				$menu['display_name'],
				$menu['display_name'],
				$this->plugin_permission,
				$menu['slug'],
				array( $this, 'render_page' )
			);
		}

		foreach ( $hook_suffixes as $hook_suffix ) {
			add_action( 'load-' . $hook_suffix , array( $this, 'plugin_load' ) );
		}
	}

	/**
	 * Plugin Load
	 * Tells plugin to handle post requests
	 *
	 * @access public
	 * @return void
	 * @since 0.1.0
	 */
	function plugin_load() {
		//add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		$this->handle_post_request();
		//do_action( 'dho_dropzone_plugin_load', $this );
	}

	/**
	 * Handle Post Requests
	 *
	 * This saves our settings
	 *
	 * @access public
	 * @return void
	 * @since 0.1.0
	 */
	function handle_post_request() {
		if ( empty( $_POST['action'] ) || 'save' != $_POST['action'] || !current_user_can( 'edit_posts' ) ) return;

		if ( !wp_verify_nonce( $_POST['_wpnonce'], 'taxicons-save-settings' ) ) die( 'Cheating, eh?' );

		$this->get_settings();

		$post_vars = $this->plugin_vars;
		foreach ( $post_vars as $var => $default ) {
			if ( !isset( $_POST[$var] ) ) continue;
			$this->set_setting( $var, sanitize_text_field( $_POST[$var] ) );
		}

		$this->save_settings();
	}

	/**
	 * Render admin settings page
	 * If setup is not complete, display setup instead.
	 *
	 * @access public
	 * @return void
	 * @since 0.1.0
	 */
	function render_page() {
		// Not sure why we'd ever end up here, but just in case
		if ( empty( $_GET['page'] ) ) wp_die( 'Error, page cannot render.' );

		$screen = get_current_screen();
		$view  = $screen->id;

		$this->render_view( $view );
	}

	/**
	 * Render page view
	 *
	 * @access public
	 * @param mixed $view
	 * @param array $args ( default: array() )
	 * @return content based on the $view param
	 * @since 0.1.0
	 */
	function render_view( $view, $args = array() ) {
		extract( $args );
		include 'view-' . $view . '.php';
	}

	/**
	 * Render Taxicon
	 *
	 * This outputs the taxonomy icon associated with a specific taxonomy
	 *
	 * @access public
	 * @param mixed $taxonomy
	 * @return void
	 */
	public function render_taxicon( $taxonomy ) {

		// BAIL: If it's empty, or the taxonomy doesn't exist
		if ( !$taxonomy || taxonomy_exists( $taxonomy ) == false ) return;

		$filename = $this->get_setting( $taxonomy );

		// BAIL: If the setting is false or otherwise empty
		if ( $filename == false || !$filename || empty( $filename ) ) return;

		$svg      = wp_remote_get( LP_SYMBOLICONSCOLOR_PATH . $filename  . '.svg' );
		$icon     = $svg['body'];
		$taxicon  = '<span role="img" class="symlclr-icon ' . $filename . '">' . $icon . '</span>';

		return $taxicon;
	}

	/*
	 * Shortcode
	 *
	 * Generate the Taxicon via shortcode
	 *
	 * @param array $atts Attributes for the shortcode
	 *        - tax: The taxonomy
	 * @return SVG icon of awesomeness
	 */
	function shortcode( $atts ) {
		return $this->render_taxicon( $atts[ 'tax' ] );
	}

}

// This requires symbolicons
if ( defined( 'LP_SYMBOLICONSCOLOR_PATH' ) ) {
	new LWComm_TaxonomyIcons();
}