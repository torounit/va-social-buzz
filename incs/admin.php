<?php
/**
 * WordPress plugin admin class.
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
 * Class ADMIN.
 *
 * @since 0.0.1 (Alpha)
 */
class VASOCIALBUZZ_Admin extends VASOCIALBUZZ_Singleton {
	/**
	 * This hook is called once any activated themes have been loaded.
	 *
	 * @since 0.0.1 (Alpha)
	 *
	 * @param array $settings If the set value is required, pass a value in an array.
	 */
	public function __construct( $settings = array() ) {
		if ( is_admin() ) {
			add_action( 'admin_init', array( &$this, 'admin_init' ) );
			add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );
		}
	}

	/**
	 * Add settings.
	 */
	public function admin_init() {
		register_setting( 'reading', 'va_social_buzz', array( &$this, '_sanitize_option' ) );

		add_settings_section( 'vasocialbuzz_section', __( 'VA Social Buzz', 'va-social-buzz' ), null, 'reading' );

		do_action( 'vasocialbuzz_admin_form_field_0' );

		add_settings_field(
			'vasocialbuzz_fb_page',
			'<label for="vasocialbuzz_fb_page">' . __( 'Facebook Page Web Address', 'va-social-buzz' ) . '</label>',
			array( &$this, 'render_fb_page' ),
			'reading',
			'vasocialbuzz_section'
		);

		do_action( 'vasocialbuzz_admin_form_field_1' );

		add_settings_field(
			'vasocialbuzz_fb_appid',
			'<label for="vasocialbuzz_fb_appid">' . esc_html__( 'Facebook App ID', 'va-social-buzz' ) . '</label>',
			array( &$this, 'render_fb_appid' ),
			'reading',
			'vasocialbuzz_section'
		);

		do_action( 'vasocialbuzz_admin_form_field_2' );

		add_settings_field(
			'vasocialbuzz_tw_account',
			'<label for="vasocialbuzz_tw_account">' . esc_html__( 'Twitter Account', 'va-social-buzz' ) . '</label>',
			array( &$this, 'render_tw_account' ),
			'reading',
			'vasocialbuzz_section'
		);

		do_action( 'vasocialbuzz_admin_form_field_3' );

		add_settings_field(
			'vasocialbuzz_text',
			__( 'Text', 'va-social-buzz' ),
			array( &$this, 'render_text' ),
			'reading',
			'vasocialbuzz_section'
		);

		do_action( 'vasocialbuzz_admin_form_field_4' );

		add_settings_field(
			'vasocialbuzz_like_button_area',
			__( 'Like Button Aria', 'va-social-buzz' ),
			array( &$this, 'render_like_button_area' ),
			'reading',
			'vasocialbuzz_section'
		);

		do_action( 'vasocialbuzz_admin_form_field_5' );

		add_settings_field(
			'vasocialbuzz_post_types',
			__( 'Show In', 'va-social-buzz' ),
			array( &$this, 'render_post_types' ),
			'reading',
			'vasocialbuzz_section'
		);

		do_action( 'vasocialbuzz_admin_form_field_6' );
	}

	/**
	 * Facebook page url.
	 *
	 * @since 0.0.1 (Alpha)
	 */
	public function render_fb_page() {
		$options  = self::get_option();
		$output[] = '<label for="vasocialbuzz_fb_page">https://facebook.com/</label>';
		$output[] = sprintf(
			'<input id="vasocialbuzz_fb_page" class="regular-text code" type="text" name="va_social_buzz[fb_page]" value="%s">',
			esc_attr( $options['fb_page'] )
		);
		$output[] = '<p class="description">' . esc_html__( 'Facebook Page Web Address can only contain A-Z, a-z, 0-9, and periods (.)', 'va-social-buzz' ) . '</p>';

		echo implode( PHP_EOL, $output );
	}

	/**
	 * Facebook app id.
	 *
	 * @since 0.0.1 (Alpha)
	 */
	public function render_fb_appid() {
		$options  = self::get_option();
		$output[] = sprintf(
			'<input id="vasocialbuzz_fb_appid" class="regular-text" type="text" name="va_social_buzz[fb_appid]" value="%s">',
			esc_attr( $options['fb_appid'] )
		);
		$output[] = '<p class="description">' . esc_html__( 'Facebook App ID can only contain 0-9.', 'va-social-buzz' ) . '</p>';

		echo implode( PHP_EOL, $output );
	}

	/**
	 * Twitter account.
	 *
	 * @since 0.0.1 (Alpha)
	 */
	public function render_tw_account() {
		$options  = self::get_option();
		$output[] = sprintf(
			'<input id="vasocialbuzz_tw_account" class="regular-text code" type="text" name="va_social_buzz[tw_account]" value="%s">',
			esc_attr( $options['tw_account'] )
		);
		$output[] = '<p class="description">' . esc_html__( 'Twitter Account can only contain A-Z, a-z, 0-9, and underscore (_)', 'va-social-buzz' ) . '</p>';

		echo implode( PHP_EOL, $output );
	}

	/**
	 * Chenge text.
	 *
	 * @since 0.0.1 (Alpha)
	 */
	public function render_text() {
		$options = self::get_option();

		$output[] = '<p><label for="vasocialbuzz_text_like_0">';
		$output[] = sprintf(
			'<input id="vasocialbuzz_text_like_0" class="regular-text" type="text" name="va_social_buzz[text][like][0]" value="%s">',
			esc_attr( $options['text']['like'][0] )
		);
		$output[] = '</label></p>';
		$output[] = '<p><label for="vasocialbuzz_text_like_1">';
		$output[] = sprintf(
			'<input id="vasocialbuzz_text_like_1" class="regular-text" type="text" name="va_social_buzz[text][like][1]" value="%s">',
			esc_attr( $options['text']['like'][1] )
		);
		$output[] = '</label></p>';
		$output[] = '<p class="description">' . esc_html__( 'Appear on top of the like button.', 'va-social-buzz' ) . '</p>';

		$output[] = '<p><label for="vasocialbuzz_text_share">';
		$output[] = sprintf(
			'<input id="vasocialbuzz_text_share" class="regular-text" type="text" name="va_social_buzz[text][share]" value="%s">',
			esc_attr( $options['text']['share'] )
		);
		$output[] = '</label></p>';
		$output[] = '<p class="description">' . esc_html__( 'Share button to Facebook.', 'va-social-buzz' ) . '</p>';

		$output[] = '<p><label for="vasocialbuzz_text_tweet">';
		$output[] = sprintf(
			'<input id="vasocialbuzz_text_tweet" class="regular-text" type="text" name="va_social_buzz[text][tweet]" value="%s">',
			esc_attr( $options['text']['tweet'] )
		);
		$output[] = '</label></p>';
		$output[] = '<p class="description">' . esc_html__( 'Tweet button to Twitter.', 'va-social-buzz' ) . '</p>';

		$output = apply_filters( 'vasocialbuzz_admin_form_button_text', $output );

		if ( ! is_null( get_option( 'push7_appno', null ) ) ) {
			$output[] = '<p><label for="vasocialbuzz_text_push7">';
			$output[] = sprintf(
				'<input id="vasocialbuzz_text_push7" class="regular-text" type="text" name="va_social_buzz[text][push7]" value="%s">',
				esc_attr( $options['text']['push7'] )
			);
			$output[] = '</label></p>';
			$output[] = '<p class="description">' . esc_html__( 'Follow Push7 button of the text.', 'va-social-buzz' ) . '</p>';
		}

		$output[] = '<p><label for="vasocialbuzz_text_follow">';
		$output[] = sprintf(
			'<input id="vasocialbuzz_text_follow" class="regular-text" type="text" name="va_social_buzz[text][follow]" value="%s">',
			esc_attr( $options['text']['follow'] )
		);
		$output[] = '</label></p>';
		$output[] = '<p class="description">' . esc_html__( 'Follow button left of the text.', 'va-social-buzz' ) . '</p>';

		echo implode( PHP_EOL, $output );
	}

	/**
	 * Backgrund of the like button box.
	 */
	public function render_like_button_area() {
		$dummy_option                = self::_dummy_option();
		$options                     = self::get_option();
		$options['like_button_area'] = array_merge( $dummy_option['like_button_area'], $options['like_button_area'] );
		$selected                    = '0' === $options['like_button_area']['bg_opacity'] ? ' selected' : '';

		$output[] = sprintf(
			'<p><label>' . esc_html__( 'Background color:', 'va-social-buzz' ) . ' <input class="vasb-color-picker" type="text" name="va_social_buzz[like_button_area][bg]" value="%s"></label></p>',
			$options['like_button_area']['bg']
		);

		if ( function_exists( 'bcadd' ) ) {
			$output[] = '<p><label>' . esc_html__( 'Background color opacity:', 'va-social-buzz' ) . ' <select name="va_social_buzz[like_button_area][bg_opacity]">';
			$fields[] = '<option value="0"' . $selected . '>0</option>';
			for ( $i = 0; $i < 1; ) {
				$i = (string) bcadd( $i, 0.05, 2 );
				$i = preg_replace( '/\.?0+$/', '', $i );

				if ( '1' === (string) floor( $i ) ) {
					$i = (string) floor( $i );
				}

				$selected = $i === $options['like_button_area']['bg_opacity'] ? ' selected' : '';
				$fields[] = '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
			}
			$output   = array_merge( $output, array_reverse( $fields ) );
			$output[] = '</select></label></p>';
			unset( $fields );
		}

		$output[] = sprintf(
			'<p><label>' . __( 'Font color:', 'va-social-buzz' ) . ' <input class="vasb-color-picker" type="text" name="va_social_buzz[like_button_area][color]" value="%s"></label></p>',
			$options['like_button_area']['color']
		);

		echo implode( PHP_EOL, $output );
	}

	/**
	 * Show in post types.
	 *
	 * @since 1.0.3
	 */
	public function render_post_types() {
		$options      = self::get_option();
		$dummy_option = self::_dummy_option();

		$post_types = array_values( get_post_types( array(
			'public' => true,
		) ) );
		$output[]   = '<ul>';

		if ( empty( $options['post_type'] ) ) {
			$options['post_type'] = apply_filters( 'vasocialbuzz_showin_post_type', $dummy_option['post_type'] );
		}
		foreach ( $post_types as $post_type ) {
			$checked          = in_array( $post_type, $options['post_type'] ) ? ' checked' : '';
			$post_type_object = get_post_type_object( $post_type );
			$output[]         = sprintf(
				'<li><label><input class="vasocialbuzz_post_types" type="checkbox" name="va_social_buzz[post_type][]" value="%s"%s> %s</label></li>',
				$post_type,
				$checked,
				$post_type_object->labels->name
			);
		}

		$output[] = '</ul>';
		$output[] = '<p class="description">' . esc_html__( 'Choose the post type to display.', 'va-social-buzz' ) . '</p>';
		$output[] = '<p class="description">' . esc_html__( 'Please select the one or more.', 'va-social-buzz' ) . '</p>';

		echo implode( PHP_EOL, apply_filters( 'vasocialbuzz_admin_render_post_types', $output ) );
	}

	/**
	 * Admin enqueue scripts.
	 *
	 * @param string $hook
	 */
	public function admin_enqueue_scripts( $hook ) {
		if ( 'options-reading.php' === $hook ) {
			wp_enqueue_style( 'va-social-buzz-admin', VASOCIALBUZZ_URL . 'assets/css/admin.css' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'va-social-buzz-admin', VASOCIALBUZZ_URL . 'assets/js/admin.js', array(
				'jquery',
				'wp-color-picker',
			), false, true );
		}
	}

	/**
	 * Sanitize.
	 *
	 * @since 0.0.1 (Alpha)
	 *
	 * @param $options_raw array Settings.
	 *
	 * @return array
	 */
	public function _sanitize_option( $options_raw ) {
		$dummy_option = self::_dummy_option();

		foreach ( $options_raw['text'] as $key => $value ) {
			if ( 'like' === $key ) {
				foreach ( $value as $key2 => $value2 ) {
					$options_raw['text']['like'][ $key2 ] = ! empty( $value2 ) ? $value2 : $dummy_option['text']['like'][ $key2 ];
				}
			} else {
				$options_raw['text'][ $key ] = ! empty( $value ) ? $value : $dummy_option['text'][ $key ];
			}
		}
		$options                                   = wp_parse_args( $options_raw, $dummy_option );
		$options['fb_page']                        = preg_replace( '/[^a-zA-Z0-9\-.]/', '', $options['fb_page'] );
		$options['fb_appid']                       = preg_replace( '/[\D]/', '', $options['fb_appid'] );
		$options['tw_account']                     = preg_replace( '/[\W]/', '', $options['tw_account'] );
		$options['text']['like'][0]                = sanitize_text_field( $options['text']['like'][0] );
		$options['text']['like'][1]                = sanitize_text_field( $options['text']['like'][1] );
		$options['text']['share']                  = sanitize_text_field( $options['text']['share'] );
		$options['text']['tweet']                  = sanitize_text_field( $options['text']['tweet'] );
		$options['text']['follow']                 = sanitize_text_field( $options['text']['follow'] );
		$options['like_button_area']['bg']         = self::sanitize_hex_color( $options['like_button_area']['bg'] );
		$options['like_button_area']['color']      = self::sanitize_hex_color( $options['like_button_area']['color'] );
		$options['like_button_area']['bg_opacity'] = filter_var( $options['like_button_area']['bg_opacity'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );

		if ( ! is_null( get_option( 'push7_appno', null ) ) ) {
			$options['text']['push7'] = sanitize_text_field( $options['text']['push7'] );
		}

		foreach ( $options['like_button_area'] as $key => $hash ) {
			if ( empty( $options['like_button_area'][ $key ] ) ) {
				$options['like_button_area'][ $key ] = $dummy_option['like_button_area'][ $key ];
			}
		}

		if ( ! empty( $options['post_type'] ) ) {
			foreach ( $options['post_type'] as $key => $post_type ) {
				$options['post_type'][ $key ] = sanitize_key( $post_type );
			}
		}

		return apply_filters( 'vasocialbuzz_sanitize_option', $options );
	}
}
