<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Plausible Connector
 * Plugin URI:        https://eq3.net/plausible-wp
 * Description:       Connect WordPress to a Plausible Analytics instance.
 * Version:           1.2.0
 * Requires at least: 4.1
 * Requires PHP:      5.6
 * Author:            alyx
 * Author URI:        https://eq3.net
 * License:           GPL v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt

 * Plausible Connector is based off of Soapberry by Brooke (https://brooke.codes/projects/soapberry)
 * Plausible Connector is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Plausible Connector is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 * along with Plausible Connector. If not, see https://www.gnu.org/licenses/gpl-3.0.en.html.
 */

namespace Plausible;

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The main Plausible class which includes all other functionality.
 */
class Plausible {
	public function __construct() {
		define( 'PLAUSIBLE_VERSION', '1.1.0' );
		define( 'PLAUSIBLE_BASE', plugin_basename( __FILE__ ) );
		add_action( 'plugins_loaded', array( $this, 'includes' ) );
		register_uninstall_hook( __FILE__, 'uninstall' );
	}

	/**
	 * Includes
	 */
	public function includes() {
		include_once plugin_dir_path( __FILE__ ) . "/includes/class-admin-settings.php";
		include_once plugin_dir_path( __FILE__ ) . "/includes/class-frontend.php";
	}

	public static function uninstall() {
		if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
			die;
		}

		delete_option( 'plausible' );
	}
}

new Plausible();
