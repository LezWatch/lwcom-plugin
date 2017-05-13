<?php

/*
    This file is part of Taxonomy Icons, a plugin for LezWatch Commercials.
*/

?>

<div class="wrap">

	<h1><?php _e( 'Taxonomy Icons', 'taxonomy-icons' ); ?></h1>

	<div>

		<p><?php printf( __( 'Taxonomy Icons allows you to assign a <a href="%s">Color Symbolicon</a> to a non-default taxonomy in order to make it look damn awesome.', 'taxonomy-icons'), admin_url( 'themes.php?page=symboliconscolor' ) ); ?></p>

		<p><?php __( 'By default, Taxonomy Icons don\'t display in your theme. In order to use them, you can use a shortcode or a function:' , 'taxonomy-icons' ); ?></p>

		<ul>
			<li>Shortcode: <code>[taxonomy-icon tax=TAXONOMY]</code></li>
			<li>Function: <code>LWComm_TaxonomyIcons::render_taxicon( 'TAXONOMY' );</code></li>
		</ul>

		<form method="post">

		<?php
		if ( isset( $_GET['updated'] ) ) {
			?>
			<div class="notice notice-success is-dismissible"><p><?php _e( 'Settings saved.', 'taxonomy-icons' ); ?></p></div>
			<?php
		}
		?>

		<input type="hidden" name="action" value="save" />
		<?php wp_nonce_field( 'taxicons-save-settings' ) ?>

		<table class="form-table">

			<tr>
				<th scope="row"><?php _e( 'Category', 'taxonomy-icons' ); ?></th>
				<th scope="row"><?php _e( 'Current Icon', 'taxonomy-icons' ); ?></th>
				<th scope="row"><?php _e( 'Select Icon', 'taxonomy-icons' ); ?></th>
			</tr>

			<?php

			foreach ( $this->plugin_vars as $taxonomy => $value ) {
				?>
				<tr>
					<td>
						<strong><?php echo get_taxonomy( $taxonomy )->label; ?></strong>
						<br /><em><?php echo get_taxonomy( $taxonomy )->name; ?></em>
					</td>

					<td>
						<?php
						if ( $this->get_setting( $taxonomy ) && $this->get_setting( $taxonomy ) !== false ) {
							echo $this->render_taxicon( $taxonomy );
						}
						?>

					</td>

					<td>
						<select name="<?php echo $taxonomy; ?>" class="taxonomy-icon">
							<option value="">-- <?php _e( 'Select an Icon', 'taxonomy-icons' ); ?> --</option>
							<?php
							foreach ( $this->symbolicon_array as $file => $name ) {
								?><option value="<?php echo esc_attr( $file ); ?>" <?php echo $file == $this->get_setting( $taxonomy ) ? 'selected="selected"' : ''; ?>><?php echo esc_html( $name ); ?></option><?php
								};
							?>
						</select>
					</td>

				</tr><?php
			}

			?>

			<tr valign="top">
				<td colspan="3">
					<button type="submit" class="button button-primary"><?php _e( 'Save', 'taxonomy-icons' ); ?></button>
				</td>
			</tr>
		</table>
		</form>
	</div>
</div>