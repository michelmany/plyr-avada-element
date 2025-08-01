<?php
/**
 * @package       PLYRAE
 * @author        Michel Many
 * @version       1.2.0
 *
 * @wordpress-plugin
 * Plugin Name:   Plyr.io Avada Element
 * Plugin URI:    https://michelmany.com
 * Description:   This is a Plyr.io Audio Player Element for Avada
 * Version:       1.2.0
 * Author:        Michel Many
 * Author URI:    https://michelmany.com
 * Text Domain:   plyr-avada-element
 * Domain Path:   /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PLYRAE_NAME', 'Plyr Avada Element' );
define( 'PLYRAE_VERSION', '1.2.0' );
define( 'PLYRAE_PLUGIN_FILE', __FILE__ );
define( 'PLYRAE_PLUGIN_BASE', plugin_basename( PLYRAE_PLUGIN_FILE ) );
define( 'PLYRAE_PLUGIN_DIR', plugin_dir_path( PLYRAE_PLUGIN_FILE ) );
define( 'PLYRAE_PLUGIN_URL', plugin_dir_url( PLYRAE_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once PLYRAE_PLUGIN_DIR . 'core/class-plyr-avada-element.php';

/**
 * Activate the Plyr Avada Element plugin.
 *
 * This function initializes the plugin if FusionBuilder is available
 * and the element class hasn't been loaded yet.
 *
 * @since 1.0.0
 * @return void
 */
function plyrae_activate() {
	// Only activate if FusionBuilder exists and the plugin hasn't been initialized yet.
	if ( class_exists( 'FusionBuilder' ) && ! class_exists( 'PLYRAE_Plyr_Audio' ) ) {
		Plyr_Avada_Element::instance();
	}
}
add_action( 'after_setup_theme', 'plyrae_activate', 11 );

/**
 * Initialize Plyr Elements.
 *
 * Loads all element files from the elements directory.
 *
 * @since 1.0.0
 * @return void
 */
function plyrae_init_plyr_elements() {
	$element_files = glob( PLYRAE_PLUGIN_DIR . 'elements/*.php', GLOB_NOSORT );
	if ( is_array( $element_files ) ) {
		foreach ( $element_files as $filename ) {
			require_once $filename;
		}
	}
}
add_action( 'fusion_builder_shortcodes_init', 'plyrae_init_plyr_elements' );
