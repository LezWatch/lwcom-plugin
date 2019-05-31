<?php

/**
 * Commercial Sidebar Stuff
 */

class LWCOM_Commercial_Sidebar_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'LWCOM_Commercial_Sidebar_Widget', // Base ID
			'LWCOM Commercial Single', // Name
			array( 'description' => 'Shows the sidebar stuff for individual commercials.' ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 */

	public function widget( $args, $instance ) {


		// Get what's needed from $args array ($args populated with options from widget area register_sidebar function)
		$before_widget = isset( $args['before_widget'] ) ? $args['before_widget'] : '';
		$after_widget  = isset( $args['after_widget'] ) ? $args['after_widget'] : '';
		$before_title  = isset( $args['before_title'] ) ? $args['before_title'] : '';
		$after_title   = isset( $args['after_title'] ) ? $args['after_title'] : '';

		// Get what's needed from $instance array ($instance populated with user inputs from widget form)
		$title = isset( $instance['title'] ) && ! empty( trim( $instance['title'] ) ) ? $instance['title'] : '';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		/** Output widget HTML BEGIN **/
		echo wp_kses_post( $before_widget );

		$queried_object = get_queried_object();

		if ( $queried_object ) {
			$post_id = $queried_object->ID;
		}

		if ( 'on' === get_post_meta( $post_id, 'lezcommercial_lezploitation', true ) ) {
			$warning = '<svg viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" fill="#c0392b" r="12"/><g fill="#fff"><path d="m11 6c0-.552.448-1 1-1 .552 0 1 .447 1 1v7c0 .552-.448 1-1 1h-.001c-.552 0-1-.447-1-1v-7z"/><circle cx="12" cy="17.5" r="1.5"/></g></svg>';
			$callout = '<div class="callout callout-trigger"><div class="svg" aria-label="Warning Symbol" title="Warning Symbol">' . $warning . '</div><p><strong>WARNING!</strong></p></p>This commercial was created for the male gaze. It does contain queer females, but not in a good way.</p></div>';
			echo $callout;
		}

		echo '<h3>Details</h3>';

		$air_year = ' <strong>Year Aired:</strong> ' . get_post_meta( $post_id, 'lezcommercial_air_year', true );
		$company  = get_the_term_list( $post_id, 'lez_company', ' <strong>Company:</strong> ', ', ' );
		$focus    = get_the_term_list( $post_id, 'lez_focus', ' <strong>Subject(s):</strong> ', ', ' );
		$country  = get_the_term_list( $post_id, 'lez_country', ' <strong>Country:</strong> ', ', ' );
		$source   = ' <strong>Unknown Source</strong>';
		if ( get_post_meta( $post_id, 'lezcommercial_video_url', true ) ) {
			$source = ' <a href="' . esc_url( get_post_meta( $post_id, 'lezcommercial_video_url', true ) ) . '"><strong>Source</strong></a>';
		}

		$video_details = '<ul><li>' . $air_year . '</li><li>' . $source . '</li><li>' . $country . '</li><li>' . $company . '</li><li>' . $focus . '</li></ul>';

		echo wp_kses_post( $video_details );

		echo wp_kses_post( $after_widget );
		/** Output widget HTML END **/

	}

	/**
	 * Sanitize widget form values as they are saved.
	 */

	public function update( $new_instance, $old_instance ) {

		// Set old settings to new $instance array
		$instance = $old_instance;

		// Update each setting to new values entered by user
		$instance['title'] = wp_strip_all_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 */

	public function form( $instance ) {

		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title (optional)' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<?php
	}

}

// Register widget
function register_lwcom_commercial_sidebar_widget() {
	register_widget( 'LWCOM_Commercial_Sidebar_Widget' );
}
add_action( 'widgets_init', 'register_lwcom_commercial_sidebar_widget' );
