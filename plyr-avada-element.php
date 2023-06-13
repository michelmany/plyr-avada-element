<?php
/**
 * @package       PLYRAE
 * @author        Michel Many
 * @version       1.0.1
 *
 * @wordpress-plugin
 * Plugin Name:   Plyr.io Avada Element
 * Plugin URI:    https://michelmany.com
 * Description:   This is a Plyr.io Audio Player Element for Avada
 * Version:       1.0.1
 * Author:        Michel Many
 * Author URI:    https://michelmany.com
 * Text Domain:   plyr-avada-element
 * Domain Path:   /languages
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

define('PLYRAE_NAME', 'Plyr Avada Element');
define('PLYRAE_VERSION', '1.0.1');
define('PLYRAE_PLUGIN_FILE', __FILE__);
define('PLYRAE_PLUGIN_BASE', plugin_basename(PLYRAE_PLUGIN_FILE));
define('PLYRAE_PLUGIN_DIR', plugin_dir_path(PLYRAE_PLUGIN_FILE));
define('PLYRAE_PLUGIN_URL', plugin_dir_url(PLYRAE_PLUGIN_FILE));

/**
 * Load the main class for the core functionality
 */
require_once PLYRAE_PLUGIN_DIR . 'core/class-plyr-avada-element.php';

function PLYRAE_activate(): void
{
    if (class_exists('FusionBuilder')) {
        Plyr_Avada_Element::instance();
    }
}
add_action('after_setup_theme', 'PLYRAE_activate', 11);


function initPlyrElements(): void
{
    foreach (glob(PLYRAE_PLUGIN_DIR . 'elements/*.php', GLOB_NOSORT) as $filename) {
        require_once $filename;
    }
}
add_action('fusion_builder_shortcodes_init', 'initPlyrElements');
