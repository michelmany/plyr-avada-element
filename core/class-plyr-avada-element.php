<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Plyr_Avada_Element')) :

    /**
     * Main Plyr_Avada_Element Class.
     *
     * @package        PLYRAE
     * @subpackage    Classes/Plyr_Avada_Element
     * @since        1.0.0
     * @author        Michel Many
     */
    final class Plyr_Avada_Element
    {

        /**
         * The real instance
         *
         * @access private
         * @since 1.0.0
         * @var object|Plyr_Avada_Element
         */
        private static $instance;

        /**
         * PLYRAE helpers object.
         *
         * @access public
         * @since 1.0.0
         * @var object|Plyr_Avada_Element_Helpers
         */
        public $helpers;

        /**
         * PLYRAE settings object.
         *
         * @access public
         * @since 1.0.0
         * @var object|Plyr_Avada_Element_Settings
         */
        public $settings;

        /**
         * Throw error on object clone.
         *
         * Cloning instances of the class is forbidden.
         *
         * @access public
         * @return void
         * @since 1.0.0
         */
        public function __clone()
        {
            _doing_it_wrong(__FUNCTION__, __('You are not allowed to clone this class.', 'plyr-avada-element'), '1.0.0');
        }

        /**
         * Disable unserializing of the class.
         *
         * @access public
         * @return void
         * @since 1.0.0
         */
        public function __wakeup()
        {
            _doing_it_wrong(__FUNCTION__, __('You are not allowed to unserialize this class.', 'plyr-avada-element'), '1.0.0');
        }

        /**
         * Main Plyr_Avada_Element Instance.
         *
         * Insures that only one instance of Plyr_Avada_Element exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         * @access public
         * @return object|Plyr_Avada_Element The one true Plyr_Avada_Element
         * @since 1.0.0
         * @static
         */
        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof Plyr_Avada_Element)) {
                self::$instance = new Plyr_Avada_Element;
                self::$instance->base_hooks();
                self::$instance->includes();
                self::$instance->helpers = new Plyr_Avada_Element_Helpers();
                self::$instance->settings = new Plyr_Avada_Element_Settings();

                //Fire the plugin logic
                new Plyr_Avada_Element_Run();

                /**
                 * Fire a custom action to allow dependencies
                 * after the successful plugin setup
                 */
                do_action('PLYRAE/plugin_loaded');
            }

            return self::$instance;
        }

        /**
         * Include required files.
         *
         * @access  private
         * @return  void
         * @since   1.0.0
         */
        private function includes(): void
        {
            require_once PLYRAE_PLUGIN_DIR . 'core/includes/classes/class-plyr-avada-element-helpers.php';
            require_once PLYRAE_PLUGIN_DIR . 'core/includes/classes/class-plyr-avada-element-settings.php';

            require_once PLYRAE_PLUGIN_DIR . 'core/includes/classes/class-plyr-avada-element-run.php';
        }

        /**
         * Add base hooks for the core functionality
         *
         * @access  private
         * @return  void
         * @since   1.0.0
         */
        private function base_hooks(): void
        {
            add_action('plugins_loaded', array(self::$instance, 'load_textdomain'));
        }

        /**
         * Loads the plugin language files.
         *
         * @access  public
         * @return  void
         * @since   1.0.0
         */
        public function load_textdomain()
        {
            load_plugin_textdomain('plyr-avada-element', FALSE, dirname(plugin_basename(PLYRAE_PLUGIN_FILE)) . '/languages/');
        }

    }

endif;