<?php
if ( fusion_is_element_enabled( 'PLYRAE_plyr_audio' ) && ! class_exists( 'PLYRAE_Plyr_Audio' ) ) {

	class PLYRAE_Plyr_Audio extends Fusion_Element {


		protected $args;
		protected static $counter = 0;

		public function __construct() {
			parent::__construct();

			add_shortcode( 'PLYRAE_plyr_audio', array( $this, 'render' ) );
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
		public function render( $args, $content = '' ) {
			global $fusion_library, $fusion_settings;

			$this->args = $args;
			++self::$counter;

			if ( ! empty( $this->args['dynamic_params'] ) ) {
				(array) $params              = json_decode( fusion_decode_if_needed( $this->args['dynamic_params'] ), true, 512, JSON_THROW_ON_ERROR );
				$this->args['plyr_file_url'] = get_field( $params['plyr_file_url']['field'] );
			}

			// Enqueue the registered scripts when shortcode is used
			$this->enqueue_scripts();

			$html = '';

			if ( '' !== locate_template( 'templates/plyr-audio/plyr-audio.php' ) ) {
				include locate_template( 'templates/plyr-audio/plyr-audio.php', false );
			} else {
				include PLYRAE_PLUGIN_DIR . 'templates/plyr-audio/plyr-audio.php';
			}

			return $html;
		}

		/**
		 * Enqueue scripts and styles when needed.
		 *
		 * @access public
		 * @return void
		 * @since 2.3
		 */
		public function enqueue_scripts() {
			// Enqueue Plyr CSS directly (not relying on registration)
			if ( ! wp_style_is( 'plyr-css', 'enqueued' ) ) {
				wp_enqueue_style(
					'plyr-css',
					PLYRAE_PLUGIN_URL . 'core/includes/assets/css/plyrae-frontend-styles.min.css',
					array(),
					'3.4-' . time(), // Cache busting
					'all'
				);
			}

			// Enqueue custom frontend styles
			if ( ! wp_style_is( 'plyrae-custom-styles', 'enqueued' ) ) {
				wp_enqueue_style(
					'plyrae-custom-styles',
					PLYRAE_PLUGIN_URL . 'core/includes/assets/css/frontend-styles.css',
					array( 'plyr-css' ),
					PLYRAE_VERSION . '-' . time(), // Cache busting
					'all'
				);
			}

			// Enqueue scripts using Fusion_Dynamic_JS for better Avada compatibility
			if ( ! wp_script_is( 'plyr-js', 'enqueued' ) ) {
				Fusion_Dynamic_JS::enqueue_script(
					'plyr-js',
					PLYRAE_PLUGIN_URL . 'assets/js/plyr.js',
					PLYRAE_PLUGIN_DIR . 'assets/js/plyr.js',
					array( 'jquery' ),
					'3.4',
					true
				);
			}

			if ( ! wp_script_is( 'plyr-options', 'enqueued' ) ) {
				Fusion_Dynamic_JS::enqueue_script(
					'plyr-options',
					PLYRAE_PLUGIN_URL . 'assets/js/plyr-options.js',
					PLYRAE_PLUGIN_DIR . 'assets/js/plyr-options.js',
					array( 'plyr-js' ),
					'2.0-' . time(), // Cache busting
					true
				);
			}
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
function mapPlyrAudio(): void {
	global $fusion_settings;

	fusion_builder_map(
		array(
			'name'      => esc_attr__( 'Plyr.io Audio Player', 'plyr-avada-element' ),
			'shortcode' => 'PLYRAE_plyr_audio',
			'icon'      => 'fa-play-circle fas',
			'params'    => array(
				array(
					'type'         => 'textfield',
					'heading'      => esc_attr__( 'Audio File URL', 'plyr-avada-element' ),
					'param_name'   => 'plyr_file_url',
					'value'        => '',
					'dynamic_data' => true,
				),
			),
		)
	);

	// Auto-activate element only when mapping, not on every page load
	if ( function_exists( 'fusion_builder_auto_activate_element' ) ) {
		fusion_builder_auto_activate_element( 'PLYRAE_plyr_audio' );
	}
}

add_action( 'fusion_builder_before_init', 'mapPlyrAudio', 99 );