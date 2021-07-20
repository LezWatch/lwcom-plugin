<?php

	/**
	 * Adds The LWCOM Commercial Widgets.
	 */

class LWCOM_Commercial extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'lwcom_commercial', // Base ID
			'Commercial Data', // Name
			array( 'description' => 'Displays information about the commercial.' ) // Args
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

		// Get what's needed from $instanse array ($instance populated with user inputs from widget form)
		$title = isset( $instance['title'] ) && ! empty( trim( $instance['title'] ) ) ? $instance['title'] : 'Details';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		/** Output widget HTML BEGIN **/
		echo wp_kses_post( $before_widget );

		$queried_object = get_queried_object();

		// If there's a post queery...
		if ( $queried_object && isset( $queried_object->ID ) ) {
			$post_id    = $queried_object->ID;
			$year_aired = get_post_meta( $post_id, 'lezcommercial_air_year', true );
			$video_url  = get_post_meta( $post_id, 'lezcommercial_video_url', true );
			$lezploit   = get_post_meta( $post_id, 'lezcommercial_lezploitation', true );
		}

		echo wp_kses_post( $before_title . $title . $after_title );

		echo '<ul>';

		if ( isset( $year_aired ) && ! empty( $year_aired ) ) {
			echo '<li><strong>First Aired:</strong>' . esc_html( $year_aired ) . '</li>';
		}

		if ( isset( $video_url ) && ! empty( $video_url ) ) {
			$source = wp_parse_url( $video_url );
			echo '<li><strong>Source:</strong><a href="' . esc_html( $video_url ) . '">' . esc_html( $source['host'] ) . '</a></li>';
		}

		if ( isset( $lezploit ) && ! empty( $lezploit ) ) {
			echo '<li><strong>Lezploitative</strong></li>';
		}

		// phpcs:ignore WordPress.Security.EscapeOutput
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

// Register LWCOM_Commercial widget
function register_lwcom_commercial() {
	register_widget( 'LWCOM_Commercial' );
}
add_action( 'widgets_init', 'register_lwcom_commercial' );
