<?php
if (fusion_is_element_enabled('PLYRAE_plyr_audio') && !class_exists('PLYRAE_Plyr_Audio')) {

    class PLYRAE_Plyr_Audio extends Fusion_Element
    {

        protected $args;

        public function __construct()
        {
            parent::__construct();

            add_shortcode('PLYRAE_plyr_audio', array($this, 'render'));
        }

        /**
         * Render the shortcode.
         *
         * @access public
         * @param array $args Shortcode paramters.
         * @param string $content Content between shortcode.
         * @return string HTML output.
         * @throws JsonException
         * @since 1.0
         */
        public function render($args, $content = '')
        {
            global $fusion_library, $fusion_settings;

            $this->args = $args;

            if (!empty($this->args['dynamic_params'])) {
                (array) $params = json_decode(fusion_decode_if_needed($this->args['dynamic_params']), true, 512, JSON_THROW_ON_ERROR);
                $this->args['plyr_file_url'] = get_field($params['plyr_file_url']['field']);
            }

            $html = '';

            if ('' !== locate_template('templates/plyr-audio/plyr-audio.php')) {
                include locate_template('templates/plyr-audio/plyr-audio.php', false);
            } else {
                include PLYRAE_PLUGIN_DIR . 'templates/plyr-audio/plyr-audio.php';
            }

            return $html;
        }

        /**
         * Sets the necessary scripts.
         *
         * @access public
         * @return void
         * @since 2.3
         */
        public function add_scripts()
        {
            Fusion_Dynamic_JS::enqueue_script(
                'plyr-js',
                PLYRAE_PLUGIN_URL . 'assets/js/plyr.js',
                PLYRAE_PLUGIN_DIR . 'assets/js/plyr.js',
                array('jquery'),
                '3.4',
                true
            );

            Fusion_Dynamic_JS::enqueue_script(
                'plyr-options',
                PLYRAE_PLUGIN_URL . 'assets/js/plyr-options.js',
                PLYRAE_PLUGIN_DIR . 'assets/js/plyr-options.js',
                array('plyr-js'),
                '1.5',
                true
            );
        }
    }

    new PLYRAE_Plyr_Audio();
}


/**
 * Map shortcode for Plyr Audio.
 *
 * @return void
 * @since 1.0
 */
function mapPlyrAudio(): void
{
    global $fusion_settings;

    fusion_builder_map(
        array(
            'name' => esc_attr__('Plyr.io Audio Player', 'plyr-avada-element'),
            'shortcode' => 'PLYRAE_plyr_audio',
            'icon' => 'fa-play-circle fas',
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => esc_attr__('Audio File URL', 'plyr-avada-element'),
                    'param_name' => 'plyr_file_url',
                    'value' => '',
                    'dynamic_data' => true,
                ),
            ),
        )
    );
}

add_action('fusion_builder_before_init', 'mapPlyrAudio', 99);
