<?php

/**
 * Frontend Output
 *
 * This file is used to store functions that control the Plausible output on the front end of a site.
 *
 * @package   Plausible Connector
 * @author    alyx
 * @copyright 2020 alyx
 * @license   GPL-3.0-or-later
 * @link      https://eq3.net/plausible-wp
 */

namespace Plausible;

class Plausible_Frontend {
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'plausible_maybe_show_script' ) );
	}

	/**
	 * Hooks into wp_enqueue_script to append script to the footer if settings approve
	 */
	public function plausible_maybe_show_script() {
		if ( !get_option( 'plausible_settings' ) ) {
			return;
		}

		if ( true == (bool) $this->plausible_get_options( 'exclude_logged_in' ) && is_user_logged_in() ) {
			return;
		}

		add_filter( 'script_loader_tag', array( $this, 'plausible_generate_script' ), 10, 3 );

		$tracking_url = trailingslashit( $this->plausible_get_options('instance_url') ) . $this->plausible_get_options( 'tracking_script' );

		wp_register_script( 'plausible', $tracking_url, '', PLAUSIBLE_VERSION, 'true' );
		wp_enqueue_script( 'plausible' );
	}

	/**
	 * Renders the script
	 */
	public function plausible_generate_script( $tag, $handle, $src ) {
		if ( 'plausible' == $handle ) {
			$tag = sprintf(
				'<script async src="%s/js/%s" data-domain="%s"></script>',
				$this->plausible_get_options( 'instance_url' ),
				$this->plausible_get_options( 'tracking_script' ),
				$this->plausible_get_options( 'domain_id' )
			);

			$tag .= '<script>window.plausible = window.plausible || function() { (window.plausible.q = window.plausible.q || []).push(arguments) }</script>';
		}

		return $tag;
	}

	private function plausible_get_options( $key ) {
		$settings = get_option( 'plausible_settings' );

		if ( !is_array( $settings ) || !array_key_exists( $key, $settings ) ) {
			return;
		}

		if ( $key === 'instance_url' ) {
			$value = esc_url_raw( $settings[$key] );
		} else {
			$value = sanitize_text_field( $settings[$key] );
		}

		return $value;
	}
}

new Plausible_Frontend();
