<?php

/**
 * Adds The Recently Added Commercials widget.
 */

class LWCOM_Recent_Commercial_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'LWCOM_Recent_Commercial_Widget', // Base ID
			'LWCOM Recent Commercial', // Name
			array( 'description' => 'Displays the commercial most recently added to the database.' ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 */

	public function widget( $args, $instance ) {

		// start a Queery
		$char_args = array(
			'post_type'      => 'commercials',
			'posts_per_page' => '1',
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		// Get what's needed from $args array ($args populated with options from widget area register_sidebar function)
		$before_widget = isset( $args['before_widget'] ) ? $args['before_widget'] : '';
		$after_widget  = isset( $args['after_widget'] ) ? $args['after_widget'] : '';
		$before_title  = isset( $args['before_title'] ) ? $args['before_title'] : '';
		$after_title   = isset( $args['after_title'] ) ? $args['after_title'] : '';

		// Get what's needed from $instanse array ($instance populated with user inputs from widget form)
		$title = isset( $instance['title'] ) && ! empty( trim( $instance['title'] ) ) ? $instance['title'] : 'YIKES Example Widget';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		/** Output widget HTML BEGIN **/
		echo wp_kses_post( $before_widget );

		$queery = new WP_Query( $char_args );
		while ( $queery->have_posts() ) {
			$queery->the_post();
			$thumb_title       = get_the_title();

			echo '<div class="card">';
			echo '<div class="card-header"><h4>Recently Added Commecial</h4></div>';

			// Featured Image
			echo '<div class="commercial-image-wrapper">';
			echo '<a href="' . esc_url( get_the_permalink() ) . '">';
			echo the_post_thumbnail( 'commercial-img', array(
				'class' => 'card-img-top',
				'alt'   => $thumb_title,
				'title' => $thumb_title,
			) );
			echo '</a>';
			echo '</div>';

			echo '<div class="card-body">';
				// Title
				echo '<h4 class="card-title">' . get_the_title() . '</h4>';
				echo '<div class="card-text">';
					// Only show one show
					echo lwtv_yikes_chardata( get_the_ID(), 'oneshow' );
					// Actor
					echo lwtv_yikes_chardata( get_the_ID(), 'oneactor' );
				echo '</div>
			</div>';

			// Button
			echo '<div class="card-footer">
					<a href="' . esc_url( get_the_permalink() ) . '" class="btn btn-outline-primary">More Details</a>
				  </div>';

			echo '</div>';

			wp_reset_postdata();

			echo wp_kses_post( $after_widget );
			/** Output widget HTML END **/
		}

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

// Register LWCOM_Recent_Commercial_Widget widget
function register_lwcom_recent_commercial_widget() {
	register_widget( 'LWCOM_Recent_Commercial_Widget' );
}
add_action( 'widgets_init', 'register_lwcom_recent_commercial_widget' );
