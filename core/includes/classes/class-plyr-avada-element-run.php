<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Plyr_Avada_Element_Run
 *
 * That's where we bring the plugin to life
 *
 * @package PLYRAE
 * @subpackage Classes/Plyr_Avada_Element_Run
 * @author Michel Many
 * @since 1.0.0
 */
class Plyr_Avada_Element_Run
{

    /**
     * Our Plyr_Avada_Element_Run constructor
     * to run the plugin logic.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->add_hooks();
    }

    /**
     * ######################
     * ###
     * #### WORDPRESS HOOKS
     * ###
     * ######################
     */

    /**
     * Registers all WordPress and plugin related hooks
     *
     * @access private
     * @return void
     * @since 1.0.0
     */
    private function add_hooks(): void
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts_and_styles'), 20);
    }

    /**
     * ######################
     * ###
     * #### WORDPRESS HOOK CALLBACKS
     * ###
     * ######################
     */


    /**
     * Enqueue the frontend related scripts and styles for this plugin.
     *
     * @access public
     * @return void
     * @since 1.0.0
     *
     */
    public function enqueue_frontend_scripts_and_styles(): void
    {
        wp_enqueue_style('plyrae-frontend-styles-min', PLYRAE_PLUGIN_URL . 'core/includes/assets/css/plyrae-frontend-styles.min.css', array(), PLYRAE_VERSION, 'all');
        wp_enqueue_style('frontend-styles', PLYRAE_PLUGIN_URL . 'core/includes/assets/css/frontend-styles.css', array('plyrae-frontend-styles-min'), PLYRAE_VERSION, 'all');
        wp_enqueue_script('plyrae-frontend-scripts', PLYRAE_PLUGIN_URL . 'core/includes/assets/js/frontend-scripts.js', array('jquery'), PLYRAE_VERSION, true);
        wp_localize_script('plyrae-frontend-scripts', 'plyrae', array(
            'demo_var' => __('This is some demo text coming from the backend through a variable within javascript.', 'plyr-avada-element'),
            'ajaxurl' => admin_url('admin-ajax.php'),
            'security_nonce' => wp_create_nonce("your-nonce-name"),
        ));
    }
}
