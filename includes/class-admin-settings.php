<?php // phpcs:ignore -- ignore class naming
/**
 * Admin Settings
 *
 * This file registers and outputs the admin page dispalyed WP Admin.
 *
 * @package   Plausible
 * @author    Alyx.
 * @copyright 2020 Alyx.
 * @license   GPL-3.0-or-later
 * @link      https://eq3.net/plausible-wp
 */

namespace Plausible;

if (!class_exists('Plausible_Admin_Settings')) :
    class Plausible_Admin_Settings {
        public function __construct() {
			add_action( 'admin_menu', array( $this, 'plausible_settings_page' ) );
			add_action( 'admin_init', array( $this, 'plausible_register_settings' ) );
        }
        
        public function plausible_settings_page() {
			add_options_page(
				__( 'Plausible Settings', 'plausible' ),
				__( 'Plausible', 'plausible' ),
				'manage_options',
				'plausible',
				array( $this, 'plausible_settings_display' )
			);
        }
        
        public function plausible_register_settings() {
			$args = array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'plausible_validate_settings' ),
				'default'           => array(
					'instance_url'    => 'https://plausible.io',
					'tracking_script' => 'plausible.js',
					'domain_id'       => '',
				),
			);
			register_setting( 'plausible_settings_group', 'plausible_settings', $args );
        }
        
        public function plausible_validate_settings( $settings ) {
			if ( ! isset( $_POST['plausible_settings_options_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['plausible_settings_options_nonce'] ), 'plausible_settings_save_nonce' ) ) { // phpcs:ignore
				return;
			}

			$plausible_settings                    = $settings;
			$plausible_settings['instance_url']    = esc_url_raw( $settings['instance_url'] );
			$plausible_settings['tracking_script'] = sanitize_text_field( $settings['tracking_script'] );
			$plausible_settings['domain_id']       = sanitize_text_field( $settings['domain_id'] );

			if ( isset( $settings['exclude_logged_in'] ) && 1 === $settings['exclude_logged_in'] ) {
				$plausible_settings['exclude_logged_in'] = 1;
			}

			return $plausible_settings;
        }
        
        public function plausible_settings_display() {
			$plausible_settings = get_option( 'plausible_settings' );
			$exclude_logged_in  = ( isset( $plausible_settings['exclude_logged_in'] ) ) ? 1 : 0;
			?>
			<div class="wrap">
			<h1><?php echo esc_html__( 'Plausible Settings', 'plausible' ); ?></h1>
			<table class="form-table" role="presentation">
				<form method="post" action="options.php">
				<?php settings_fields( 'plausible_settings_group' ); ?>
					<tbody>
						<tr>
							<th scope="row"><label for="plausible_instance_url"><?php esc_html_e( 'Plausible Install URL', 'plausible' ); ?></label></th>
							<td>
								<input name="plausible_settings[instance_url]" type="url" id="plausible_instance_url" value="<?php echo ( esc_url( $plausible_settings['instance_url'] ) ); ?>" class="regular-text" placeholder="Plausible Install URL" required>
								<p class="description" id="plausible-tracking-script-description">
									<?php esc_html_e( 'The base URL for your Plausible install.', 'plausible' ); ?>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="plausible_ackee_tracking_script"><?php esc_html_e( 'Plausible Tracker', 'plausible' ); ?> </label></th>
							<td>
								<input name="plausible_settings[tracking_script]" type="text" id="plausible_ackee_tracking_script" value="<?php echo ( esc_attr( $plausible_settings['tracking_script'] ) ); ?>" placeholder="tracking.js" class="regular-text ltr" required>
								<p class="description" id="plausible_domain_id-description">
									<?php
									printf(
										/* translators: This adds a link to the Ackee GitHub repo instruction on Tracking URL and adds code tags  */
										esc_html__( '%1$s %2$s. %3$s.', 'plausible' ),
										esc_html__( 'The name of your', 'plausible' ),
										/* Link and anchor text*/
										sprintf(
											'<a href="%s">%s</a>',
											esc_url( 'https://docs.plausible.io/javascript-snippet' ),
											esc_html__( 'Plausible tracker', 'plausible' )
										),
										/* Wrapping script name in code tags*/
										sprintf(
											'%s <code>%s</code>',
											esc_html__( 'The default value is', 'plausible' ),
											esc_html__( 'plausible.js', 'plausible' )
										)
									);
									?>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="plausible_ackee_domain_id"><?php esc_html_e( 'Plausible Domain ID', 'plausible' ); ?></label></th>
							<td>
								<input name="plausible_settings[domain_id]" type="text" id="plausible_domain_id" value="<?php echo ( esc_attr( $plausible_settings['domain_id'] ) ); ?>" placeholder="Domain ID" class="regular-text" required>
								<p class="description" id="plausible_domain_id-description">
									<?php
									printf(
										/* translators: Requests unique Domain ID with current site URL  */
										esc_html__( 'The unique Domain ID for %s.', 'plausible' ),
										esc_url_raw( home_url() )
									);
									?>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html__( 'Exclude Logged In', 'plausible' ); ?></th>
							<td>
								<label for="plausible_exclude_logged_in">
									<input name="plausible_settings[exclude_logged_in]" type="checkbox" id="plausible_exclude_logged_in" value="1" <?php checked( $exclude_logged_in, 1 ); ?> > 
									<?php esc_html_e( "If checked, the tracking code won't be output for logged in visits.", 'plausible' ); ?>
								</label>
							</td>
						</tr>
					</tbody>
				</table>
				<?php echo ( wp_nonce_field( 'plausible_settings_save_nonce', 'plausible_settings_options_nonce' ) ); // phpcs:ignore ?>
				<?php submit_button(); ?>
			</form>
            <?php
		} /* end of admin page settings */
	} /* end of class */
	new Plausible_Admin_Settings();
endif;
