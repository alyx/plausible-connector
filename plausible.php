<?php // phpcs:ignore -- ignore class naming
/**
 * Plugin Name
 *
 * @package   Plausible
 * @author    Alyx.
 * @copyright 2020 Alyx.
 * @license   GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Plausible
 * Plugin URI:        https://eq3.net/plausible-wp
 * Description:       Connect WordPress to a Plausible Analytics instance.
 * Version:           1.0.0
 * Requires at least: 4.1
 * Requires PHP:      5.6
 * Author:            Alyx.
 * Author URI:        https://eq3.net
 * Text Domain:       plausible
 * Domain Path:       /languages
 * License:           GPL v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt

 * Plausible is based off of Soapberry by Brooke (https://brooke.codes/projects/soapberry)
 * Plausible is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Plausible is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 * along with Plausible. If not, see https://www.gnu.org/licenses/gpl-3.0.en.html.
 */

namespace Plausible;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The main Plausible class which includes our other classes and sets things up.
 */
class Plausible {

	/**
	 * Constructor.
	 */
	public function __construct() {
		define( 'PLAUSIBLE_FILE', __FILE__ );
		define( 'PLAUSIBLE_DIR', trailingslashit( dirname( __FILE__ ) ) );
		define( 'PLAUSIBLE_VERSION', '1.0.0' );

		register_activation_hook( basename( PLAUSIBLE_DIR ) . '/' . basename( PLAUSIBLE_FILE ), array( $this, 'activate' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'plugins_loaded', array( $this, 'includes' ) );
		register_uninstall_hook( PLAUSIBLE_FILE, 'uninstall' );
	}
	/**
	 * Textdomain.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'plausible', false, dirname( plugin_basename( PLAUSIBLE_FILE ) ) . '/languages/' );
	}

	/**
	 * Includes.
	 */
	public function includes() {
		include_once PLAUSIBLE_DIR . 'includes/class-admin-settings.php';
		include_once PLAUSIBLE_DIR . 'includes/class-frontend.php';
	}

	/**
	 * Remove option at uninstall
	 */
	private static function uninstall() {
		if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
			die;
		}
		$option_name = 'plausible';
		delete_option( $option_name );
	}
}
new Plausible();
