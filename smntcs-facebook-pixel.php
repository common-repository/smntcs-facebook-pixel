<?php
/**
 * Plugin Name: SMNTCS Facebook Pixel
 * Plugin URI: https://github.com/nielslange/smntcs-facebook-pixel
 * Description: Adds the <a href="https://www.facebook.com/business/help/952192354843755">Facebook Pixel</a> to your site.
 * Author: Niels Lange <info@nielslange.de>
 * Author URI: https://nielslange.de
 * Text Domain: smntcs-facebook-pixel
 * Domain Path: /languages/
 * Version: 1.2
 * Requires at least: 3.4
 * Requires PHP: 5.6
 * Tested up to: 5.1
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @category   Plugin
 * @package    WordPress
 * @subpackage SMNTCS Facebook Pixel
 * @author     Niels Lange <info@nielslange.de>
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
 * Avoid direct plugin access
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '¯\_(ツ)_/¯' );
}

add_action( 'plugins_loaded', 'smntcs_facebook_pixel_load_textdomain' );
/**
 * Load text domain
 */
function smntcs_facebook_pixel_load_textdomain() {
	load_plugin_textdomain( 'smntcs-facebook-pixel', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action( 'customize_register', 'smntcs_facebook_pixel_register_customize' );
/**
 * Enhance customizer
 *
 * @param WP_Customize_Manager $wp_customize The instance of the WP_Customize_Manager class.
 */
function smntcs_facebook_pixel_register_customize( $wp_customize ) {
	$wp_customize->add_section(
		'smntcs_facebook_pixel_section',
		array(
			'priority' => 150,
			'title'    => __( 'Facebook Pixel', 'smntcs-facebook-pixel' ),
		)
	);

	$wp_customize->add_setting(
		'smntcs_facebook_pixel_code',
		array(
			'default'           => '',
			'type'              => 'option',
			'callback_function' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'smntcs_facebook_pixel_code',
		array(
			'label'   => __( 'Facebook Pixel code', 'smntcs-facebook-pixel' ),
			'section' => 'smntcs_facebook_pixel_section',
			'type'    => 'textarea',
		)
	);
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'smntcs_facebook_pixel_settings_link' );
/**
 * Add settings link on plugin page
 *
 * @param string $links The settings link on the plugin page.
 *
 * @return mixed
 */
function smntcs_facebook_pixel_settings_link( $links ) {
	$admin_url     = admin_url( 'customize.php?autofocus[control]=smntcs_facebook_pixel_code' );
	$settings_link = '<a href="' . $admin_url . '">' . __( 'Settings', 'smntcs-facebook-pixel' ) . '</a>';
	array_unshift( $links, $settings_link );

	return $links;
}

add_action( 'wp_head', 'smntcs_facebook_pixel_enqueue' );
/**
 * Load the Facebook Pixel code
 */
function smntcs_facebook_pixel_enqueue() {
	if ( get_option( 'smntcs_facebook_pixel_code' ) ) {
		print( get_option( 'smntcs_facebook_pixel_code' ) . "\n" );
	}
}
