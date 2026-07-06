<?php
/**
 * Admin settings screen with per-module toggles (Settings API).
 *
 * @package AOSARS_Commerce_Skin
 */

namespace ACS;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Self-launching admin menu and options.
 */
class Settings {

	/**
	 * Modules with labels.
	 *
	 * @return array
	 */
	private function modules() {
		return array(
			'm1' => __( 'Shop card redesign (M1)', 'aosars-commerce-skin' ),
			'm2' => __( 'Single product skin (M2)', 'aosars-commerce-skin' ),
			'm3' => __( 'Related products carousel (M3)', 'aosars-commerce-skin' ),
			'm4' => __( 'Cart skin (M4)', 'aosars-commerce-skin' ),
			'm5' => __( 'Checkout skin (M5)', 'aosars-commerce-skin' ),
			'm7' => __( 'Trust bar & widgets (M7)', 'aosars-commerce-skin' ),
			'm9' => __( 'Mini-cart drawer (M9)', 'aosars-commerce-skin' ),
		);
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'admin_menu', acs_guard( array( $this, 'menu' ) ) );
		add_action( 'admin_init', acs_guard( array( $this, 'register' ) ) );
	}

	/**
	 * Add the top-level menu.
	 *
	 * @return void
	 */
	public function menu() {
		add_menu_page(
			__( 'AOSARS Commerce Skin', 'aosars-commerce-skin' ),
			__( 'AOSARS Skin', 'aosars-commerce-skin' ),
			'manage_options',
			ACS_SLUG,
			array( $this, 'page' ),
			'dashicons-cart',
			58
		);
	}

	/**
	 * Register the setting and sanitizer.
	 *
	 * @return void
	 */
	public function register() {
		register_setting(
			ACS_SLUG . '_group',
			ACS_OPT,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize' ),
				'default'           => array(),
			)
		);
	}

	/**
	 * Sanitize incoming options.
	 *
	 * @param mixed $input Raw input.
	 * @return array
	 */
	public function sanitize( $input ) {
		$out = array();
		$input = is_array( $input ) ? $input : array();
		foreach ( array_keys( $this->modules() ) as $key ) {
			$out[ 'mod_' . $key ] = ( isset( $input[ 'mod_' . $key ] ) && '1' === (string) $input[ 'mod_' . $key ] ) ? '1' : '0';
		}
		return $out;
	}

	/**
	 * Render the settings page.
	 *
	 * @return void
	 */
	public function page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$options = get_option( ACS_OPT, array() );
		$options = is_array( $options ) ? $options : array();
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'AOSARS Commerce Skin', 'aosars-commerce-skin' ); ?></h1>
			<p><?php echo esc_html__( 'Toggle each redesign module. All are on by default. Turning everything off restores the default WooCommerce look with no residue.', 'aosars-commerce-skin' ); ?></p>
			<form method="post" action="options.php">
				<?php settings_fields( ACS_SLUG . '_group' ); ?>
				<table class="form-table" role="presentation">
					<tbody>
					<?php
					foreach ( $this->modules() as $key => $label ) {
						$name    = ACS_OPT . '[mod_' . esc_attr( $key ) . ']';
						$checked = ( ! array_key_exists( 'mod_' . $key, $options ) || '1' === (string) $options[ 'mod_' . $key ] );
						?>
						<tr>
							<th scope="row"><?php echo esc_html( $label ); ?></th>
							<td>
								<label>
									<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="0" />
									<input type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="1" <?php checked( $checked ); ?> />
									<?php echo esc_html__( 'Enabled', 'aosars-commerce-skin' ); ?>
								</label>
							</td>
						</tr>
						<?php
					}
					?>
					</tbody>
				</table>
				<?php submit_button(); ?>
			</form>
			<p style="color:#666;max-width:60ch">
				<?php echo esc_html__( 'This plugin never edits WooCommerce, Tutor LMS, the theme or the currency plugin. It skins the store through the supported template-override filter and hooks, and every callback is wrapped in a fatal firewall so a fault can never take the site down.', 'aosars-commerce-skin' ); ?>
			</p>
		</div>
		<?php
	}
}
