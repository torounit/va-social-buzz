<?php
/**
 * WordPress plugin installer class.
 *
 * @package    WordPress
 * @subpackage VA Social Buzz
 * @author     KUCKLU <kuck1u@visualive.jp>
 *             Copyright (C) 2015 KUCKLU and VisuAlive.
 *             This program is free software; you can redistribute it and/or modify
 *             it under the terms of the GNU General Public License as published by
 *             the Free Software Foundation; either version 2 of the License, or
 *             (at your option) any later version.
 *             This program is distributed in the hope that it will be useful,
 *             but WITHOUT ANY WARRANTY; without even the implied warranty of
 *             MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU General Public License for more details.
 *             You should have received a copy of the GNU General Public License along
 *             with this program; if not, write to the Free Software Foundation, Inc.,
 *             51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *             It is also available through the world-wide-web at this URL:
 *             http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace VASOCIALBUZZ\Modules;

use VASOCIALBUZZ\VASOCIALBUZZ_Singleton;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Installer.
 *
 * @since 0.0.1 (Alpha)
 */
class VASOCIALBUZZ_Installer extends VASOCIALBUZZ_Singleton {
	/**
	 * This hook is called once any activated plugins have been loaded.
	 */
	public function __construct() {
	}

	/**
	 * Uninstall.
	 *
	 * @since 0.0.1 (Alpha)
	 */
	public static function uninstall() {
		delete_option( 'va_social_buzz' );

		if ( ! is_null( get_option( 'push7_appno', null ) ) ) {
			delete_transient( 'vasocialbuzz_push7_register_url' );
		}
	}
}
