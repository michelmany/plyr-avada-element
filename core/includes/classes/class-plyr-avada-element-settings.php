<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Plyr_Avada_Element_Settings
 *
 * This class contains all of the plugin settings.
 * Here you can configure the whole plugin data.
 *
 * @package        PLYRAE
 * @subpackage    Classes/Plyr_Avada_Element_Settings
 * @author        Michel Many
 * @since        1.0.0
 */
class Plyr_Avada_Element_Settings
{

    /**
     * The plugin name
     *
     * @var string
     * @since 1.0.0
     */
    private $plugin_name;

    /**
     * Our Plyr_Avada_Element_Settings constructor
     * to run the plugin logic.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->plugin_name = PLYRAE_NAME;
    }

    /**
     * ######################
     * ###
     * #### CALLABLE FUNCTIONS
     * ###
     * ######################
     */

    /**
     * Return the plugin name
     *
     * @access public
     * @return string The plugin name
     * @since 1.0.0
     */
    public function get_plugin_name(): string
    {
        return apply_filters('PLYRAE/settings/get_plugin_name', $this->plugin_name);
    }
}
