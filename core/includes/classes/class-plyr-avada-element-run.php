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
        add_action('wp_ajax_nopriv_my_demo_ajax_call', array($this, 'my_demo_ajax_call_callback'), 20);
        add_action('wp_ajax_my_demo_ajax_call', array($this, 'my_demo_ajax_call_callback'), 20);

        add_action('fusion_builder_before_init', array($this, 'mapFusionElementPlyrAudio'), 99);
        add_action('init', array($this, 'mapFusionElementPlyrAudio'), 99);
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
        wp_enqueue_style('plyrae-frontend-styles', PLYRAE_PLUGIN_URL . 'core/includes/assets/css/frontend-styles.css', array(), PLYRAE_VERSION, 'all');
        wp_enqueue_script('plyrae-frontend-scripts', PLYRAE_PLUGIN_URL . 'core/includes/assets/js/frontend-scripts.js', array('jquery'), PLYRAE_VERSION, true);
        wp_localize_script('plyrae-frontend-scripts', 'plyrae', array(
            'demo_var' => __('This is some demo text coming from the backend through a variable within javascript.', 'plyr-avada-element'),
            'ajaxurl' => admin_url('admin-ajax.php'),
            'security_nonce' => wp_create_nonce("your-nonce-name"),
        ));
    }

    public function mapFusionElementPlyrAudio(): void
    {
        fusion_builder_map(
            [
                'name' => esc_attr__('Plyr Audio', 'plyr-avada-element'),
                'shortcode' => 'plyrae_audio_player',
                'icon' => 'fusiona-tag',
                'inline_editor' => true,
                'params' => [
                    [
                        'type' => 'textfield',
                        'heading' => esc_attr__('Taxonomy', 'avada_addons'),
                        'description' => esc_attr__('Write taxonomy slug to filter', 'avada_addons'),
                        'param_name' => 'taxonomy',
                        'value' => '',
                    ],

                ],
            ]
        );
    }


    /**
     * The callback function for my_demo_ajax_call
     *
     * @access public
     * @return void
     * @since 1.0.0
     *
     */
    public function my_demo_ajax_call_callback(): void
    {
        check_ajax_referer('your-nonce-name', 'ajax_nonce_parameter');

        $demo_data = isset($_REQUEST['demo_data']) ? sanitize_text_field($_REQUEST['demo_data']) : '';
        $response = array('success' => false);

        if (!empty($demo_data)) {
            $response['success'] = true;
            $response['msg'] = __('The value was successfully filled.', 'plyr-avada-element');
        } else {
            $response['msg'] = __('The sent value was empty.', 'plyr-avada-element');
        }

        if ($response['success']) {
            wp_send_json_success($response);
        } else {
            wp_send_json_error($response);
        }

        die();
    }

}
