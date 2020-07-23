<?php // phpcs:ignore -- ignore class naming
/**
 * Admin Settings
 *
 * This file registers and outputs the admin page dispalyed WP Admin.
 *
 * @package   Plausible Connector
 * @author    alyx
 * @copyright 2020 alyx
 * @license   GPL-3.0-or-later
 * @link      https://eq3.net/plausible-wp
 */

namespace Plausible;

class Plausible_Admin_Settings {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'plausible_settings_page' ) );
		add_action( 'admin_init', array( $this, 'plausible_register_settings' ) );
		add_filter( 'plugin_action_links_' . PLAUSIBLE_BASE, array( $this, 'plausible_settings_link' ) );
	}

	public function plausible_settings_link( $links ) {
		$plausible_link = '<a href="options-general.php?page=plausible">Settings</a>';

		array_unshift( $links, $plausible_link );

		return $links;
	}

	public function plausible_settings_page() {
		add_options_page(
			'Plausible Settings',
			'Plausible',
			'manage_options',
			'plausible',
			array( $this, 'plausible_settings_display' )
		);
	}

	public function plausible_register_settings() {
		$args = array(
			'type' => 'array',
			'sanitize_callback' => array( $this, 'plausible_validate_settings' ),
			'default' => array(
				'instance_url' => 'https://plausible.io',
				'tracking_script' => 'plausible.js',
				'domain_id' => '',
			),
		);
		register_setting( 'plausible_settings_group', 'plausible_settings', $args );
	}

	public function plausible_validate_settings( $settings ) {
		if ( !isset( $_POST['plausible_settings_options_nonce'] ) || !wp_verify_nonce( wp_unslash( $_POST['plausible_settings_options_nonce'] ), 'plausible_settings_save_nonce' ) ) {
			return;
		}

		$plausible_settings = $settings;
		$plausible_settings['instance_url']    = esc_url_raw( $settings['instance_url'] );
		$plausible_settings['tracking_script'] = sanitize_text_field( $settings['tracking_script'] );
		$plausible_settings['domain_id']       = sanitize_text_field( $settings['domain_id'] );

		if ( isset( $settings['exclude_logged_in']) && 1 === $settings['exclude_logged_in'] ) {
			$plausible_settings['exclude_logged_in'] = 1;
		}

		return $plausible_settings;
	}

	public function plausible_settings_display() {
		$settings = get_option( 'plausible_settings' );
		$exclude_logged_in = ( isset( $settings['exclude_logged_in'] ) ) ? 1 : 0;
?>
		<div class="wrap">
			<h1>Plausible Connector Settings</h1>
			<form method="post" action="options.php">
				<table class="form-table" role="presentation">
					<?php settings_fields( 'plausible_settings_group' ); ?>
					<tbody>
						<tr>
							<th scope="row">
								<label for="plausible_instance_url">Plausible Instance URL</label>
							</th>
							<td>
								<input name="plausible_settings[instance_url]" type="url" id="plausible_instance_url" value="<?php echo ( esc_url( $settings['instance_url'] ) ); ?>" class="regular-text" placeholder="Plausible Instance URL" required>
								<p class="description" id="plausible-tracking-script-description">
									The base URL for your Plausible instance. This may not need changing unless you've self-hosted a Plausible instance or configured a custom domain.
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="plausible_tracking_script">Plausible Tracker</label>
							</th>
							<td>
								<input name="plausible_settings[tracking_script]" type="text" id="plausible_tracking_script" value="<?php echo ( esc_attr( $settings['tracking_script'] ) ); ?>" placeholder="tracking.js" class="regular-text ltr" required>
								<p class="description" id="plausible_domain_id-description">
									The name of your <a href='https://docs.plausible.io/javascript-snippet'>Plausible tracker</a>. The default value is <code>plausible.js</code>.
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="plausible_domain_id">Plausible Domain ID</label>
							</th>
							<td>
								<input name="plausible_settings[domain_id]" type="text" id="plausible_domain_id" value="<?php echo ( esc_attr( $settings['domain_id'] ) ); ?>" placeholder="Domain ID" class="regular-text" required>
								<p class="description" id="plausible_domain_id-description">
									The unique Domain ID for <?php echo ( esc_url_raw( home_url() ) ); ?>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row">Exclude Logged In</th>
							<td>
								<label for="plausible_exclude_logged_in">
									<input name="plausible_settings[exclude_logged_in]" type="checkbox" id="plausible_exclude_logged_in" value="1" <?php checked( $exclude_logged_in, 1 ); ?>>
									If checked, the tracking code won't be output for logged in visits.
								</label>
							</td>
						</tr>
					</tbody>
				</table>
				<?php echo ( wp_nonce_field( 'plausible_settings_save_nonce', 'plausible_settings_options_nonce' ) ); ?>
				<?php submit_button(); ?>
			</form>
		</div>
<?php
	}
}

new Plausible_Admin_Settings();
