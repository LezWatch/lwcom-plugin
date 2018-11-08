<?php

/*
 * This file is part of Taxonomy Icons, a plugin for LezWatch Commercials.
 */

?>

<div class="wrap">

	<h1>Taxonomy Icons</h1>
	<div>

		<p>
			<?php
			printf( 'Taxonomy Icons allows you to assign a <a href="%s">Color Symbolicon</a> to a non-default taxonomy in order to make it look damn awesome.', esc_url_raw( admin_url( 'themes.php?page=symboliconscolor' ) ) );
			?>
		</p>

		<p>By default, Taxonomy Icons don't display in your theme. In order to use them, you can use a shortcode or a function:</p>

		<ul>
			<li>Shortcode: <code>[taxonomy-icon tax=TAXONOMY]</code></li>
			<li>Function: <code>do_shortcode( '[taxonomy-icon tax=TAXONOMY]' );</code></li>
		</ul>

		<form method="post">

		<?php
		if ( isset( $_GET['updated'] ) ) {
			?>
			<div class="notice notice-success is-dismissible"><p>Settings saved.</p></div>
			<?php
		}
		?>

		<input type="hidden" name="action" value="save" />
		<?php
			wp_nonce_field( 'taxicons-save-settings' );
		?>

		<table class="form-table">

			<tr>
				<th scope="row">Category</th>
				<th scope="row">Current Icon</th>
				<th scope="row">Select Icon</th>
			</tr>

			<?php

			foreach ( $this->plugin_vars as $this_tax => $value ) {
				?>
				<tr>
					<td>
						<strong><?php echo lwcom_sanitized( get_taxonomy( $this_tax )->label ); ?></strong>
						<br /><em><?php echo lwcom_sanitized( get_taxonomy( $this_tax )->name ); ?></em>
					</td>

					<td>
						<?php
						if ( $this->get_setting( $taxonomy ) && $this->get_setting( $taxonomy ) !== false ) {
							echo lwcom_sanitized( $this->render_taxicon( $taxonomy ) );
						}
						?>

					</td>

					<td>
						<select name="<?php echo lwcom_sanitized( $taxonomy ); ?>" class="taxonomy-icon">
							<option value="">-- Select an Icon --</option>
							<?php
							foreach ( $this->symbolicon_array as $file => $name ) {
								?>
								<option value="<?php echo esc_attr( $file ); ?>" <?php echo $file === $this->get_setting( $taxonomy ) ? 'selected="selected"' : ''; ?>><?php echo esc_html( $name ); ?></option>
								<?php
							};
							?>
						</select>
					</td>

				</tr>
				<?php
			}

			?>

			<tr valign="top">
				<td colspan="3">
					<button type="submit" class="button button-primary">Save</button>
				</td>
			</tr>
		</table>
		</form>
	</div>
</div>
